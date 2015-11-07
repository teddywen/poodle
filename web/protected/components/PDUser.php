<?php
class PDUser extends CWebUser {

    private $_gov_user = null;
    private $_admember = null;

    //you can use Yii::app()->user->member to access Member's model
    //if isGuest , then member is null
    public function getGovUser() {
        if($this->getId() === null)
            return null;

        if($this->_gov_user === null) {
            $this->_gov_user = GovUser::model()->findByPk($this->getId());
        }
        return $this->_gov_user;
    }

    /**
     * @return array [<updated>, <error>]
     */
    public function updateLastLoginTime() {
        $gov_user = $this->getGovUser();
        if ($gov_user) {
            $gov_user->last_login_time = Util::time();
            $gov_user->update_time = Util::time();
            if (!$gov_user->save(false)) {
                $error = print_r($gov_user->getErrors(), true);
                return array(false, $error);
            } else {
                return array(true, "");
            }
        } else {
            return array(false, "用户ID不存在");
        }
    }
}