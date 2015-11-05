<?php

/**
 * This is the model class for table "problem".
 *
 * The followings are the available columns in table 'problem':
 * @property string $id
 * @property string $release_uid
 * @property string $release_username
 * @property string $address
 * @property string $description
 * @property integer $deal_cate_id
 * @property string $deal_uid
 * @property string $deal_username
 * @property integer $deal_time
 * @property integer $status
 * @property integer $is_delay
 * @property string $delay_time
 * @property integer $is_assistant
 * @property string $assist_unit
 * @property string $create_time
 * @property integer $assign_time
 * @property integer $check_time
 * @property string $update_time
 */
class Problem extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'problem';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('deal_cate_id, deal_time, status, is_delay, is_assistant, assign_time, check_time', 'numerical', 'integerOnly'=>true),
			array('release_uid, deal_uid, delay_time, create_time, update_time', 'length', 'max'=>10),
			array('release_username, address, deal_username', 'length', 'max'=>150),
			array('description, assist_unit', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, release_uid, release_username, address, description, deal_cate_id, deal_uid, deal_username, deal_time, status, is_delay, delay_time, is_assistant, assist_unit, create_time, assign_time, check_time, update_time', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'release_uid' => 'Release Uid',
			'release_username' => 'Release Username',
			'address' => 'Address',
			'description' => 'Description',
			'deal_cate_id' => 'Deal Cate',
			'deal_uid' => 'Deal Uid',
			'deal_username' => 'Deal Username',
			'deal_time' => 'Deal Time',
			'status' => 'Status',
			'is_delay' => 'Is Delay',
			'delay_time' => 'Delay Time',
			'is_assistant' => 'Is Assistant',
			'assist_unit' => 'Assist Unit',
			'create_time' => 'Create Time',
			'assign_time' => 'Assign Time',
			'check_time' => 'Check Time',
			'update_time' => 'Update Time',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('release_uid',$this->release_uid,true);
		$criteria->compare('release_username',$this->release_username,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('deal_cate_id',$this->deal_cate_id);
		$criteria->compare('deal_uid',$this->deal_uid,true);
		$criteria->compare('deal_username',$this->deal_username,true);
		$criteria->compare('deal_time',$this->deal_time);
		$criteria->compare('status',$this->status);
		$criteria->compare('is_delay',$this->is_delay);
		$criteria->compare('delay_time',$this->delay_time,true);
		$criteria->compare('is_assistant',$this->is_assistant);
		$criteria->compare('assist_unit',$this->assist_unit,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('assign_time',$this->assign_time);
		$criteria->compare('check_time',$this->check_time);
		$criteria->compare('update_time',$this->update_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Problem the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
