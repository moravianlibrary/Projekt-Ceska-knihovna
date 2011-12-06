<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Update Organisation');
?>

<?php $this->insertDialog(); ?>

<h1><?php echo Yii::t('app', 'Update Organisation') . Yii::t('app', ' #') . $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>