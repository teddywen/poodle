<?php
//用户服务
class UserService extends Service
{
    const DEFAULT_PWD = '123456';
    const STATUS_INVALID = 0;
    const STATUS_VALID = 1;

    /**
     * 获得用户列表
     * @param int $page 页数
     * @param int $limit 每页个数
     * @param array $condition 条件
     * @return array 用户集合
     */
    public function getAllUsersByPage($page = 1, $limit = 10, $condition = array())
    {
        $criteria = $this->getFindCond($condition);
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
        $criteria = $this->getFindCond($condition);
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
     * @param int $uid 用户ID
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
     * 根据ID查用户
     * @param int $uid 用户ID
     * @return GovUser 用户信息
     */
    public function getGovUserById($uid = 0)
    {
        $model = GovUser::model()->findByPk($uid);
        return $model;
    }
    
    /**
     * 根据ID查用户
     * @param array() $uid 用户ID集合
     * @return GovUser 用户信息集合
     */
    public function getGovUserByIds($uids = array())
    {
        $criteria = new CDbCriteria();
        $criteria->addInCondition('id', $uids);
        $users = GovUser::model()->findAll($criteria);
        return $users;
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
     * 修改用户状态
     * @param int $uid 用户ID
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
    
    /**
     * 根据单位分类查询用户，用于分配任务
     * @param int $cate_id 单位分类ID
     * @return array 用户集合
     */
    public function getUserByCate($cate_id = 0)
    {
        $criteria = new CDbCriteria();
        $criteria->compare('gov_cate_id', $cate_id);
        $criteria->compare('status', 1);
        $criteria->compare('u_type', 2);
        $users = GovUser::model()->findAll($criteria);
        return $users;
    }
    
    /**
     * 根据指定类型查询用户
     * @param int $u_type 用户类型
     * @return array 用户集合
     */
    public function getUserByType($u_type = 0)
    {
        $criteria = new CDbCriteria();
        $criteria->compare('status', 1);
        $criteria->compare('u_type', $u_type);
        $users = GovUser::model()->findAll($criteria);
        return $users;
    }
    
    /**
     * 获得所有有效的用户
     * @return GovUser 分类集合
     */
    public function getAvailableUsers()
    {
        $users = GovUser::model()->findAllByAttributes(array('status' => 1, 'u_type' => 2));
        return $users;
    }
}
?>