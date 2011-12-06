<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Update Publisher');
?>

<?php $this->insertDialog(array(
'Organisation'=>array('success'=>"\$('#Publisher_organisation_id').val(data.model.id);\$('#organisation_id_save').val(data.model.name);\$('#organisation_id_lookup').val(data.model.name);"),
'User'=>array('success'=>"\$('#Organisation_user_id').val(data.model.id);\$('#user_id_save').val(data.model.username);\$('#user_id_lookup').val(data.model.username);"),
)); ?>

<h1><?php echo Yii::t('app', 'Update Publisher') . Yii::t('app', ' #') . $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>