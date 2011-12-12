<?php

class BookTitle extends ActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{book_title}}';
	}

	public function rules()
	{
		return array(
			array('user_id, book_id', 'required'),
			array('user_id, book_id', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>255),
		);
	}

	public function relations()
	{
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'book' => array(self::BELONGS_TO, 'Book', 'book_id'),
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
			'book_id' =>  Yii::t('app', 'Book'),
			'title' =>  Yii::t('app', 'Title'),
		);
	}
}