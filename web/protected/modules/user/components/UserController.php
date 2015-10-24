<?php
//用户模块下面的controller都继承此controller
class UserController extends Controller
{
    public function init(){
        
    }

    public function filters() {
        return array(
            'accessControl',
        );
    }

    public function accessRules() {
        return array(
            array('deny',
                'users'=>array('?'),
            ),
            array('allow',
                'users'=>array('root'),
            )
        );
    }
}
?>