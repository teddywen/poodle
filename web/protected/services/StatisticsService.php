<?php
/**
 * 统计汇总Service
 */
class StatisticsService extends Service {

    /**
     * @param string $assign_start_date - Y-m-d
     * @param string $assign_end_date - Y-m-d
     * @return array - {<assign_date>: {<deal_username>: [<release_rows>]}}
     */
    public function getReleaseStatistics($assign_start_date, $assign_end_date, $user_id = 0) {
        $rows = $this->_getReleaseRows($assign_start_date, $assign_end_date, $user_id);

        $statistics = array();
        foreach ($rows as $key => $row) {
            if (!isset($statistics[$row["deal_username"]][$row["assign_date"]])) {
                $statistics[$row["deal_username"]][$row["assign_date"]] = array();
            }
            $statistics[$row["deal_username"]][$row["assign_date"]][] = $row;
        }
        return $statistics;
    }

    /**
     * @param string $assign_start_date - Y-m-d
     * @param string $assign_end_date - Y-m-d
     * @return array - [[<`problem`.*, `img_paths`, `img_widths`, `img_heights`, `assign_date`, `duration_lv`, `delay_time`>], ...]
     */
    private function _getReleaseRows($assign_start_date, $assign_end_date, $user_id = 0) {
        $assign_start_date = date("Y-m-d 00:00:00", strtotime($assign_start_date));
        $assign_end_date = date("Y-m-d 23:59:59", strtotime($assign_end_date));

        $sql = "SELECT `problem`.*, 
                    GROUP_CONCAT(`problem_image`.`img_path`) AS `img_paths`, 
                    GROUP_CONCAT(`problem_image`.`img_width`) AS `img_widths`, 
                    GROUP_CONCAT(`problem_image`.`img_height`) AS `img_heights`, 
                    FROM_UNIXTIME(`problem`.`assign_time`, '%Y-%m-%d') AS `assign_date`, 
                    IF(`problem`.`status`<>:status_qualified, 0, 
                        IF(`problem`.`times_up`=1, 2, 1)) AS `duration_lv`, 
                    FLOOR(`problem`.`delay_time`/24) AS `delay_day`
                FROM `problem` 
                LEFT JOIN `problem_image` ON `problem`.`id`=`problem_image`.`pid`
                WHERE `problem`.`assign_time` BETWEEN :assign_start_time AND :assign_end_time AND (`problem_image`.`id` IS NULL OR `problem_image`.`status`=:status_release)";
        if ($user_id) {
            $sql .= " AND `problem`.`deal_uid`=:user_id";
        }
        $sql .= " GROUP BY `problem`.`id`
                ORDER BY `problem`.`deal_uid` ASC, `problem`.`assign_time` ASC";
        $params = array(
            ":assign_start_time" => strtotime($assign_start_date), 
            ":assign_end_time" => strtotime($assign_end_date), 
            ":status_qualified" => ProblemService::BE_QUALIFIED, 
            ":status_release" => 1, 
        );
        if ($user_id) {
            $params[":user_id"] = $user_id;
        }
        return Yii::app()->getDb()->createCommand($sql)->queryAll(true, $params);
    }

    /**
     * @param string $assign_start_date - Y-m-d
     * @param string $assign_end_date - Y-m-d
     * @return array - [{deal_username, problem_count, problem_qualified_count, problem_is_assistant_count, problem_is_delay_count, problem_times_up_count, problem_unqualified_count}, ...]
     */
    public function getSolveStatistics($assign_start_date, $assign_end_date, $user_id = 0) {
        $assign_start_date = date("Y-m-d 00:00:00", strtotime($assign_start_date));
        $assign_end_date = date("Y-m-d 23:59:59", strtotime($assign_end_date));

        $sql = "SELECT `deal_username`, COUNT(*) AS `problem_count`, SUM(IF(`status`=:status_qualified, 1, 0)) AS `problem_qualified_count`, 
                    SUM(`is_assistant`) AS `problem_is_assistant_count`, SUM(`is_delay`) AS `problem_is_delay_count`, 
                    SUM(`times_up`) AS `problem_times_up_count`, 
                    SUM(IF(`status`<>:status_qualified, 1, 0)) AS `problem_unqualified_count`
                FROM `problem`
                WHERE `assign_time` BETWEEN :assign_start_time AND :assign_end_time";
        if ($user_id) {
            $sql .= " AND `deal_uid`=:user_id";
        }
        $sql .= " GROUP BY `deal_uid`";
        $params = array(
            ":assign_start_time" => strtotime($assign_start_date), 
            ":assign_end_time" => strtotime($assign_end_date), 
            ":status_qualified" => ProblemService::BE_QUALIFIED, 
        );
        if ($user_id) {
            $params[":user_id"] = $user_id;
        }

        return Yii::app()->getDb()->createCommand($sql)->queryAll(true, $params);
    }


