<?php

class IncNumber extends ActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{inc_number}}';
	}

	public function rules()
	{
		return array(
			array('number', 'numerical', 'integerOnly'=>true),
		);
	}

	public function relations()
	{
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'lib_order' => array(self::BELONGS_TO, 'LibOrder', 'liborder_id'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' =>  Yii::t('app', 'ID'),
			'create_time' =>  Yii::t('app', 'Create Time'),
			'modify_time' =>  Yii::t('app', 'Modify Time'),
			'ip_address' =>  Yii::t('app', 'Ip Address'),
			'user_id' =>  Yii::t('app', 'User'),
			'liborder_id' =>  Yii::t('app', 'Lib Order'),
			'number' =>  Yii::t('app', 'Number'),
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('number',$this->number);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'t.id DESC',
				'attributes'=>array(
					'*',
					),
				),
			'pagination'=>array('pageSize'=>20,),
		));
	}
}