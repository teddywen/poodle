<?php

/**
 * This is the model class for table "problem".
 *
 * The followings are the available columns in table 'problem':
 * @property integer $id
 * @property integer $up_uid
 * @property string $address
 * @property string $description
 * @property integer $deal_uid
 * @property integer $status
 * @property integer $is_delay
 * @property string $delay_time
 * @property integer $is_assistant
 * @property string $assist_unit
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
			array('up_uid, deal_uid, status, is_delay, is_assistant', 'numerical', 'integerOnly'=>true),
			array('address', 'length', 'max'=>150),
			array('description, assist_unit', 'length', 'max'=>255),
			array('delay_time', 'length', 'max'=>15),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, up_uid, address, description, deal_uid, status, is_delay, delay_time, is_assistant, assist_unit', 'safe', 'on'=>'search'),
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
			'up_uid' => 'Up Uid',
			'address' => 'Address',
			'description' => 'Description',
			'deal_uid' => 'Deal Uid',
			'status' => 'Status',
			'is_delay' => 'Is Delay',
			'delay_time' => 'Delay Time',
			'is_assistant' => 'Is Assistant',
			'assist_unit' => 'Assist Unit',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('up_uid',$this->up_uid);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('deal_uid',$this->deal_uid);
		$criteria->compare('status',$this->status);
		$criteria->compare('is_delay',$this->is_delay);
		$criteria->compare('delay_time',$this->delay_time,true);
		$criteria->compare('is_assistant',$this->is_assistant);
		$criteria->compare('assist_unit',$this->assist_unit,true);

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
