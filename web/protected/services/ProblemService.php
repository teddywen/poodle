<?php
class ProblemService extends Service
{
    //问题状态
    const BE_CREATED = 0;//未分配
    const BE_ASSIGNED = 1;//已经分配(现已废除, 管理员分配问题后直接置为处理中)
    const APPLY_DELAYING = 2;//申请延时
    const APPLY_ASSISTING = 3;//申请联动
    const BE_BACKING = 4;//退单
    const BE_DEALING = 5;//处理中
    const WAIT_CHECKING = 6;//待审核
    const BE_UNQUALIFIED = 7;//打回
    const BE_QUALIFIED = 8;//审核通过
    const BE_CLOSED = 9;//关闭
    const BE_CANCELED = 10;//撤销
    
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
        self::BE_CLOSED => '关闭',
        self::BE_CANCELED => '取销'
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
            
            $res2 = true;
            if(!empty($p_imgs)){
                $pimg_service = new ProblemImageService();
                $res2 = $pimg_service->addNewProblemImage($p_imgs, $model->id);
            }
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
                'update_time' => $cur_time, 
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
            $cur_status = self::BE_DEALING;
            $problem->deal_cate_id = $deal_user->gov_cate_id;
            $problem->deal_uid = $deal_uid;
            $problem->deal_username = $deal_user->username;
            $problem->deal_time = $deal_time;
            $problem->is_delay = 0;
            $problem->delay_count = 0;
            $problem->delay_time = 0;
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

            list($count, $affacted) = $plog_service->setDelayApplyInvalid($pid);
            if ($count > $affacted) {
                throw new Exception("延时申请无效化失败");
            }

            $log_data = array(
                'pid' => $pid,
                'pre_status' => $pre_status,
                'cur_status' => $cur_status,
                'oper_uid' => Yii::app()->user->id,
                'oper_user' => Yii::app()->user->name,
                'log_desc' => Yii::app()->user->name.'将问题分配给'.$deal_user->username,"，时长：".$deal_time,
                'update_time' => $cur_time, 
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
            $problem->accept_time = $cur_time;
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
                'update_time' => $cur_time, 
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
                'update_time' => $cur_time, 
                'create_time' => $cur_time
            );
            $res3 = $plog_service->addNewProblemLog($log_data);
            if(!$res3){
                throw new Exception(self::getLastErrMsg());
            }

