<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'stock-form',
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
		<?php echo $form->error($model,'book_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'type'); ?>
		<?php echo $form->dropDownList($model, 'type', DropDownItem::items('LibOrder.type'), array("prompt"=>"&lt;".t('types')."&gt;")); ?>
		<?php echo $form->error($model,'type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'count'); ?>
		<?php echo CHtml::textField('count', $model->count, array('size'=>5, 'readonly'=>'readonly', 'class'=>'readonly')); ?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->