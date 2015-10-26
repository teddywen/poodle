<?php

class SiteController extends Controller
{
    public $layout = "site";

    public function actionIndex() {
        $this->actionLogin();
    }

    public function actionLogin() {
        if (!Yii::app()->user->getIsGuest()) {
            $this->goToMainPage();
        }

        $request = Yii::app()->getRequest();
        $error = "";
        $username = $request->getParam("username", "");
        $password = $request->getParam("password", "");

        $login = $request->getParam("login", null);
        if ($login !== null) {
            if (trim($username) == "") {
                $error = Yii::t("Site", "user name is empty.");
            } else if ($password == "") {
                $error = Yii::t("Site", "pass word is empty.");
            } else {
                $identity = new UserIdentity($username, $password);
                if($identity->authenticate()) {
                    Yii::app()->user->login($identity, 3600*24*7);
                    $this->goToMainPage();
                } else {
                    $error = $identity->errorMessage;
                }
            }
        }
        
        $this->render("login", compact("username", "password", "error"));
    }

    protected function goToMainPage() {
        if (Yii::app()->user->checkAccess("unit")) {
            $this->redirect("/user/upload");
        } else if (Yii::app()->user->checkAccess("finder")) {
            $this->redirect("/problem/release");
        } else if (Yii::app()->user->checkAccess("admin")) {
            $this->redirect("/user/index");
        } else if (Yii::app()->user->checkAccess("superAdmin")) {
            $this->redirect("/user/manager");
        } else {
            Yii::app()->user->logout();
            throw new CHttpException(500, Yii::t("Site", "privilige error."));
        }
    }

    public function actionLogout() {
        if (!Yii::app()->user->getIsGuest()) {
            Yii::app()->user->logout();
        }
        $this->redirect("/site/index");
    }

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError() {
	    if($error = Yii::app()->errorHandler->error) {
	    	if(Yii::app()->request->getIsAjaxRequest())
                echo CJSON::encode(array("ok"=>false, "msg"=>$error["message"]));
	    	else
	        	$this->render('error', $error);
	    }
	}
}
