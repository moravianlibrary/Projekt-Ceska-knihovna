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
			'name'=>'library_name',
			'type'=>'raw',
			'value'=>CHtml::link(CHtml::encode($model->library_name), '#', array('onclick'=>CHtml::ajax(array('url'=>url('library/view',array('id'=>$model->library_id)), 'success'=>'function(data){$("#library-juidialog-content").html(data);$("#library-juidialog").dialog("option", "modal", false).dialog("open");return false;}')))),
        ),
		'count',
		'delivered',
		'type_c',
	),
)); ?>
