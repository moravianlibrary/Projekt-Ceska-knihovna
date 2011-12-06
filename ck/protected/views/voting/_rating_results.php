<div class="view" id="book_<?echo $data->id?>">

	<b><?php echo CHtml::encode($data->getAttributeLabel('publisher_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->name), '#', array('onclick'=>CHtml::ajax(array('url'=>url('publisher/view',array('id'=>$data->publisher_id)), 'success'=>'function(data){$("#publisher-juidialog-content").html(data);$("#publisher-juidialog").dialog("option", "modal", false).dialog("open");return false;}')))); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->title), '#', array('onclick'=>CHtml::ajax(array('url'=>url('book/view',array('id'=>$data->id)), 'success'=>'function(data){$("#book-juidialog-content").html(data);$("#book-juidialog").dialog("option", "modal", false).dialog("open");return false;}')))); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('annotation')); ?>:</b><br />
	<?php echo $data->annotation; ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('council_comment')); ?>:</b><br />
	<?php echo $data->council_comment; ?>
	<br />

	<b><?php echo t('Points'); ?>:</b>
	<?php echo $data->points; ?>
	<br />

</div>