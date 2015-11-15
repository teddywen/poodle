<?php
class SolveController extends UnitController
{
    public $problem_service = null;
    public function init(){
        parent::init();
        $this->problem_service = new ProblemService();
    }
    
    public function actionIndex()
    {
        $back_url_top = Yii::app()->getRequest()->getParam("back_url_top", "#");
        $back_url = Yii::app()->getRequest()->getParam("back_url", "#");
        $this->pageTitle = '提交解决问题凭证';
        $this->breadcrumbs = array("问题列表"=>urldecode($back_url_top), "问题详情"=>urldecode($back_url), "提交解决问题凭证");
        
        $pid = isset($_REQUEST['pid'])?intval($_REQUEST['pid']):0;
        $result_key = 'solve_problem_result';
        $problem = $this->problem_service->getProlemById($pid);
        if(empty($problem)){
            Yii::app()->user->setFlash($result_key, '问题信息新一场');
        }
        elseif(isset($_POST) && !empty($_POST)){
            $img_lists = isset($_POST['img_names'])?$_POST['img_names']:array();
            $res = $this->problem_service->solveProblem($pid, $img_lists);
            if($res){
                $this->redirect('/problem/index/view?id='.$pid);
            }
            else{
                $msg = $this->problem_service->getLastErrMsg();
                Yii::app()->user->setFlash($result_key, '提交凭证失败：'.$msg);
            }
        }
        
        $data['problem'] = $problem;
        $data['result_key'] = $result_key;
        $this->render('index', $data);
    }
}