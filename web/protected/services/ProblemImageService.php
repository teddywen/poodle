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
            $sql = "insert into problem_image(pid,img_path,img_width,img_height,img_type,status,create_time,update_time) values ";
            $values_str = "";
            foreach($p_imgs as $p_img){
                list($p_path, $p_width, $p_height) = explode(',', $p_img);
                $tmp_path = Yii::app()->params['upload_img_url'].$p_path;
                $values_str .= ",({$pid},'{$tmp_path}','{$p_width}','{$p_height}',{$img_type},1,{$cur_time},{$cur_time})";
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
    
    /**
     * 获得问题相关图片
     * @param int $pid 问题ID
     * @param int $img_type 图片类型
     * @return array 问题图片集合
     */
    public function getImagesByPid($pid = 0, $img_type = 1)
    {
        $criteria = new CDbCriteria();
        $criteria->compare('status', 1);
        $criteria->compare('pid', $pid);
        $criteria->compare('img_type', $img_type);
        $criteria->order = 'id asc';
        $images = ProblemImage::model()->findAll($criteria);
        return $images;
    }
    
    /**
     * 删除问题图片
     * @param int $pid 问题ID
     * @param array $img_paths 删除图片名
     * @return boolean 删除结果
     */
    public function deleteProblemImages($pid = 0, $img_paths = array())
    {
        if(empty($pid) || empty($img_paths)){
            return false;
        }
        
        $criteria = new CDbCriteria();
        $criteria->compare('pid', $pid);
        $criteria->addInCondition('img_path', $img_paths);
        
        $res = ProblemImage::model()->updateAll(array('status' => 0, 'update_time' => time()), $criteria);
        if($res){
            return true;
        }
        return false;
    }
}