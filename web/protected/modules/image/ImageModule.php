<?php
class ImageModule extends CWebModule
{
    public $defaultController = 'index';
    
	public function init(){
		parent::init();
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			return true;
		}
		else
			return false;
	}
}
?>