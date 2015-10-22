<?php
class IndexController extends UserController
{
    public $user_service = null;
    
    public function init(){
        parent::init();
        $this->user_service = new UserService();
    }
    
    public function actionIndex()
    {
        $this->pageTitle = '用户列表';
        
        $page = !empty($_REQUEST['page'])?intval($_REQUEST['page']):1;
        $condition = array();
        $s_user_name = isset($_REQUEST['s_user_name'])?trim($_REQUEST['s_user_name']):"";
        if(!empty($s_user_name)){
            $condition['user_name'] = $s_user_name;
        }
        $s_status = isset($_REQUEST['s_status'])&&strlen(trim($_REQUEST['s_status']))>0?intval($_REQUEST['s_status']):"";
        if(strlen($s_status)){
            $condition['status'] = $s_status;
        }
        list($lists, $count) = $this->user_service->getAllUsersByPage($page, $this->PAGE_SIZE, $condition);
        
        $data['lists'] = $lists;
        $data['count'] = $count;
        $data['page'] = $page;
        $this->render('index', $data);
    }
    
    public function actionCreate()
    {
        $this->pageTitle = '添加用户';
        $model = new GovUser();
        $result_key = 'create_user_result';
        if(isset($_POST) && $_POST){
            $model = $this->user_service->createGovUser($_POST);
            if(empty($model)){
                Yii::app()->user->setFlash($result_key, $this->user_service->getLastErrMsg());
            }
            else{
                Yii::app()->user->setFlash($result_key, '创建成功');
            }
        }
        $cate_service = new CategoryService();
        $cates = $cate_service->getAvailableCate();
        
        $data['model'] = $model;
        $data['result_key'] = $result_key;
        $data['cates'] = $cates;
        $this->render('create', $data);
    }
}
?>