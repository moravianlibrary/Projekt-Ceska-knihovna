<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Publishers');
?>

<?php $this->insertDialog(); ?>

<h1><?php echo Yii::t('app', 'Publishers'); ?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_item',
)); ?>
