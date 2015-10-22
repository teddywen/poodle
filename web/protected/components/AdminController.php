<?php
/**
 * Admin controller. Only admin can access.
 */
class AdminController extends Controller {

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
                'users'=>array('admin'),
            ),
            array('deny',
                'users'=>array('*'),
            ),
        );
    }
}
?>