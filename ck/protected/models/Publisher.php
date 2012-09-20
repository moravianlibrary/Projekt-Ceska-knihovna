<?php

class Publisher extends ActiveRecord
{
	private $_name = null;

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{publisher}}';
	}

	public function scopes()
	{
		return array_merge(parent::scopes(), array(
			'unSelected'=>array(
				'condition'=>'selected=0',
			),
			'selected'=>array(
				'condition'=>'selected=1',
			),
			'orderPlaced'=>array(
				'condition'=>'order_placed=1',
			),
		));
	}

	public function rules()
	{
		$rules = array(
			array('code', 'filter', 'filter'=>'strip_tags'),
			array('code', 'required'),
			array('code', 'length', 'max'=>255),
			array('private_data, confirmation', 'confirmsValid'),
			array('name', 'safe', 'on'=>'search'),
		);

		if (user()->checkAccess('BackOffice'))
		{
			$rules = array_merge($rules,
			array(
				array('organisation_id', 'required'),
				array('organisation_id', 'numerical', 'integerOnly'=>true),
				array('request_date', 'date', 'format'=>Yii::app()->locale->dateFormat, 'allowEmpty'=>true),
			));
		}

		return $rules;
	}

	public function confirmsValid($attribute, $params)
	{
		if (!$this->$attribute) $this->addError($attribute, strtr(Yii::t('app','{attribute} must be checked.'),array('{attribute}'=>$this->getAttributeLabel($attribute))));
	}

	public function isAttributeRequired($attribute)
	{
		switch ($attribute)
		{
			case 'private_data': case 'confirmation': return true; break;
			default: return parent::isAttributeRequired($attribute); break;
		}
	}

	public function relations()
	{
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'organisation' => array(self::BELONGS_TO, 'Organisation', 'organisation_id'),
			'books' => array(self::HAS_MANY, 'Book', 'publisher_id'),
			'count_last_year_books' => array(self::STAT, 'Book', 'publisher_id', 'condition'=>'user_id=\''.user()->id.'\' AND issue_year=\''.(param('projectYear')-1).'\''),
			'count_this_year_books' => array(self::STAT, 'Book', 'publisher_id', 'condition'=>'user_id=\''.user()->id.'\' AND issue_year=\''.param('projectYear').'\''),
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
			'organisation_id' =>  Yii::t('app', 'Organisation'),
			'code' =>  Yii::t('app', 'Publisher Code'),
			'request_date' =>  Yii::t('app', 'Request Delivery Date'),
			'private_data' =>  Yii::t('app', 'We agree with processing our private data.'),
			'confirmation' =>  Yii::t('app', 'We confirm, that the entered informations are correct and we do not have liabilities to national budget.'),
			'order_placed' =>  Yii::t('app', 'Order Placed'),
			'name' =>  Yii::t('app', 'Name'),
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('organisation.name',$this->name, true);
		$criteria->compare('t.request_date',DT::toIso($this->request_date));
		$criteria->compare('t.code',$this->code,true);
		$criteria->with = array('organisation');

		return new CActiveDataProvider($this->my(), array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'organisation.name',
				'attributes'=>array(
					'*',
					'name'=>array(
						'asc'=>'organisation.name',
						'desc'=>'organisation.name desc',
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
			if (!am()->checkAccess('PublisherRole', $this->user_id))
			{
				am()->assign('PublisherRole', $this->user_id);
				am()->save();
			}
		}
	}

	public function getName()
	{
		if ($this->_name === null && $this->organisation !== null)
		{
			$this->_name = $this->organisation->name;
		}
		return $this->_name;
	}

	public function setName($value)
	{
		$this->_name = $value;
	}

	public function getCanDelete()
	{
		return (user()->checkAccess('Publisher'));
	}
}