<?php
class SolveStaticController extends AdminController
{
    public function actionIndex()
    {
        $this->pageTitle = '整改汇总';
        $data = array();
        
        $this->render('index', $data);
    }
}