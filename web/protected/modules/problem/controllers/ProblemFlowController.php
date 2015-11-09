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
    
    /*
     * 设置处理结果
     */
    public function actionSetSolveResult()
    {
        if(!Yii::app()->user->checkAccess('admin')){
            $data['code'] = 0; $data['msg'] = '没有权限';
            exit(json_encode($data));
        }
        $pid = isset($_POST['pid'])?intval($_POST['pid']):0;
        $solve_result = isset($_POST['solve_result'])?intval($_POST['solve_result']):0;
        $problem_log_remark = isset($_POST['problem_log_remark'])?trim($_POST['problem_log_remark']):0;
        $res = $this->problem_service->setSolveResult($pid, $solve_result, $problem_log_remark);

        $data['code'] = 1; $data['msg'] = '审核成功';
        if($res){
            exit(json_encode($data));
        }
        $data['code'] = 0; $data['msg'] = '审核失败';
        exit(json_encode($data));
    }
    
    /**
     * 申请退单
     */
    public function actionBackProblem()
    {
        if(!Yii::app()->user->checkAccess('unit')){
            $data['code'] = 0; $data['msg'] = '没有权限';
            exit(json_encode($data));
        }
        $pid = isset($_POST['pid'])?intval($_POST['pid']):0;
        $problem_log_remark = isset($_POST['problem_log_remark'])?trim($_POST['problem_log_remark']):"";
        $res = $this->problem_service->backProblem($pid, $problem_log_remark);
        $data['code'] = 1; $data['msg'] = '退单申请成功';
        if($res){
            exit(json_encode($data));
        }
        $data['code'] = 0; $data['msg'] = '退单申请失败';
        exit(json_encode($data));
    }
    
    /**
     * 申请延时
     */
    public function actionApplyDelayProblem()
    {
        if(!Yii::app()->user->checkAccess('unit')){
            $data['code'] = 0; $data['msg'] = '没有权限';
            exit(json_encode($data));
        }
        $pid = isset($_POST['pid'])?intval($_POST['pid']):0;
        $problem_log_remark = isset($_POST['problem_log_remark'])?trim($_POST['problem_log_remark']):"";
        $delay_month = isset($_POST['delay_month'])?intval($_POST['delay_month']):0;
        $delay_day = isset($_POST['delay_day'])?intval($_POST['delay_day']):1;
        $delay_time = $delay_month * 30 *24 + $delay_day * 24;
        $res = $this->problem_service->delayProblem($pid, $problem_log_remark, $delay_time);
        $data['code'] = 1; $data['msg'] = '延时申请成功';
        if($res){
            exit(json_encode($data));
        }
        $data['code'] = 0; $data['msg'] = '延时申请失败';
        exit(json_encode($data));
    }
    
    /**
     * 申请联动
     */
    public function actionApplyAssitedProblem()
    {

        if(!Yii::app()->user->checkAccess('unit')){
            $data['code'] = 0; $data['msg'] = '没有权限';
            exit(json_encode($data));
        }
        $pid = isset($_POST['pid'])?intval($_POST['pid']):0;
        $problem_log_remark = isset($_POST['problem_log_remark'])?trim($_POST['problem_log_remark']):"";
        $unit_users = isset($_POST['user_ids'])?$_POST['user_ids']:array();
        $res = $this->problem_service->assitedProblem($pid, $problem_log_remark, $unit_users);
        $data['code'] = 1; $data['msg'] = '联动申请成功';
        if($res){
            exit(json_encode($data));
        }
        $data['code'] = 0; $data['msg'] = '联动申请失败';
        exit(json_encode($data));
    }
}