<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Title Changes');
?>

<?php $this->insertDialog(); ?>

<h1><?php echo Yii::t('app', 'Title Changes'); ?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'id'=>'books-list',
	'dataProvider'=>$dataProvider,
	'template'=>'{summary}{sorter}<table class="blackborder"><tr><th>'.t('Book').'</th><th>'.t('Author').'</th><th>'.t('Publisher').'</th><th>'.t('Original Title').'</th></tr>{items}</table>{pager}',
	'itemView'=>'_titleChanges',
	'sortableAttributes'=>array(
		'title'=>t('Book'),
		'author'=>t('Author'),
		'name'=>t('Publisher'),
	),
)); ?>
