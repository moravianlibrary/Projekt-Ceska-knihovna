<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('code')); ?>:</b>
	<?php echo CHtml::encode($data->code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('private_data')); ?>:</b>
	<?php echo CHtml::encode(app()->format->formatBoolean($data->private_data)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('confirmation')); ?>:</b>
	<?php echo CHtml::encode(app()->format->formatBoolean($data->confirmation)); ?>
	<br />

</div>