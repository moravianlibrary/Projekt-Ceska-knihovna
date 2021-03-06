<div class="view" id="book_<?echo $data->id?>">

	<form name='f_book_<?echo $data->id?>'>
	
	<?echo CHtml::hiddenField('Voting[type]', Voting::POLL)?>
	
	<b>Poř. č.:</b>
	<?php echo $index+1; ?>

	<b><?php echo CHtml::encode($data->getAttributeLabel('publisher_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->name), '#', array('onclick'=>CHtml::ajax(array('url'=>url('publisher/view',array('id'=>$data->publisher_id)), 'success'=>'function(data){$("#publisher-juidialog-content").html(data);$("#publisher-juidialog").dialog("option", "modal", false).dialog("open");return false;}')))); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->title), '#', array('onclick'=>CHtml::ajax(array('url'=>url('book/view',array('id'=>$data->id)), 'success'=>'function(data){$("#book-juidialog-content").html(data);$("#book-juidialog").dialog("option", "modal", false).dialog("open");return false;}')))); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('points')); ?>:</b>
	<?php echo $data->points; ?>

	<b><?php echo CHtml::encode($data->getAttributeLabel('annotation')); ?>:</b><br />
	<?php echo $data->annotation; ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('council_comment')); ?>:</b><br />
	<?php echo $data->council_comment; ?>
	<br />

	<b><?php echo t('Poll'); ?>:</b>
	<?php echo CHtml::encode($data->poll->vote); ?>
	<br />
	<?echo CHtml::radioButtonList('Voting[points]', $data->poll->points, array(''=>t('Not voted'), '1'=>t('Yea'), '-1'=>t('Nay'), '0'=>t('Withholding')), array('onchange'=>CHtml::ajax(array('url'=>url('voting/savePoll',array('book_id'=>$data->id)), 'type'=>'POST', 'dataType'=>'json', 'success'=>'function(data) {if (data.status == "OK") {jQuery("#book_'.$data->id.'").replaceWith(data.val); if (data.msg != "") {jQuery("#flash-common-success div").html(data.msg); jQuery("#flash-common-success").dialog({show:"fade", hide:"puff", modal:true, title:"'.Yii::t('app', 'Information').'", autoOpen:true, width:400, minHeight:50, buttons:{"Ok":function(){$(this).dialog("close");}}});}} else {if (data.msg != "") {jQuery("#flash-common-error div").html(data.msg); jQuery("#flash-common-error").dialog({show:"fade", hide:"puff", modal:true, title:"'.Yii::t('app', 'Error').'", autoOpen:true, width:400, minHeight:50, buttons:{"Ok":function(){$(this).dialog("close");}}});}}}'))))?>
	<br />

	</form>

</div>