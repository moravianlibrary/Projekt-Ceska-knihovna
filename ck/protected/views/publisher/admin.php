<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Manage Publishers');
?>

<?php $this->insertDialog(); ?>

<h1><?php echo Yii::t('app', 'Manage Publishers'); ?></h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'publisher-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'header'=>Yii::t('app', 'Actions'),
			'class'=>'ButtonColumn',
			'template'=>'{view} {update} {delete} {request}',
			'buttons'=>array(
				'delete'=>array(
					'visible'=>'$data->canDelete',
				),
				'request'=>array(
					'label'=>t('Request'),
					'imageUrl'=>Yii::app()->request->baseUrl.'/images/basic.png',
					'url'=>'Yii::app()->createUrl("publisher/printBooks", array("id"=>$data->id))',
					'visible'=>'$data->offer_id',
					//'visible'=>'true',
                                ),
			),
		),
		array(
			'name'=>'name',
			'value'=>'CHtml::link(CHtml::encode($data->name), \'#\', array(\'onclick\'=>CHtml::ajax(array(\'url\'=>url(\'organisation/view\',array(\'id\'=>$data->organisation_id)), \'success\'=>\'function(data){$("#organisation-juidialog-content").html(data);$("#organisation-juidialog").dialog("option", "modal", false).dialog("open");return false;}\'))));',
			'type'=>'raw',
		),
		'request_date',
	),
)); ?>
