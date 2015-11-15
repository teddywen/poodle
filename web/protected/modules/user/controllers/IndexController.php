<?php
class IndexController extends UserController
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
        $this->pageTitle = '用户列表';
        $this->breadcrumbs = array("用户列表");
        
        $page = !empty($_REQUEST['page'])?intval($_REQUEST['page']):1;
        $condition = array();
        $s_username = isset($_REQUEST['s_username'])?trim($_REQUEST['s_username']):"";
        if(!empty($s_username)){
            $condition['username'] = $s_username;
        }
        $s_status = isset($_REQUEST['s_status'])&&strlen(trim($_REQUEST['s_status']))>0?intval($_REQUEST['s_status']):"";
        if(strlen($s_status) > 0){
            $condition['status'] = $s_status;
        }
        $s_gov_cate_id = isset($_REQUEST['s_gov_cate_id'])&&strlen(trim($_REQUEST['s_gov_cate_id']))>0?intval($_REQUEST['s_gov_cate_id']):"";
        if(strlen($s_gov_cate_id) > 0){
            $condition['gov_cate_id'] = $s_gov_cate_id;
        }
        $s_u_type = isset($_REQUEST['s_u_type'])&&strlen(trim($_REQUEST['s_u_type']))>0?intval($_REQUEST['s_u_type']):"";
        if(strlen($s_u_type) > 0){
            $condition['u_type'] = $s_u_type;
        }
        list($lists, $count) = $this->user_service->getAllUsersByPage($page, $this->PAGE_SIZE, $condition);
        
        $cate_service = new CategoryService();
        $cates = $cate_service->getAvailableCate();
        $data['lists'] = $lists;
        $data['count'] = $count;
        $data['page'] = $page;
        $data['cates'] = $cates;
        $this->render('index', $data);
    }
    
    /**
     * 创建用户
     */
    public function actionCreate()
    {
        $this->pageTitle = '添加用户';
        $this->breadcrumbs = array("添加用户");

        $model = new GovUser();
        $result_key = 'create_user_result';
        if(isset($_POST) && $_POST){
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
        $cates = $cate_service->getAvailableCate();
        
        $data['model'] = $model;
        $data['result_key'] = $result_key;
        $data['cates'] = $cates;
        $this->render('create', $data);
    }
    
    /**
     * 更新用户
     * @param int $id 用户ID
     */
    public function actionUpdate($id, $back_url = "#")
    {
        $this->pageTitle = '修改用户';
        $this->breadcrumbs = array("用户列表"=>urldecode($back_url), "修改用户");

        $model = $this->user_service->getGovUserById($id);
        $result_key = 'create_user_result';
        if(isset($_POST) && $_POST){
            $model = $this->user_service->updateGovUser($id, $_POST);
            if(empty($model)){
                Yii::app()->user->setFlash($result_key, $this->user_service->getLastErrMsg());
            }
            else{
                Yii::app()->user->setFlash($result_key, '修改成功');
            }
        }
        $cate_service = new CategoryService();
        $cates = $cate_service->getAvailableCate();
        
        $data['model'] = $model;
        $data['result_key'] = $result_key;
        $data['cates'] = $cates;
        $this->render('update', $data);
    }
    
    /**
     * 更新用户的状态
     */
    public function actionChangeStatus()
    {
        $uid = isset($_REQUEST['uid'])?intval($_REQUEST['uid']):0;
        $status = isset($_REQUEST['status'])?intval($_REQUEST['status']):0;
        
        $res = $this->user_service->changeStatus($uid, $status);
        if($res){
            exit(json_encode(array('code' => 1, 'msg' => '修改成功')));
        }
        exit(json_encode(array('code' => 0, 'msg' => '修改失败')));
    }
    
    /**
     * 重置密码
     */
    public function actionResetPwd()
    {
        $uid = isset($_REQUEST['uid'])?intval($_REQUEST['uid']):0;
        $res = $this->user_service->resetUserPwd($uid);
        exit(json_encode(array('code' => 1, 'msg' => '重置成功')));
    }
    
    /**
     * 指派任务时指定分类下的用户ID
     */
    public function actionGetCateUsers()
    {
        $cate_id = isset($_REQUEST['cate_id'])?intval($_REQUEST['cate_id']):0;
        $users = $this->user_service->getUserByCate($cate_id);
        $u_infos = array();
        if(!empty($users)){
            foreach($users as $user){
                $u_infos[] = array('uid' => $user->id, 'username' => $user->username);
            }
        }
        
        exit(json_encode($u_infos));
    }
}
?>