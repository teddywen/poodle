<?php
/**
 * 统计汇总Service
 */
class StatisticsService extends Service {

    /**
     * @param string $assign_start_date - Y-m-d
     * @param string $assign_end_date - Y-m-d
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
     * @return array - [[<column `problem`.*, `assign_date`, `duration_lv`>], ...]
     */
    private function _getReleaseRows($assign_start_date, $assign_end_date) {
        $assign_start_date = date("Y-m-d 00:00:00", strtotime($assign_start_date));
        $assign_end_date = date("Y-m-d 23:59:59", strtotime($assign_end_date));

        $sql = "SELECT *, FROM_UNIXTIME(`assign_time`, '%Y.%c.%e') AS `assign_date`, 
                    IF(`status`<>:status_qualified, 0, 
                        IF(`assign_time`+7*24*3600>`check_time`, 1, IF(
                            `assign_time`+14*24*3600>`check_time`, 2, IF(
                                `assign_time`+30*24*3600>`check_time`, 3, 4)))) AS `duration_lv`
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

    public function getSolveStatistics() {
        
    }
}