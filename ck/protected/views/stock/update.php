<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Update Stock');
?>

<?php $this->insertDialog(array('StockActivity'=>array('forceDialog'=>true, 'success'=>'jQuery("#count").val(data.model.stock.count); $.fn.yiiGridView.update("stock-activity-grid");'))); ?>

<h1><?php echo Yii::t('app', 'Update Stock') . Yii::t('app', ' #') . $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>

<hr />

<?/*$this->widget('wsext.JuiDialogCreateButton', array('model'=>'StockActivity', 'urlParams'=>array('stock_id'=>$model->id)));*/?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'stock-activity-grid',
	'ajaxUpdate'=>true,
	'formatter'=>new Formatter,
	'dataProvider'=>$stockActivityProvider,
	'columns'=>array(
		array(
			'header'=>Yii::t('app', 'Actions'),
			'class'=>'ButtonColumn',
			'template'=>'{update} {delete}',
			'updateButtonUrl'=>'url(\'stockActivity/update\', array(\'id\'=>$data->id))',
			'deleteButtonUrl'=>'url(\'stockActivity/delete\', array(\'id\'=>$data->id))',
			'beforeDeleteMessages'=>'jQuery("#count").val(data.model.stock.count);',
		),
		'date',
		'count:pcs',
		array(
			'header'=>Yii::t('app', 'Library'),
			'name'=>'lib_order.library.name',
			'value'=>'CHtml::link(CHtml::encode($data->lib_order->library->name), \'#\', array(\'onclick\'=>CHtml::ajax(array(\'url\'=>url(\'library/view\',array(\'id\'=>$data->lib_order->library_id)), \'success\'=>\'function(data){$("#library-juidialog-content").html(data);$("#library-juidialog").dialog("option", "modal", false).dialog("open");return false;}\'))));',
			'type'=>'raw',
		),
		array(
			'header'=>Yii::t('app', 'Publisher'),
			'name'=>'pub_order.book.publisher.name',
			'value'=>'CHtml::link(CHtml::encode($data->pub_order->book->publisher->name), \'#\', array(\'onclick\'=>CHtml::ajax(array(\'url\'=>url(\'publisher/view\',array(\'id\'=>$data->pub_order->book->publisher_id)), \'success\'=>\'function(data){$("#publisher-juidialog-content").html(data);$("#publisher-juidialog").dialog("option", "modal", false).dialog("open");return false;}\'))));',
			'type'=>'raw',
		),
	),
)); ?>
