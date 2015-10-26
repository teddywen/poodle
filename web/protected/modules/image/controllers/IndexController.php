<?php
class IndexController extends ImageController
{
    public function actionIndex()
    {
        $upload_service = new UploadService();
        $upload_service->uploadImage();
    }
}
?>