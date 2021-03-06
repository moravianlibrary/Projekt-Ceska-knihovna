<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'basic-list',
	'dataProvider'=>$dataProvider,
	'template'=>'{items}',
	'emptyText'=>'',
	'enableSorting'=>false,
	'itemsCssClass'=>'blackborder',
	'columns'=>array(
		'book.selected_order',
		'book.name',
		'book.author',
		'book.title',
		'book.issue_year',
		array(
			'header'=>t('Price'),
			'name'=>'book.project_price',
			'type'=>'czk',
			'htmlOptions'=>array(
				'align'=>'right',
				),
			),
		'count',
		),
	));
?>
