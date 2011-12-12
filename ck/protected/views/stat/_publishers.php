<tr>
	<td><?php echo CHtml::encode($data['name']); ?></td>
	<td><?php echo CHtml::encode($data['sum_dod_tituly']); ?></td>
	<td><?php echo CHtml::encode($data['sum_nedod_tituly']); ?></td>
	<td><?php echo CHtml::encode($data['sum_dod_svazky']); ?></td>
	<td><?php echo CHtml::encode($data['sum_nedod_svazky']); ?></td>
	<td style='text-align:right'><?php echo currf(CHtml::encode($data['sum_cena'])); ?></td>
</tr>
