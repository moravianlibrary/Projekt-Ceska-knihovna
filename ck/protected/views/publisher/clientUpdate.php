<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Update Publisher');
?>

<h1><?php echo Yii::t('app', 'Update Publisher') . Yii::t('app', ' #') . $publisher->id; ?></h1>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'publisher-form',
)); ?>

	<p class="note"><?php echo Yii::t('app', 'Fields with <span class="required">*</span> are required.'); ?></p>

	<?php echo $form->errorSummary(array($organisation, $publisher)); ?>

	<?php echo $this->renderPartial('/organisation/_form_fields', array('model'=>$organisation, 'form'=>$form)); ?>

	<?php echo $this->renderPartial('_form_fields', array('model'=>$publisher, 'form'=>$form)); ?>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('app', 'Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->	