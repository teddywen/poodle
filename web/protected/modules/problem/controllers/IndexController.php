<?php
class IndexController extends ProblemController
{
    public function __construct($id){
        parent::__construct($id);
    }
    
    public function actionIndex()
    {
        echo 'image lists';
    }
}
?>