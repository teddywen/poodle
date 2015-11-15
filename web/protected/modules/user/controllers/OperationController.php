<?php

class OperationController extends UserController
{
    public $op_service = NULL;
    public $cate_service = NUll;

    const DAY_BEGIN = '00:00:00';
    const DAY_END = '23:59:59';
    const DATE_PICKER_FORMAT = 'yyyy/MM/dd HH:mm:ss';

    public function init()
    {
        parent::init();
        $this->op_service = new OperationLogService();
        $this->cate_service = new CategoryService();
    }

    public function actionIndex()
    {
        $this->pageTitle = '日志列表';
        $this->breadcrumbs = array("日志列表");

        $page = filter_input(INPUT_GET, 'page');
        $page = (isset($page) && $page > 0) ? $page : 1;

        $op_type_value = filter_input(INPUT_GET, 'op_type_value');
        $op_type_value = (isset($op_type_value) && $op_type_value) ? $op_type_value : '';
        $u_type_value = filter_input(INPUT_GET, 'u_type_value');
        $u_type_value = (isset($u_type_value) && $u_type_value) ? $u_type_value : '';
        $u_cate_value = filter_input(INPUT_GET, 'u_cate_value');
        $u_cate_value = (isset($u_cate_value) && $u_cate_value) ? $u_cate_value : '';
        $u_name_value = filter_input(INPUT_GET, 'u_name_value');
        $u_name_value = (isset($u_name_value) && $u_name_value) ? $u_name_value : '';
        $u_date_from = filter_input(INPUT_GET, 'u_date_from');
        $u_date_from = (isset($u_date_from) && $u_date_from) ? $u_date_from : '';
        $u_date_to = filter_input(INPUT_GET, 'u_date_to');
        $u_date_to = (isset($u_date_to) && $u_date_to) ? $u_date_to : '';

        //Convert date_from and date_to type from date to timestamp and add them hour, minute and second
        $date_from_timestamp = $this->_format_date($u_date_from, 'day_begin');
        $date_to_timestamp = $this->_format_date($u_date_to, 'day_end');

        /** @var OperationLogService $op_service */
        $op_service = $this->op_service;
        $option = array();
        if($op_type_value) {
            $option['op_type'] = $op_type_value;
        }
        if($u_type_value) {
            $option['u_type'] = $u_type_value;
        }
        if($u_cate_value) {
            $option['u_cate_value'] = $u_cate_value;
        }
        if($u_name_value) {
            $option['u_name_value'] = $u_name_value;
        }
        if($date_from_timestamp) {
            $option['u_date_from'] = $date_from_timestamp;
        }
        if($date_to_timestamp) {
            $option['u_date_to'] = $date_to_timestamp;
        }


        //get category obj list
        $availableCats = $this->cate_service->getAvailableCate();

        list($op_log_list, $count) = $op_service->getAllOperationLogsByPage($page, $this->PAGE_SIZE, $option);
        //convert timesatmp to date
        $op_log_list = $this->_timestamp_to_date($op_log_list);


        $data['u_types'] = Yii::app()->params['gov_user_type'];
        $data['op_type_list'] = Yii::app()->params['operation_type'];
        $data['cates'] = $availableCats;
        $data['page'] = $page;
        $data['count'] = $count;
        $data['op_log_list'] = $op_log_list;
        $data['op_type_value'] = $op_type_value;
        $data['u_type_value'] = $u_type_value;
        $data['u_cate_value'] = $u_cate_value;
        $data['u_name_value'] = $u_name_value;
        $data['u_date_from'] = $u_date_from;
        $data['u_date_to'] = $u_date_to;
        $this->render('index', $data);
    }

    public function _timestamp_to_date($model) {
        foreach($model as $record) {
            $record->op_time = Util::timestamp2date($record->op_time);
        }
        return $model;
    }

    public function _format_date($date, $time_slot = 'day_begin') {
        $timestamp = '';
        if($time_slot == 'day_begin') {
            $date .= ' ' . self::DAY_BEGIN;
        }
        if($time_slot == 'day_end') {
            $date .= ' ' . self::DAY_END;
        }
        return Util::date2timestamp($date, self::DATE_PICKER_FORMAT);
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