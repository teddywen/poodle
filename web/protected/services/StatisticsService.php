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

        $sql = "SELECT *, FROM_UNIXTIME(`assign_time`, '%Y.%c.%e') AS `assign_date`, 
                    IF(`status`<>:status_qualified, 0, 
                        IF(`assign_time`+7*24*3600>`check_time`, 1, IF(
                            `assign_time`+14*24*3600>`check_time`, 2, IF(
                                `assign_time`+30*24*3600>`check_time`, 3, 4)))) AS `duration_lv`, 
                    FLOOR(`delay_time`/24) AS `delay_day`
                FROM `problem` 
                WHERE `assign_time` BETWEEN :assign_start_time AND :assign_end_time
                ORDER BY `assign_date` ASC, `deal_uid` ASC";
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

    public function exportReleaseStatistics($statistics, $assign_start_date, $assign_end_date) {

    }

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

        // set sheet and set cell content
        $objExcel->setActiveSheetIndex(0);
        $objActSheet = $objExcel->getActiveSheet();
        $objActSheet->setTitle("{$assign_start_date} ~ {$assign_end_date}");
        // set thead
        $objActSheet->setCellValue('A1', '序号');
        $objActSheet->setCellValue('B1', '单位');
        $objActSheet->setCellValue('C1', '问题数');
        $objActSheet->setCellValue('D1', '已整改');
        $objActSheet->setCellValue('E1', '联动问题');
        $objActSheet->setCellValue('F1', '申请延期');
        $objActSheet->setCellValue('G1', '整改超时');
        $objActSheet->setCellValue('H1', '未整改');
        $objActSheet->setCellValue('I1', '备注');
        // set tbody
        foreach ($statistics as $key => $row) {
            $cellRow = $key + 2;
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
        $sumStartRow = 2;
        $sumEndRow = count($statistics) + 1;
        $lastRow = count($statistics) + 2;
        $objActSheet->setCellValue("A{$lastRow}", "合计");
        $objActSheet->setCellValue("C{$lastRow}", "=SUM(C{$sumStartRow}:C{$sumEndRow})");
        $objActSheet->setCellValue("D{$lastRow}", "=SUM(D{$sumStartRow}:D{$sumEndRow})");
        $objActSheet->setCellValue("E{$lastRow}", "=SUM(E{$sumStartRow}:E{$sumEndRow})");
        $objActSheet->setCellValue("F{$lastRow}", "=SUM(F{$sumStartRow}:F{$sumEndRow})");
        $objActSheet->setCellValue("G{$lastRow}", "=SUM(G{$sumStartRow}:G{$sumEndRow})");
        $objActSheet->setCellValue("H{$lastRow}", "=SUM(H{$sumStartRow}:H{$sumEndRow})");
        $objActSheet->mergeCells("A{$lastRow}:B{$lastRow}");

        // set default style
        $objActSheet->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        // set specific style
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