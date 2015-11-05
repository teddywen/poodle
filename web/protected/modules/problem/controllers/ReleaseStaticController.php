<?php
class ReleaseStaticController extends AdminController
{
    public function actionIndex()
    {
        $this->pageTitle = '反馈汇总';
        $data = array();
        
        $this->render('index', $data);
    }
}