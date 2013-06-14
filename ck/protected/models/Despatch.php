<?php

class Despatch extends ActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{despatch}}';
	}

	public function rules()
	{
		return array(
			array('library_id, contact_place_id, bill_count, print_address', 'numerical', 'integerOnly'=>true),
			array('date_from, date_to', 'date', 'format'=>Yii::app()->locale->dateFormat),
		);
	}

	public function relations()
	{
		return array(
			'contactPlace' => array(self::BELONGS_TO, 'Library', 'contact_place_id'),
			'library' => array(self::BELONGS_TO, 'Library', 'library_id'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' =>  Yii::t('app', 'ID'),
			'create_time' =>  Yii::t('app', 'Create Time'),
			'modify_time' =>  Yii::t('app', 'Modify Time'),
			'ip_address' =>  Yii::t('app', 'Ip Address'),
			'library_id' =>  Yii::t('app', 'Library'),
			'contact_place_id' =>  Yii::t('app', 'Contact Place'),
			'date_from' =>  Yii::t('app', 'Date From'),
			'date_to' =>  Yii::t('app', 'Date To'),
			'bill_count' =>  Yii::t('app', 'Bill Count'),
			'print_address' =>  Yii::t('app', 'Print Address'),
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('library_id',$this->library_id);
		$criteria->compare('contact_place_id',$this->contact_place_id);
		$criteria->compare('date_from',$this->date_from,true);
		$criteria->compare('date_to',$this->date_to,true);
		$criteria->compare('bill_count',$this->bill_count);
		$criteria->compare('print_address',$this->print_address);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'t.id DESC',
				'attributes'=>array(
					'*',
					),
				),
			'pagination'=>array('pageSize'=>20,),
		));
	}

	public static function Last()
	{
		$result = db()->createCommand("SELECT MAX(id) AS last FROM {{despatch}}'")->queryScalar();
		if ($result)
			return $result;
		else
			return 0;
	}
}