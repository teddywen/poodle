<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
    public $PAGE_SIZE = 1;
    
    public function init(){
        if (isset(Yii::app()->params["page_size"]["common"]))
            $this->PAGE_SIZE = Yii::app()->params["page_size"]["common"];
    }
}