<?php
/**
 * Unit controller. Only unit can access.
 */
class UnitController extends Controller {

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
                'roles'=>array('unit'),
            ),
            array('deny',
                'users'=>array('*'),
            ),
        );
    }
}
?>