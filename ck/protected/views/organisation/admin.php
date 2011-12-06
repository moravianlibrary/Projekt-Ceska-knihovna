<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Manage Organisations');
?>

<?php $this->insertDialog(); ?>

<h1><?php echo Yii::t('app', 'Manage Organisations'); ?></h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'organisation-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'header'=>Yii::t('app', 'Actions'),
			'class'=>'ButtonColumn',
			'buttons'=>array(
				'delete'=>array(
					'visible'=>'$data->canDelete',
				),
			),
		),
		'name',
		'street',
		'postal_code',
		'city',
		'company_id_number',
		'email:email',
	),
)); ?>
