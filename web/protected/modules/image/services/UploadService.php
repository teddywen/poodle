<?php
class UploadService extends Service
{
    const IMAGE_MAX_SIZE = 800;//图片最长的一边边长
    
    public function uploadImage($up_name = 'file')
    {
        $upload_path = Yii::app()->params->upload_img_url;

        $image = CUploadedFile::getInstanceByName($up_name);
        if(empty($image->tempName)){
            $up_name = 'problem_image';
            $image = CUploadedFile::getInstanceByName($up_name);
            if(empty($image->tempName)){
                self::$errorMsg = '图片上传失败';
                return null;
            }
        }
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
            $new_width = $new_height = self::IMAGE_MAX_SIZE; $radio = 1;
        }
        $upload_date = date('Y-m-d');
        $file_path = $_SERVER['DOCUMENT_ROOT'].$upload_path.$upload_date;
        $this->creteDir($file_path);
        list($img_name, $img_extension) = explode('.', $image->name);
        $file_name = md5($img_name.$_SERVER['REQUEST_TIME']);
        $image_path = $file_path.'/'.$file_name.'.'.$img_extension;
        Yii::import("ext.EPhpThumb.EPhpThumb");
        $thumb=new EPhpThumb();
        $thumb->init(); //this is needed
        //chain functions
        $res = $thumb->create($image->tempName)
        ->resize($new_width, $new_height)
        ->save($image_path);
        if(!$res){
            self::$errorMsg = '图片压缩失败';
            return null;
        }
        return array(
            'img_path' => $upload_date.'/'.$file_name.'.'.$img_extension,
            'img_size' => array(
                'radio' => $radio,
                'width' => $new_width,
                'height' => $new_height
            ));
    }
    
    public function creteDir($dir_path = '')
    {
        if (!file_exists($dir_path))
        {
            mkdir($dir_path, 0777);
        }
    }
}