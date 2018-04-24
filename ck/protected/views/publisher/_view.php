<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'formatter'=>new Formatter,
	'attributes'=>array(
		array(
			'name'=>t('Applicant Working Name'),
			'type'=>'raw',
			'value'=>CHtml::link(CHtml::encode($model->name), '#', array('onclick'=>CHtml::ajax(array('url'=>url('organisation/view',array('id'=>$model->organisation_id)), 'success'=>'function(data){$("#organisation-juidialog-content").html(data);$("#organisation-juidialog").dialog("option", "modal", false).dialog("open");return false;}')))),
		),
		array(
			'name'=>t('Applicant Original name'),
			'type'=>'raw',
			'value'=>CHtml::encode($model->organisation->original_name)
		),
		'code',
		'private_data:boolean',
		'confirmation:boolean',
	),
)); ?>
