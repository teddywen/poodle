<?php
//图片模块下面的controller都继承此controller
class ProblemController extends Controller
{
    public function __construct($id){
        parent::__construct($id);
    }
}
?>