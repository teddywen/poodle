<?php
class ReleaseController extends FinderController
{
    public function actionIndex()
    {
        $this->pageTitle = '发布问题';
        
        $result_key = 'release_problem_result';
        
        $data['result_key'] = $result_key;
        $this->render('index', $data);
    }
}