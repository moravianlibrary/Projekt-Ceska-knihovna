<tr <?if ($data->id == $book_id) echo "style=\"background-color: #dddddd;\""?>>
	<td><?php echo CHtml::link(CHtml::encode($data->title), '#', array('onclick'=>CHtml::ajax(array('url'=>url('book/view',array('id'=>$data->id)), 'success'=>'function(data){$("#book-juidialog-content").html(data);$("#book-juidialog").dialog("option", "modal", false).dialog("open");return false;}')))); ?></td>
	<td><?php echo CHtml::encode($data->author); ?></td>
	<td><?php echo CHtml::encode($data->publisher->name); ?></td>
	<td><?php echo CHtml::encode($data->project_year); ?></td>
</tr>
