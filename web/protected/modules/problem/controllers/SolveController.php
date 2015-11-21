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
        $pimg_service = new ProblemImageService();
        if(empty($problem) || $problem->deal_uid != Yii::app()->user->id){
            Yii::app()->user->setFlash($result_key, '问题信息异常');
        }
        elseif(isset($_POST) && !empty($_POST)){
            $img_lists = isset($_POST['img_names'])?$_POST['img_names']:array();
            $modify_solve = isset($_POST['modify_solve'])?intval($_POST['modify_solve']):0;
            //重新提交审核
            if($modify_solve == 1){
                $cur_images = isset($_POST['cur_images'])?$_POST['cur_images']:array();
                $delete_image_names = array();
                //选出被删除的照片，删除照片
                $delete_images = array_diff($cur_images, $img_lists);
                foreach($delete_images as $delete_image){
                    $delete_image_name = explode(',', $delete_image);
                    $delete_image_names[] = Yii::app()->params['upload_img_url'].$delete_image_name[0];
                }
                $pimg_service->deleteProblemImages($pid, $delete_image_names);
                //选出新添加的照片，添加照片
                $img_lists = array_diff($img_lists, $cur_images);
            }
            $res = true;
            //第一次提交审核或者修改有新照片时，插入新的图片
            if($modify_solve == 0 || !empty($img_lists)){
                $res = $this->problem_service->solveProblem($pid, $img_lists);
            }
            if($res){
                $this->redirect('/problem/index/view?id='.$pid);
            }
            else{
                $msg = $this->problem_service->getLastErrMsg();
                Yii::app()->user->setFlash($result_key, '提交凭证失败：'.$msg);
            }
        }
        $solve_images = $pimg_service->getImagesByPid($pid, 2);
        
        $data['problem'] = $problem;
        $data['result_key'] = $result_key;
        $data['solve_images'] = $solve_images;
        
        $this->render('index', $data);
    }
}