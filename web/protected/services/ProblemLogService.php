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
        $res = $problem_log->save();
        if($res){
            return true;
        }
        self::$errorMsg = print_r($problem_log->getErrors(), true);
        return false;
    }
}