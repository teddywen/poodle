<?php

/**
 * Created by PhpStorm.
 * User: gray
 * Date: 2015/10/26
 * Time: 22:42
 */
class OperationLogService extends Service
{


    /**
     * @param int $uid  用户ID
     * @param int $op_type 操作ID
     * @param array $options 其他操作备注
     * @return null|OperationLog
     */
    public function writeOperationLog($uid, $op_type, $options = array()) {
        if(!$uid){
            self::$errorMsg = '用户uid不能为空';
            return NULL;
        }
        if(!$op_type){
            self::$errorMsg = '用户操作ID不能为空';
            return NULL;
        }

        if($options) {
            if(!is_array($options)) {
                $options = array($options);
                $options_ser = serialize($options);
            }
        }

        $log_model = new OperationLog();
        $log_model->uid = $uid;
        $log_model->op_type = $op_type;
        $log_model->op_time = time();
        if(isset($options_ser) && $options_ser) {
            $log_model->op_markup;
        }

        if($log_model->save()) {
            return $log_model;
        }
        self::$errorMsg = print_r($log_model->getErrors(), TRUE);
        return NULL;
    }

    public function getAllOperationLogsByPage($page = 1, $limit = 10, $condition = array())
    {
        $criteria = new CDbCriteria();

        if(isset($condition['op_type'])){
            $criteria->compare('op_type', $condition['op_type']);
        }
        $count = OperationLog::model()->count($criteria);
        $criteria->limit = $limit;
        $criteria->offset = ($page - 1) * $limit;
        $criteria->order = 't.op_time DESC';
        $lists = OperationLog::model()->with(
            array(
                'users' => array('select' => 'username, gov_cate_name, u_type'),
            )
        )->findAll($criteria);
        return array($lists, $count);
    }
}