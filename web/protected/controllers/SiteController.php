<?php

class SiteController extends Controller
{
    public $layout = "site";
    public $op_log_service = NULL;

    public function init() {
        parent::init();
        $this->op_log_service = new OperationLogService();
    }

    public function actionIndex()
    {
        $this->pageTitle = '登录';
        $this->actionLogin();
    }

    public function actionLogin() {
        if (!Yii::app()->user->getIsGuest()) {
            $this->goToMainPage();
        }

        $this->render("login");
    }

    public function actionDoLogin() {
        $request = Yii::app()->getRequest();
        if (!$request->getIsAjaxRequest()) {
            echo CJSON::encode(array("ok"=>false, "msg"=>"Invalid request."));
            Yii::app()->end();
        }

        $error = "";
        $username = $request->getParam("username", "");
        $password = $request->getParam("password", "");
        $remember_me = $request->getParam("remember_me", "on");
        
        if (trim($username) == "") {
            $error = "用户名不能为空";
        } else if ($password == "") {
            $error = "密码不能为空";
        } else {
            $identity = new UserIdentity($username, $password);
            if($identity->authenticate()) {
                $duration = $remember_me == "on" ? 3600*24*7 : 0;
                Yii::app()->user->login($identity, $duration);
                list($updated, $error) = Yii::app()->user->updateLastLoginTime();
                echo $updated ? CJSON::encode(array("ok"=>true, "msg"=>"登陆成功")) : CJSON::encode(array("ok"=>true, "msg"=>$error));
                Yii::app()->end();
            } else {
                $error = $identity->errorMessage;
            }
        }
        echo CJSON::encode(array("ok"=>false, "msg"=>$error));
    }

    protected function goToMainPage() {
        /** @var OperationLogService $op_log_service */
        $op_log_service = $this->op_log_service;
        if (Yii::app()->user->checkAccess("unit")) {
            $op_log_service->writeOperationLog(Yii::app()->user->getId(), ConstVariables::OP_USER_LOGIN);
            $this->redirect("/problem/index?nav_status=5");
        } else if (Yii::app()->user->checkAccess("finder")) {
            $op_log_service->writeOperationLog(Yii::app()->user->getId(), ConstVariables::OP_USER_LOGIN);
            $this->redirect("/problem/release");
        } else if (Yii::app()->user->checkAccess("admin")) {
            $op_log_service->writeOperationLog(Yii::app()->user->getId(), ConstVariables::OP_USER_LOGIN);
            $this->redirect("/problem");
        } else if (Yii::app()->user->checkAccess("superAdmin")) {
            $op_log_service->writeOperationLog(Yii::app()->user->getId(), ConstVariables::OP_USER_LOGIN);
            $this->redirect("/user/manager");
        } else {
            Yii::app()->user->logout();
            throw new CHttpException(500, Yii::t("Site", "privilige error."));
        }
    }

    public function actionLogout() {
        /** @var OperationLogService $op_log_service */
        $op_log_service = $this->op_log_service;
        if (!Yii::app()->user->getIsGuest()) {
            $op_log_service->writeOperationLog(Yii::app()->user->getId(), ConstVariables::OP_USER_LOGOUT);
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
