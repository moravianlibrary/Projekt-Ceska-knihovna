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

	<b><?php echo t('Poll'); ?>:</b>
	<br />

	<form name='f_book_<?echo $data->id?>' id='f_book_<?echo $data->id?>'>

		<?echo t('Yea')?>: <?echo CHtml::textField('Book[votes_yes]', $data->votes_yes, array('size'=>'5')); ?> / <?echo t('Nay')?>: <?echo CHtml::textField('Book[votes_no]', $data->votes_no, array('size'=>'5')); ?> / <?echo t('Withheld')?>: <?echo CHtml::textField('Book[votes_withheld]', $data->votes_withheld, array('size'=>'5')); ?> =&gt; <?echo t('Selected')?>: <?echo CHtml::checkBox('Book[selected]', $data->selected, array('uncheckValue'=>0)); ?> <?php echo CHtml::ajaxSubmitButton(t('Save'), array('savePoll', 'id'=>$data->id), array('type'=>'POST', 'dataType'=>'json', 'beforeSend'=>'function(){$("#book_'.$data->id.'").addClass("loading");}', 'success'=>'function(data) {$("#book_'.$data->id.'").removeClass("loading"); if (data.status == "OK") {jQuery("#book_'.$data->id.'").replaceWith(data.val); if (data.msg != "") {jQuery("#flash-common-success div").html(data.msg); jQuery("#flash-common-success").dialog({show:"fade", hide:"puff", modal:true, title:"'.Yii::t('app', 'Information').'", autoOpen:true, width:400, minHeight:50, buttons:{"Ok":function(){$(this).dialog("close");}}});}} else {if (data.msg != "") {jQuery("#flash-common-error div").html(data.msg); jQuery("#flash-common-error").dialog({show:"fade", hide:"puff", modal:true, title:"'.Yii::t('app', 'Error').'", autoOpen:true, width:400, minHeight:50, buttons:{"Ok":function(){$(this).dialog("close");}}});}}}'), array('id'=>'submit_book_'.$data->id))?>	
		
	</form>
	
	<br />

	<?php
	if ($data->selected)
	{
		$label = t('Unselect');
		$post_data = 'Book[selected]=0';
	}
	else
	{
		$label = t('Select without voting');
		$post_data = 'Book[selected]=1';
	}
	echo CHtml::ajaxButton($label, array('savePollSelect', 'id'=>$data->id), array('type'=>'POST', 'dataType'=>'json', 'beforeSend'=>'function(){$("#book_'.$data->id.'").addClass("loading");}', 'data'=>$post_data, 'success'=>'function(data) {$("#book_'.$data->id.'").removeClass("loading"); if (data.status == "OK") {jQuery("#book_'.$data->id.'").replaceWith(data.val); if (data.msg != "") {jQuery("#flash-common-success div").html(data.msg); jQuery("#flash-common-success").dialog({show:"fade", hide:"puff", modal:true, title:"'.Yii::t('app', 'Information').'", autoOpen:true, width:400, minHeight:50, buttons:{"Ok":function(){$(this).dialog("close");}}});}} else {if (data.msg != "") {jQuery("#flash-common-error div").html(data.msg); jQuery("#flash-common-error").dialog({show:"fade", hide:"puff", modal:true, title:"'.Yii::t('app', 'Error').'", autoOpen:true, width:400, minHeight:50, buttons:{"Ok":function(){$(this).dialog("close");}}});}}}'), array('id'=>'select_book_'.$data->id));
	?>

</div>