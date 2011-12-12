<?
$titles = array();
foreach ($data->book_titles as $k=>$v)
	$titles[] = $v->title;
?>

<tr>
	<td><?php echo CHtml::encode($data->title); ?></td>
	<td><?php echo CHtml::encode($data->author); ?></td>
	<td><?php echo CHtml::encode($data->name); ?></td>
	<td><?php echo CHtml::encode(implode(', ', $titles)); ?></td>
</tr>
