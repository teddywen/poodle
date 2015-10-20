<?php
class CategoryController extends UserController
{
    public function actionIndex()
    {
        $this->pageTitle = '分类列表';
        
        $page = !empty($_REQUEST['page'])?intval($_REQUEST['page']):1;
        $cate_service = new CategoryService();
        list($lists, $count) = $cate_service->getAllCatesByPage($page, $this->PAGE_SIZE);
        
        $data['lists'] = $lists;
        $data['count'] = $count;
        $data['page'] = $page;
        $this->render('index', $data);
    }
}
?>