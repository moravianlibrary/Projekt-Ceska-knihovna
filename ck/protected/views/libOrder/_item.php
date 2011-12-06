<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('book_title')); ?>:</b>
	<?php echo CHtml::encode($data->book_title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('library_name')); ?>:</b>
	<?php echo CHtml::encode($data->library_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('count')); ?>:</b>
	<?php echo CHtml::encode($data->count); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('delivered')); ?>:</b>
	<?php echo CHtml::encode($data->delivered); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type_c')); ?>:</b>
	<?php echo CHtml::encode($data->type_c); ?>
	<br />

</div>