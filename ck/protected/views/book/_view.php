<?php
$attributes = array(
	array(
		'name'=>'name',
		'type'=>'raw',
		'value'=>CHtml::link(CHtml::encode($model->name), '#', array('onclick'=>CHtml::ajax(array('url'=>url('publisher/view',array('id'=>$model->publisher_id)), 'success'=>'function(data){$("#publisher-juidialog-content").html(data);$("#publisher-juidialog").dialog("option", "modal", false).dialog("open");return false;}')))),
	),
	'project_year',
	'author',
	'title',
	'editor',
	'redactors',
	'reviewer',
	'illustrator',
	'preamble',
	'epilogue',
	'issue_year',
	'issue_order',
	'available',
	'pages_printed',
	'pages_other',
	'format_width',
	'format_height',
	'binding',
	'retail_price:czk',
	'offer_price:czk',
	'project_price:czk',
	'isbn',
	'annotation',
	'comment',
);

if (user()->checkAccess('BackOffice') || user()->checkAccess('PublisherRole'))
{
	$attributes[] = array(
		'name'=>Yii::t('app', 'manuscript'),
		'type'=>'raw',
		'value'=>CHtml::link($model->manuscript, array('book/download', 'id' => $model->id)),
	);
}

$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'formatter'=>new Formatter,
	'attributes'=>$attributes,
));
?>
