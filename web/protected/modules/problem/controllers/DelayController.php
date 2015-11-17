<?php
class DelayController extends AdminController {
    public $problem_service = null;
    
    public function init(){
        parent::init();
        $this->problem_service = new ProblemService();
    }

    public function actionIndex() {
        $this->pageTitle = '延时申请';
        $this->breadcrumbs = array("延时申请");
        
        $request = Yii::app()->getRequest();
        $delayApplies = $this->problem_service->getWaitingDelayApplies();
        // var_dump($delayApplies);exit();

        $this->render('index', compact("delayApplies"));
    }
}