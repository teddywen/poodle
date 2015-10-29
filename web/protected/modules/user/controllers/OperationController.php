<?php

class OperationController extends UserController
{
    public $op_service = null;

    public function init()
    {
        parent::init();
        $this->op_service = new OperationLogService();
    }

    public function actionIndex()
    {
        $this->pageTitle = '用户操作列表';


        $page = filter_input(INPUT_GET, 'page');
        $page = (isset($page) && $page > 0) ? $page : 1;

        /** @var OperationLogService $op_service */
        $op_service = $this->op_service;
        list($op_log_list, $count) = $op_service->getAllOperationLogsByPage($page, $this->PAGE_SIZE);

        $op_log_list = $this->_timestamp_to_date($op_log_list);

        $data['u_type'] = Yii::app()->params['gov_user_type'];
        $data['op_type_list'] = Yii::app()->params['operation_type'];
        $data['page'] = $page;
        $data['count'] = $count;
        $data['op_log_list'] = $op_log_list;
        $this->render('index', $data);
    }

    public function _timestamp_to_date($model) {
        foreach($model as $record) {
            $record->op_time = Util::timestamp2date($record->op_time);
        }
        return $model;
    }

    // Uncomment the following methods and override them if needed
    /*
    public function filters()
    {
        // return the filter configuration for this controller, e.g.:
        return array(
            'inlineFilterName',
            array(
                'class'=>'path.to.FilterClass',
                'propertyName'=>'propertyValue',
            ),
        );
    }

    public function actions()
    {
        // return external action classes, e.g.:
        return array(
            'action1'=>'path.to.ActionClass',
            'action2'=>array(
                'class'=>'path.to.AnotherActionClass',
                'propertyName'=>'propertyValue',
            ),
        );
    }
    */
}