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
    public function getReleaseStatistics($assign_start_date, $assign_end_date) {
        $rows = $this->_getReleaseRows($assign_start_date, $assign_end_date);

        $statistics = array();
        foreach ($rows as $key => $row) {
            if (!isset($statistics[$row["assign_date"]][$row["deal_username"]])) {
                $statistics[$row["assign_date"]][$row["deal_username"]] = array();
            }
            $statistics[$row["assign_date"]][$row["deal_username"]][] = $row;
        }
        return $statistics;
    }

    /**
     * @param string $assign_start_date - Y-m-d
     * @param string $assign_end_date - Y-m-d
     * @return array - [[<`problem`.*, `assign_date`, `duration_lv`, `delay_time`>], ...]
     */
    private function _getReleaseRows($assign_start_date, $assign_end_date) {
        $assign_start_date = date("Y-m-d 00:00:00", strtotime($assign_start_date));
        $assign_end_date = date("Y-m-d 23:59:59", strtotime($assign_end_date));

        $sql = "SELECT *, FROM_UNIXTIME(`assign_time`, '%Y-%m-%d') AS `assign_date`, 
                    IF(`status`<>:status_qualified, 0, 
                        IF(`assign_time`+(`deal_time` + `delay_time`)*3600>`check_time`, 1, 0)) AS `duration_lv`, 
                    FLOOR(`delay_time`/24) AS `delay_day`
                FROM `problem` 
                WHERE `assign_time` BETWEEN :assign_start_time AND :assign_end_time
                ORDER BY `assign_time` ASC, `deal_uid` ASC";
        $params = array(
            ":assign_start_time" => strtotime($assign_start_date), 
            ":assign_end_time" => strtotime($assign_end_date), 
            ":status_qualified" => ProblemService::BE_QUALIFIED, 
        );
        return Yii::app()->getDb()->createCommand($sql)->queryAll(true, $params);
    }

    /**
     * @param string $assign_start_date - Y-m-d
     * @param string $assign_end_date - Y-m-d
     * @return array - [{deal_username, problem_count, problem_qualified_count, problem_is_assistant_count, problem_is_delay_count, problem_times_up_count, problem_unqualified_count}, ...]
     */
    public function getSolveStatistics($assign_start_date, $assign_end_date) {
        $assign_start_date = date("Y-m-d 00:00:00", strtotime($assign_start_date));
        $assign_end_date = date("Y-m-d 23:59:59", strtotime($assign_end_date));

        $sql = "SELECT `deal_username`, COUNT(*) AS `problem_count`, SUM(IF(`status`=:status_qualified, 1, 0)) AS `problem_qualified_count`, 
                    SUM(`is_assistant`) AS `problem_is_assistant_count`, SUM(`is_delay`) AS `problem_is_delay_count`, 
                    SUM(`times_up`) AS `problem_times_up_count`, 
                    SUM(IF(`status`<>:status_qualified, 1, 0)) AS `problem_unqualified_count`
                FROM `problem`
                WHERE `assign_time` BETWEEN :assign_start_time AND :assign_end_time
                GROUP BY `deal_uid`";
        $params = array(
            ":assign_start_time" => strtotime($assign_start_date), 
            ":assign_end_time" => strtotime($assign_end_date), 
            ":status_qualified" => ProblemService::BE_QUALIFIED, 
        );

        return Yii::app()->getDb()->createCommand($sql)->queryAll(true, $params);
    }


    /**
     * Export excel code was reference from http://blog.csdn.net/samxx8/article/details/8138072
     */
    public function exportReleaseStatistics($statistics, $assign_start_date, $assign_end_date) {
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
        // set cell
        $offsetRow = 0;
        foreach ($statistics as $assign_date => $deal_username_rows) {
            $no = 0;
            $totalRows = 0;
            $ttitleRow = $offsetRow + 1;
            $theadRow = $ttitleRow + 1;
            $thead2Row = $theadRow + 1;
            // set ttitle
            $ynj = date('Y.n.j', strtotime($assign_date));
            $objActSheet->setCellValue("A{$ttitleRow}", "{$assign_start_date} ~ {$assign_end_date} 堡政整改反馈汇总表 ($ynj)");
            // set thead
            $objActSheet->setCellValue("A{$theadRow}", '序号');
            $objActSheet->setCellValue("B{$theadRow}", '单位');
            $objActSheet->setCellValue("C{$theadRow}", '派单日期');
            $objActSheet->setCellValue("D{$theadRow}", '发现问题数');
            $objActSheet->setCellValue("E{$theadRow}", '存在问题');
            $objActSheet->setCellValue("E{$thead2Row}", '编号');
            $objActSheet->setCellValue("F{$thead2Row}", '具体问题');
            $objActSheet->setCellValue("G{$theadRow}", '按时完成');
            $objActSheet->setCellValue("H{$theadRow}", '需要县镇联动');
            $objActSheet->setCellValue("I{$theadRow}", '申请延时');
            $objActSheet->setCellValue("J{$theadRow}", '完成情况');
            $objActSheet->setCellValue("K{$theadRow}", '备注');
            // set tbody
            $offsetCellRow = $thead2Row + 1;
            foreach ($deal_username_rows as $deal_username => $rows) {
                ++$no;
                $rowsCount = count($rows);
                $totalRows += $rowsCount;
                $objActSheet->setCellValue("A{$offsetCellRow}", $no);
                $objActSheet->setCellValue("B{$offsetCellRow}", $deal_username);
                $objActSheet->setCellValue("C{$offsetCellRow}", date("Y.n.j", strtotime($assign_date)));
                $objActSheet->setCellValue("D{$offsetCellRow}", $rowsCount);
                foreach ($rows as $key => $row) {
                    $currentRow = $offsetCellRow + $key;
                    $objActSheet->setCellValue("E{$currentRow}", '#'.$row["id"]);
                    $objActSheet->setCellValue("F{$currentRow}", $row["description"]);
                    $objActSheet->setCellValue("G{$currentRow}", $row["duration_lv"] == 1 ? "√" : "");
                    $objActSheet->setCellValue("H{$currentRow}", $row["is_assistant"] ? "√": "");
                    $objActSheet->setCellValue("I{$currentRow}", $row["is_delay"] ? ("{$row["delay_day"]}天完成"): "");
                    $objActSheet->setCellValue("J{$currentRow}", $row["status"] == ProblemService::BE_QUALIFIED ? "完成": "");
                    $objActSheet->setCellValue("K{$currentRow}", "");
                }

                // set cell merge
                $offsetCellEndRow = $offsetCellRow + $rowsCount - 1;
                $objActSheet->mergeCells("A{$offsetCellRow}:A{$offsetCellEndRow}");
                $objActSheet->mergeCells("B{$offsetCellRow}:B{$offsetCellEndRow}");
                $objActSheet->mergeCells("C{$offsetCellRow}:C{$offsetCellEndRow}");
                $objActSheet->mergeCells("D{$offsetCellRow}:D{$offsetCellEndRow}");

                $offsetCellRow += $rowsCount;
            }
            // set cell merge
            $objActSheet->mergeCells("A{$ttitleRow}:K{$ttitleRow}");
            $objActSheet->mergeCells("A{$theadRow}:A{$thead2Row}");
            $objActSheet->mergeCells("B{$theadRow}:B{$thead2Row}");
            $objActSheet->mergeCells("C{$theadRow}:C{$thead2Row}");
            $objActSheet->mergeCells("D{$theadRow}:D{$thead2Row}");
            $objActSheet->mergeCells("E{$theadRow}:F{$theadRow}");
            $objActSheet->mergeCells("E{$thead2Row}:E{$thead2Row}");
            $objActSheet->mergeCells("F{$thead2Row}:F{$thead2Row}");
            $objActSheet->mergeCells("G{$theadRow}:G{$thead2Row}");
            $objActSheet->mergeCells("H{$theadRow}:H{$thead2Row}");
            $objActSheet->mergeCells("I{$theadRow}:I{$thead2Row}");
            $objActSheet->mergeCells("J{$theadRow}:J{$thead2Row}");
            $objActSheet->mergeCells("K{$theadRow}:K{$thead2Row}");

            // set cell border
            for ($row=$offsetRow+1; $row<=$offsetRow+$totalRows+3+1; ++$row) { 
                for ($col=ord('A'); $col<=ord('K'); ++$col) { 
                    $colChr = chr($col);
                    $this->_setBorder($objActSheet, "{$colChr}{$row}");
                }
            }

            // set toffset
            $offsetRow += $totalRows + 3 + 1; // rows + 3个thread + 1个ttitle
            $offsetRow += 2; // 表格之间空开2行
        }
        

        $this->_saveAndExport($objExcel);
    }

    /**
     * Export excel code was reference from http://blog.csdn.net/samxx8/article/details/8138072
     */
    public function exportSolveStatistics($statistics, $assign_start_date, $assign_end_date) {
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
        $objActSheet->setCellValue("A{$lastRow}", "合计");
        $objActSheet->setCellValue("C{$lastRow}", "=SUM(C{$sumStartRow}:C{$sumEndRow})");
        $objActSheet->setCellValue("D{$lastRow}", "=SUM(D{$sumStartRow}:D{$sumEndRow})");
        $objActSheet->setCellValue("E{$lastRow}", "=SUM(E{$sumStartRow}:E{$sumEndRow})");
        $objActSheet->setCellValue("F{$lastRow}", "=SUM(F{$sumStartRow}:F{$sumEndRow})");
        $objActSheet->setCellValue("G{$lastRow}", "=SUM(G{$sumStartRow}:G{$sumEndRow})");
        $objActSheet->setCellValue("H{$lastRow}", "=SUM(H{$sumStartRow}:H{$sumEndRow})");

        // set cell merge
        $objActSheet->mergeCells("A1:I1");
        $objActSheet->mergeCells("A{$lastRow}:B{$lastRow}");

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