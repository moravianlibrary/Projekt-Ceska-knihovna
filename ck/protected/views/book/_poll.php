<div class="view" id="book_<?echo $data->id?>">

	<b><?echo t('Order nr.')?>:</b>
	<span><?php echo $index+1; ?></span><br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('points')); ?>:</b>
	<span><?php echo $data->points; ?></span><br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('publisher_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->name), '#', array('onclick'=>CHtml::ajax(array('url'=>url('publisher/view',array('id'=>$data->publisher_id)), 'success'=>'function(data){$("#publisher-juidialog-content").html(data);$("#publisher-juidialog").dialog("option", "modal", false).dialog("open");return false;}')))); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->title), '#', array('onclick'=>CHtml::ajax(array('url'=>url('book/view',array('id'=>$data->id)), 'success'=>'function(data){$("#book-juidialog-content").html(data);$("#book-juidialog").dialog("option", "modal", false).dialog("open");return false;}')))); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('annotation')); ?>:</b><br />
	<span><?php echo $data->annotation; ?></span>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('council_comment')); ?>:</b><br />
	<?php echo $data->council_comment; ?>
	<br />

	<b><?php echo t('Poll'); ?>:</b>
	<br />

	<form name='f_book_<?echo $data->id?>' id='f_book_<?echo $data->id?>'>

		<?echo CHtml::hiddenField('index', $index); ?>
		<?echo t('Yea')?>: <?echo CHtml::textField('Book[votes_yes]', $data->votes_yes, array('size'=>'5')); ?> / <?echo t('Nay')?>: <?echo CHtml::textField('Book[votes_no]', $data->votes_no, array('size'=>'5')); ?> / <?echo t('Withheld')?>: <?echo CHtml::textField('Book[votes_withheld]', $data->votes_withheld, array('size'=>'5')); ?> =&gt; <?echo t('Selected')?>: <?echo CHtml::checkBox('Book[selected]', $data->selected, array('uncheckValue'=>0)); ?>
		<?echo CHtml::link(t('Save'), array('savePoll', 'id'=>$data->id), array('class'=>'button bookVotes', 'id'=>'submit_book_'.$data->id, 'rel'=>$data->id));?>
		
	</form>
	
	<br />

	<form name='f_book_select_<?echo $data->id?>' id='f_book_select_<?echo $data->id?>'>

		<?echo CHtml::hiddenField('Book[selected]', ($data->selected ? '0' : '1')); ?>
		<?echo CHtml::hiddenField('index', $index); ?>
		<?echo CHtml::link(($data->selected ? t('Unselect') : t('Select without voting')), array('savePollSelect', 'id'=>$data->id), array('class'=>'button bookVotes', 'id'=>'submit_book_select_'.$data->id, 'rel'=>$data->id));?>
		
	</form>

</div>