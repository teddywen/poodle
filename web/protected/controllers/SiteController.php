<?php

class SiteController extends Controller
{
    public function actionIndex()
    {
        $this->render('index');
    }

    public function actionLogin() {
        $this->layout = "site";
        $this->render("login");
    }

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	        exit($error['message']);
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}
}
