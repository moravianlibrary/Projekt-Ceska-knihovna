<?php

class m130614_085351_create_despatch_table extends CDbMigration
{
	public function up()
	{
		$this->createTable('yii_despatch', array(
				'id'=>'int(11) NOT NULL AUTO_INCREMENT',
				'create_time'=>'datetime DEFAULT NULL',
				'modify_time'=>'datetime DEFAULT NULL',
				'ip_address'=>'int(11) DEFAULT NULL',
				'library_id'=>'int(11) DEFAULT NULL',
				'contact_place_id'=>'int(11) DEFAULT NULL',
				'date_from'=>'date DEFAULT NULL',
				'date_to'=>'date DEFAULT NULL',
				'bill_count'=>'tinyint(4) DEFAULT NULL',
				'print_address'=>'tinyint(4) DEFAULT NULL',
				'PRIMARY KEY (id)',
				'KEY fk_yii_despatch_yii_library1 (library_id)',
				'KEY fk_yii_despatch_yii_library2 (contact_place_id)',
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci'
		);

		$this->createTable('yii_despatch_has_stock_activity', array(
				'despatch_id'=>'int(11) DEFAULT 0 NOT NULL',
				'stock_activity_id'=>'int(11) DEFAULT 0 NOT NULL',
				'PRIMARY KEY (despatch_id, stock_activity_id)',
				'KEY fk_yii_despatch_has_stock_activity_yii_despatch1 (despatch_id)',
				'KEY fk_yii_despatch_has_stock_activity_yii_stock_activity1 (stock_activity_id)',
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci'
		);
	}

	public function down()
	{
		echo "m130614_085351_create_despatch_table does not support migration down.\n";
		return false;
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}