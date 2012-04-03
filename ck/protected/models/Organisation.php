<?php

class Organisation extends ActiveRecord
{
	private $_email = null;

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{organisation}}';
	}
	
	public function rules()
	{
		$rules = array(
			array('name, street, house_number, city, vat_id_number, representative, telephone, fax, bank_account_number, revenue_authority, worker_name, worker_telephone, worker_fax', 'filter', 'filter'=>'strip_tags'),		
			array('name, street, postal_code, city, region, representative, telephone, worker_name, worker_telephone, worker_email', 'required'),
			array('bank_account_number,  revenue_authority, telephone, company_id_number, vat_id_number', 'required', 'on'=>'publisher'),
			array('land_registry_number, postal_code, company_id_number', 'numerical', 'integerOnly'=>true),
			array('name, street, city, representative, telephone, fax, www, bank_account_number, worker_name, worker_telephone, worker_fax, worker_email', 'length', 'max'=>255),
			array('house_number', 'length', 'max'=>5),		
			array('house_number', 'YiiConditionalValidator', 'validation'=>array('compare', 'compareValue'=>''), 'dependentValidations'=>array(
                'land_registry_number'=>array(array('required', 'message'=>Yii::t('app','{dependentAttribute} cannot be blank if the {attribute} is blank.')),),
				),			
			),			
			array('land_registry_number', 'YiiConditionalValidator', 'validation'=>array('compare', 'compareValue'=>''), 'dependentValidations'=>array(
                'house_number'=>array(array('required', 'message'=>Yii::t('app','{dependentAttribute} cannot be blank if the {attribute} is blank.')),),
				),			
			),
			array('postal_code', 'length', 'is'=>5),
			array('region', 'in', 'range'=>array_keys(DropDownItem::items('Organisation.region'))),
			array('company_id_number', 'length', 'is'=>8),
			array('vat_id_number', 'length', 'min'=>10, 'max'=>12),
			array('www', 'url', 'defaultScheme'=>'http'),
			array('revenue_authority', 'length', 'max'=>1024),
			array('worker_email', 'email'),
			array('email', 'safe', 'on'=>'search'),
		);
		
		if (user()->checkAccess('BackOffice'))
		{
			$rules = array_merge($rules,
			array(
				array('user_id', 'required'),
				array('user_id', 'numerical', 'integerOnly'=>true),
				array('salutation', 'length', 'max'=>255),
			));
		}		

		return $rules;
	}

	public function relations()
	{
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'library' => array(self::HAS_ONE, 'Library', 'organisation_id'),
			'publisher' => array(self::HAS_ONE, 'Publisher', 'organisation_id'),
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
			'name' =>  Yii::t('app', 'Applicant Name'),
			'street' =>  Yii::t('app', 'Street'),
			'land_registry_number' =>  Yii::t('app', 'Land Registry Number'),
			'house_number' =>  Yii::t('app', 'House Number'),
			'postal_code' =>  Yii::t('app', 'Postal Code'),
			'city' =>  Yii::t('app', 'City'),
			'region' =>  Yii::t('app', 'Region'),
			'company_id_number' =>  Yii::t('app', 'Company Identification Number'),
			'vat_id_number' =>  Yii::t('app', 'VAT Identification Number'),
			'representative' =>  Yii::t('app', 'Representative'),
			'telephone' =>  Yii::t('app', 'Telephone'),
			'fax' =>  Yii::t('app', 'Fax'),
			'www' =>  Yii::t('app', 'WWW'),
			'bank_account_number' =>  Yii::t('app', 'Bank Account Number'),
			'revenue_authority' =>  Yii::t('app', 'Revenue Authority'),
			'worker_name' =>  Yii::t('app', 'Worker Name'),
			'salutation' =>  Yii::t('app', 'Salutation'),
			'worker_telephone' =>  Yii::t('app', 'Telephone'),
			'worker_fax' =>  Yii::t('app', 'Fax'),
			'worker_email' =>  Yii::t('app', 'E-mail'),
			'email' =>  Yii::t('app', 'E-mail'),
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('t.name',$this->name,true);
		$criteria->compare('t.street',$this->street,true);
		$criteria->compare('t.postal_code',$this->postal_code);
		$criteria->compare('t.city',$this->city,true);
		$criteria->compare('t.company_id_number',$this->company_id_number);
		$criteria->compare('user.username',$this->email,true);
		$criteria->with = array('user');

		return new CActiveDataProvider($this->my(), array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'t.name',
				'attributes'=>array(
					'*',
					'email'=>array(
						'asc'=>'user.username',
						'desc'=>'user.username desc',
						),
					),
				),
			'pagination'=>array('pageSize'=>20,),
		));
	}
	
	public function getEmail()
	{
		if ($this->_email === null && $this->user !== null)
		{
			$this->_email = $this->user->username;
		}
		return $this->_email;
	}
	
	public function setEmail($value)
	{
		$this->_email = $value;
	}
	
	public function getAddress()
	{
		$ret = array();
		if ($this->street != '') $ret[] = $this->fullStreet;
		if ($this->city != '') $ret[] = $this->fullCity;
		if ($this->postal_code != '') $ret[] = $this->postal_code;
		return implode(', ', $ret);
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
		$ret = array();
		$ret[] = $this->name;
		if ($this->worker_name) $ret[] = $this->worker_name;
		if ($this->street != '') $ret[] = $this->fullStreet;
		if ($this->city != '') $ret[] = $this->fullCity;
		if ($this->postal_code != '') $ret[] = $this->postal_code;
		return $ret;
	}
	
	public function getRegion_c()
	{
		return DropDownItem::item('Organisation.region', $this->region);
	}
	
	public function getCanDelete()
	{
		return (user()->checkAccess('Organisation'));
	}
}