<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'formatter'=>new Formatter,
	'attributes'=>array(
		array(
			'name'=>'name',
			'type'=>'raw',
			'value'=>CHtml::link(CHtml::encode($model->name), '#', array('onclick'=>CHtml::ajax(array('url'=>url('organisation/view',array('id'=>$model->organisation_id)), 'success'=>'function(data){$("#organisation-juidialog-content").html(data);$("#organisation-juidialog").dialog("option", "modal", false).dialog("open");return false;}')))),
        ),
		'code',
		'private_data:boolean',
		'confirmation:boolean',
	),
)); ?>
