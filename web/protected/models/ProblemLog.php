<?php

/**
 * This is the model class for table "problem_log".
 *
 * The followings are the available columns in table 'problem_log':
 * @property string $id
 * @property string $pid
 * @property integer $pre_status
 * @property integer $cur_status
 * @property string $oper_uid
 * @property string $oper_user
 * @property string $log_desc
 * @property string $remark
 * @property string $data
 * @property integer $status
 * @property string $update_time
 * @property string $create_time
 */
class ProblemLog extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'problem_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('update_time', 'required'),
			array('pre_status, cur_status, status', 'numerical', 'integerOnly'=>true),
			array('pid, oper_uid, update_time, create_time', 'length', 'max'=>10),
			array('oper_user, log_desc', 'length', 'max'=>150),
			array('remark', 'length', 'max'=>255),
			array('data', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, pid, pre_status, cur_status, oper_uid, oper_user, log_desc, remark, data, status, update_time, create_time', 'safe', 'on'=>'search'),
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
			'pid' => 'Pid',
			'pre_status' => 'Pre Status',
			'cur_status' => 'Cur Status',
			'oper_uid' => 'Oper Uid',
			'oper_user' => 'Oper User',
			'log_desc' => 'Log Desc',
			'remark' => 'Remark',
			'data' => 'Data',
			'status' => 'Status',
			'update_time' => 'Update Time',
			'create_time' => 'Create Time',
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
		$criteria->compare('pid',$this->pid,true);
		$criteria->compare('pre_status',$this->pre_status);
		$criteria->compare('cur_status',$this->cur_status);
		$criteria->compare('oper_uid',$this->oper_uid,true);
		$criteria->compare('oper_user',$this->oper_user,true);
		$criteria->compare('log_desc',$this->log_desc,true);
		$criteria->compare('remark',$this->remark,true);
		$criteria->compare('data',$this->data,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('create_time',$this->create_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ProblemLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
