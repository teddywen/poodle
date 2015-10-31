<?php

class OperationController extends UserController
{
    public $op_service = NULL;
    public $cate_service = NUll;

    public function init()
    {
        parent::init();
        $this->op_service = new OperationLogService();
        $this->cate_service = new CategoryService();
    }

    public function actionIndex()
    {
        $this->pageTitle = '用户操作列表';


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

        //category obj list
        $availableCats = $this->cate_service->getAvailableCate();

        list($op_log_list, $count) = $op_service->getAllOperationLogsByPage($page, $this->PAGE_SIZE, $option);

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