<tr>
	<td><?php echo CHtml::encode($data->book->name); ?></td>
	<td><?php echo CHtml::encode($data->book->author); ?></td>
	<td><?php echo CHtml::encode($data->book->title); ?></td>
	<td><?php echo CHtml::encode($data->book->issue_year); ?></td>
	<td><?php echo CHtml::encode(currf($data->book->project_price)); ?></td>
	<td><?php echo CHtml::encode($data->count); ?></td>
</tr>
