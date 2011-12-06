<?php

class User extends ActiveRecord
{
	public $_oldPassword;
	public $password_repeat;
	public $register_as;
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{user}}';
	}

	/*
	public function defaultScope()
    {
		return array();
    }
    */

	public function rules()
	{
		return array(
			array('username, password', 'required'),
			array('password_repeat', 'required', 'on'=>'set_password, registration'),
			array('username', 'length', 'max'=>255),
			array('username', 'email'),
			array('username', 'normalizeUserName'),
			array('username', 'unique', 'attributeName'=>'username', 'on'=>'create, update, set_password'),
			array('password', 'length', 'max'=>40),
			array('password', 'compare', 'on'=>'set_password, registration'),
		);
	}

	public function relations()
	{
		return array(
			'organisation' => array(self::HAS_ONE, 'Organisation', 'user_id'),
			'publisher' => array(self::HAS_ONE, 'Publisher', 'user_id'),
			'library' => array(self::HAS_ONE, 'Library', 'user_id'),
			'books' => array(self::HAS_MANY, 'Book', 'user_id'),
			'liborders' => array(self::HAS_MANY, 'LibOrder', 'user_id'),
			'votings' => array(self::HAS_MANY, 'Voting', 'user_id'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' =>  Yii::t('app', 'ID'),
			'create_time' =>  Yii::t('app', 'Create Time'),
			'modify_time' =>  Yii::t('app', 'Modify Time'),
			'ip_address' =>  Yii::t('app', 'Ip Address'),
			'username' =>  Yii::t('app', 'E-mail'),
			'password' =>  Yii::t('app', 'Password'),
			'password_repeat' =>  Yii::t('app', 'Password Repeat'),
			'salt' =>  Yii::t('app', 'Salt'),
			'register_as' =>  Yii::t('app', 'Register As'),
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('t.username',$this->username,true);

		return new CActiveDataProvider($this->my(), array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'t.username',
				'attributes'=>array(
					'*',
					),
				),
			'pagination'=>array('pageSize'=>20,),
		));
	}
	
	public function validatePassword($password)
    {
        return $this->hashPassword($password, $this->salt) === $this->password;
    }
    
	public function normalizeUserName($attribute, $params)
	{
		$this->username = mb_strtolower($this->username, "UTF-8");
	}
	
    public function generateSalt()
    {
		return uniqid(rand());
    }
 
    public function hashPassword($password, $salt)
    {
		$salt1 = mb_substr($salt, 0, ceil(mb_strlen($salt, "UTF-8")/2), "UTF-8");
		$salt2 = mb_substr($salt, ceil(mb_strlen($salt, "UTF-8")/2), mb_strlen($salt, "UTF-8"), "UTF-8");
        return sha1($salt2.'abc'.$password.'xyz'.$salt1);
    }

	protected function beforeSave()
	{
		if (parent::beforeSave())
		{
			if ($this->isNewRecord)
			{
				$this->salt = $this->generateSalt();
				$this->password = $this->hashPassword($this->password, $this->salt);
			}
			else
			{
				if ($this->_oldPassword != $this->password)
				{
					$this->salt = $this->generateSalt();
					$this->password = $this->hashPassword($this->password, $this->salt);
				}
			}
			return true;
		}
		else return false;
	}
	
	protected function afterFind()
	{
		parent::afterFind();
		$this->_oldPassword = $this->password;
	}
}