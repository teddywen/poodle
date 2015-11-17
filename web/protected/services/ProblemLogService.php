<?php
class ProblemLogService extends Service
{
    const STATUS_DEFAULT = 1;
    const STATUS_DELAY_WAIT = 1; // 审核中
    const STATUS_DELAY_AGREE = 2; // 已通过
    const STATUS_DELAY_REFUSE = 3; // 不通过
    const STATUS_DELAY_CANCEL = 4; // 已撤销
    const STATUS_DELAY_INVALID = 5; // 无效记录

    /**
     * 添加问题进度日志
     * @param array $data 日志信息
     * @return boolean 添加结果
     */
    public function addNewProblemLog($data = array())
    {
        $problem_log = new ProblemLog();
        $problem_log->attributes = $data;
        $problem_log->status = self::STATUS_DEFAULT;
        $res = $problem_log->save();
        if($res){
            return true;
        }
        self::$errorMsg = print_r($problem_log->getErrors(), true);
        return false;
    }
    
    /**
     * 获得指定问题的处理日志
     * @param int $pid
     * @return array 日记集合
     */
    public function getProblemLog($pid = 0)
    {
        $criteria = new CDbCriteria();
        $criteria->compare('status', 1);
        $criteria->compare('pid', $pid);
        $p_logs = ProblemLog::model()->findAll($criteria);
        return $p_logs;
    }
    
    /**
     * 获得指定状态问题的处理日志
     * @param int $pid 问题ID
     * @param int $cur_status 当前状态
     * @param int $pre_status 上一个状态 
     * @return array 日记集合
     */
    public function getProblemStatusLog($pid = 0, $cur_status = -1, $pre_status = -1)
    {
        $criteria = new CDbCriteria();
        $criteria->compare('status', 1);
        $criteria->compare('pid', $pid);
        if($cur_status >= 0){
            $criteria->compare('cur_status', $cur_status);
        }
        if($pre_status >= 0){
            $criteria->compare('pre_status', $pre_status);
        }
        $p_logs = ProblemLog::model()->findAll($criteria);
        return $p_logs;
    }

    /**
     * 获得所有指定问题的延时申请
     * @param int $pid 问题ID
     * @return array 日记集合
     */
    public function getDelayApply($pid = 0)
    {
        $criteria = new CDbCriteria();
        $criteria->compare('pid', $pid);
        $criteria->compare('cur_status', ProblemService::APPLY_DELAYING);
        $criteria->addCondition("status<>".self::STATUS_DELAY_INVALID);
        $criteria->order = 'update_time DESC';
        $p_logs = ProblemLog::model()->findAll($criteria);
        return $p_logs;
    }

    /**
     * 所有指定问题的延时申请无效化
     * @param int $pid 问题ID
     * @return array [count, affacted]
     */
    public function setDelayApplyInvalid($pid = 0) {
        $sql = "SELECT count(*) FROM `problem_log`
                WHERE `pid`=:id AND `cur_status`=:status_apply_delaying AND `status`<>:status_delay_invalid";
        $params = array(
            ":id" => $pid, 
            ":status_delay_invalid" => self::STATUS_DELAY_INVALID, 
            ":status_apply_delaying" => ProblemService::APPLY_DELAYING, 
        );
        $count = Yii::app()->getDb()->createCommand($sql)->queryScalar($params);

        $sql = "UPDATE `problem_log`
                SET `status`=:status_delay_invalid, `update_time`=:update_time
                WHERE `pid`=:id AND `cur_status`=:status_apply_delaying AND `status`<>:status_delay_invalid";
        $params = array(
            ":id" => $pid, 
            ":update_time" => Util::time(), 
            ":status_delay_invalid" => self::STATUS_DELAY_INVALID, 
            ":status_apply_delaying" => ProblemService::APPLY_DELAYING, 
        );
        $affacted = Yii::app()->getDb()->createCommand($sql)->execute($params);
        return array($count, $affacted);
    }

    /**
     * 所有指定问题的正在等待审核延时申请都撤销掉
     * @param int $pid 问题ID
     * @return array [count, affacted]
     */
    public function setDelayApplyCancel($pid = 0) {
        $sql = "SELECT count(*) FROM `problem_log`
                WHERE `pid`=:id AND `cur_status`=:status_apply_delaying AND `status`=:status_delay_wait";
        $params = array(
            ":id" => $pid, 
            ":status_apply_delaying" => ProblemService::APPLY_DELAYING, 
            ":status_delay_wait" => self::STATUS_DELAY_WAIT, 
        );
        $count = Yii::app()->getDb()->createCommand($sql)->queryScalar($params);

        $sql = "UPDATE `problem_log`
                SET `status`=:status_delay_cancel, `update_time`=:update_time
                WHERE `pid`=:id AND `cur_status`=:status_apply_delaying AND `status`=:status_delay_wait";
        $params = array(
            ":id" => $pid, 
            ":update_time" => Util::time(), 
            ":status_delay_cancel" => self::STATUS_DELAY_CANCEL, 
            ":status_delay_wait" => self::STATUS_DELAY_WAIT, 
            ":status_apply_delaying" => ProblemService::APPLY_DELAYING, 
        );
        $affacted = Yii::app()->getDb()->createCommand($sql)->execute($params);
        return array($count, $affacted);
    }
}