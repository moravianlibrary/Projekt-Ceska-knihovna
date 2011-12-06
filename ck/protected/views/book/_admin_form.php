<?$this->widget('wsext.JuiDialogForm', array('model'=>'checkBook'));?>

<div class="admin_form">
	<?php echo CHtml::ajaxButton(t('Check Book'), url('book/checkHistory'), array(
		'data'=>'js:"Book[author]="+jQuery("#Book_author").val()+"&book_id='.$model->id.'"',
		'success'=>'js:function(data){$("#check-book-juidialog-content").html(data);$("#check-book-juidialog").dialog("option", "modal", false).dialog("open");return false;}'
	)); ?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'publisher_id'); ?>
		<?
		$this->widget('wsext.EJuiAutoCompleteFkField', array(
			'model'=>$model, 
			'attribute'=>'publisher_id',
			'sourceUrl'=>array('findPublisher'), 
			'relName'=>'publisher',
			'displayAttr'=>'name',
			'autoCompleteLength'=>60,
			'options'=>array(
				'minLength'=>3, 
			),
		));
		?>
		<?php $this->widget('wsext.JuiDialogCreateButton', array('model'=>'Publisher'));?>
		<?php echo $form->error($model,'publisher_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?echo $form->dropDownList($model, 'status', DropDownItem::items('Book.status'), array('onchange'=>'if ($(this).val != \'\') $(\'#Book_offered\').attr(\'checked\', true);'));?>
		<?php echo $form->error($model,'status'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'project_price'); ?>
		<?php echo $form->textField($model,'project_price'); ?> <?echo t('CZK')?>
		<?php echo $form->error($model,'project_price'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'council_comment'); ?>
		<?php echo $form->textArea($model,'council_comment',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'council_comment'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'votes_yes'); ?>
		<?php echo $form->textField($model,'votes_yes',array('size'=>5,'maxlength'=>5)); ?>
		<?php echo $form->error($model,'votes_yes'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'votes_no'); ?>
		<?php echo $form->textField($model,'votes_no',array('size'=>5,'maxlength'=>5)); ?>
		<?php echo $form->error($model,'votes_no'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'votes_withheld'); ?>
		<?php echo $form->textField($model,'votes_withheld',array('size'=>5,'maxlength'=>5)); ?>
		<?php echo $form->error($model,'votes_withheld'); ?>
	</div>
</div>
