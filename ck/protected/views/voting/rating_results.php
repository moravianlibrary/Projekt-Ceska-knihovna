<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Rating Results');
?>

<?php $this->insertDialog(); ?>

<h1><?php echo Yii::t('app', 'Rating Results'); ?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'id'=>'rating-results-list',
	'dataProvider'=>$dataProvider,
	'itemView'=>'_rating_results',
)); ?>
