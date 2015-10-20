<?php
//单位分类服务
class CategoryService extends  Service
{
    /**
     * 获得单位分类列表
     * @param int $page 页数
     * @param int $limit 每页个数
     * @param string $condition 条件
     * @return array 单位分类集合
     */
    public function getAllCatesByPage($page = 1, $limit = 10, $condition = '')
    {
        $criteria = new CDbCriteria();
        if(!empty($condition)){
            $criteria->condition = $condition;
        }
        $count = GovCategory::model()->count($criteria);
        $criteria->limit = $limit;
        $criteria->offset = ($page - 1) * $limit;
        $lists = GovCategory::model()->findAll($criteria);
        return array($lists, $count);
    } 
}
?>