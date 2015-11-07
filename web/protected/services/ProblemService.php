<?php
class ProblemService extends Service
{
    //问题状态
    const BE_CREATED = 0;//未分配
    const BE_ASSIGNED = 1;//已经分配
    const APPLY_DELAYING = 2;//申请延时
    const APPLY_ASSISTING = 3;//申请联动
    const BE_BACKING = 4;//退单
    const BE_DEALING = 5;//处理中
    const WAIT_CHECKING = 6;//待审核
    const BE_UNQUALIFIED = 7;//打回
    const BE_QUALIFIED = 8;//审核通过
    const BE_CLOSED = 9;//关闭
    
    public static $status = array(
        self::BE_CREATED => '未分配',
        self::BE_ASSIGNED => '已经分配',
        self::APPLY_DELAYING => '申请延时',
        self::APPLY_ASSISTING => '申请联动',
        self::BE_BACKING => '退单',
        self::BE_DEALING => '处理中',
        self::WAIT_CHECKING => '待审核',
        self::BE_UNQUALIFIED => '打回',
        self::BE_QUALIFIED => '审核通过',
        self::BE_CLOSED => '关闭'
    );
    
    /**
     * 创建新的问题
     * @param array $data 问题信息
     * @param array $p_imgs 问题图片
     * @throws Exception 异常信息
     * @return boolean 创建结果
     */
    public function addNewProblem($data = array(), $p_imgs = array())
    {
        if(empty($data)){
            self::$errorMsg = '问题资料有缺失';
            return false;
        }
        
        $transaction = Yii::app()->db->beginTransaction();
        try{
            $cur_time = $_SERVER['REQUEST_TIME'];
            $model = new Problem();
            $model->attributes = $data;
            $model->create_time = $cur_time;
            $res1 = $model->save();
            if(!$res1){
                throw new Exception(print_r($model->getErrors(), true));
            }
            
            $pimg_service = new ProblemImageService();
            $res2 = $pimg_service->addNewProblemImage($p_imgs, $model->id);
            if(!$res2){
                throw new Exception($pimg_service->getLastErrMsg());
            }
            
            $plog_service = new ProblemLogService();
            $log_data = array(
                'pid' => $model->id,
                'pre_status' => 0,
                'cur_status' => 0,
                'oper_uid' => Yii::app()->user->id,
                'oper_user' => Yii::app()->user->name,
                'log_desc' => Yii::app()->user->name.'发布问题',
                'create_time' => $cur_time
            );
            $res3 = $plog_service->addNewProblemLog($log_data);
            if(!$res3){
                throw new Exception(self::getLastErrMsg());
            }
            $res = true;
        }
        catch(Exception $e){
            self::$errorMsg = $e->getMessage();
            $res = false;
        }
        if($res){
            $transaction->commit();
        }
        else{
            $transaction->rollback();
        }
        return $res;
    }
    
    /**
     * 获得指定问题信息
     * @param int $pid 问题ID
     * @return Problem 问题信息
     */
    public function getProlemById($pid = 0)
    {
        $problem = Problem::model()->findByPk($pid);
        return $problem;
    }
    
    /**
     * 获得问题列表
     * @param int $page 当前页数
     * @param int $limit 每页问题数量
     * @param array $condition 搜索条件
     * @param boolean $is_count 是否只进行计数
     * @return int or array 数量或者问题集合
     */
    public function getProblemByPage($page = 1, $limit = 10 ,$condition = array(), $is_count = false)
    {
        $criteria = $this->getFindCond($condition);
        
        if($is_count){
            $count = Problem::model()->count($criteria);
            return $count;
        }
        $criteria->limit = $limit;
        $criteria->offset = ($page - 1) * $limit;
        $criteria->order = 'status asc,id asc';
        $problems = Problem::model()->findAll($criteria);
        
        return $problems;
    }
    
