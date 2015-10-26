<?php
//图片模块下面的controller都继承此controller
class ImageController extends Controller
{
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
                'roles'=>array('finder', 'unit'),
            ),
            array('deny',
                'users'=>array('*'),
            ),
        );
    }
}
?>