<?php
class IndexController extends ImageController
{
    public function actionIndex()
    {
        $upload_service = new UploadService();
        $img_info = $upload_service->uploadImage();
        if(empty($img_info)){
            exit(json_encode(array('code' => 0, 'msg' => $upload_service->getLastErrMsg())));
        }
        exit(json_encode(array('code' => 1, 'img_info' => $img_info)));
    }
}
?>