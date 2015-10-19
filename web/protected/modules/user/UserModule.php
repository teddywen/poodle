<?php
class UserModule extends CWebModule
{
    public $defaultController = 'index';
    
	public function init(){
		parent::init();
		$this->setImport(array(
		    'user.components.*'
		));
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