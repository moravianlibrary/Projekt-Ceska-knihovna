<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Manage Libraries');
?>

<?php $this->insertDialog(); ?>

<h1><?php echo Yii::t('app', 'Manage Libraries'); ?></h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'library-grid',
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
		array(
			'name'=>'libraryName',
			'value'=>'CHtml::link(CHtml::encode($data->libraryName), \'#\', array(\'onclick\'=>CHtml::ajax(array(\'url\'=>url(\'organisation/view\',array(\'id\'=>$data->organisation_id)), \'success\'=>\'function(data){$("#organisation-juidialog-content").html(data);$("#organisation-juidialog").dialog("option", "modal", false).dialog("open");return false;}\'))));',
			'type'=>'raw',
		),
		'internal_number',
		'number',
		'order_date',
		'type',
	),
)); ?>
