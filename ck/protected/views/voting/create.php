<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Create Voting');
?>

<?php $this->insertDialog(); ?>

<h1><?php echo Yii::t('app', 'Create Voting'); ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>