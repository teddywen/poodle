<?php
class UploadService extends Service
{
    const IMAGE_MAX_SIZE = 800;//图片最长的一边边长
    
    public function uploadImage($up_name = 'problem_image')
    {
        $upload_path = Yii::app()->params->upload_img_url;
        
        $image = CUploadedFile::getInstanceByName($up_name);
        $size_info = getimagesize($image->tempName);
        $width = $size_info[0];
        $height = $size_info[1];
        if($width > $height){
            $new_width = self::IMAGE_MAX_SIZE; $radio = round($width / $height, 2);
            $new_height = ceil($new_width / $radio);
        }
        elseif($height > $width){
            $new_height = self::IMAGE_MAX_SIZE; $radio = round($width / $height, 2);
            $new_width = ceil($new_height * $radio);
        }
        else{
            $new_width = $new_height = self::IMAGE_MAX_SIZE;
        }
        $file_name = $_SERVER['DOCUMENT_ROOT'].$upload_path.date('Y-m-d');
        $this->creteDir($file_name);
        list($img_name, $img_extension) = explode('.', $image->name);
        $file_name = $file_name.'/'.(md5($img_name.$_SERVER['REQUEST_TIME'])).'.'.$img_extension;
        Yii::import("ext.EPhpThumb.EPhpThumb");
        $thumb=new EPhpThumb();
        $thumb->init(); //this is needed
        //chain functions
        $thumb->create($image->tempName)
        ->resize($new_width, $new_height)
        ->save($file_name);
    }
    
    public function creteDir($dir_path = '')
    {
        if (!file_exists($dir_path))
        {
            mkdir($dir_path, 0777);
        }
    }
}