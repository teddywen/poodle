<?php
//单位分类服务
class UserService extends Service
{
    /**
     * 获得单位分类列表
     * @param int $page 页数
     * @param int $limit 每页个数
     * @param array $condition 条件
     * @return array 单位分类集合
     */
    public function getAllUsersByPage($page = 1, $limit = 10, $condition = array())
    {
        $criteria = new CDbCriteria();
        if(isset($condition['cate_name'])){
            $criteria->compare('cate_name', $condition['cate_name'], true);
        }
        if(isset($condition['status'])){
            $criteria->compare('status', $condition['status']);
        }
        $count = GovCategory::model()->count($criteria);
        $criteria->limit = $limit;
        $criteria->offset = ($page - 1) * $limit;
        $lists = GovCategory::model()->findAll($criteria);
        return array($lists, $count);
    }
    
    /**
     * 创建单位分类
     * @param string $cate_name 单位名称
     * @param int $status 状态
     * @return GovCategory 单位分类信息
     */
    public function createGovCate($cate_name = '', $status = 0)
    {
        if(empty($cate_name)){
            self::$errorMsg = '分类名不能为空';
            return null;
        }
        if($this->getGovCateByName($cate_name)){
            self::$errorMsg = '该分类名已经存在';
            return null;
        }
        $cur_time = $_SERVER['REQUEST_TIME'];
        $model = new GovCategory();
        $model->cate_name = $cate_name;
        $model->status = $status;
        $model->create_time = $cur_time;
        $model->update_time = $cur_time;
        if($model->save()){
            return $model;
        }
        self::$errorMsg = print_r($model->getErrors(), true);
        return null;
    }
    
    /**
     * 修改单位分类
     * @param int $cid 分类ID
     * @param string $cate_name 单位名称
     * @param int $status 状态
     * @return GovCategory 单位分类信息
     */
    public function updateGovCate($cid = 0, $cate_name = '', $status = 0)
    {
        $model = $this->getGovCateById($cid);
        if($model === null){
            self::$errorMsg = '该分类不存在';
            return null;
        }
        if(empty($cate_name)){
            self::$errorMsg = '分类名不能为空';
            return null;
        }
        if($cate_name != $model->cate_name && $this->getGovCateByName($cate_name)){
            self::$errorMsg = '该分类名已经存在';
            return null;
        }
        $cur_time = $_SERVER['REQUEST_TIME'];
        $model->cate_name = $cate_name;
        $model->status = $status;
        $model->update_time = $cur_time;
        if($model->save()){
            return $model;
        }
        self::$errorMsg = print_r($model->getErrors(), true);
        return null;
    }
    
    /**
     * 根据ID查分类
     * @param int $cid 分类ID
     * @return GovCategory 单位分类信息
     */
    public function getGovCateById($cid = 0)
    {
        $model = GovCategory::model()->findByPk($cid);
        return $model;
    }
    
    /**
     * 根据分类名查分类
     * @param string $cate_name 分类名
     * @return GovCategory 单位分类信息
     */
    public function getGovCateByName($cate_name = '')
    {
        $model = GovCategory::model()->findByAttributes(array('cate_name' => $cate_name));
        return $model;
    }
    
    /**
     * 修改分类状态
     * @param int $cid 分类ID
     * @param int $status 状态
     * @return boolean 修改结果
     */
    public function changeStatus($cid = 0, $status = 0)
    {
        $res = GovCategory::model()->updateByPk($cid, array('status' => $status));
        return $res;
    }
}
?>