            list($count, $affacted) = $plog_service->setDelayApplyCancel($pid);
            if ($count > $affacted) {
                throw new Exception("延时申请撤销失败");
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

            // Check whether times up.
            if ($cur_status == self::BE_QUALIFIED && $problem->accept_time + $problem->deal_time * 3600 < $cur_time) {
                $problem->times_up = 1;
            }

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
                'update_time' => $cur_time, 
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
                'update_time' => $cur_time, 
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
     * @param int $delay_time 延时时间(小时)
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
                'data' => CJSON::encode(array("hour"=>$delay_time)), 
                'update_time' => $cur_time, 
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
                'update_time' => $cur_time, 
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
     * 发布者撤销问题
     * @param int $pid 问题ID
     * @throws Exception 错误信息
     * @return boolean 撤销结果
     */
    public function cancelProblem($pid = 0)
    {
        if(empty($pid)){
            self::$errorMsg = '撤销请求信息缺失';
            return false;
        }
        
        $transaction = Yii::app()->db->beginTransaction();
        try{
            $cur_time = $_SERVER['REQUEST_TIME'];
            $problem = $this->getProlemById($pid);
            $pre_pstatus = $problem->status;
            $cur_status = self::BE_CANCELED;
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
                'log_desc' => Yii::app()->user->name.'撤销问题',
                'update_time' => $cur_time, 
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
     * 获取延时申请 获取还没有审核通过同时又提出延时申请的记录
     * @param int $deal_uid 处理问题人员ID
     * @return array [{`id`, `description`, `remark`, `data`, `log_id`}, ...]
     */
    public function getWaitingDelayApplies() {
        $sql = "SELECT `p`.`id`, `p`.`description`, `pl`.`remark`, `pl`.`data`, `pl`.`id` AS `log_id` 
                FROM `problem` AS `p` 
                INNER JOIN `problem_log` AS `pl` ON `p`.`id`=`pl`.`pid`
                WHERE `p`.`status`=:status_dealing AND `pl`.`status`=:status_delay_waiting
                ORDER BY `pl`.`create_time` DESC";
        $params = array(":status_dealing"=>self::APPLY_DELAYING, ":status_delay_waiting"=>ProblemLogService::STATUS_DELAY_WAIT);

        return Yii::app()->getDb()->createCommand($sql)->queryAll(true, $params);
    }

    /**
     * 同意延时申请
     * @param int $id 问题ID
     * @param int $log_id 延时申请ID
     * @return boolean 操作是否成功
     */
    public function agreeDelay($id, $log_id) {
        $transaction = Yii::app()->db->beginTransaction();
        $res = false;

        try {
            // Get delay hour.
            $sql = "SELECT `data` FROM `problem_log` WHERE `id`=:log_id";
            $params = array(":log_id"=>$log_id);
            $data = Yii::app()->getDb()->createCommand($sql)->queryScalar($params);
            $data = CJSON::decode($data);
            $delay_time = isset($data["hour"]) ? $data["hour"] : 0;

            // Update problem. If apply has approvaled by other admin, the update will not success and rollback transaction.
            $sql = "UPDATE `problem` 
                    INNER JOIN `problem_log` ON `problem_log`.`pid`=`problem`.`id`
                    SET `problem`.`status`=:status_be_dealing, `problem`.`is_delay`=:is_delay, 
                        `problem`.`delay_count`=`problem`.`delay_count`+1, `problem`.`delay_time`=`problem`.`delay_time`+:delay_time, 
                        `problem`.`update_time`=:update_time 
                    WHERE `problem`.`id`=:id AND `problem_log`.`id`=:log_id AND 
                        `problem`.`status`=:status_apply_delaying AND `problem_log`.`status`=:status_delay_wait";
            $params = array(
                ":is_delay" => 1, 
                ":delay_time" => $delay_time, 
                ":update_time" => Util::time(), 
                ":id" => $id, 
                ":log_id" => $log_id, 
                ":status_be_dealing" => self::BE_DEALING, 
                ":status_apply_delaying" => self::APPLY_DELAYING, 
                ":status_delay_wait" => ProblemLogService::STATUS_DELAY_WAIT, 
            );
            $affacted = Yii::app()->getDb()->createCommand($sql)->execute($params);
            if ($affacted == 0) {
                throw new CHttpException(500, "更新问题失败");
            }

            // Update problem log. If apply has approvaled by other admin, the update will not success and rollback transaction.
            $sql = "UPDATE `problem_log`
                    INNER JOIN `problem` ON `problem_log`.`pid`=`problem`.`id`
                    SET `problem_log`.`status`=:status_delay_agree, `problem_log`.`update_time`=:update_time
                    WHERE `problem_log`.`id`=:log_id AND `problem`.`id`=:id AND 
                        `problem_log`.`status`=:status_delay_wait";
            $params = array(
                ":id" => $id, 
                ":log_id" => $log_id, 
                ":status_delay_wait" => ProblemLogService::STATUS_DELAY_WAIT, 
                ":status_delay_agree" => ProblemLogService::STATUS_DELAY_AGREE, 
                ":update_time" => Util::time(), 
            );
            $affacted = Yii::app()->getDb()->createCommand($sql)->execute($params);
            if ($affacted == 0) {
                throw new CHttpException(500, "更新延时申请失败");
            }

            $res = true;
        } catch(Exception $e){
            self::$errorMsg = $e->getMessage();
            $res = false;
        }
        
        if ($res) {
            $transaction->commit();
        } else {
            $transaction->rollback();
        }

        return $res;
    }

    /**
     * 拒绝延时申请
     * @param int $id 问题ID
     * @param int $log_id 延时申请ID
     * @return boolean 操作是否成功
     */
    public function refuseDelay($id, $log_id) {
        $transaction = Yii::app()->db->beginTransaction();
        $res = false;

        try {
            // Update problem. If apply has approvaled by other admin, the update will not success and rollback transaction.
            $sql = "UPDATE `problem` 
                    INNER JOIN `problem_log` ON `problem_log`.`pid`=`problem`.`id`
                    SET `problem`.`status`=:status_be_dealing, `problem`.`update_time`=:update_time
                    WHERE `problem`.`id`=:id AND `problem_log`.`id`=:log_id AND 
                        `problem`.`status`=:status_apply_delaying AND `problem_log`.`status`=:status_delay_wait";
            $params = array(
                ":update_time" => Util::time(), 
                ":id" => $id, 
                ":log_id" => $log_id, 
                ":status_be_dealing" => self::BE_DEALING, 
                ":status_apply_delaying" => self::APPLY_DELAYING, 
                ":status_delay_wait" => ProblemLogService::STATUS_DELAY_WAIT, 
            );
            $affacted = Yii::app()->getDb()->createCommand($sql)->execute($params);
            if ($affacted == 0) {
                throw new CHttpException(500, "更新问题失败");
            }

            // Update problem log. If apply has approvaled by other admin, the update will not success and rollback transaction.
            $sql = "UPDATE `problem_log`
                    INNER JOIN `problem` ON `problem_log`.`pid`=`problem`.`id`
                    SET `problem_log`.`status`=:status_delay_refuse, `problem_log`.`update_time`=:update_time
                    WHERE `problem_log`.`id`=:log_id AND `problem`.`id`=:id AND
                        `problem_log`.`status`=:status_delay_wait";
            $params = array(
                ":id" => $id, 
                ":log_id" => $log_id, 
                ":status_delay_wait" => ProblemLogService::STATUS_DELAY_WAIT, 
                ":status_delay_refuse" => ProblemLogService::STATUS_DELAY_REFUSE, 
                ":update_time" => Util::time(), 
            );
            $affacted = Yii::app()->getDb()->createCommand($sql)->execute($params);
            if ($affacted == 0) {
                throw new CHttpException(500, "更新延时申请失败");
            }

            $res = true;
        } catch(Exception $e){
            self::$errorMsg = $e->getMessage();
            $res = false;
        }
        
        if ($res) {
            $transaction->commit();
        } else {
            $transaction->rollback();
        }

        return $res;
        return true;
    }
}