<?php
class IndexController extends UserController
{
    public function __construct($id){
        parent::__construct($id);
    }
    
    public function actionIndex()
    {
        echo 'user lists';
    }
}
?>