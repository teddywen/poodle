<?php
class ProblemService extends Service
{
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
}