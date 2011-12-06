<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'pub-order-form',
)); ?>

	<p class="note"><?php echo Yii::t('app', 'Fields with <span class="required">*</span> are required.'); ?></p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'book_id'); ?>
		<?
		$this->widget('wsext.EJuiAutoCompleteFkField', array(
			'model'=>$model, 
			'attribute'=>'book_id',
			'selectCallBack'=>"js:function(event, ui){\$('#PubOrder_book_id').val(ui.item.id);\$('#book_id_save').val(ui.item.value); \$('#PubOrder_price').val(ui.item.project_price);}",
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
		<?php echo $form->labelEx($model,'date'); ?>
		<?
		$htmlOptions = array();
		if ($model->hasErrors('date')) $htmlOptions = array('class'=>'error');
		$this->widget('zii.widgets.jui.CJuiDatePicker', array(
			'name'=>'PubOrder[date]',
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
		<?php echo $form->textField($model,'count',array('size'=>5,'maxlength'=>5)); ?> <?echo t('pcs')?>
		<?php echo $form->error($model,'count'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'delivered'); ?>
		<?php echo CHtml::textField('delivered', $model->delivered, array('size'=>5, 'readonly'=>'readonly', 'class'=>'readonly')); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'price'); ?>
		<?php echo $form->textField($model,'price',array('size'=>10,'maxlength'=>10)); ?> <?echo t('CZK')?>
		<?php echo $form->error($model,'price'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'type'); ?>
		<?php echo $form->dropDownList($model, 'type', DropDownItem::items('PubOrder.type'), array("prompt"=>"&lt;".t('types')."&gt;")); ?>
		<?php echo $form->error($model,'type'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->