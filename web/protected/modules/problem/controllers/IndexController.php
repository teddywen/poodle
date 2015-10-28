<?php
class IndexController extends ProblemController
{
    public $problem_service = null;
    
    public function init(){
        parent::init();
        $this->problem_service = new ProblemService();
    }
    
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
    
    private function setSearchCond()
    {
        return array();
    }
}
?>