<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Create Organisation');
?>

<?php $this->insertDialog(); ?>

<h1><?php echo Yii::t('app', 'Create Organisation'); ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>