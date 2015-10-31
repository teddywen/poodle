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
        
        $page = isset($_REQUEST['page'])?intval($_REQUEST['page']):1;
        $condition = $this->setSearchCond();
        
        $count = $this->problem_service->getProblemByPage($page, $this->PAGE_SIZE, array(), true);
        $problems = $this->problem_service->getProblemByPage($page, $this->PAGE_SIZE);
        
        $data['count'] = $count;
        $data['problems'] = $problems;
        
        $this->render('index', $data);
    }
    
    /**
     * 设置查询条件
     * @return array 条件
     */
    private function setSearchCond()
    {
        return array();
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
        if(Yii::app()->user->checkAccess('finder') && $problem->release_uid != Yii::app()->user->id){
            $this->redirect(Yii::app()->createUrl('problem'));
        }
        $pimg_service = new ProblemImageService();
        $problem_images = $pimg_service->getImagesByPid($id, 1);
        
        $data['problem'] = $problem;
        $data['problem_images'] = $problem_images;
        
        $this->render('view', $data);
    }
    
    public function actionAssignDealUser()
    {
        $pid = isset($_POST['pid'])?intval($_POST['pid']):0;
        unset($_POST['pid']);
        $res = $this->problem_service->assginDealUser($pid, $_POST);
        $data['code'] = 1; $data['msg'] = '分配成功';
        if($res){
            exit(json_encode($data));
        }
        $data['code'] = 0; $data['msg'] = '分配失败';
        exit(json_encode($data));
    }
}
?>