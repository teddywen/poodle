<?php
class ManagerController extends SuperAdminController
{
    public $user_service = null;
    
    public function init(){
        parent::init();
        $this->user_service = new UserService();
    }
    
    /**
     * 用户列表
     */
    public function actionIndex()
    {
        $this->pageTitle = '管理员列表';
        $this->breadcrumbs = array("管理员列表");
        
        $page = !empty($_REQUEST['page'])?intval($_REQUEST['page']):1;
        $condition = array();
        $s_username = isset($_REQUEST['s_username'])?trim($_REQUEST['s_username']):"";
        if(!empty($s_username)){
            $condition['username'] = array('like' => $s_username);
        }
        $s_status = isset($_REQUEST['s_status'])&&strlen(trim($_REQUEST['s_status']))>0?intval($_REQUEST['s_status']):"";
        if(strlen($s_status) > 0){
            $condition['status'] = $s_status;
        }
        
        list($lists, $count) = $this->user_service->getAllManagersByPage($page, $this->PAGE_SIZE, $condition);
        
        $data['lists'] = $lists;
        $data['count'] = $count;
        $data['page'] = $page;
        $this->render('index', compact("s_username", "s_status", "lists", "count", "page"));
    }
    
    /**
     * 创建用户
     */
    public function actionCreate()
    {
        $this->pageTitle = '添加管理员';
        $this->breadcrumbs = array("添加管理员");

        $model = new GovUser();
        $result_key = 'create_manager_result';
        if(isset($_POST) && $_POST){
            $_POST['u_type'] = 3;
            $model = $this->user_service->createGovUser($_POST);
            if(empty($model)){
                Yii::app()->user->setFlash($result_key, $this->user_service->getLastErrMsg());
            }
            else{
                Yii::app()->user->setFlash($result_key, '创建成功');
                $this->redirect(array('update', 'id' => $model->id));
            }
        }
        $cate_service = new CategoryService();
        
        $data['model'] = $model;
        $data['result_key'] = $result_key;
        
        $this->render('create', $data);
    }
    
    /**
     * 更新用户
     * @param int $id 用户ID
     */
    public function actionUpdate($id, $back_url = "#")
    {
        $this->pageTitle = '修改管理员';
        $this->breadcrumbs = array("管理员列表"=>urldecode($back_url), "修改管理员");

        $model = $this->user_service->getGovUserById($id);
        $result_key = 'create_manager_result';
        if(isset($_POST) && $_POST){
            $_POST['u_type'] = 3;
            $model = $this->user_service->updateGovUser($id, $_POST);
            if(empty($model)){
                Yii::app()->user->setFlash($result_key, $this->user_service->getLastErrMsg());
            }
            else{
                Yii::app()->user->setFlash($result_key, '修改成功');
            }
        }
        
        $data['model'] = $model;
        $data['result_key'] = $result_key;
        $this->render('update', $data);
    }
    
    /**
     * 更新用户的状态
     */
    public function actionChangeStatus()
    {
        $uid = isset($_REQUEST['uid'])?intval($_REQUEST['uid']):0;
        $user_info = $this->user_service->getGovUserById($uid);
        if($user_info->u_type != 3){
            exit(json_encode(array('code' => 0, 'msg' => '不可修改非管理员状态')));
        }
        $status = isset($_REQUEST['status'])?intval($_REQUEST['status']):0;
        
        $res = $this->user_service->changeStatus($uid, $status);
        if($res){
            exit(json_encode(array('code' => 1, 'msg' => '修改成功')));
        }
        exit(json_encode(array('code' => 0, 'msg' => '修改失败')));
    }
    
    public function actionResetPwd()
    {
        $uid = isset($_REQUEST['uid'])?intval($_REQUEST['uid']):0;
        $user_info = $this->user_service->getGovUserById($uid);
        if($user_info->u_type != 3){
            exit(json_encode(array('code' => 0, 'msg' => '不可重置非管理员密码')));
        }
        $res = $this->user_service->resetUserPwd($uid);
        exit(json_encode(array('code' => 1, 'msg' => '重置成功')));
    }
}
?>