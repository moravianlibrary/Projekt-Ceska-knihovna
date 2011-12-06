<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Users');
?>

<?php $this->insertDialog(); ?>

<h1><?php echo Yii::t('app', 'Users'); ?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_item',
)); ?>
