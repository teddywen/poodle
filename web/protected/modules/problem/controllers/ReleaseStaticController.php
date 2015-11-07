<?php
class ReleaseStaticController extends AdminController {
    public $statistics_service = NULL;
    public function init() {
        parent::init();
        $this->statistics_service = new StatisticsService;
    }

    public function actionIndex() {
        $this->pageTitle = '反馈汇总';

        $request = Yii::app()->getRequest();
        $assign_start_date = $request->getParam("assign_start_date", date('Y-m-01'));
        $assign_end_date = $request->getParam("assign_end_date", date('Y-m-d'));

        $statistics = array();
        echo $request->getParam("preview", ""); 
        if ($request->getParam("preview") !== NULL) {
            $statistics = $this->statistics_service->getReleaseStatistics($assign_start_date, $assign_end_date);
        }
        
        $this->render('index', compact("statistics", "assign_start_date", "assign_end_date"));
    }
}