    /**
     * Export excel code was reference from http://blog.csdn.net/samxx8/article/details/8138072
     */
    public function exportReleaseStatistics($statistics, $assign_start_date, $assign_end_date, $with_image = false) {
        Util::usePhpExcel();
        $objExcel = new PHPExcel();
        // set properties
        $objProps = $objExcel->getProperties();
        $objProps->setCreator("SCW"); 
        $objProps->setLastModifiedBy("SCW");
        $objProps->setTitle("Problem Statistics");
        $objProps->setSubject("Release Statistics");
        $objProps->setDescription("Release problem summary for every problem.");
        $objProps->setKeywords("Problem Release Statistics");
        $objProps->setCategory("Statistics");

        // set sheet
        $objExcel->setActiveSheetIndex(0);
        $objActSheet = $objExcel->getActiveSheet();
        // set sheet title
        $objActSheet->setTitle("{$assign_start_date} ~ {$assign_end_date}");
        // set default style
        $objActSheet->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActSheet->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        // set default row size
        $objActSheet->getDefaultRowDimension()->setRowHeight(18);
        $objActSheet->setCellValue('A1', "{$assign_start_date} ~ {$assign_end_date} 堡镇整改反馈汇总表");
        $objActSheet->mergeCells("A1:L1");
        // set thead
        $objActSheet->setCellValue('A2', '序号');
        $objActSheet->mergeCells("A2:A3");
        $objActSheet->setCellValue('B2', '单位');
        $objActSheet->getColumnDimension('B')->setWidth(18);
        $objActSheet->mergeCells("B2:B3");
        $objActSheet->setCellValue('C2', '派单日期');
        $objActSheet->mergeCells("C2:C3");
        $objActSheet->setCellValue('D2', '派单数');
        $objActSheet->mergeCells("D2:D3");
        $objActSheet->setCellValue('E2', '截止日期');
        $objActSheet->mergeCells("E2:E3");
        $objActSheet->setCellValue('F2', '存在问题');
        $objActSheet->mergeCells("F2:I2");
        $objActSheet->setCellValue('F3', '编号');
        $objActSheet->setCellValue('G3', '问题地址');
        $objActSheet->getColumnDimension('G')->setWidth(36);
        $objActSheet->setCellValue('H3', '具体问题');
        $objActSheet->getColumnDimension('H')->setWidth(36);
        $objActSheet->setCellValue('I3', '问题图片');
        $objActSheet->getColumnDimension('I')->setWidth(30);
        $objActSheet->setCellValue('J2', '申请延时');
        $objActSheet->getColumnDimension('J')->setWidth(18);
        $objActSheet->mergeCells("J2:J3");
        $objActSheet->setCellValue('K2', '完成情况');
        $objActSheet->getColumnDimension('K')->setWidth(18);
        $objActSheet->mergeCells("K2:K3");
        $objActSheet->setCellValue('L2', '备注');
        $objActSheet->mergeCells("L2:L3");
        // set tbody
        $no = 0;
        $rowLv1 = 4;
        $rowLv2 = 4;
        $rowLv3 = 4;
        foreach ($statistics as $deal_user => $assign_date_rows) {
            ++$no;
            $rowspan_deal_user = 0; foreach($assign_date_rows as $rows) $rowspan_deal_user += count($rows);
            foreach($assign_date_rows as $assign_date => $rows) {
                $rowspan_assign_date = count($rows);
                foreach($rows as $key => $row) {
                    if(!isset($current_deal_user) || $current_deal_user != $deal_user) {
                        $current_deal_user = $deal_user;
                        $objActSheet->setCellValue("A{$rowLv1}", $no);
                        $objActSheet->setCellValue("B{$rowLv1}", $deal_user);
                        if ($rowspan_deal_user > 1) {
                            $rowLv1End = $rowLv1 + $rowspan_deal_user - 1;
                            $objActSheet->mergeCells("A{$rowLv1}:A{$rowLv1End}");
                            $objActSheet->mergeCells("B{$rowLv1}:B{$rowLv1End}");
                        }
                        $rowLv1 += $rowspan_deal_user;
                    }
                    if($key == 0) {
                        $objActSheet->setCellValue("C{$rowLv2}", date("Y.n.j", strtotime($assign_date)));
                        $objActSheet->setCellValue("D{$rowLv2}", $rowspan_assign_date);
                        if ($rowspan_assign_date > 1) {
                            $rowLv2End = $rowLv2 + $rowspan_assign_date - 1;
                            $objActSheet->mergeCells("C{$rowLv2}:C{$rowLv2End}");
                            $objActSheet->mergeCells("D{$rowLv2}:D{$rowLv2End}");
                        }
                        $rowLv2 += $rowspan_assign_date;
                    }
                    $objActSheet->setCellValue("E{$rowLv3}", date("Y.n.j", $row["assign_time"] + ($row["deal_time"] + $row["delay_time"]) * 3600));
                    $objActSheet->setCellValue("F{$rowLv3}", "#{$row["id"]}");
                    $objActSheet->setCellValue("G{$rowLv3}", $row["address"]);
                    $objActSheet->getStyle("G{$rowLv3}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objActSheet->setCellValue("H{$rowLv3}", $row["description"]);
                    $objActSheet->getStyle("H{$rowLv3}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    
                    if($with_image && $row["img_paths"]) {
                        $img_paths = explode(",", $row["img_paths"]);
                        $img_widths = explode(",", $row["img_widths"]);
                        $img_heights = explode(",", $row["img_heights"]);
                        foreach($img_paths as $key => $img_path) {
                            $objDrawing = new PHPExcel_Worksheet_Drawing();
                            $objDrawing->setName("image_{$row["id"]}");
                            $objDrawing->setDescription("Image for #{$row["id"]}");
                            $imgUnixPath = Yii::app()->basePath . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $img_path;
                            if (file_exists($imgUnixPath)) {
                                $objDrawing->setPath($imgUnixPath);
                                $objDrawing->setHeight(120);
                                $objDrawing->setCoordinates("I{$rowLv3}"); 
                                $objDrawing->setOffsetX(10); 
                                $objDrawing->setOffsety(10); 
                                // $objDrawing->setRotation(50);
                                $objDrawing->getShadow()->setVisible(true); 
                                // $objDrawing->getShadow()->setDirection(36); 
                                $objDrawing->setWorksheet($objActSheet); 
                                $objActSheet->getRowDimension($rowLv3)->setRowHeight(120);
                            }
                            break;
                        }
                    }

                    $objActSheet->setCellValue("J{$rowLv3}", $row["is_delay"] ? "延时{$row["delay_time"]}小时" : "");
                    $finishStatus = "";
                    switch ($row['duration_lv']) {
                        case 0:
                            $finishStatus = "未完成";
                            break;
                        case 1:
                            $finishStatus = "准时完成";
                            break;
                        case 2:
                            $finishStatus = "超时完成";
                            break;
                        default:
                            $finishStatus = "未完成";
                            break;
                    }
                    $objActSheet->setCellValue("K{$rowLv3}", $finishStatus);
                    $objActSheet->setCellValue("L{$rowLv3}", "");
                    ++$rowLv3;
                }
            }
        }

        // set cell border
        for ($row=1; $row<=$rowLv3-1; ++$row) { 
            for ($col=ord('A'); $col<=ord('L'); ++$col) { 
                $colChr = chr($col);
                $this->_setBorder($objActSheet, "{$colChr}{$row}");
            }
        }

        $this->_saveAndExport($objExcel);
    }

    /**
     * Export excel code was reference from http://blog.csdn.net/samxx8/article/details/8138072
     */
    public function exportSolveStatistics($statistics, $assign_start_date, $assign_end_date, $displaySummary) {
        Util::usePhpExcel();
        $objExcel = new PHPExcel();
        // set properties
        $objProps = $objExcel->getProperties();
        $objProps->setCreator("SCW"); 
        $objProps->setLastModifiedBy("SCW");
        $objProps->setTitle("Problem Statistics");
        $objProps->setSubject("Solve Statistics");
        $objProps->setDescription("Solve problem summary for every unit.");
        $objProps->setKeywords("Problem Solve Statistics");
        $objProps->setCategory("Statistics");

        // set sheet
        $objExcel->setActiveSheetIndex(0);
        $objActSheet = $objExcel->getActiveSheet();
        // set sheet title
        $objActSheet->setTitle("{$assign_start_date} ~ {$assign_end_date}");
        // set default style
        $objActSheet->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActSheet->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        // set default row size
        $objActSheet->getDefaultRowDimension()->setRowHeight(18);
        $objActSheet->getDefaultColumnDimension()->setWidth(18);
        // set cell
        // set ttitle
        $objActSheet->setCellValue('A1', "{$assign_start_date} ~ {$assign_end_date} 相关部门问题清单整改情况汇总");
        // set thead
        $objActSheet->setCellValue('A2', '序号');
        $objActSheet->setCellValue('B2', '单位');
        $objActSheet->setCellValue('C2', '问题数');
        $objActSheet->setCellValue('D2', '已整改');
        $objActSheet->setCellValue('E2', '联动问题');
        $objActSheet->setCellValue('F2', '申请延期');
        $objActSheet->setCellValue('G2', '整改超时');
        $objActSheet->setCellValue('H2', '未整改');
        $objActSheet->setCellValue('I2', '备注');
        // set tbody
        foreach ($statistics as $key => $row) {
            $cellRow = $key + 3;
            $no = $key + 1;
            $objActSheet->setCellValue("A{$cellRow}", $no);
            $objActSheet->setCellValue("B{$cellRow}", $row["deal_username"]);
            $objActSheet->setCellValue("C{$cellRow}", $row["problem_count"]);
            $objActSheet->setCellValue("D{$cellRow}", $row["problem_qualified_count"]);
            $objActSheet->setCellValue("E{$cellRow}", $row["problem_is_assistant_count"]);
            $objActSheet->setCellValue("F{$cellRow}", $row["problem_is_delay_count"]);
            $objActSheet->setCellValue("G{$cellRow}", $row["problem_times_up_count"]);
            $objActSheet->setCellValue("H{$cellRow}", $row["problem_unqualified_count"]);
        }
        $sumStartRow = 3;
        $sumEndRow = count($statistics) + 2;
        $lastRow = count($statistics) + 3;
        if ($displaySummary) {
            $objActSheet->setCellValue("A{$lastRow}", "合计");
            $objActSheet->setCellValue("C{$lastRow}", "=SUM(C{$sumStartRow}:C{$sumEndRow})");
            $objActSheet->setCellValue("D{$lastRow}", "=SUM(D{$sumStartRow}:D{$sumEndRow})");
            $objActSheet->setCellValue("E{$lastRow}", "=SUM(E{$sumStartRow}:E{$sumEndRow})");
            $objActSheet->setCellValue("F{$lastRow}", "=SUM(F{$sumStartRow}:F{$sumEndRow})");
            $objActSheet->setCellValue("G{$lastRow}", "=SUM(G{$sumStartRow}:G{$sumEndRow})");
            $objActSheet->setCellValue("H{$lastRow}", "=SUM(H{$sumStartRow}:H{$sumEndRow})");
            $objActSheet->mergeCells("A{$lastRow}:B{$lastRow}");
        } else {
            --$lastRow;
        }

        // set cell merge
        $objActSheet->mergeCells("A1:I1");

        // set cell border
        for ($row=1; $row<=$lastRow; ++$row) { 
            for ($col=ord('A'); $col<=ord('I'); ++$col) { 
                $colChr = chr($col);
                $this->_setBorder($objActSheet, "{$colChr}{$row}");
            }
        }

        $this->_saveAndExport($objExcel);
    }

    private function _setBorder($objActSheet, $position) {
        $objStyle = $objActSheet ->getStyle($position);
        $objBorder = $objStyle->getBorders();
        $objBorder->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objBorder->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objBorder->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objBorder->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    }

    private function _saveAndExport(PHPExcel $objExcel) {
        $objWriter = new PHPExcel_Writer_Excel5($objExcel);
        // $objWriter = new PHPExcel_Writer_Excel2007($objExcel);
        // $objWriter->setOffice2003Compatibility(true); 
        header("Content-Type: application/force-download"); 
        header("Content-Type: application/octet-stream"); 
        header("Content-Type: application/download"); 
        header('Content-Disposition:inline;filename="solve_statistic.xls"'); 
        header("Content-Transfer-Encoding: binary"); 
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
        header("Pragma: no-cache");
        $objWriter->save('php://output'); 
    }
}