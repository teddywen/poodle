<?php
class IndexController extends AdminController
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
        $s_cate_name = isset($_REQUEST['s_cate_name'])?trim($_REQUEST['s_cate_name']):"";
        if(!empty($s_cate_name)){
            $condition['cate_name'] = $s_cate_name;
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
}
?>