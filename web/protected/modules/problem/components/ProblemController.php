<?php
//问题模块下面的controller都继承此controller
class ProblemController extends Controller
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
        );
    }
}
?>