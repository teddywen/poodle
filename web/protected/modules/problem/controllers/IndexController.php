<?php
class IndexController extends ProblemController
{
    public $problem_service = null;
    
    public function init(){
        parent::init();
        $this->problem_service = new ProblemService();
    }
    
    /**
     * 问题列表
     */
    public function actionIndex()
    {
        $this->pageTitle = '问题列表';
        $this->breadcrumbs = array("问题列表");
        
        $page = isset($_REQUEST['page'])?intval($_REQUEST['page']):1;
        $condition = $this->setSearchCond();
        $count = $this->problem_service->getProblemByPage($page, $this->PAGE_SIZE, $condition, true);
        $problems = $this->problem_service->getProblemByPage($page, $this->PAGE_SIZE, $condition);
        
        $data['count'] = $count;
        $data['problems'] = $problems;
        $data['page'] = $page;
        
        $this->render('index', $data);
    }
    
    /**
     * 设置查询条件
     * @return array 条件
     */
    private function setSearchCond()
    {
        $condition = array();
        //发布者只能看到自己发布的问题
        if(Yii::app()->user->checkAccess('finder')){
            $condition['release_uid'] = Yii::app()->user->id;
        }
        //解决者只能看到分配给自己的问题
        if(Yii::app()->user->checkAccess('unit')){
            $condition['deal_uid'] = Yii::app()->user->id;
        }
        if(Yii::app()->user->checkAccess('admin')){
            $s_release_uid = isset($_REQUEST['s_release_uid'])?intval($_REQUEST['s_release_uid']):0;
            if(!empty($s_release_uid)){
                $condition['release_uid'] = $s_release_uid;
            }
            $s_deal_uid = isset($_REQUEST['s_deal_uid'])?intval($_REQUEST['s_deal_uid']):0;
            if(!empty($s_deal_uid)){
                $condition['deal_uid'] = $s_deal_uid;
            }
        }
        $s_status = isset($_REQUEST['s_status'])&&strlen($_REQUEST['s_status'])>0?intval($_REQUEST['s_status']):"";
        if(strlen($s_status)){
            $condition['status'] = $s_status;
        }
        $s_delay = isset($_REQUEST['s_delay'])&&strlen($_REQUEST['s_delay'])>0?intval($_REQUEST['s_delay']):"";
        if(strlen($s_delay)){
            $condition['is_delay'] = $s_delay;
        }
        $s_assisted = isset($_REQUEST['s_assisted'])&&strlen($_REQUEST['s_assisted'])>0?intval($_REQUEST['s_assisted']):"";
        if(strlen($s_assisted)){
            $condition['is_assistant'] = $s_assisted;
        }
        $default_start_time = 0; $default_end_time = $_SERVER['REQUEST_TIME'];
        $create_start_time = isset($_REQUEST['create_start_time'])?trim($_REQUEST['create_start_time']):"";
        $create_end_time = isset($_REQUEST['create_end_time'])?trim($_REQUEST['create_end_time']):"";
        if(!empty($create_start_time) || !empty($create_end_time)){
            $create_start_time = empty($create_start_time)?$default_start_time:strtotime($create_start_time.' 00:00:00');
            $create_end_time = empty($create_end_time)?$default_end_time:strtotime($create_end_time.' 23:59:59');
            $condition['create_time'] = array("between" => array($create_start_time, $create_end_time));
        }
        $update_start_time = isset($_REQUEST['update_start_time'])?trim($_REQUEST['update_start_time']):"";
        $update_end_time = isset($_REQUEST['update_end_time'])?trim($_REQUEST['update_end_time']):"";
        if(!empty($update_start_time) || !empty($update_end_time)){
            $update_start_time = empty($create_start_time)?$default_start_time:strtotime($update_start_time.' 00:00:00');
            $update_end_time = empty($update_end_time)?$default_end_time:strtotime($update_end_time.' 23:59:59');
            $condition['update_time'] = array("between" => array($update_start_time, $update_end_time));
        }
        
        return $condition;
    }
    
    /**
     * 查看问题详情
     * @param int $id 问题ID
     */
    public function actionView($id)
    {
        $this->pageTitle = '问题详情';
        
        $problem = $this->problem_service->getProlemById($id);
        //如果不是当前发布人的发布问题则跳回到列表
        if(Yii::app()->user->checkAccess('finder') && $problem->release_uid != Yii::app()->user->id || Yii::app()->user->checkAccess('unit') && $problem->deal_uid != Yii::app()->user->id){
            $this->redirect(Yii::app()->createUrl('problem'));
        }
        $pimg_service = new ProblemImageService();
        $problem_images = $pimg_service->getImagesByPid($id, 1);
        
        $data['problem'] = $problem;
        $data['problem_images'] = $problem_images;
        $data['pimg_service'] = $pimg_service;

        $this->render('view', $data);
    }
}
?>