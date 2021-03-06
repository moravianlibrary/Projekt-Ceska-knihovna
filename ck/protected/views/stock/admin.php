<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Manage Stocks');
?>

<?php $this->insertDialog(); ?>

<h1><?php echo Yii::t('app', 'Manage Stocks'); ?></h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'stock-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'header'=>Yii::t('app', 'Actions'),
			'class'=>'ButtonColumn',
			'template'=>'{view} {update}',
		),
		'name'=>'book_selected_order',
		array(
			'name'=>'book_title',
			'value'=>'CHtml::link(CHtml::encode($data->book_title), \'#\', array(\'onclick\'=>CHtml::ajax(array(\'url\'=>url(\'book/view\',array(\'id\'=>$data->book_id)), \'success\'=>\'function(data){$("#book-juidialog-content").html(data);$("#book-juidialog").dialog("option", "modal", false).dialog("open");return false;}\'))));',
			'type'=>'raw',
		),
		array(
			'name'=>'type',
			'value'=>'$data->type_c',
			'type'=>'raw',
			'filter'=>DropDownItem::items('Stock.type'),
		),
		'count',
	),
)); ?>
