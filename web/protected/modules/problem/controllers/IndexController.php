<?php
class IndexController extends ProblemController
{
    public $problem_service = null;
    
    public function init(){
        parent::init();
        $this->problem_service = new ProblemService();
    }
    
    /**
     * 问题列表
     */
    public function actionIndex()
    {
        $this->pageTitle = '问题列表';
        
        $page = isset($_REQUEST['page'])?intval($_REQUEST['page']):1;
        $condition = $this->setSearchCond();
        
        $count = $this->problem_service->getProblemByPage($page, $this->PAGE_SIZE, $condition, true);
        $problems = $this->problem_service->getProblemByPage($page, $this->PAGE_SIZE, $condition);
        
        $data['count'] = $count;
        $data['problems'] = $problems;
        
        $this->render('index', $data);
    }
    
    /**
     * 设置查询条件
     * @return array 条件
     */
    private function setSearchCond()
    {
        $condition = array();
        //发布者只能看到自己发布的问题
        if(Yii::app()->user->checkAccess('finder')){
            $condition['release_uid'] = Yii::app()->user->id;
        }
        //解决者只能看到分配给自己的问题
        if(Yii::app()->user->checkAccess('unit')){
            $condition['deal_uid'] = Yii::app()->user->id;
        }
        if(Yii::app()->user->checkAccess('admin')){
            $s_release_uid = isset($_REQUEST['s_release_uid'])?intval($_REQUEST['s_release_uid']):0;
            if(!empty($s_release_uid)){
                $condition['release_uid'] = $s_release_uid;
            }
            $s_deal_uid = isset($_REQUEST['s_deal_uid'])?intval($_REQUEST['s_deal_uid']):0;
            if(!empty($s_deal_uid)){
                $condition['deal_uid'] = $s_deal_uid;
            }
        }
        $s_status = isset($_REQUEST['s_status'])&&strlen($_REQUEST['s_status'])>0?intval($_REQUEST['s_status']):"";
        if(strlen($s_status)){
            $condition['status'] = $s_status;
        }
        $s_delay = isset($_REQUEST['s_delay'])&&strlen($_REQUEST['s_delay'])>0?intval($_REQUEST['s_delay']):"";
        if(strlen($s_delay)){
            $condition['is_delay'] = $s_delay;
        }
        $s_assisted = isset($_REQUEST['s_assisted'])&&strlen($_REQUEST['s_assisted'])>0?intval($_REQUEST['s_assisted']):"";
        if(strlen($s_assisted)){
            $condition['is_assistant'] = $s_assisted;
        }
        $default_start_time = 0; $default_end_time = $_SERVER['REQUEST_TIME'];
        $create_start_time = isset($_REQUEST['create_start_time'])?trim($_REQUEST['create_start_time']):"";
        $create_end_time = isset($_REQUEST['create_end_time'])?trim($_REQUEST['create_end_time']):"";
        if(!empty($create_start_time) || !empty($create_end_time)){
            $create_start_time = empty($create_start_time)?$default_start_time:strtotime($create_start_time.' 00:00:00');
            $create_end_time = empty($create_end_time)?$default_end_time:strtotime($create_end_time.' 23:59:59');
            $condition['create_time'] = array("between" => array($create_start_time, $create_end_time));
        }
        $update_start_time = isset($_REQUEST['update_start_time'])?trim($_REQUEST['update_start_time']):"";
        $update_end_time = isset($_REQUEST['update_end_time'])?trim($_REQUEST['update_end_time']):"";
        if(!empty($update_start_time) || !empty($update_end_time)){
            $update_start_time = empty($create_start_time)?$default_start_time:strtotime($update_start_time.' 00:00:00');
            $update_end_time = empty($update_end_time)?$default_end_time:strtotime($update_end_time.' 23:59:59');
            $condition['update_time'] = array("between" => array($update_start_time, $update_end_time));
        }
        return $condition;
    }
    
    /**
     * 查看问题详情
     * @param int $id 问题ID
     */
    public function actionView($id)
    {
        $this->pageTitle = '问题详情';
        
        $problem = $this->problem_service->getProlemById($id);
        //如果不是当前发布人的发布问题则跳回到列表
        if(Yii::app()->user->checkAccess('finder') && $problem->release_uid != Yii::app()->user->id || Yii::app()->user->checkAccess('unit') && $problem->deal_uid != Yii::app()->user->id){
            $this->redirect(Yii::app()->createUrl('problem'));
        }
        $pimg_service = new ProblemImageService();
        $problem_images = $pimg_service->getImagesByPid($id, 1);
        
        $data['problem'] = $problem;
        $data['problem_images'] = $problem_images;
        $data['pimg_service'] = $pimg_service;

        $this->render('view', $data);
    }
    
    //导出列表
    private function exportCsv()
    {
        set_time_limit(0);
        ini_set('memory_limit', '128M');
        session_write_close();
        $_REQUEST['status'] = 15;
    
        $criteria = $this->setSearchCondition();
        $orders = Order::model()->setDbCriteria($criteria);
        $orders = Order::model()->findAll();
    
        $title = "订单号,订单金额,订单状态,备注状态,商户ID,用户ID,会员用户名,收货人,手机号码,商品名称,商品数量,商品单价,抵扣金额,下单时间,支付时间,更新时间,支付方式,支付流水号\n";
        $data_str = iconv("utf-8", "gb2312//IGNORE", $title);
    
        foreach($orders as $order){
            $order_id = $order->id;
            $total_price = $order->total_price;
            $status = OrderService::$status[$order->status];
            $is_remarked = $order->is_remarked?'已处理':'未处理';
            $merchant_id = $order->merchant_id;
            $customer_id = $order->customer_id;
            $customer_name = $order->customer_name;
            $receiver = $order->receiver;
            $customer_phone = $order->customer_phone;
            $item_name = $order->item_name;
            $sum_num = 0; $item_price = array();
            foreach($order->items as $oi){
                $sum_num += $oi->item_num; $item_price[] = $oi->item_price;
            }
            $item_price_str = implode('/', $item_price);
            $use_price = $order->use_price;
            $create_time = date('Y-m-d H:i:s',$order->create_time);
            $pay_time = empty($order->o_pay)?'':date('Y-m-d H:i:s',$order->o_pay->pay_time);
            $update_time = date('Y-m-d H:i:s',$order->update_time);
            $pay_type = '';
            if(!empty($order->o_pay)){
                switch($order->o_pay->pay_type){
                    case 1:
                        $pay_type = '支付宝支付';
                        break;
                    case 2:
                        $pay_type = '微信支付';
                        break;
                    default:
                        $pay_type = '积分支付';
                }
            }
            $pay_serial = empty($order->o_pay)?'':$order->o_pay->pay_serial;
            $t_info = "{$order_id},{$total_price},{$status},{$is_remarked},{$merchant_id},{$customer_id},{$customer_name},{$receiver},{$customer_phone},{$item_name},"
            ."{$sum_num},{$item_price_str},{$use_price},{$create_time},{$pay_time},{$update_time},{$pay_type},{$pay_serial}\n";
    
            $data_str .= iconv("utf-8", "gb2312//IGNORE", $t_info);
        }
    
        $filename = date('Y年m月d日').'导出订单列表.csv';
    
        ob_clean();
        header("Content-type:text/csv");
        header("Content-Disposition:attachment;filename=".$filename);
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        echo $data_str;
        exit;
    }
}
?>