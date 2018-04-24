<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Manage Votings');
?>

<?php $this->insertDialog(); ?>

<h1><?php echo Yii::t('app', 'Manage Votings'); ?></h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'voting-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'header'=>Yii::t('app', 'Actions'),
			'class'=>'ButtonColumn',
		),
		'username:email',
		array(
			'name'=>'name',
			'value'=>'CHtml::link(CHtml::encode($data->name), \'#\', array(\'onclick\'=>CHtml::ajax(array(\'url\'=>url(\'publisher/view\',array(\'id\'=>$data->book->publisher_id)), \'success\'=>\'function(data){$("#publisher-juidialog-content").html(data);$("#publisher-juidialog").dialog("option", "modal", false).dialog("open");return false;}\'))));',
			'type'=>'raw',
		),
		array(
			'name'=>'title',
			'value'=>'CHtml::link(CHtml::encode($data->title), \'#\', array(\'onclick\'=>CHtml::ajax(array(\'url\'=>url(\'book/view\',array(\'id\'=>$data->book_id)), \'success\'=>\'function(data){$("#book-juidialog-content").html(data);$("#book-juidialog").dialog("option", "modal", false).dialog("open");return false;}\'))));',
			'type'=>'raw',
		),
		array(
			'name'=>'vote',
			'filter'=>false,
		),
		array(
			'name'=>'type',
			'value'=> '$data->type_c',
			'type'=>'raw',
			'filter'=>DropDownItem::items('Voting.type'),
		),
	),
)); ?>
