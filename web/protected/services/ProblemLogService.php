<?php
class ProblemLogService extends Service
{
    /**
     * 添加问题进度日志
     * @param array $data 日志信息
     * @return boolean 添加结果
     */
    public function addNewProblemLog($data = array())
    {
        $problem_log = new ProblemLog();
        $problem_log->attributes = $data;
        $problem_log->status = 1;
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
}