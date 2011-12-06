<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Stocks');
?>

<?php $this->insertDialog(); ?>

<h1><?php echo Yii::t('app', 'Stocks'); ?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_item',
)); ?>
