<?php

class ModpassController extends Controller
{
    public $layout = "main";

    public $user_service = null;
    
    public function init(){
        parent::init();
        $this->user_service = new UserService();
    }

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
        );
    }

    public function actionIndex() {
        $this->pageTitle = '修改密码';
        $ok = false;
        $error = "";
        $request = Yii::app()->getRequest();
        $old_pass = $request->getParam("old_pass", "");
        $new_pass = $request->getParam("new_pass", "");
        $new_pass_confirm = $request->getParam("new_pass_confirm", "");

        if ($request->getParam("submit", "") != "") {
            do {
                if ($old_pass == "") {
                    $error = "密码不能为空";
                    break;
                }

                if ($new_pass == "") {
                    $error = "新密码不能为空";
                    break;
                }

                if ($old_pass == $new_pass) {
                    $error = "新密码必须和原密码不同";
                    break;
                }

                if ($new_pass != $new_pass_confirm) {
                    $error = "新密码重复错误";
                    break;
                }

                $model = Yii::app()->user->getGovUser();
                if (!CPasswordHelper::verifyPassword($old_pass, $model->password)) {
                    $error = "密码错误";
                    break;
                }
                $model->password = CPasswordHelper::hashPassword($new_pass);
                $model->update_time = Util::time();
                if ($model->save()) {
                    Yii::app()->user->logout();
                    $ok = true;
                } else 
                    throw new CHttpException(500, print_r($model->getErrors(), true));
            } while (false);
        }

        $this->render("index", compact("ok", "error", "old_pass", "new_pass", "new_pass_confirm"));
    }
}
