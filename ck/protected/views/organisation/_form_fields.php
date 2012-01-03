	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name'); ?>
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

	<div class="row">
		<?php echo $form->labelEx($model,'region'); ?>
		<?php echo $form->dropDownList($model,'region', DropDownItem::items('Organisation.region'), array("prompt"=>"&lt;".t('regions')."&gt;")); ?>
		<?php echo $form->error($model,'region'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'company_id_number'); ?>
		<?php echo $form->textField($model,'company_id_number',array('size'=>8,'maxlength'=>8)); ?>
		<?php echo $form->error($model,'company_id_number'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'vat_id_number'); ?>
		<?php echo $form->textField($model,'vat_id_number',array('size'=>12,'maxlength'=>12)); ?>
		<?php
			/*$this->widget('CMaskedTextField',array(
				'model'=>$model,
				'attribute'=>'vat_id_number',
				'mask'=>'CZ99999999',
				'htmlOptions'=>array('size'=>12),
			));*/
		?>
		<?php echo $form->error($model,'vat_id_number'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'representative'); ?>
		<?php echo $form->textField($model,'representative',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'representative'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'telephone'); ?>
		<?php echo $form->textField($model,'telephone',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'telephone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'fax'); ?>
		<?php echo $form->textField($model,'fax',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'fax'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'www'); ?>
		<?php echo $form->textField($model,'www',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'www'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'bank_account_number'); ?>
		<?php echo $form->textField($model,'bank_account_number',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'bank_account_number'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'revenue_authority'); ?>
		<?php echo $form->textField($model,'revenue_authority',array('size'=>60,'maxlength'=>1024)); ?>
		<?php echo $form->error($model,'revenue_authority'); ?>
	</div>

	<fieldset>
		<legend><?php echo t('Worker Responsible for Project'); ?></legend>
		
		<div class="row">
			<?php echo $form->labelEx($model,'worker_name'); ?>
			<?php echo $form->textField($model,'worker_name',array('size'=>60,'maxlength'=>255)); ?>
			<?php echo $form->error($model,'worker_name'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'worker_telephone'); ?>
			<?php echo $form->textField($model,'worker_telephone',array('size'=>60,'maxlength'=>255)); ?>
			<?php echo $form->error($model,'worker_telephone'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'worker_fax'); ?>
			<?php echo $form->textField($model,'worker_fax',array('size'=>60,'maxlength'=>255)); ?>
			<?php echo $form->error($model,'worker_fax'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'worker_email'); ?>
			<?php echo $form->textField($model,'worker_email',array('size'=>60,'maxlength'=>255)); ?>
			<?php echo $form->error($model,'worker_email'); ?>
		</div>
	
	</fieldset>
