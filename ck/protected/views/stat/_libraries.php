<tr>
	<td><?php echo CHtml::encode($data->name); ?></td>
	<td><?php echo CHtml::encode(DropDownItem::item('Organisation.region', $data->organisation->region)); ?></td>
	<td><?php echo CHtml::encode($data->organisation->city); ?></td>
	<td><?php echo CHtml::encode($data->type); ?></td>
</tr>
