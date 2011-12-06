<?php

class Document extends ActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{document}}';
	}

	public function rules()
	{
		return array(
		);		
	}

	public function relations()
	{
		return array(
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' =>  Yii::t('app', 'ID'),
			'create_time' =>  Yii::t('app', 'Create Time'),
			'modify_time' =>  Yii::t('app', 'Modify Time'),
			'ip_address' =>  Yii::t('app', 'Ip Address'),
			'name' =>  Yii::t('app', 'Name'),
			'file_name' =>  Yii::t('app', 'File Name'),
			'type' =>  Yii::t('app', 'Type'),
			'mime' =>  Yii::t('app', 'MIME Type'),
			'comment' =>  Yii::t('app', 'Comment'),
		);
	}
}