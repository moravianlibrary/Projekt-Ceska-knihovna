<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('book_title')); ?>:</b>
	<?php echo CHtml::encode($data->book_title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('publisher_name')); ?>:</b>
	<?php echo CHtml::encode($data->publisher_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date')); ?>:</b>
	<?php echo CHtml::encode($data->date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('count')); ?>:</b>
	<?php echo CHtml::encode($data->count); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('delivered')); ?>:</b>
	<?php echo CHtml::encode($data->delivered); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('price')); ?>:</b>
	<?php echo currf(CHtml::encode($data->price)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type_c')); ?>:</b>
	<?php echo CHtml::encode($data->type_c); ?>
	<br />

</div>