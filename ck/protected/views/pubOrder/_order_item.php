<tr>
	<td><?php echo $data->book->selected_order; ?></td>
	<td><?php echo CHtml::encode($data->book->author); ?></td>
	<td><?php echo CHtml::encode($data->book->title); ?></td>
	<td><?php echo CHtml::encode($data->count); ?></td>
	<td style="text-align: right;"><?php echo CHtml::encode(currf($data->price)); ?></td>
	<td style="text-align: right;"><?php echo CHtml::encode(currf($data->total_price)); ?></td>
</tr>
