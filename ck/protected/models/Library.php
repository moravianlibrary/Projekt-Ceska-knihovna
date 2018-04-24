<?php

class Library extends ActiveRecord
{
	public $notSubjectOfLaw = 0;

	private $_name = null;
	private $_longName = null;
	private $_libraryName = null;
	private $_selfContactPlace = null;

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{library}}';
	}

	public function scopes()
	{
		return array_merge(parent::scopes(), array(
			'contactPlaces'=>array(
				'condition'=>'is_contact_place=1',
			),
			'orderPlaced'=>array(
				'condition'=>'order_placed=1',
			),
		));
	}

	public function inRegion($region)
	{
		$this->getDbCriteria()->mergeWith(array(
			'condition'=>'region=:region',
			'params'=>array(':region'=>$region),
		));
		return $this;
	}

	public function rules()
	{
		$rules = array(
			array('libname, street, house_number, city, type', 'filter', 'filter'=>'strip_tags'),
			array('number, type, headcount, units_total, units_new, budget, budget_czech', 'required'),
			array('number', 'match', 'pattern'=>'/^[0-9]{4,5}\/[0-9]{4}$/'),
			array('headcount, units_total, units_new', 'numerical', 'integerOnly'=>true),
			array('budget, budget_czech', 'filter', 'filter'=>array($this, 'numerize')),
			array('land_registry_number, postal_code', 'numerical', 'integerOnly'=>true),
			array('budget, budget_czech', 'numerical'),
			array('notSubjectOfLaw', 'YiiConditionalValidator', 'validation'=>array('compare', 'compareValue'=>1),
				'dependentValidations'=>array(
					'libname, street, city, postal_code'=>array(array('required', 'message'=>Yii::t('app','{dependentAttribute} cannot be blank if the {attribute} is checked.')),),
					'house_number'=>array(array('YiiConditionalValidator', 'validation'=>array('compare', 'compareValue'=>''),
						'dependentValidations'=>array(
							'land_registry_number'=>array(array('required', 'message'=>Yii::t('app','{dependentAttribute} cannot be blank if the {attribute} is blank.')),),),),),
					'land_registry_number'=>array(array('YiiConditionalValidator', 'validation'=>array('compare', 'compareValue'=>''),
						'dependentValidations'=>array(
							'house_number'=>array(array('required', 'message'=>Yii::t('app','{dependentAttribute} cannot be blank if the {attribute} is blank.')),),),),),
				),
			),
			array('libname, street, city, type', 'length', 'max'=>255),
			array('house_number', 'length', 'max'=>5),
			array('postal_code', 'length', 'is'=>5),
			array('private_data, confirmation', 'confirmsValid'),
			array('name, libraryName', 'safe', 'on'=>'search'),
			//array('number', 'unique', 'attributeName'=>'number', 'on'=>'create, update, set_password'),
		);

		if (user()->checkAccess('BackOffice'))
		{
			$rules = array_merge($rules,
			array(
				array('organisation_id, is_contact_place, internal_number', 'required'),
				array('organisation_id, contact_place_id', 'numerical', 'integerOnly'=>true),
				array('contact_place_id', 'contactPlaceValid'),
				array('internal_number', 'length', 'max'=>16),
				array('order_date', 'date', 'format'=>Yii::app()->locale->dateFormat, 'allowEmpty'=>true),
			));
		}

		return $rules;
	}

	public function numerize($value)
	{
		return str_replace(',', '.', $value);
	}

	public function contactPlaceValid($attribute, $params)
	{
		if ($this->selfContactPlace == '0' && $this->contact_place_id == '')
		{
			$this->addError($attribute, strtr(Yii::t('yii','{attribute} cannot be blank.'),array('{attribute}'=>$this->getAttributeLabel($attribute))));
		}
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
			'contact_place' => array(self::BELONGS_TO, 'Library', 'contact_place_id'),
			'orders' => array(self::HAS_MANY, 'LibOrder', 'library_id'),
			'books' => array(self::MANY_MANY, 'Book', '{{lib_order}}(library_id, book_id)'),
			'libraries' => array(self::HAS_MANY, 'Library', 'contact_place_id'),
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
			'internal_number' =>  Yii::t('app', 'Internal Number'),
			'order_date' =>  Yii::t('app', 'Order Delivery Date'),
			'libname' =>  Yii::t('app', 'Name'),
			'street' =>  Yii::t('app', 'Street'),
			'land_registry_number' =>  Yii::t('app', 'Land Registry Number'),
			'house_number' =>  Yii::t('app', 'House Number'),
			'postal_code' =>  Yii::t('app', 'Postal Code'),
			'city' =>  Yii::t('app', 'City'),
			'number' =>  Yii::t('app', 'Library Number'),
			'type' =>  Yii::t('app', 'Library Type'),
			'headcount' =>  Yii::t('app', 'Headcount'),
			'units_total' =>  Yii::t('app', 'Units Total On ').DT::toLoc((param('projectYear')-1).'-12-31'),
			'units_new' =>  Yii::t('app', 'Units New In Year ').(param('projectYear')-1),
			'budget' =>  Yii::t('app', 'Budget For Year ').param('projectYear'),
			'budget_czech' =>  Yii::t('app', 'Budget For Czech Authors For Year ').param('projectYear'),
			'private_data' =>  Yii::t('app', 'We agree with processing our private data.'),
			'confirmation' =>  Yii::t('app', 'We confirm, that the entered informations are correct.'),
			'is_contact_place' =>  Yii::t('app', 'Are You Contact Place?'),
			'contact_place_id' =>  Yii::t('app', 'Contact Place'),
			'order_placed' =>  Yii::t('app', 'Order Placed'),
			'libraryName' =>  Yii::t('app', 'Library Name'),
			'name' =>  Yii::t('app', 'Name'),
			'contactPlaceOrganisationAddress' => Yii::t('app', 'Contact Place Organisation Address'),
			'notSubjectOfLaw' => Yii::t('app', 'Library Is Not a Subject of Law'),
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('t.internal_number',$this->internal_number);
		$criteria->compare('t.number',$this->number,true);
		$criteria->compare('t.order_date',DT::toIso($this->order_date));
		$criteria->compare('t.type',$this->type,true);
		if ($this->libraryName != '')
		{
			$criteria->addCondition('((organisation.name LIKE :name AND (t.libname = \'\' OR t.libname IS NULL)) OR (t.libname LIKE :name))');
			$criteria->params[':name'] = '%'.$this->libraryName.'%';
		}
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

	protected function afterFind()
	{
		parent::afterFind();

		if ($this->libname != '')
			$this->notSubjectOfLaw = 1;
	}

	protected function afterSave()
	{
		parent::afterSave();

		if ($this->isNewRecord)
		{
			if (!am()->checkAccess('LibraryRole', $this->user_id))
			{
				am()->assign('LibraryRole', $this->user_id);
				am()->save();
			}
		}

		if ($this->selfContactPlace == '1')
		{
			$this->contact_place_id = $this->id;
			$this->isNewRecord = false;
			$this->saveAttributes(array('contact_place_id'));
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

	public function getLongName()
	{
		if ($this->_longName === null && $this->organisation !== null)
		{
			$this->_longName = $this->organisation->name.', '.$this->organisation->city;
		}
		return $this->_longName;
	}

	public function getSelfContactPlace()
	{
		if ($this->_selfContactPlace === null)
		{
			if ($this->contact_place_id == $this->id) $this->_selfContactPlace = '1';
			else $this->_selfContactPlace = '0';
		}
		return $this->_selfContactPlace;
	}

	public function setSelfContactPlace($value)
	{
		$this->_selfContactPlace = $value;
	}

	public function getContactPlaceOrganisationAddress()
	{
		return $this->contact_place->organisation->address;
	}

	public function getFullStreet()
	{
		$ret = '';
		if ($this->street != '')
		{
			$ret .= $this->street.' ';
			if ($this->land_registry_number != '')
			{
				$ret .= $this->land_registry_number;
				if ($this->house_number != '') $ret .= '/'.$this->house_number;
			}
			elseif ($this->house_number != '')
				{
					$ret .= $this->house_number;
				}
		}
		return $ret;
	}

	public function getFullCity()
	{
		$ret = '';
		if ($this->city != '')
		{
			$ret = $this->city;
			if ($this->street == '' && $this->land_registry_number != '') $ret .= ' '.$this->land_registry_number;
		}
		return $ret;
	}

	public function getFullAddress()
	{
		if ($this->libname != '')
		{
			$ret = array();
			$ret[] = $this->libname;
			if ($this->street != '') $ret[] = $this->fullStreet;
			if ($this->city != '') $ret[] = $this->fullCity;
			if ($this->postal_code != '') $ret[] = $this->postal_code;
			return $ret;
		}
		else
			return $this->organisation->fullAddress;
	}

	public function getLibraryName()
	{
		if ($this->_libraryName === null)
		{
			if ($this->libname != '')
				$this->_libraryName = $this->libname;
			elseif ($this->organisation !== null)
					$this->_libraryName = $this->organisation->name;
		}
		return $this->_libraryName;
	}

	public function setLibraryName($value)
	{
		$this->_libraryName = $value;
	}

	public function getCityAndIntNum()
	{
		return $this->organisation->city.' - '.$this->internal_number;
	}

	public function getCanDelete()
	{
		return (user()->checkAccess('Library'));
	}

	public function getBasicCount()
	{
		$reserveCount = db()->createCommand("SELECT SUM({{lib_order}}.count) AS count FROM {{lib_order}} WHERE {{lib_order}}.library_id=".$this->id." AND {{lib_order}}.type='".LibOrder::BASIC."' GROUP BY {{lib_order}}.library_id")->queryScalar();
		return ($reserveCount ? $reserveCount : 0);
	}

	public function getBasicPrice()
	{
		$basicPrice = db()->createCommand("SELECT SUM({{lib_order}}.count*{{book}}.project_price) AS price FROM {{lib_order}} LEFT JOIN {{book}} ON {{lib_order}}.book_id={{book}}.id WHERE {{lib_order}}.library_id=".$this->id." AND {{lib_order}}.type='".LibOrder::BASIC."' GROUP BY {{lib_order}}.library_id")->queryScalar();
		return ($basicPrice ? $basicPrice : 0);
	}

	public function getReserveCount()
	{
		$reserveCount = db()->createCommand("SELECT SUM({{lib_order}}.count) AS count FROM {{lib_order}} WHERE {{lib_order}}.library_id=".$this->id." AND {{lib_order}}.type='".LibOrder::RESERVE."' GROUP BY {{lib_order}}.library_id")->queryScalar();
		return ($reserveCount ? $reserveCount : 0);
	}

	public function getReservePrice()
	{
		$basicPrice = db()->createCommand("SELECT SUM({{lib_order}}.count*{{book}}.project_price) AS price FROM {{lib_order}} LEFT JOIN {{book}} ON {{lib_order}}.book_id={{book}}.id WHERE {{lib_order}}.library_id=".$this->id." AND {{lib_order}}.type='".LibOrder::RESERVE."' GROUP BY {{lib_order}}.library_id")->queryScalar();
		return ($basicPrice ? $basicPrice : 0);
	}
}