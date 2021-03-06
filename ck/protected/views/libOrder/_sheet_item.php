<div class="view" id="book_<?echo $data->id?>">

	<b><?php echo CHtml::encode($data->getAttributeLabel('selected_order')); ?>:</b>
	<span><?php echo CHtml::encode($data->selected_order); ?></span>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('publisher_id')); ?>:</b>
	<span><?php echo CHtml::encode($data->name); ?></span>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<span><?php echo CHtml::encode($data->title); ?></span>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('author')); ?>:</b>
	<span><?php echo CHtml::encode($data->author); ?></span>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('issue_year')); ?>:</b>
	<span><?php echo CHtml::encode($data->issue_year); ?></span>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('isbn')); ?>:</b>
	<?php echo CHtml::encode($data->isbn); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('project_price')); ?>:</b>
	<span><?php echo currf(CHtml::encode($data->project_price)); ?></span>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('annotation')); ?>:</b><br />
	<span><?php echo CHtml::encode($data->annotation); ?></span>
	<br />
	<br />

	<b><?php echo t('Order'); ?>:</b>
	<table>
		<tr>
			<td style="text-align:right"><?echo t('Basic Order Count')?>: </td>
			<td>
				<?
				if (user()->library_order_placed)
				{
					echo ($data->basic ? $data->basic->count : '0').' '.t('pcs');
				}
				else
				{
					?>
					<form name='f_book_basic_<?echo $data->id?>'>
					<?echo CHtml::hiddenField('LibOrder[book_id]', $data->id)?>
					<?echo CHtml::hiddenField('LibOrder[type]', 'B')?>
					<?php echo isset($data->basic) ? CHtml::encode($data->basic->count) : 0; ?>
					<?echo CHtml::textField('LibOrder[count]', '', array('size'=>'5')); ?> <?echo t('pcs')?>
					<?echo CHtml::link((isset($data->basic) && $data->basic->count ? t('Change Order') : t('Add To Order')), array('saveSheet', 'id'=> isset($data->basic) ? $data->basic->id : null), array('class'=>'button libOrder', 'id'=>'submit_book_basic_'.$data->id, 'rel'=>$data->id));?>
					</form>
					<?
				}
				?>
			</td>
		</tr>
		<tr>
			<td style="text-align:right"><?echo t('Reserve Count')?>: </td>
			<td>
				<?
				if (user()->library_order_placed)
				{
					echo ($data->reserve ? $data->reserve->count : '0').' '.t('pcs');
				}
				else
				{
					?>
					<form name='f_book_reserve_<?echo $data->id?>'>
					<?echo CHtml::hiddenField('LibOrder[book_id]', $data->id)?>
					<?echo CHtml::hiddenField('LibOrder[type]', 'R')?>
					<?php echo isset($data->reserve) ? CHtml::encode($data->reserve->count) : 0; ?> <?echo CHtml::textField('LibOrder[count]', '', array('size'=>'5')); ?> <?echo t('pcs')?>
					<?echo CHtml::link((isset($data->reserve) && $data->reserve->count ? t('Change Order') : t('Add To Order')), array('saveSheet', 'id'=> isset($data->reserve) ? $data->reserve->id : null), array('class'=>'button libOrder', 'id'=>'submit_book_reserve_'.$data->id, 'rel'=>$data->id));?>
					</form>
					<?
				}
				?>
			</td>
		</tr>
	</table>

</div>
