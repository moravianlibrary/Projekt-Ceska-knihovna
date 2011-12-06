<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Create User');
?>

<?php $this->insertDialog(); ?>

<h1><?php echo Yii::t('app', 'Create User'); ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>