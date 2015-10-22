<?php
//用户服务
class UserService extends Service
{
    /**
     * 获得用户列表
     * @param int $page 页数
     * @param int $limit 每页个数
     * @param array $condition 条件
     * @return array 用户集合
     */
    public function getAllUsersByPage($page = 1, $limit = 10, $condition = array())
    {
        $criteria = new CDbCriteria();
//         if(isset($condition['user_name'])){
//             $criteria->compare('user_name', $condition['user_name'], true);
//         }
//         if(isset($condition['status'])){
//             $criteria->compare('status', $condition['status']);
//         }
        $count = GovUser::model()->count($criteria);
        $criteria->limit = $limit;
        $criteria->offset = ($page - 1) * $limit;
        $lists = GovUser::model()->findAll($criteria);
        return array($lists, $count);
    }
    
    /**
     * 创建用户
     * @param array $data 用户信息
     * @return GovUser 用户信息
     */
    public function createGovUser($data = array())
    {
        if(empty($data)){
            self::$errorMsg = '用户信息不能为空';
            return null;
        }
        $username = isset($data['username'])?trim($data['username']):"";
        if(empty($username)){
            self::$errorMsg = '用户名不能为空';
            return null;
        }
        if($this->getGovUserByName($username)){
            self::$errorMsg = '该用户名已经存在';
            return null;
        }
        $cur_time = $_SERVER['REQUEST_TIME'];
        $model = new GovUser();
        $model->attributes = $data;
        $model->create_time = $cur_time;
        $model->update_time = $cur_time;
        if($model->save()){
            return $model;
        }
        self::$errorMsg = print_r($model->getErrors(), true);
        return null;
    }
    
    /**
     * 修改用户
     * @param int $cid 分类ID
     * @param string $user_name 单位名称
     * @param int $status 状态
     * @return GovUser 用户信息
     */
    public function updateGovUser($uid = 0, $data = array())
    {
        $model = $this->getGovUserById($uid);
        if($model === null){
            self::$errorMsg = '该分类不存在';
            return null;
        }
        if(empty($user_name)){
            self::$errorMsg = '用户名不能为空';
            return null;
        }
        if($user_name != $model->user_name && $this->getGovUserByName($username)){
            self::$errorMsg = '该用户名已经存在';
            return null;
        }
        $cur_time = $_SERVER['REQUEST_TIME'];
        $model->user_name = $user_name;
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
     * @return GovUser 用户信息
     */
    public function getGovUserById($uid = 0)
    {
        $model = GovUser::model()->findByPk($uid);
        return $model;
    }
    
    /**
     * 根据用户名查用户
     * @param string $username 用户名
     * @return GovUser 用户信息
     */
    public function getGovUserByName($username = '')
    {
        $model = GovUser::model()->findByAttributes(array('username' => $username));
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
        $res = GovUser::model()->updateByPk($cid, array('status' => $status));
        return $res;
    }
}
?>