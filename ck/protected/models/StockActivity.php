<?php

class StockActivity extends ActiveRecord
{
	private $_oldCount = null;

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{stock_activity}}';
	}

	public function rules()
	{
		return array(
			array('count, date', 'required'),
			array('date', 'date', 'format'=>Yii::app()->locale->dateFormat),
			array('count', 'numerical', 'integerOnly'=>true),
			array('count', 'countValid', 'on'=>'insert, update, stock'),
			array('invoice', 'length', 'max'=>64, 'allowEmpty'=>true),
			array('price', 'filter', 'filter'=>array($this, 'numerize')),
			array('price', 'numerical', 'allowEmpty'=>true),
		);
	}

	public function countValid($attribute, $params)
	{
		if ($this->scenario == 'stock')
		{
			$label = t('Actual Delivery');
			$modif = 1;
		}
		else
		{
			$label = $this->getAttributeLabel($attribute);
			$modif = -1;
		}

		if ($this->pub_order_id !== null)
		{
			if ($this->count <= 0)
			{
				$this->addError($attribute, strtr(Yii::t('yii','{attribute} must be greater than "{compareValue}".'),array('{attribute}'=>$label, '{compareValue}'=>0)));
				return;
			}

			$remaining = $this->pub_order->remaining;
			if (!$this->isNewRecord)
				$remaining += abs($this->_oldCount);

			if (abs($this->count) > $remaining) $this->addError($attribute, strtr(Yii::t('yii','{attribute} must be less than or equal to "{compareValue}".'),array('{attribute}'=>$label, '{compareValue}'=>$remaining)));
		}
		if ($this->lib_order_id !== null)
		{
			if ($this->count >= 0)
			{
				$this->addError($attribute, strtr(Yii::t('yii','{attribute} must be less than "{compareValue}".'),array('{attribute}'=>$label, '{compareValue}'=>0)));
				return;
			}

			$stock_count = $this->stock->count;
			if (!$this->isNewRecord)
				$stock_count += abs($this->_oldCount);

			if (abs($this->count) > $stock_count)
			{
				$this->addError($attribute, strtr(Yii::t('yii','Na skladě není dostatečné množství položek (maximální hodnota je {maxValue}).'), array('{maxValue}'=>$modif*$stock_count)));
				return;
			}

			$remaining = $this->lib_order->remaining;
			if (!$this->isNewRecord)
				$remaining += abs($this->_oldCount);

			if (abs($this->count) > $remaining)
			{
				if ($this->scenario == 'stock')
					$this->addError($attribute, strtr(Yii::t('yii','{attribute} must be less than or equal to "{compareValue}".'),array('{attribute}'=>$label, '{compareValue}'=>$remaining)));
				else
					$this->addError($attribute, strtr(Yii::t('yii','{attribute} must be greater than or equal to "{compareValue}".'),array('{attribute}'=>$label, '{compareValue}'=>-$remaining)));
			}
		}
	}

	public function numerize($value)
	{
		return str_replace(',', '.', $value);
	}

	public function relations()
	{
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'stock' => array(self::BELONGS_TO, 'Stock', 'stock_id'),
			'pub_order' => array(self::BELONGS_TO, 'PubOrder', 'pub_order_id'),
			'lib_order' => array(self::BELONGS_TO, 'LibOrder', 'lib_order_id'),
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
			'stock_id' =>  Yii::t('app', 'Stock'),
			'publisher_id' =>  Yii::t('app', 'Publisher'),
			'library_id' =>  Yii::t('app', 'Library'),
			'date' =>  Yii::t('app', 'Date'),
			'count' =>  Yii::t('app', 'Count'),
		);
	}

	public function search()
	{
		return new CActiveDataProvider($this->my(), array(
			'sort'=>array(
				'defaultOrder'=>'t.id DESC',
				'attributes'=>array(
					'*',
					),
				),
			'pagination'=>array('pageSize'=>20,),
		));
	}

	protected function afterFind()
	{
		parent::afterFind();
		$this->_oldCount = $this->count;
	}

	protected function afterSave()
	{
		parent::afterSave();
		$stock = Stock::model()->findByPk($this->stock_id);
		$stock->count = $stock->sum_activities;
		$stock->save(false);

		if ($this->pub_order_id)
		{
			$puborder = PubOrder::model()->findByPk($this->pub_order_id);
			$puborder->delivered = $puborder->sum_activities;
			$puborder->save(false);
		}

		if ($this->lib_order_id)
		{
			$liborder = LibOrder::model()->findByPk($this->lib_order_id);
			$liborder->delivered = abs($liborder->sum_activities);
			$liborder->save(false);
		}
	}

	protected function afterDelete()
	{
		parent::afterDelete();
		$stock = Stock::model()->findByPk($this->stock_id);
		$stock->count = $stock->sum_activities;
		$stock->save(false);

		if ($this->pub_order_id)
		{
			$puborder = PubOrder::model()->findByPk($this->pub_order_id);
			$puborder->delivered = $puborder->sum_activities;
			$puborder->save(false);
		}

		if ($this->lib_order_id)
		{
			$liborder = LibOrder::model()->findByPk($this->lib_order_id);
			$liborder->delivered = abs($liborder->sum_activities);
			$liborder->save(false);
		}
	}
}