    /**
     * 分配处理问题单位
     * @param int $pid 问题ID
     * @param array $data 分配信息
     * @throws Exception 错误信息
     * @return boolean 分配结果
     */
    public function assginDealUser($pid = 0, $data = array())
    {
        $deal_uid = isset($data['deal_uid'])?intval($data['deal_uid']):0;
        if(empty($pid) || empty($deal_uid)){
            self::$errorMsg = '分配单位有缺失';
            return false;
        }
        $transaction = Yii::app()->db->beginTransaction();
        try{
            $cur_time = $_SERVER['REQUEST_TIME'];
            $deal_month = isset($data['deal_month'])?intval($data['deal_month']):0;
            $deal_day = isset($data['deal_day'])?intval($data['deal_day']):1;
            $deal_time = $deal_month * 30 *24 + $deal_day * 24;
            $need_assistant = isset($data['need_assistant'])?intval($data['need_assistant']):0;
            $uint_uids = isset($data['user_ids'])?$data['user_ids']:array();
            $user_service = new UserService();
            $deal_user = $user_service->getGovUserById($deal_uid);
            $problem = $this->getProlemById($pid);
            $pre_status = $problem->status;
            $cur_status = self::BE_ASSIGNED;
            $problem->deal_cate_id = $deal_user->gov_cate_id;
            $problem->deal_uid = $deal_uid;
            $problem->deal_username = $deal_user->username;
            $problem->deal_time = $deal_time;
            $problem->status = $cur_status;
            $problem->assign_time = $cur_time;
            $problem->update_time = $cur_time;
            $problem->is_assistant = $need_assistant;
            $problem->assist_unit = json_encode($uint_uids);
            $res1 = $problem->save();
            if(!$res1){
                throw new Exception(print_r($problem->getErrors(), true));
            }
            
            $plog_service = new ProblemLogService();
            $log_data = array(
                'pid' => $pid,
                'pre_status' => $pre_status,
                'cur_status' => $cur_status,
                'oper_uid' => Yii::app()->user->id,
                'oper_user' => Yii::app()->user->name,
                'log_desc' => Yii::app()->user->name.'将问题分配给'.$deal_user->username,"，时长：".$deal_time,
                'create_time' => $cur_time
            );
            $res2 = $plog_service->addNewProblemLog($log_data);
            if(!$res2){
                throw new Exception(self::getLastErrMsg());
            }
            $res = true;
        }
        catch(Exception $e){
            $res = false;
            self::$errorMsg = $e->getMessage();
        }
        if($res){
            $transaction->commit();
        }
        else{
            $transaction->rollback();
        }
        
        return $res;
    }
    
    /**
     * 单位接收问题
     * @param int $pid 问题ID
     * @throws Exception 错误信息
     * @return boolean 接受结果
     */
    public function acceptProblem($pid = 0)
    {
        if(empty($pid)){
            self::$errorMsg = '问题信息缺失';
            return false;
        }
        
        $transaction = Yii::app()->db->beginTransaction();
        try{
            $cur_time = $_SERVER['REQUEST_TIME'];
            $problem = $this->getProlemById($pid);
            $pre_status = $problem->status;
            $cur_status = self::BE_DEALING;
            $problem->status = $cur_status;
            $problem->update_time = $cur_time;
            $res1 = $problem->save();
            if(!$res1){
                throw new Exception(print_r($problem->getErrors(), true));
            }
            
            $plog_service = new ProblemLogService();
            $log_data = array(
                'pid' => $pid,
                'pre_status' => $pre_status,
                'cur_status' => $cur_status,
                'oper_uid' => Yii::app()->user->id,
                'oper_user' => Yii::app()->user->name,
                'log_desc' => Yii::app()->user->name.'接受处理问题',
                'create_time' => $cur_time
            );
            
            $res2 = $plog_service->addNewProblemLog($log_data);
            if(!$res2){
                throw new Exception(self::getLastErrMsg());
            }
            $res = true;
        }
        catch(Exception $e){
            self::$errorMsg = $e->getMessage();
            $res = false;
        }
        
        if($res){
            $transaction->commit();
        }
        else{
            $transaction->rollback();
        }
        return $res;
    }
    
