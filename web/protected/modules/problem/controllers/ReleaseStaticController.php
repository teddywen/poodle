<?php
class ReleaseStaticController extends ProblemController {
    public $statistics_service = NULL;

    public function init() {
        parent::init();
        $this->statistics_service = new StatisticsService;
    }

    public function actionIndex() {
        if (!Yii::app()->user->checkAccess("export_release_problem")) {
            throw new CHttpException(403, "Request fobidden.");
        }

        $this->pageTitle = '反馈汇总';
        $this->breadcrumbs = array("反馈汇总");

        $request = Yii::app()->getRequest();
        $assign_start_date = $request->getParam("assign_start_date", date('Y-m-01'));
        $assign_end_date = $request->getParam("assign_end_date", date('Y-m-d'));
        $preview_assign_start_date = $request->getParam("preview_assign_start_date", date('Y-m-01'));
        $preview_assign_end_date = $request->getParam("preview_assign_end_date", date('Y-m-d'));
        $preview = $request->getParam("preview", false);
        $export = $request->getParam("export", false);        
        $with_image = ($preview || $export) ? $request->getParam("with_image", "off") : (Yii::app()->user->checkAccess("admin") ? "off" : "on");
        
        $statistics = $this->statistics_service->getReleaseStatistics($assign_start_date, $assign_end_date, Yii::app()->user->checkAccess("export_all") ? 0 : Yii::app()->user->getId());
        
        if ($request->getParam("export") !== null) {
            $this->statistics_service->exportReleaseStatistics($statistics, $preview_assign_start_date, $preview_assign_end_date, $with_image == "on");
        }
        
        $this->render('index', compact("statistics", "assign_start_date", "assign_end_date", "with_image"));
    }
}