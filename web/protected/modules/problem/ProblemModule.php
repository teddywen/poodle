<?php
class ProblemModule extends CWebModule
{
    public $defaultController = 'index';
    
	public function init(){
		parent::init();
		$this->setImport(array(
		    'image.components.*'
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