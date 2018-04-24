<div class="view" id="book_<?echo $data->id?>">
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('Pořadí')); ?>:</b>
	<?php echo $index+1; ?>
	<br />
	
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('publisher_id')); ?>:</b>
	<?php echo $data->name; ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('project_year')); ?>:</b>
	<?php echo CHtml::encode($data->project_year); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('isbn')); ?>:</b>
	<?php echo CHtml::encode($data->isbn); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('author')); ?>:</b>
	<?php echo CHtml::encode($data->author); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('editor')); ?>:</b>
	<?php echo CHtml::encode($data->editor); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('redactors')); ?>:</b>
	<?php echo CHtml::encode($data->redactors); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('reviewer')); ?>:</b>
	<?php echo CHtml::encode($data->reviewer); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('illustrator')); ?>:</b>
	<?php echo CHtml::encode($data->illustrator); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('preamble')); ?>:</b>
	<?php echo CHtml::encode($data->preamble); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('epilogue')); ?>:</b>
	<?php echo CHtml::encode($data->epilogue); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('issue_year')); ?>:</b>
	<?php echo CHtml::encode($data->issue_year); ?>
	<br />

        <b><?php echo CHtml::encode($data->getAttributeLabel('issue_order')); ?>:</b>
        <?php echo CHtml::encode($data->issue_order); ?>
        <br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('available')); ?>:</b>
	<?php echo CHtml::encode($data->available); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pages_printed')); ?>:</b>
	<?php echo CHtml::encode($data->pages_printed); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pages_other')); ?>:</b>
	<?php echo CHtml::encode($data->pages_other); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('format_width')); ?>:</b>
	<?php echo CHtml::encode($data->format_width); ?> mm
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('format_height')); ?>:</b>
	<?php echo CHtml::encode($data->format_height); ?> mm
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('binding')); ?>:</b>
	<?php echo CHtml::encode($data->binding); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('retail_price')); ?>:</b>
	<?php echo CHtml::encode(currf($data->retail_price)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('offer_price')); ?>:</b>
	<?php echo CHtml::encode(currf($data->offer_price)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('project_price')); ?>:</b>
	<?php echo CHtml::encode(currf($data->project_price)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('annotation')); ?>:</b>
	<?php echo CHtml::encode($data->annotation); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('comment')); ?>:</b>
	<?php echo CHtml::encode($data->comment); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('council_comment')); ?>:</b>
	<?php echo CHtml::encode($data->council_comment); ?>
	<br />

	<b><?php echo t('Points'); ?>:</b>
	<?php echo $data->points; ?>
	<br />

</div>
<br />
