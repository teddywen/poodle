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
}