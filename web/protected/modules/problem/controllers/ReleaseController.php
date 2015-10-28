<?php
class ReleaseController extends FinderController
{
    public $problem_service = null;
    
    public function init(){
        parent::init();
        $this->problem_service = new ProblemService();
    }
    
    public function actionIndex()
    {
        $this->pageTitle = '发布问题';
        
        $result_key = 'release_problem_result';
        
        if(isset($_POST) && !empty($_POST)){
            $img_lists = isset($_POST['img_names'])?$_POST['img_names']:array();
            $_POST['release_uid'] = Yii::app()->user->id;
            $_POST['release_username'] = Yii::app()->user->name;
            unset($_POST['img_names']);
            $res = $this->problem_service->addNewProblem($_POST, $img_lists);
            if($res){
                $this->redirect('/problem');
            }
            else{
                $post_data = $_POST;
                $data['post_data'] = $post_data;
                $msg = $this->problem_service->getLastErrMsg();
                Yii::app()->user->setFlash($result_key, '发布失败：'.$msg);
            }
        }
        
        $data['result_key'] = $result_key;
        $this->render('index', $data);
    }
}