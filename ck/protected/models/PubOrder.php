<?php

class PubOrder extends ActiveRecord
{
	const BASIC = 'B';
	const RESERVE = 'R';

	private $_book_title = null;
	private $_publisher_name = null;
	private $_remaining = null;
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{pub_order}}';
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
			array('book_id, count, date, price, type', 'required'),
			array('book_id, count', 'numerical', 'integerOnly'=>true),
			array('date', 'date', 'format'=>Yii::app()->locale->dateFormat),
			array('count', 'compare', 'compareAttribute'=>'delivered', 'operator'=>'>='),
			array('price', 'filter', 'filter'=>array($this, 'numerize')),
			array('price', 'numerical'),
			array('type', 'in', 'range'=>array_keys(DropDownItem::items('PubOrder.type'))),
			array('book_title, publisher_name, delivered, remaining', 'safe', 'on'=>'search'),
		);
	}
	
	public function numerize($value)
	{
		return str_replace(',', '.', $value);
	}
	
	public function relations()
	{
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'book' => array(self::BELONGS_TO, 'Book', 'book_id'),
			'sum_activities' => array(self::STAT, 'StockActivity', 'pub_order_id', 'select'=>'SUM(count)'),
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
			'date' =>  Yii::t('app', 'Date'),
			'count' =>  Yii::t('app', 'Count'),
			'delivered' =>  Yii::t('app', 'Delivered'),
			'price' =>  Yii::t('app', 'Price').' / '.t('pcs'),
			'type' =>  Yii::t('app', 'Type'),
			'type_c' =>  Yii::t('app', 'Type'),
			'book_title' =>  Yii::t('app', 'Book'),
			'publisher_name' =>  Yii::t('app', 'Publisher'),
			'remaining' =>  Yii::t('app', 'Remaining'),
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('book.title',$this->book_title,true);
		$criteria->compare('organisation.name',$this->publisher_name,true);
		$criteria->compare('t.date',DT::toIso($this->date));
		$criteria->compare('t.count',$this->count);
		$criteria->compare('t.delivered',$this->delivered);
		$criteria->compare('(t.count - t.delivered)',$this->remaining);
		$criteria->compare('t.price',$this->price);
		$criteria->compare('t.type',$this->type);
		$criteria->with = array('book', 'book.publisher', 'book.publisher.organisation');

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
					'publisher_name'=>array(
						'asc'=>'publisher.name',
						'desc'=>'publisher.name DESC',
						),
					'remaining'=>array(
						'asc'=>'(t.count - t.delivered)',
						'desc'=>'(t.count - t.delivered) DESC',
						),
					),
				),
			'pagination'=>array('pageSize'=>20,),
		));
	}
	
	protected function afterSave()
	{
		parent::afterSave();
		if ($this->isNewRecord)
		{
			$stock = new Stock();
			$stock->user_id = user()->id;
			$stock->book_id = $this->book_id;
			$stock->count = 0;
			$stock->type =  $this->type;
			$stock->save(false);
			
			$publisher = Publisher::model()->findByPk($this->book->publisher_id);
			$publisher->order_placed = 1;
			$publisher->save(false);
		}
	}
	
	public function getType_c()
	{
		return DropDownItem::item('PubOrder.type', $this->type);
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
	
	public function getTotal_price()
	{
		return $this->price * $this->count;
	}
	
	public function getPublisher_name()
	{
		if ($this->_publisher_name === null && $this->book !== null && $this->book->publisher !== null && $this->book->publisher->organisation !== null)
		{
			$this->_publisher_name = $this->book->publisher->organisation->name;
		}
		return $this->_publisher_name;
	}
	
	public function setPublisher_name($value)
	{
		$this->_publisher_name = $value;
	}
	
	public function getRemaining()
	{
		if ($this->scenario != 'search' && $this->_remaining === null)
			$this->_remaining = $this->count - $this->delivered;
		return $this->_remaining;
	}
	
	public function setRemaining($value)
	{
		$this->_remaining = $value;
	}
	
	public static function getTotalDelivered($type, $book_id = null)
	{
		$criteria = "";
		if ($book_id !== null)
			$criteria .= " AND book_id=${book_id}";
		$result = db()->createCommand("SELECT SUM(delivered) AS sum_delivered FROM {{pub_order}} WHERE type='${type}' ${criteria}")->queryScalar();
		if ($result)
			return $result;
		else
			return 0;
	}
	
	public static function getTotalRemaining($type, $book_id = null)
	{
		$criteria = "";
		if ($book_id !== null)
			$criteria .= " AND book_id=${book_id}";
		$result = db()->createCommand("SELECT SUM(count-delivered) AS sum_remaining FROM {{pub_order}} WHERE type='${type}' ${criteria}")->queryScalar();
		if ($result)
			return $result;
		else
			return 0;
	}
}