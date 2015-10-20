<?php
class IndexController extends UserController
{
    public function init(){
        
    }
    
    public function actionIndex()
    {
        $this->render('index');
    }
}
?>