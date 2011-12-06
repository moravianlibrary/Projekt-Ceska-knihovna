<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Manage Pub Orders');
?>

<?php $this->insertDialog(); ?>

<h1><?php echo Yii::t('app', 'Manage Pub Orders'); ?></h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'formatter'=>new Formatter,
	'id'=>'pub-order-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'header'=>Yii::t('app', 'Actions'),
			'class'=>'ButtonColumn',
		),
		array(
			'name'=>'book_title',
			'value'=>'CHtml::link(CHtml::encode($data->book_title), \'#\', array(\'onclick\'=>CHtml::ajax(array(\'url\'=>url(\'book/view\',array(\'id\'=>$data->book_id)), \'success\'=>\'function(data){$("#book-juidialog-content").html(data);$("#book-juidialog").dialog("option", "modal", false).dialog("open");return false;}\'))));',
			'type'=>'raw',
		),
		array(
			'name'=>'publisher_name',
			'value'=>'CHtml::link(CHtml::encode($data->publisher_name), \'#\', array(\'onclick\'=>CHtml::ajax(array(\'url\'=>url(\'publisher/view\',array(\'id\'=>$data->book->publisher_id)), \'success\'=>\'function(data){$("#publisher-juidialog-content").html(data);$("#publisher-juidialog").dialog("option", "modal", false).dialog("open");return false;}\'))));',
			'type'=>'raw',
		),
		'date',
		'count',
		'delivered',
		'remaining',
		'price:czk',
		array(
			'name'=>'type',
			'value'=>'$data->type_c',
			'type'=>'raw',
			'filter'=>DropDownItem::items('PubOrder.type'),
		),
		array(
			'header'=>t('Order'),
			'value'=>'($data->remaining > 0 ? CHtml::link(\'<img src="/images/list.png">\',  array(\'printOrder\', \'publisher_id\'=>$data->book->publisher_id, \'puborder_id\'=>$data->id), array(\'target\'=>\'_blank\')) : \'\')',
			'type'=>'raw',
			'filter'=>false,
			'htmlOptions'=>array('style'=>'text-align: center;'),
		),
	),
)); ?>
