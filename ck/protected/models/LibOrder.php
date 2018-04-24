<?php

class LibOrder extends ActiveRecord
{
	const BASIC = 'B';
	const RESERVE = 'R';

	private $_book_title = null;
	private $_library_name = null;
	private $_remaining = null;

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{lib_order}}';
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
		$rules = array(
			array('book_id, date, count, type', 'required'),
			array('book_id, count', 'numerical', 'integerOnly'=>true),
			array('date', 'date', 'format'=>Yii::app()->locale->dateFormat),
			array('count', 'countValid'),
			array('type', 'in', 'range'=>array_keys(DropDownItem::items('LibOrder.type'))),
			array('book_title, library_name, delivered, remaining', 'safe', 'on'=>'search'),
		);

		if (user()->checkAccess('BackOffice'))
		{
			$rules = array_merge($rules,
			array(
				array('library_id', 'required'),
				array('library_id', 'numerical', 'integerOnly'=>true),
			));
		}

		return $rules;
	}

	public function countValid($attribute, $params)
	{
		if ($this->count < 0) {
			$this->addError($attribute, Yii::t('app','Total count must not be less than zero.'));
		}
		$extraCrit = '';  
		if ($this->id) {
			$extraCrit = ' AND {{lib_order}}.id!='.$this->id;
		}
		switch ($this->type)
		{
			case self::BASIC:
				$price = db()->createCommand("SELECT SUM({{lib_order}}.count*{{book}}.project_price) AS price FROM {{lib_order}} LEFT JOIN {{book}} ON {{lib_order}}.book_id={{book}}.id WHERE {{lib_order}}.library_id=".$this->library_id." AND {{lib_order}}.type='".self::BASIC."' ${extraCrit} GROUP BY {{lib_order}}.library_id")->queryScalar();
				if ($price + ($this->book->project_price * $this->count) > param('libBasicLimit')) $this->addError($attribute, strtr(Yii::t('app','Total price of basic order must be less than or equal to {compareValue} CZK.'),array('{compareValue}'=>param('libBasicLimit'))));
				break;
			case self::RESERVE:
				$count = db()->createCommand("SELECT SUM({{lib_order}}.count) AS count FROM {{lib_order}} WHERE {{lib_order}}.library_id=".$this->library_id." AND {{lib_order}}.type='".self::RESERVE."' ${extraCrit} GROUP BY {{lib_order}}.library_id")->queryScalar();
				if ($count + $this->count > param('libReserveLimit')) $this->addError($attribute, strtr(Yii::t('app','Total count of items in reserve must be less than or equal to {compareValue} pcs.'),array('{compareValue}'=>param('libReserveLimit'))));
				break;
		}
	}

	public function relations()
	{
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'book' => array(self::BELONGS_TO, 'Book', 'book_id'),
			'library' => array(self::BELONGS_TO, 'Library', 'library_id'),
			'stock_activities' => array(self::HAS_MANY, 'StockActivity', 'lib_order_id'),
			'sum_activities' => array(self::STAT, 'StockActivity', 'lib_order_id', 'select'=>'SUM(count)'),
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
			'library_id' =>  Yii::t('app', 'Library'),
			'date' =>  Yii::t('app', 'Date'),
			'count' =>  Yii::t('app', 'Count'),
			'delivered' =>  Yii::t('app', 'Delivered'),
			'type' =>  Yii::t('app', 'Type'),
			'type_c' =>  Yii::t('app', 'Type'),
			'library_name' =>  Yii::t('app', 'Library'),
			'book_title' =>  Yii::t('app', 'Book'),
			'remaining' =>  Yii::t('app', 'Remaining'),
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('book.title',$this->book_title,true);
		$criteria->compare('organisation.name',$this->library_name,true);
		$criteria->compare('t.date',DT::toIso($this->date));
		$criteria->compare('t.count',$this->count);
		$criteria->compare('t.delivered',$this->delivered);
		$criteria->compare('(t.count - t.delivered)',$this->remaining);
		$criteria->compare('t.type',$this->type);
		$criteria->with = array('book','library','library.organisation');

		return new CActiveDataProvider($this->my(), array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'t.id DESC',
				'attributes'=>array(
					'*',
					'library_name'=>array(
						'asc'=>'organisation.name',
						'desc'=>'organisation.name DESC',
						),
					'book_title'=>array(
						'asc'=>'book.title',
						'desc'=>'book.title DESC',
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
		IncNumber::model()->deleteAllByAttributes(array('liborder_id'=>$this->id));
		for ($i = 0; $i < $this->count; $i++)
		{
			$incNumber = new IncNumber;
			$incNumber->user_id = user()->id;
			$incNumber->liborder_id = $this->id;
			$incNumber->save(false);
		}
	}

	protected function beforeDelete()
	{
		if (parent::beforeDelete())
		{
			// mazani prirustkovych cisel reseno pres ON DELETE CASCADE
			return true;
		}
		else return false;
	}

	public function getType_c()
	{
		return DropDownItem::item('LibOrder.type', $this->type);
	}

	public function getBook_title()
	{
		if ($this->_book_title === null && $this->book !== null)
		{
			$this->_book_title = $this->book->title;
		}
		return $this->_book_title;
	}

	public function setBook_title($value)
	{
		$this->_book_title = $value;
	}

	public function getLibrary_name()
	{
		if ($this->_library_name === null && $this->library !== null && $this->library->organisation !== null)
		{
			$this->_library_name = $this->library->organisation->name;
		}
		return $this->_library_name;
	}

	public function setLibrary_name($value)
	{
		$this->_library_name = $value;
	}

	public function getRemaining()
	{
		if ($this->scenario != 'search')
			$this->_remaining = $this->count - $this->delivered;
		return $this->_remaining;
	}

	public function setRemaining($value)
	{
		$this->_remaining = $value;
	}

	public static function getSumOrders($type)
	{
		return db()->createCommand("SELECT SUM({{lib_order}}.count) AS sum_count, selected_order, book_id, title, author, SUM({{lib_order}}.count)*project_price AS total_price, publisher_id, name FROM (((({{lib_order}} LEFT JOIN {{library}} ON {{lib_order}}.library_id={{library}}.id) LEFT JOIN {{book}} ON {{lib_order}}.book_id={{book}}.id) LEFT JOIN {{publisher}} ON {{book}}.publisher_id={{publisher}}.id) LEFT JOIN {{organisation}} ON {{publisher}}.organisation_id={{organisation}}.id) WHERE {{lib_order}}.type='${type}' AND {{library}}.order_placed=1 AND {{library}}.order_date>'0000-00-00' GROUP BY book_id ORDER BY sum_count DESC")->queryAll();
	}
	
	public static function getSumOrdersAll() {
		return db()->createCommand("SELECT SUM({{lib_order}}.count) AS sum_count, selected_order, book_id, title, author, SUM({{lib_order}}.count)*project_price AS total_price, publisher_id, name 
FROM 
(((({{lib_order}} LEFT JOIN {{library}} ON {{lib_order}}.library_id={{library}}.id) LEFT JOIN {{book}} ON {{lib_order}}.book_id={{book}}.id) LEFT JOIN {{publisher}} ON {{book}}.publisher_id={{publisher}}.id) LEFT JOIN {{organisation}} ON {{publisher}}.organisation_id={{organisation}}.id) WHERE {{library}}.order_placed=1 AND {{library}}.order_date>'0000-00-00' GROUP BY book_id ORDER BY sum_count DESC")->queryAll();
	}
}
