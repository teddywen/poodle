<?php
/**
 * Super admin controller. Only super admin can access.
 */
class SuperAdminController extends Controller {
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
                'users'=>array('superAdmin'),
            ),
            array('deny',
                'users'=>array('*'),
            ),
        );
    }
}
?>