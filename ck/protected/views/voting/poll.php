<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Voting');
?>

<?php $this->insertDialog(); ?>

<h1><?php echo Yii::t('app', 'Voting'); ?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'id'=>'poll-list',
	'dataProvider'=>$dataProvider,
	'itemView'=>'_poll',
	'sortableAttributes'=>array(
        'title',
        'name',
    ),
)); ?>
