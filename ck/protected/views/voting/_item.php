<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('username')); ?>:</b>
	<?php echo CHtml::mailto($data->username); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('vote')); ?>:</b>
	<?php echo CHtml::encode($data->vote); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type_c')); ?>:</b>
	<?php echo CHtml::encode($data->type_c); ?>
	<br />

</div>