<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Registration'). ' - '.Yii::t('app', 'Step 1');
?>

<h1><?php echo Yii::t('app', 'Registration').' - '.Yii::t('app', 'Step 1').' - '.Yii::t('app', 'Create User'); ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>