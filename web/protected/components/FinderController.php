<?php
/**
 * Unit controller. Only unit can access.
 */
class FinderController extends Controller {

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
                'roles'=>array('finder'),
            ),
            array('deny',
                'users'=>array('*'),
            ),
        );
    }
}
?>