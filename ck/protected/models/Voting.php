<?php

class Voting extends ActiveRecord
{
	const POLL = 'P';
	const RATING = 'R';
	
	private $_vote = null;
	private $_username = null;
	private $_name = null;
	private $_title = null;
	
	public static $_types = array(self::POLL, self::RATING);
	public static $pollOptions = array('-1'=>'-1', '0'=>'0', '1'=>'1');
	public static $ratingOptions = array(null => 'N - nehlasováno', '0'=>'0 - nedoporučuji', '1'=>'1 - spíše nedoporučuji', '2'=>'2 - spíše doporučuji', '3'=>'3 - doporučuji s výhradami', '4'=>'4 - rozhodně doporučuji');	
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{voting}}';
	}
	
	public function yea($book_id)
	{
		$this->getDbCriteria()->mergeWith(array(
			'condition'=>'book_id=:book_id AND type=\''.self::POLL.'\' AND points=1',
			'params'=>array(':book_id'=>$book_id),
		));
		return $this;		
	}
	
	public function nay($book_id)
	{
		$this->getDbCriteria()->mergeWith(array(
			'condition'=>'book_id=:book_id AND type=\''.self::POLL.'\' AND points=-1',
			'params'=>array(':book_id'=>$book_id),
		));
		return $this;		
	}
	
	public function rules()
	{
		$rules = array(
			array('book_id, type', 'required'),
			array('book_id', 'numerical', 'integerOnly'=>true),
			array('points', 'required', 'on'=>'insert'),
			array('points', 'numerical', 'integerOnly'=>true, 'allowEmpty'=>true),
			array('type', 'in', 'range'=>self::$_types),
			array('points', 'pointsValid'),
			array('username, name, title', 'safe', 'on'=>'search'),
		);
		
		if (user()->checkAccess('BackOffice'))
		{
			$rules = array_merge($rules,
			array(
				array('user_id', 'required'),
				array('user_id', 'numerical', 'integerOnly'=>true),
			));
		}

		return $rules;
	}
	
	public function pointsValid($attribute, $params)
	{
		switch ($this->type)
		{
			case Voting::POLL:
				$options = self::$pollOptions;
				break;
			case Voting::RATING:
				$options = self::$ratingOptions;
				break;
			default:
				$options = array();
				break;
		}
		if ($this->$attribute != ''  && !array_key_exists($this->$attribute, $options)) $this->addError($attribute, strtr(Yii::t('yii', '{attribute} is invalid.'),array('{attribute}'=>t('voting'))));
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
			'points' =>  Yii::t('app', 'Points'),
			'type' =>  Yii::t('app', 'Type'),
			'vote' =>  Yii::t('app', 'Voting'),
			'username' =>  Yii::t('app', 'Username'),
			'name' =>  Yii::t('app', 'Publisher'),
			'title' =>  Yii::t('app', 'Book'),
			'type_c' =>  Yii::t('app', 'Type'),
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('user.username',$this->username,true);
		$criteria->compare('organisation.name',$this->name,true);
		$criteria->compare('book.title',$this->title,true);
		$criteria->compare('t.type',$this->type);
		$criteria->with = array('user','book','book.publisher','book.publisher.organisation');
		$criteria->together = true;

		return new CActiveDataProvider($this->my(), array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'t.id DESC',
				'attributes'=>array(
					'*',
					'username'=>array(
						'asc'=>'user.username',
						'desc'=>'user.username DESC',
					),
					'name'=>array(
						'asc'=>'organisation.name',
						'desc'=>'organisation.name DESC',
					),
					'title'=>array(
						'asc'=>'book.title',
						'desc'=>'book.title DESC',
					),
				),
			),
			'pagination'=>array('pageSize'=>20,),
		));
	}
	
	public function getVote()
	{
		if ($this->_vote === null)
		{
			switch ($this->type)
			{
				case self::POLL:
					if ($this->points === null) $this->_vote = t('Not voted');
					elseif ($this->points == 1) $this->_vote = t('Yea');
						elseif ($this->points == -1) $this->_vote = t('Nay');
							else $this->_vote = t('Withheld');
					break;
				case self::RATING:
					if ($this->points === null) $this->_vote = t('Not voted');
					else $this->_vote = Yii::t('app', '{n} point|{n} points', $this->points);
			}
		}
		return $this->_vote;
	}
	
	public function getUsername()
	{
		if ($this->_username === null && $this->user !== null)
		{
			$this->_username = $this->user->username;
		}
		return $this->_username;
	}
	
	public function setUsername($value)
	{
		$this->_username = $value;
	}
	
	public function getName()
	{
		if ($this->_name === null && $this->book !== null && $this->book->publisher !== null && $this->book->publisher->organisation !== null)
		{
			$this->_name = $this->book->publisher->organisation->name;
		}
		return $this->_name;
	}
	
	public function setName($value)
	{
		$this->_name = $value;
	}
	
	public function getTitle()
	{
		if ($this->_title === null && $this->book)
		{
			$this->_title = $this->book->title;
		}
		return $this->_title;
	}
	
	public function setTitle($value)
	{
		$this->_title = $value;
	}
	
	public function getType_c()
	{
		return DropDownItem::item('Voting.type', $this->type);
	}	
}
