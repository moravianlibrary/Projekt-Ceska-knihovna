<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Registration'). ' - '.Yii::t('app', 'Step 2');
?>

<h1><?php echo Yii::t('app', 'Registration').' - '.Yii::t('app', 'Step 2').' - '.Yii::t('app', 'Create Organisation'); ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>