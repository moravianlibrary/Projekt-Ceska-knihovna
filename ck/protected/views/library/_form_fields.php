	<fieldset>
		<legend>Vyplňují pouze knihovny, které nemají právní subjektivitu</legend>
		<div class="row">
			<?php echo $form->labelEx($model,'libname'); ?>
			<?php echo $form->textField($model,'libname',array('size'=>60,'maxlength'=>255)); ?>
			<?php echo $form->error($model,'libname'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'street'); ?>
			<?php echo $form->textField($model,'street',array('size'=>60,'maxlength'=>255)); ?>
			<?php echo $form->error($model,'street'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'land_registry_number'); ?>
			<?php echo $form->textField($model,'land_registry_number'); ?>
			<?php echo $form->error($model,'land_registry_number'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'house_number'); ?>
			<?php echo $form->textField($model,'house_number'); ?>
			<?php echo $form->error($model,'house_number'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'postal_code'); ?>
			<?php echo $form->textField($model,'postal_code',array('size'=>5,'maxlength'=>5)); ?>
			<?php echo $form->error($model,'postal_code'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'city'); ?>
			<?php echo $form->textField($model,'city',array('size'=>60,'maxlength'=>255)); ?>
			<?php echo $form->error($model,'city'); ?>
		</div>
	</fieldset>

	<div class="row">
		<?php echo $form->labelEx($model,'number'); ?>
		<?php echo $form->textField($model,'number',array('size'=>32,'maxlength'=>32)); ?>
		<?php echo $form->error($model,'number'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'type'); ?>
		<?echo CHtml::dropDownList('select_type', $model->type, DropDownItem::items('Library.type'), array('onchange'=>'document.getElementById(\'Library_type\').value = this.value'));?>
		<?php echo $form->textField($model,'type',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'headcount'); ?>
		<?php echo $form->textField($model,'headcount'); ?>
		<?php echo $form->error($model,'headcount'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'units_total'); ?>
		<?php echo $form->textField($model,'units_total'); ?> <?echo t('pcs')?>
		<?php echo $form->error($model,'units_total'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'units_new'); ?>
		<?php echo $form->textField($model,'units_new'); ?> <?echo t('pcs')?>
		<?php echo $form->error($model,'units_new'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'budget'); ?>
		<?php echo $form->textField($model,'budget'); ?> <?echo t('CZK')?>
		<?php echo $form->error($model,'budget'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'budget_czech'); ?>
		<?php echo $form->textField($model,'budget_czech'); ?> <?echo t('CZK')?>
		<?php echo $form->error($model,'budget_czech'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'private_data'); ?>
		<?php echo $form->checkBox($model,'private_data'); ?>
		<?php echo $form->error($model,'private_data'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'confirmation'); ?>
		<?php echo $form->checkBox($model,'confirmation'); ?>
		<?php echo $form->error($model,'confirmation'); ?>
	</div>
