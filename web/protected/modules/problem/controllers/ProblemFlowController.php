<?php
//问题处理业务流程控制器
class ProblemFlowController extends ProblemController
{
    public $problem_service = null;
    public function init(){
        parent::init();
        $this->problem_service = new ProblemService();
    }
    
    /**
     * 管理员分配
     */
    public function actionAssignDealUser()
    {
        if(!Yii::app()->user->checkAccess('admin')){
            $data['code'] = 0; $data['msg'] = '没有权限';
            exit(json_encode($data));
        }
        $pid = isset($_POST['pid'])?intval($_POST['pid']):0;
        unset($_POST['pid']);
        $res = $this->problem_service->assginDealUser($pid, $_POST);
        $data['code'] = 1; $data['msg'] = '分配成功';
        if($res){
            exit(json_encode($data));
        }
        $data['code'] = 0; $data['msg'] = '分配失败';
        exit(json_encode($data));
    }
    
    /**
     * 处理单位接受问题
     */
    public function actionAcceptProblem()
    {
        if(!Yii::app()->user->checkAccess('unit')){
            $data['code'] = 0; $data['msg'] = '没有权限';
            exit(json_encode($data));
        }
        
        $pid = isset($_POST['pid'])?intval($_POST['pid']):0;
        
        $res = $this->problem_service->acceptProblem($pid);

        $data['code'] = 1; $data['msg'] = '接受成功';
        if($res){
            exit(json_encode($data));
        }
        $data['code'] = 0; $data['msg'] = '接受失败';
        exit(json_encode($data));
    }
    
    public function actionSetSolveResult()
    {
        if(!Yii::app()->user->checkAccess('admin')){
            $data['code'] = 0; $data['msg'] = '没有权限';
            exit(json_encode($data));
        }
        $pid = isset($_POST['pid'])?intval($_POST['pid']):0;
        $solve_result = isset($_POST['solve_result'])?intval($_POST['solve_result']):0;
        $res = $this->problem_service->setSolveResult($pid, $solve_result);

        $data['code'] = 1; $data['msg'] = '审核成功';
        if($res){
            exit(json_encode($data));
        }
        $data['code'] = 0; $data['msg'] = '审核失败';
        exit(json_encode($data));
    }
}