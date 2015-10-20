<?php
class CategoryController extends UserController
{
    public function actionIndex()
    {
        $this->pageTitle = '分类列表';
        
        $cate_service = new CategoryService();
        list($lists, $count) = $cate_service->getAllCates(true);
        
        $data['lists'] = $lists;
        $data['count'] = $count;
        $this->render('index', $data);
    }
}
?>