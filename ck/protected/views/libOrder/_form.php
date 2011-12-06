<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'lib-order-form',
)); ?>

	<p class="note"><?php echo Yii::t('app', 'Fields with <span class="required">*</span> are required.'); ?></p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'book_id'); ?>
		<?
		$this->widget('wsext.EJuiAutoCompleteFkField', array(
			'model'=>$model, 
			'attribute'=>'book_id',
			'sourceUrl'=>array('findBook'), 
			'relName'=>'book',
			'displayAttr'=>'title',
			'autoCompleteLength'=>60,
			'options'=>array(
				'minLength'=>3, 
			),
		));
		?>
		<?php $this->widget('wsext.JuiDialogCreateButton', array('model'=>'Book'));?>
		<?php echo $form->error($model,'book_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'library_id'); ?>
		<?
		$this->widget('wsext.EJuiAutoCompleteFkField', array(
			'model'=>$model, 
			'attribute'=>'library_id',
			'sourceUrl'=>array('findLibrary'), 
			'relName'=>'library',
			'displayAttr'=>'name',
			'autoCompleteLength'=>60,
			'options'=>array(
				'minLength'=>3, 
			),
		));
		?>
		<?php $this->widget('wsext.JuiDialogCreateButton', array('model'=>'Library'));?>
		<?php echo $form->error($model,'library_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'date'); ?>
		<?
		$htmlOptions = array();
		if ($model->hasErrors('date')) $htmlOptions = array('class'=>'error');
		$this->widget('zii.widgets.jui.CJuiDatePicker', array(
			'name'=>'LibOrder[date]',
			'language'=>'cs',
			'value'=>$model->date,
			'flat'=>true,
			'options'=>array(
				'showAnim'=>'fold',
				'dateFormat'=>'d.m.yy',
			),
			'htmlOptions'=>$htmlOptions,
		));
		?>
		<?php echo $form->error($model,'date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'count'); ?>
		<?php echo $form->textField($model,'count'); ?>
		<?php echo $form->error($model,'count'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'delivered'); ?>
		<?php echo CHtml::textField('delivered', $model->delivered, array('size'=>5, 'readonly'=>'readonly', 'class'=>'readonly')); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'type'); ?>
		<?php echo $form->dropDownList($model, 'type', DropDownItem::items('LibOrder.type'), array("prompt"=>"&lt;".t('types')."&gt;")); ?>
		<?php echo $form->error($model,'type'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->