<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'voting-form',
)); ?>

	<p class="note"><?php echo Yii::t('app', 'Fields with <span class="required">*</span> are required.'); ?></p>

	<?php echo $form->errorSummary($model); ?>

	<?echo CHtml::hiddenField('Voting[points]', $model->points)?>

	<div class="row">
		<?php echo $form->labelEx($model,'user_id'); ?>
		<?
		$this->widget('wsext.EJuiAutoCompleteFkField', array(
			'model'=>$model, 
			'attribute'=>'user_id',
			'sourceUrl'=>array('findUser'), 
			'relName'=>'user',
			'displayAttr'=>'username',
			'autoCompleteLength'=>60,
			'options'=>array(
				'minLength'=>3, 
			),
		));
		?>
		<?php $this->widget('wsext.JuiDialogCreateButton', array('model'=>'User'));?>
		<?php echo $form->error($model,'user_id'); ?>
	</div>

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
		<?php echo $form->dropDownList($model, 'type', DropDownItem::items('Voting.type'), array("prompt"=>"&lt;".t('types')."&gt;", "onchange"=>"if (this.value == '".Voting::RATING."') {jQuery('#row_poll').hide(); jQuery('#row_rating').show();} if (this.value == '".Voting::POLL."') {jQuery('#row_rating').hide(); jQuery('#row_poll').show();} ")); ?>
		<?php echo $form->error($model,'type'); ?>
	</div>

	<div class="row" id="row_rating" <?if ($model->type != Voting::RATING) echo "style='display: none;'"?>>
		<?php echo $form->labelEx($model,'rating',array('label'=>t('Rating'))); ?>		
		<?echo CHtml::dropDownList('points', $model->points, Voting::$ratingOptions, array('prompt'=>'', 'onchange'=>"jQuery('#Voting_points').val(this.value);"));?>
		<?php echo $form->error($model,'points'); ?>
	</div>

	<div class="row" id="row_poll" <?if ($model->type != Voting::POLL) echo "style='display: none;'"?>>
		<?php echo $form->labelEx($model,'points',array('label'=>t('Poll'))); ?>
		<?echo CHtml::radioButtonList('poll', $model->points, array(''=>t('Not voted'), '1'=>t('Yea'), '-1'=>t('Nay'), '0'=>t('Withholding')), array('onchange'=>"jQuery('#Voting_points').val(this.value);", 'separator'=>'&nbsp;|&nbsp;', 'template'=>'{label} {input}', 'labelOptions'=>array('class'=>'inline')))?>
		<?php echo $form->error($model,'points'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->