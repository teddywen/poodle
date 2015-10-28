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
                throw new  Exception(print_r($model->getErrors(), true));
            }
            $pimg_service = new ProblemImageService();
            $res2 = $pimg_service->addNewProblemImage($p_imgs, $model->id);
            if(!$res2){
                throw new Exception($pimg_service->getLastErrMsg());
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
     * @param array $condion 搜索条件
     * @param boolean $is_count 是否只进行计数
     * @return int or array 数量或者问题集合
     */
    public function getProblemByPage($page = 1, $limit = 10 ,$condion = array(), $is_count = false)
    {
        $criteria = new CDbCriteria();
        
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
}