<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Create Stock Activity');
?>

<?php $this->insertDialog(); ?>

<h1><?php echo Yii::t('app', 'Create Stock Activity'); ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>