    /**
     * 单位提交处理问题凭证
     * @param int $pid 问题ID
     * @param array $solve_images
     * @throws Exception 错误信息
     * @return boolean 提交结果
     */
    public function solveProblem($pid = 0, $solve_images = array())
    {
        if(empty($pid) && empty($solve_images)){
            self::$errorMsg = '凭证信息缺失';
            return false;
        }
        
        $transaction = Yii::app()->db->beginTransaction();
        try{
            $cur_time = $_SERVER['REQUEST_TIME'];
            $problem = $this->getProlemById($pid);
            $pre_pstatus = $problem->status;
            $cur_status = self::WAIT_CHECKING;
            $problem->status = $cur_status;
            $problem->update_time = $cur_time;
            $res1 = $problem->save();
            if(!$res1){
                throw new Exception(print_r($problem->getErrors(), true));
            }
            
            $pimg_service = new ProblemImageService();
            $res2 = $pimg_service->addNewProblemImage($solve_images, $pid, 2);
            if(!$res2){
                throw new Exception($pimg_service->getLastErrMsg());
            }
            
            $plog_service = new ProblemLogService();
            $log_data = array(
                'pid' => $pid,
                'pre_status' => $pre_pstatus,
                'cur_status' => $cur_status,
                'oper_uid' => Yii::app()->user->id,
                'oper_user' => Yii::app()->user->name,
                'log_desc' => Yii::app()->user->name.'提交处理问题凭证',
                'create_time' => $cur_time
            );
            $res3 = $plog_service->addNewProblemLog($log_data);
            if(!$res3){
                throw new Exception(self::getLastErrMsg());
            }
            $res = true;
        }
        catch(Exception $e){
            self::$errorMsg = $e->getMessage();
            $res = false;
        }
        
        if($res){
            $transaction->commit();
        }
        else{
            $transaction->rollback();
        }
        return $res;
    }
    
    /**
     * 设置单位处理问题审核结果
     * @param int $pid 问题ID
     * @param int $solve_result 处理结果
     * @param string $problem_log_remark 处理说明
     * @throws Exception 错误信息
     * @return boolean 设置结果
     */
    public function setSolveResult($pid = 0, $solve_result = 0, $problem_log_remark = '')
    {
        if(empty($pid) && strlen($solve_result) == 0){
            self::$errorMsg = '凭证信息缺失';
            return false;
        }
        
        $transaction = Yii::app()->db->beginTransaction();
        try{
            $cur_time = $_SERVER['REQUEST_TIME'];
            $problem = $this->getProlemById($pid);
            $pre_pstatus = $problem->status;
            $cur_status = $solve_result==1?self::BE_QUALIFIED:self::BE_UNQUALIFIED;
            $problem->status = $cur_status;
            $problem->check_time = $cur_time;
            $problem->update_time = $cur_time;
            $res1 = $problem->save();
            if(!$res1){
                throw new Exception(print_r($problem->getErrors(), true));
            }
        
            $plog_service = new ProblemLogService();
            $log_data = array(
                'pid' => $pid,
                'pre_status' => $pre_pstatus,
                'cur_status' => $cur_status,
                'oper_uid' => Yii::app()->user->id,
                'oper_user' => Yii::app()->user->name,
                'log_desc' => Yii::app()->user->name.'审核'.$problem->deal_username.'的处理结果',
                'remark' => $problem_log_remark,
                'create_time' => $cur_time
            );
        
            $res2 = $plog_service->addNewProblemLog($log_data);
            if(!$res2){
                throw new Exception(self::getLastErrMsg());
            }
            $res = true;
        }
        catch(Exception $e){
            self::$errorMsg = $e->getMessage();
            $res = false;
        }
        
        if($res){
            $transaction->commit();
        }
        else{
            $transaction->rollback();
        }
        return $res;
    }
    
    /**
     * 单位申请退单
     * @param int $pid 问题ID
     * @param string $problem_log_remark 退单理由
     * @throws Exception 错误信息
     * @return boolean 退单申请结果
     */
    public function backProblem($pid = 0, $problem_log_remark = '')
    {
        if(empty($pid) && strlen($problem_log_remark) == 0){
            self::$errorMsg = '退单请求信息缺失';
            return false;
        }
        
        $transaction = Yii::app()->db->beginTransaction();
        try{
            $cur_time = $_SERVER['REQUEST_TIME'];
            $problem = $this->getProlemById($pid);
            $pre_pstatus = $problem->status;
            $cur_status = self::BE_BACKING;
            $problem->status = $cur_status;
            $problem->update_time = $cur_time;
            $res1 = $problem->save();
            if(!$res1){
                throw new Exception(print_r($problem->getErrors(), true));
            }
        
            $plog_service = new ProblemLogService();
            $log_data = array(
                'pid' => $pid,
                'pre_status' => $pre_pstatus,
                'cur_status' => $cur_status,
                'oper_uid' => Yii::app()->user->id,
                'oper_user' => Yii::app()->user->name,
                'log_desc' => Yii::app()->user->name.'申请问题退单',
                'remark' => $problem_log_remark,
                'create_time' => $cur_time
            );
        
            $res2 = $plog_service->addNewProblemLog($log_data);
            if(!$res2){
                throw new Exception(self::getLastErrMsg());
            }
            $res = true;
        }
        catch(Exception $e){
            self::$errorMsg = $e->getMessage();
            $res = false;
        }
        
        if($res){
            $transaction->commit();
        }
        else{
            $transaction->rollback();
        }
        return $res;
    }
    
