<tr>
	<td><?php echo CHtml::encode($data['title']); ?></td>
	<td><?php echo CHtml::encode($data['author']); ?></td>
	<td><?php echo CHtml::encode($data['name']); ?></td>
	<td><?php echo CHtml::encode(!$data['sum_count'] ? '0' : $data['sum_count']); ?></td>
</tr>
