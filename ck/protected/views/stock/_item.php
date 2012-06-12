<div class="view">

	<b><?php echo CHtml::encode(t('Order nr.')); ?>:</b>
	<?php echo CHtml::encode($data->book->selected_order); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('book_title')); ?>:</b>
	<?php echo CHtml::encode($data->book_title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type_c')); ?>:</b>
	<?php echo CHtml::encode($data->type_c); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('count')); ?>:</b>
	<?php echo CHtml::encode($data->count); ?>
	<br />

</div>