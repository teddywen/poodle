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
        
        $count = $this->problem_service->getProblemByPage($page, $this->PAGE_SIZE, $condition, true);
        $problems = $this->problem_service->getProblemByPage($page, $this->PAGE_SIZE, $condition);
        
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
        $condition = array();
        //发布者只能看到自己发布的问题
        if(Yii::app()->user->checkAccess('finder')){
            $condition['release_uid'] = Yii::app()->user->id;
        }
        //解决者只能看到分配给自己的问题
        if(Yii::app()->user->checkAccess('unit')){
            $condition['deal_uid'] = Yii::app()->user->id;
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