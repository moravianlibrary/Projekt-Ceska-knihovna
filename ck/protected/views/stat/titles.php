<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', $page_title);
?>

<?php $this->insertDialog(); ?>

<h1><?php echo Yii::t('app', $page_title); ?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'id'=>'books-list',
	'dataProvider'=>$dataProvider,
	'template'=>'{summary}{sorter}<table class="blackborder"><tr><th>'.t('Book').'</th><th>'.t('Author').'</th><th>'.t('Publisher').'</th><th>'.t('Count').'</th></tr>{items}</table>{pager}',
	'itemView'=>'_titles',
	'sortableAttributes'=>array(
		'title'=>t('Book'),
		'author'=>t('Author'),
		'name'=>t('Publisher'),
		'sum_count'=>t('Count'),
	),
)); ?>
