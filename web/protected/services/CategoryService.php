<?php
//单位分类服务
class CategoryService extends  Service
{
    /**
     * 获得单位分类列表
     * @param boolean $need_unable  是否查询已屏蔽的
     * @return array 单位分类集合
     */
    public function getAllCates($page = 1, $limit = 10, $need_unable = false)
    {
        $criteria = new CDbCriteria();
        if(!$need_unable){
            $criteria->compare('status', 1);
        }
        $count = GovCategory::model()->count($criteria);
        $criteria->limit = $limit;
        $criteria->offset = ($page - 1) * $limit;
        $lists = GovCategory::model()->findAll($criteria);
        return array($lists, $count);
    }  
}
?>