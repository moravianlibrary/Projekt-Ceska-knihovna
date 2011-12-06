<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'library-form',
)); ?>

	<p class="note"><?php echo Yii::t('app', 'Fields with <span class="required">*</span> are required.'); ?></p>

	<?php echo $form->errorSummary($model); ?>

	<?if (user()->checkAccess('BackOffice')) $this->renderPartial('_admin_form', array('model'=>$model, 'form'=>$form));?>

	<?$this->renderPartial('_form_fields', array('model'=>$model, 'form'=>$form));?>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save')); ?>
	</div>
	
<?php $this->endWidget(); ?>

</div><!-- form -->