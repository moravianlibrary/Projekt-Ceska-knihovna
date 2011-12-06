<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'formatter'=>new Formatter,
	'attributes'=>array(
		array(
			'name'=>'book_title',
			'type'=>'raw',
			'value'=>CHtml::link(CHtml::encode($model->book_title), '#', array('onclick'=>CHtml::ajax(array('url'=>url('book/view',array('id'=>$model->book_id)), 'success'=>'function(data){$("#book-juidialog-content").html(data);$("#book-juidialog").dialog("option", "modal", false).dialog("open");return false;}')))),
        ),
		array(
			'name'=>'publisher_name',
			'type'=>'raw',
			'value'=>CHtml::link(CHtml::encode($model->publisher_name), '#', array('onclick'=>CHtml::ajax(array('url'=>url('publisher/view',array('id'=>$model->book->publisher_id)), 'success'=>'function(data){$("#publisher-juidialog-content").html(data);$("#publisher-juidialog").dialog("option", "modal", false).dialog("open");return false;}')))),
        ),
		'date',
		'count',
		'delivered',
		'price:czk',
		'type_c',
	),
)); ?>
