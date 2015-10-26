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
     * @param int $uid  �û�ID
     * @param int $op_type ����ID
     * @param array $options ������������
     * @return null|OperationLog
     */
    public function writeOperationLog($uid, $op_type, $options = array()) {
        if(!$uid){
            self::$errorMsg = 'д������־ʱuidΪ��';
            return NULL;
        }
        if(!$op_type){
            self::$errorMsg = 'д������־ʱ��������Ϊ��';
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
}