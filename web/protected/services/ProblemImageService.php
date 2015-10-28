<?php
class ProblemImageService extends Service
{
    /**
     * 插入问题图片
     * @param array $p_imgs 问题图片
     * @param int $pid  问题ID
     * @param int $img_type  问题类型：1发布问题2解决问题
     * @return boolean|Ambigous <number, unknown>
     */
    public function addNewProblemImage($p_imgs = array(), $pid= 0, $img_type = 1)
    {
        if(empty($p_imgs) || empty($pid) || empty($img_type)){
            self::$errorMsg = '图片依据有缺失';
            return false;
        }
        try{
            $cur_time = $_SERVER['REQUEST_TIME'];
            $db = Yii::app()->db;
            $sql = "insert into problem_image(img_path,img_type,status,create_time) values ";
            $values_str = "";
            foreach($p_imgs as $p_img){
                $tmp_path = Yii::app()->params['upload_img_url'].$p_img;
                $values_str .= ",('{$tmp_path}',{$img_type},1,{$cur_time})";
            }
            $values_str = substr($values_str, 1);
            $sql = $sql.$values_str;
            $cmd = $db->createCommand($sql);
            $res = $cmd->execute();
        }
        catch(Exception $e){
            self::$errorMsg = $e->getMessage();
            $res = false;
        }
        return $res;
    }
}