<?php

class Stock extends ActiveRecord
{
	const BASIC = 'B';
	const RESERVE = 'R';

	private $_book_title = null;

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{stock}}';
	}

	public function scopes()
	{
		return array_merge(parent::scopes(), array(
			'basic'=>array(
				'condition'=>'t.type=\''.self::BASIC.'\'',
			),
			'reserve'=>array(
				'condition'=>'t.type=\''.self::RESERVE.'\'',
			),
		));
	}

	public function rules()
	{
		return array(
			array('book_id, type', 'required'),
			array('book_id', 'numerical', 'integerOnly'=>true),
			array('type', 'in', 'range'=>array_keys(DropDownItem::items('Stock.type'))),
			array('book_title, count', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'book' => array(self::BELONGS_TO, 'Book', 'book_id'),
			'sum_activities' => array(self::STAT, 'StockActivity', 'stock_id', 'select'=>'SUM(count)'),
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
			'count' =>  Yii::t('app', 'Count'),
			'type' =>  Yii::t('app', 'Type'),
			'type_c' =>  Yii::t('app', 'Type'),
			'book_title' =>  Yii::t('app', 'Book'),
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('book.title',$this->book_title,true);
		$criteria->compare('t.count',$this->count);
		$criteria->compare('t.type',$this->type);
		$criteria->with = array('book');
		
		return new CActiveDataProvider($this->my(), array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'t.id DESC',
				'attributes'=>array(
					'*',
					'book_title'=>array(
						'asc'=>'book.title',
						'desc'=>'book.title DESC',
						),
					),
				),
			'pagination'=>array('pageSize'=>20,),
		));
	}
	
	public function getType_c()
	{
		return DropDownItem::item('Stock.type', $this->type);
	}
	
	public function getBook_title()
	{
		if ($this->_book_title === null && $this->book)
		{
			$this->_book_title = $this->book->title;
		}
		return $this->_book_title;
	}
	
	public function setBook_title($value)
	{
		$this->_book_title = $value;
	}
}