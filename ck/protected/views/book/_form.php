<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'book-form',
)); ?>

	<p class="note"><?php echo Yii::t('app', 'Fields with <span class="required">*</span> are required.'); ?></p>

	<?php echo $form->errorSummary($model); ?>

	<?if (user()->checkAccess('BackOffice')) $this->renderPartial('_admin_form', array('model'=>$model, 'form'=>$form));?>

	<div class="row">
		<?php echo $form->labelEx($model,'author'); ?>
		<?php echo $form->textField($model,'author',array('size'=>60,'maxlength'=>255)); ?><br />Pozn: zadávejte ve tvaru Přijmení, Jméno (např. Novák, Jan) a jednotlivé autory oddělujte středníkem (např. Novák, Jan; Nováková, Jana).
		<?php echo $form->error($model,'author'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'isbnPart'); ?>
		<?php echo param('isbnPrefix').$form->textField($model,'isbnPart',array('size'=>10,'maxlength'=>26)); ?>
		<br /><span class="red">Pozn: uvádějte u všech publikací, u kterých je již ISBN známo.</span>
		<?php echo $form->error($model,'isbnPart'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'editor'); ?>
		<?php echo $form->textField($model,'editor',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'editor'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'redactors'); ?>
		<?php echo $form->textField($model,'redactors',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'redactors'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'reviewer'); ?>
		<?php echo $form->textField($model,'reviewer',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'reviewer'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'illustrator'); ?>
		<?php echo $form->textField($model,'illustrator',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'illustrator'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'preamble'); ?>
		<?php echo $form->textField($model,'preamble',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'preamble'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'epilogue'); ?>
		<?php echo $form->textField($model,'epilogue',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'epilogue'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'issue_year'); ?>
		<?
		$issueYears =array((param('projectYear')-1)=>(param('projectYear')-1), param('projectYear')=>param('projectYear'));
		if (!in_array($model->issue_year, $issueYears)) $issueYears[$model->issue_year] = $model->issue_year;
		echo $form->dropDownList($model, 'issue_year', $issueYears);
		?>
		<?php echo $form->error($model,'issue_year'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'available'); ?>
		<?php echo $form->textField($model,'available'); ?>
		<?php echo $form->error($model,'available'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'pages_printed'); ?>
		<?php echo $form->textField($model,'pages_printed'); ?> <?echo t('pages')?>
		<?php echo $form->error($model,'pages_printed'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'pages_other'); ?>
		<?php echo $form->textField($model,'pages_other'); ?> <?echo t('pages')?>
		<?php echo $form->error($model,'pages_other'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'format_width'); ?>
		<?php echo $form->textField($model,'format_width',array('size'=>3,'maxlength'=>3)); ?> mm
		<?php echo $form->error($model,'format_width'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'format_height'); ?>
		<?php echo $form->textField($model,'format_height',array('size'=>3,'maxlength'=>3)); ?> mm
		<?php echo $form->error($model,'format_height'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'binding'); ?>
		<?echo CHtml::dropDownList('select_binding', $model->binding, DropDownItem::items('Book.binding'), array('onchange'=>'document.getElementById(\'Book_binding\').value = this.value'));?>
		<?php echo $form->textField($model,'binding',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'binding'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'retail_price'); ?>
		<?php echo $form->textField($model,'retail_price'); ?> <?echo t('CZK')?>
		<?php echo $form->error($model,'retail_price'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'offer_price'); ?>
		<?php echo $form->textField($model,'offer_price'); ?> <?echo t('CZK')?>
		<?php echo $form->error($model,'offer_price'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'annotation'); ?>
		<?php echo $form->textArea($model,'annotation',array('rows'=>20, 'cols'=>100)); ?>
		<?php echo $form->error($model,'annotation'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'comment'); ?>
		<?php echo $form->textArea($model,'comment',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'comment'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?
cs()->registerScriptFile(req()->baseUrl.'/scripts/jquery.counter-2.1.min.js');
cs()->registerScript("annotation-counter", '
	$("#Book_annotation").counter({
		goal: 2000,
		msg: "'.t('characters remaining.').'"
	});
	',
	CClientScript::POS_READY);
?>
