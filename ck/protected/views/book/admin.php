<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Manage Books');
?>

<?php $this->insertDialog(); ?>

<h1><?php
echo Yii::t('app', 'Manage Books');
if ($publisher != null) echo t(' of publisher ').$publisher->name;
?></h1>

<?
if (user()->publisher_offer_id)
{
	echo "<div class=\"flash-notice\">";
	echo t('Žádost je již uzavřena, již ji není možné měnit. ');
	if ($publisher->request_date != '')
		echo t('Písemná žádost byla doručena na MZK dne ').DT::toLoc($publisher->request_date).'.';
	else
		echo t('Písemná žádost dosud nebyla doručena na MZK.');
	echo "</div>";
}

$statusFilter = array('-1'=>'nekontrolováno') + DropDownItem::items('Book.status');
unset($statusFilter['']);

if (!user()->publisher_id)
	$columns = array(
		array(
			'header'=>Yii::t('app', 'Actions'),
			'class'=>'ButtonColumn',
			'buttons'=>array(
				'update'=>array(
					'visible'=>'$data->canUpdate',
				),
				'delete'=>array(
					'visible'=>'$data->canDelete',
				),
			),
		),
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
			'header'=>t('Request'),
			'name'=>'offered',
			'type'=>'boolean',
			'filter'=> DropDownItem::items('YesNo'),
		),
		array(
			'name'=>'status',
			'value'=>'$data->status_c',
			'type'=>'raw',
			'filter'=> $statusFilter,
		),
		array(
			'name'=>'manuscript',
			'value'=>'($data->manuscript != null) ? "ano" : "ne"',
			'type'=>'raw',
			'filter'=>false,
		)
	);
else
	$columns = array(
		array(
			'header'=>Yii::t('app', 'Actions'),
			'class'=>'ButtonColumn',
			'buttons'=>array(
				'update'=>array(
					'visible'=>'$data->canUpdate',
				),
				'delete'=>array(
					'visible'=>'$data->canDelete',
				),
			),
		),
		array(
			'name'=>'isbn',
			'filter'=>false,
		),
		array(
			'name'=>'author',
			'filter'=>false,
		),
		array(
			'name'=>'title',
			'filter'=>false,
		),
		array(
			'name'=>'issue_year',
			'filter'=>false,
		),
		array(
			'name'=>'status',
			'value'=>'$data->status_c',
			'type'=>'raw',
			'filter'=> $statusFilter,
		),
	);
?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'book-grid',
	'formatter'=>new Formatter,
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'rowCssClassExpression'=>'($data->status === null ? "yellowbg" : ($data->status === "0" ? "greenbg" : "redbg"))',
	'columns'=>$columns,
)); ?>
