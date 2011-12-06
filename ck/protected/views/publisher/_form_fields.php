	<div class="row">
		<?php echo $form->labelEx($model,'code'); ?>
		<?php echo $form->textField($model,'code',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'code'); ?>
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
