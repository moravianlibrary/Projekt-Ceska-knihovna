<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Update Stock Activity');
?>

<?php $this->insertDialog(); ?>

<h1><?php echo Yii::t('app', 'Update Stock Activity') . Yii::t('app', ' #') . $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>