<?php
//用户服务
class UserService extends Service
{
    const DEFAULT_PWD = '123456';
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
        if(isset($condition['username'])){
            $criteria->compare('username', $condition['username'], true);
        }
        if(isset($condition['u_type'])){
            $criteria->compare('u_type', $condition['u_type']);
        }
        if(isset($condition['gov_cate_id'])){
            $criteria->compare('gov_cate_id', $condition['gov_cate_id']);
        }
        if(isset($condition['status'])){
            $criteria->compare('status', $condition['status']);
        }
        $criteria->addInCondition('u_type', array(1,2));
        $count = GovUser::model()->count($criteria);
        $criteria->limit = $limit;
        $criteria->offset = ($page - 1) * $limit;
        $lists = GovUser::model()->findAll($criteria);
        return array($lists, $count);
    }
    
    /**
     * 获得管理员列表
     * @param int $page 页数
     * @param int $limit 每页个数
     * @param array $condition 条件
     * @return array 管理员集合
     */
    public function getAllManagersByPage($page = 1, $limit = 10, $condition = array())
    {
        $criteria = new CDbCriteria();
        if(isset($condition['username'])){
            $criteria->compare('username', $condition['username'], true);
        }
        $criteria->compare('u_type', 3);
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
        $model = new GovUser();
        $cur_time = $_SERVER['REQUEST_TIME'];
        $cate_service = new CategoryService();
        $cate_id = isset($data['gov_cate_id'])?$data['gov_cate_id']:0;
        $cate_info = $cate_service->getGovCateById($cate_id);
        $model->attributes = $data;
        $model->password = CPasswordHelper::hashPassword(self::DEFAULT_PWD);
        $model->gov_cate_name = $cate_info===null?"":$cate_info->cate_name;
        $model->create_time = $cur_time;
        $model->update_time = $cur_time;
        if($model->save()){
            if (isset(Yii::app()->params["gov_type_role"][$model->u_type])) {
                Yii::app()->authManager->assign(Yii::app()->params["gov_type_role"][$model->u_type], $model->id);
            }
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
            self::$errorMsg = '该用户不存在';
            return null;
        }
        $username = isset($data['username'])?trim($data['username']):"";
        if(empty($username)){
            self::$errorMsg = '用户名不能为空';
            return null;
        }
        if($username != $model->username && $this->getGovUserByName($username)){
            self::$errorMsg = '该用户名已经存在';
            return null;
        }
        $old_u_type = $model->u_type;
        $cur_time = $_SERVER['REQUEST_TIME'];
        $cate_service = new CategoryService();
        $cate_id = isset($data['gov_cate_id'])?$data['gov_cate_id']:0;
        $cate_info = $cate_service->getGovCateById($cate_id);
        $model->attributes = $data;
        $model->password = CPasswordHelper::hashPassword(self::DEFAULT_PWD);
        $model->gov_cate_name = $cate_info===null?"":$cate_info->cate_name;
        $model->update_time = $cur_time;
        if($model->save()){
            if ($old_u_type != $model->u_type && isset(Yii::app()->params["gov_type_role"][$old_u_type]) && isset(Yii::app()->params["gov_type_role"][$model->u_type])) {
                Yii::app()->authManager->revoke(Yii::app()->params["gov_type_role"][$old_u_type], $model->id);
                Yii::app()->authManager->assign(Yii::app()->params["gov_type_role"][$model->u_type], $model->id);
            }
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
    public function changeStatus($uid = 0, $status = 0)
    {
        $res = GovUser::model()->updateByPk($uid, array('status' => $status));
        return $res;
    }
    
    /**
     * 重置用户密码
     * @param int $uid 用户ID
     * @return boolean 重置结果
     */
    public function resetUserPwd($uid = 0)
    {
        $res = GovUser::model()->updateByPk($uid, array('password' => CPasswordHelper::hashPassword(self::DEFAULT_PWD)));
        return $res;
    }
}
?>