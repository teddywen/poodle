<?php
//图片模块下面的controller都继承此controller
class ImageController extends Controller
{
    public function __construct($id){
        parent::__construct($id);
    }
}
?>