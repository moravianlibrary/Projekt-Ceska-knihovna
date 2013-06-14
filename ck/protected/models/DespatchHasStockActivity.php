<?php

class DespatchHasStockActivity extends ActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{despatch_has_stock_activity}}';
	}

	public function primaryKey()
	{
		return array('despatch_id', 'stock_activity_id');
	}

	public function rules()
	{
		return array(
		);
	}

	public function relations()
	{
		return array(
			'despatch' => array(self::BELONGS_TO, 'Despatch', 'despatch_id'),
			'stockActivity' => array(self::BELONGS_TO, 'StockActivity', 'stock_activity_id'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'despatch_id' =>  Yii::t('app', 'Despatch'),
			'stock_activity_id' =>  Yii::t('app', 'Stock Activity'),
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;


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
}