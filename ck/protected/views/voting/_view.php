<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'formatter'=>new Formatter,
	'attributes'=>array(
		'username:email',
		array(
			'name'=>'name',
			'type'=>'raw',
			'value'=>CHtml::link(CHtml::encode($model->name), '#', array('onclick'=>CHtml::ajax(array('url'=>url('publisher/view',array('id'=>$model->book->publisher_id)), 'success'=>'function(data){$("#publisher-juidialog-content").html(data);$("#publisher-juidialog").dialog("option", "modal", false).dialog("open");return false;}')))),
        ),
		array(
			'name'=>'title',
			'type'=>'raw',
			'value'=>CHtml::link(CHtml::encode($model->title), '#', array('onclick'=>CHtml::ajax(array('url'=>url('book/view',array('id'=>$model->book_id)), 'success'=>'function(data){$("#book-juidialog-content").html(data);$("#book-juidialog").dialog("option", "modal", false).dialog("open");return false;}')))),
        ),
		'vote',
		'type_c',
	),
)); ?>
