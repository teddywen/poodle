<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {
	private $_id;

	/**
	 * Authenticates a user.
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate() {
		$record = GovUser::model()->findByAttributes(array('username'=>$this->username));
        if($record === null) {
        	$this->errorMessage = Yii::t("Site", "user name is not exist.");
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        }
        else if(!CPasswordHelper::verifyPassword($this->password, $record->password)) {
        	$this->errorMessage = Yii::t("Site", "pass word error.");
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        }
        else {
            $this->_id = $record->id;
            $this->errorCode=self::ERROR_NONE;
        }
        return !$this->errorCode;
	}

	/**
	 * @return integer the ID of the user record
	 */
	public function getId() {
		return $this->_id;
	}
}