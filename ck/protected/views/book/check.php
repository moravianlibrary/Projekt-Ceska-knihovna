<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'book-grid',
	'dataProvider'=>$model->search(),
	'columns'=>array(
		'isbn',
		'author',
		'title',
		array(
			'name'=>'name',
			'value'=>'CHtml::link(CHtml::encode($data->name), \'#\', array(\'onclick\'=>CHtml::ajax(array(\'url\'=>url(\'publisher/view\',array(\'id\'=>$data->publisher_id)), \'success\'=>\'function(data){$("#publisher-juidialog-content").html(data);$("#publisher-juidialog").dialog("option", "modal", false).dialog("open");return false;}\'))));',
			'type'=>'raw',
		),
		'issue_year',
		'project_year',
		array(
			'name'=>'status',
			'value'=>'$data->status_c',
			'type'=>'raw',
			'filter'=>DropDownItem::items('Book.status'),
		),
	),
)); ?>
