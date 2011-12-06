<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Manage Stock Activities');
?>

<?php $this->insertDialog(); ?>

<h1><?php echo Yii::t('app', 'Manage Stock Activities'); ?></h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'stock-activity-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'header'=>Yii::t('app', 'Actions'),
			'class'=>'ButtonColumn',
		),
		'date',
		'count:pcs',
	),
)); ?>
