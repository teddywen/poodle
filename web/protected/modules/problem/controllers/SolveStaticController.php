<?php
class SolveStaticController extends ProblemController {
    public $statistics_service = NULL;

    public function init() {
        parent::init();
        $this->statistics_service = new StatisticsService;
    }

    public function actionIndex() {
        if (!Yii::app()->user->checkAccess("export_solve_problem")) {
            throw new CHttpException(403, "Request fobidden.");
        }

        $this->pageTitle = '整改汇总';
        $this->breadcrumbs = array("整改汇总");
        
        $request = Yii::app()->getRequest();
        $assign_start_date = $request->getParam("assign_start_date", date('Y-m-01'));
        $assign_end_date = $request->getParam("assign_end_date", date('Y-m-d'));
        $preview_assign_start_date = $request->getParam("preview_assign_start_date", date('Y-m-01'));
        $preview_assign_end_date = $request->getParam("preview_assign_end_date", date('Y-m-d'));

        $statistics = $this->statistics_service->getSolveStatistics($assign_start_date, $assign_end_date, Yii::app()->user->checkAccess("export_all") ? 0 : Yii::app()->user->getId());
        $displaySummary = Yii::app()->user->checkAccess("export_all");

        if ($request->getParam("export") !== null) {
            $this->statistics_service->exportSolveStatistics($statistics, $preview_assign_start_date, $preview_assign_end_date, $displaySummary);
        }

        $this->render('index', compact("statistics", "assign_start_date", "assign_end_date", "displaySummary"));
    }
}