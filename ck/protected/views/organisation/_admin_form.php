<div class="admin_form">

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
		<?php echo $form->labelEx($model,'salutation'); ?>
		<?php echo $form->textField($model,'salutation',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'salutation'); ?>
	</div>

</div>
