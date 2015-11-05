<?php
class CategoryController extends UserController
{
    public $cate_service = null;
    
    public function init()
    {
        parent::init();
        $this->cate_service = new CategoryService();
    }
    
    /**
     * 单位分类列表页
     */
    public function actionIndex()
    {
        $this->pageTitle = '分类列表';
        
        $page = !empty($_REQUEST['page'])?intval($_REQUEST['page']):1;
        $condition = array();
        $s_cate_name = isset($_REQUEST['s_cate_name'])?trim($_REQUEST['s_cate_name']):"";
        if(!empty($s_cate_name)){
            $condition['cate_name'] = array('like' => $s_cate_name);
        }
        $s_status = isset($_REQUEST['s_status'])&&strlen(trim($_REQUEST['s_status']))>0?intval($_REQUEST['s_status']):"";
        if(strlen($s_status)){
            $condition['status'] = $s_status;
        }
        list($lists, $count) = $this->cate_service->getAllCatesByPage($page, $this->PAGE_SIZE, $condition);
        
        $data['lists'] = $lists;
        $data['count'] = $count;
        $data['page'] = $page;
        $this->render('index', $data);
    }
    
    /**
     * 创建单位分类
     */
    public function actionCreate()
    {
        $this->pageTitle = '添加分类';
        $model = new GovCategory();
        $result_key = 'create_cate_result';
        if(isset($_POST) && $_POST){
            $model = $this->cate_service->createGovCate($_POST['cate_name'], $_POST['status']);
            if(empty($model)){
                Yii::app()->user->setFlash($result_key, $this->cate_service->getLastErrMsg());
            }
            else{
                Yii::app()->user->setFlash($result_key, '创建成功');
                $this->redirect(array('update', 'id' => $model->id));
            }
        }
        
        $data['model'] = $model;
        $data['result_key'] = $result_key;
        $this->render('create', $data);
    }
    
    /**
     * 修改分类
     * @param int $id 分类ID
     */
    public function actionUpdate($id)
    {
        $model = $this->cate_service->getGovCateById($id);
        $result_key = 'create_cate_result';
        if(isset($_POST) && $_POST){
            $model = $this->cate_service->updateGovCate($id, $_POST['cate_name'], $_POST['status']);
            if(empty($model)){
                Yii::app()->user->setFlash($result_key, $this->cate_service->getLastErrMsg());
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
     * 更新分类的状态
     */
    public function actionChangeStatus()
    {
        $cid = isset($_REQUEST['cid'])?intval($_REQUEST['cid']):0;
        $status = isset($_REQUEST['status'])?intval($_REQUEST['status']):0;
        
        $res = $this->cate_service->changeStatus($cid, $status);
        if($res){
            exit(json_encode(array('code' => 1, 'msg' => '修改成功')));
        }
        exit(json_encode(array('code' => 0, 'msg' => '修改失败')));
    }
}
?>