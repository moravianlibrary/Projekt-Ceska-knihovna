<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Manage Lib Orders');
?>

<?php $this->insertDialog(); ?>

<h1><?php echo Yii::t('app', 'Manage Lib Orders'); ?></h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'lib-order-grid',
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
			'name'=>'library_name',
			'value'=>'CHtml::link(CHtml::encode($data->library_name), \'#\', array(\'onclick\'=>CHtml::ajax(array(\'url\'=>url(\'library/view\',array(\'id\'=>$data->library_id)), \'success\'=>\'function(data){$("#library-juidialog-content").html(data);$("#library-juidialog").dialog("option", "modal", false).dialog("open");return false;}\'))));',
			'type'=>'raw',
		),
		'date',
		'count',
		'delivered',
		'remaining',
		array(
			'name'=>'type',
			'value'=>'$data->type_c',
			'type'=>'raw',
			'filter'=>DropDownItem::items('LibOrder.type'),
		),
	),
)); ?>
