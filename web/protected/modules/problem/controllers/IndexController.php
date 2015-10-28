<?php
class IndexController extends ProblemController
{
    public function actionIndex()
    {
        $this->pageTitle = '问题列表';
        
        $this->render('index');
    }
}
?>