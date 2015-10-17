<?php
class IndexController extends ImageController
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