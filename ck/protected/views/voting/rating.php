<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Rating');
?>

<?php $this->insertDialog(); ?>

<h1><?php echo Yii::t('app', 'Rating'); ?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'id'=>'rating-list',
	'dataProvider'=>$dataProvider,
	'itemView'=>'_rating',
	'sortableAttributes'=>array(
        'title',
        'name',
    ),
)); ?>
