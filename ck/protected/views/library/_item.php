<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('internal_number')); ?>:</b>
	<?php echo CHtml::encode($data->internal_number); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('number')); ?>:</b>
	<?php echo CHtml::encode($data->number); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type')); ?>:</b>
	<?php echo CHtml::encode($data->type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('headcount')); ?>:</b>
	<?php echo CHtml::encode($data->headcount); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('units_total')); ?>:</b>
	<?php echo CHtml::encode($data->units_total); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('units_new')); ?>:</b>
	<?php echo CHtml::encode($data->units_new); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('budget')); ?>:</b>
	<?php echo CHtml::encode(currf($data->budget)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('budget_czech')); ?>:</b>
	<?php echo CHtml::encode(currf($data->budget_czech)); ?>
	<br />

</div>