    /**
     * 设置问题延时信息
     * @param int $pid 问题ID
     * @param string $problem_log_remark 延时理由
     * @param int $delay_time 延时时间
     * @param int $delay_status 延时状态
     * @throws Exception 错误信息
     * @return boolean 申请结果
     */
    public function delayProblem($pid = 0, $problem_log_remark = '', $delay_time = 0, $delay_status = 1)
    {
        if(empty($pid) && strlen($problem_log_remark) == 0){
            self::$errorMsg = '延时请求信息缺失';
            return false;
        }
        
        $transaction = Yii::app()->db->beginTransaction();
        try{
            $cur_time = $_SERVER['REQUEST_TIME'];
            $problem = $this->getProlemById($pid);
            $pre_pstatus = $problem->status;
            $cur_status = self::APPLY_DELAYING;
            $problem->status = $cur_status;
            $problem->is_delay = $delay_status;
            if($delay_time > 0){
                $problem->delay_time = $delay_time;
            }
            $problem->update_time = $cur_time;
            $res1 = $problem->save();
            if(!$res1){
                throw new Exception(print_r($problem->getErrors(), true));
            }
        
            $plog_service = new ProblemLogService();
            $log_data = array(
                'pid' => $pid,
                'pre_status' => $pre_pstatus,
                'cur_status' => $cur_status,
                'oper_uid' => Yii::app()->user->id,
                'oper_user' => Yii::app()->user->name,
                'log_desc' => Yii::app()->user->name.'申请延时'.$delay_time.'个小时',
                'remark' => $problem_log_remark,
                'create_time' => $cur_time
            );
        
            $res2 = $plog_service->addNewProblemLog($log_data);
            if(!$res2){
                throw new Exception(self::getLastErrMsg());
            }
            $res = true;
        }
        catch(Exception $e){
            self::$errorMsg = $e->getMessage();
            $res = false;
        }
        
        if($res){
            $transaction->commit();
        }
        else{
            $transaction->rollback();
        }
        return $res;
    }
    
    /**
     * 问题联动设置
     * @param int $pid 问题ID
     * @param string $problem_log_remark 联动理由
     * @param array $unit_users 联动用户ID集合
     * @param int $is_assistant 联动状态
     * @throws Exception 错误信息
     * @return boolean 设置联动结果
     */
    public function assitedProblem($pid = 0, $problem_log_remark = '', $unit_users = array(), $is_assistant = 1)
    {

        if(empty($pid) && strlen($problem_log_remark) == 0){
            self::$errorMsg = '联动请求信息缺失';
            return false;
        }
        
        $transaction = Yii::app()->db->beginTransaction();
        try{
            $cur_time = $_SERVER['REQUEST_TIME'];
            $problem = $this->getProlemById($pid);
            $pre_pstatus = $problem->status;
            $cur_status = self::APPLY_ASSISTING;
            $problem->status = $cur_status;
            $problem->is_assistant = $is_assistant;
            if(!empty($unit_users)){
                $problem->assist_unit = json_encode($unit_users);
            }
            $problem->update_time = $cur_time;
            $res1 = $problem->save();
            if(!$res1){
                throw new Exception(print_r($problem->getErrors(), true));
            }
        
            $plog_service = new ProblemLogService();
            $log_data = array(
                'pid' => $pid,
                'pre_status' => $pre_pstatus,
                'cur_status' => $cur_status,
                'oper_uid' => Yii::app()->user->id,
                'oper_user' => Yii::app()->user->name,
                'log_desc' => Yii::app()->user->name.'申请联动APPLYASSITED['.json_encode($unit_users).']APPLYASSITED个小时',
                'remark' => $problem_log_remark,
                'create_time' => $cur_time
            );
        
            $res2 = $plog_service->addNewProblemLog($log_data);
            if(!$res2){
                throw new Exception(self::getLastErrMsg());
            }
            $res = true;
        }
        catch(Exception $e){
            self::$errorMsg = $e->getMessage();
            $res = false;
        }
        
        if($res){
            $transaction->commit();
        }
        else{
            $transaction->rollback();
        }
        return $res;
    }
}