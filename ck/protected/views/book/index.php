<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Books');
?>

<?php $this->insertDialog(); ?>

<h1><?php echo Yii::t('app', 'Books'); ?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_item',
	'separator'=>$separator,
)); ?>
