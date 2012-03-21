<div class="view" id="book_<?echo $data->id?>">

	<b><?php echo CHtml::encode($data->getAttributeLabel('publisher_id')); ?>:</b>
	<?php //echo CHtml::link(CHtml::encode($data->name), '#', array('onclick'=>CHtml::ajax(array('url'=>url('publisher/view',array('id'=>$data->publisher_id)), 'success'=>'function(data){$("#publisher-juidialog-content").html(data);$("#publisher-juidialog").dialog("option", "modal", false).dialog("open");return false;}')))); ?>
	<span><?php echo CHtml::encode($data->name); ?></span>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php //echo CHtml::link(CHtml::encode($data->title), '#', array('onclick'=>CHtml::ajax(array('url'=>url('book/view',array('id'=>$data->id)), 'success'=>'function(data){$("#book-juidialog-content").html(data);$("#book-juidialog").dialog("option", "modal", false).dialog("open");return false;}')))); ?>
	<span><?php echo CHtml::encode($data->title); ?></span>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('author')); ?>:</b>
	<span><?php echo CHtml::encode($data->author); ?></span>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('issue_year')); ?>:</b>
	<span><?php echo CHtml::encode($data->issue_year); ?></span>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('project_price')); ?>:</b>
	<span><?php echo currf(CHtml::encode($data->project_price)); ?></span>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('annotation')); ?>:</b><br />
	<span><?php echo CHtml::encode($data->annotation); ?></span>
	<br />
	<br />

	<b><?php echo t('Order'); ?>:</b>
	<table>
		<tr>
			<td style="text-align:right"><?echo t('Basic Order Count')?>: </td>
			<td>
				<?
				if (user()->library_order_placed)
				{
					echo ($data->basic ? $data->basic->count : '0').' '.t('pcs');
				}
				else 
				{
					?>
					<form name='f_book_basic_<?echo $data->id?>'>
					<?echo CHtml::hiddenField('LibOrder[book_id]', $data->id)?>
					<?echo CHtml::hiddenField('LibOrder[type]', 'B')?>
					<?php echo CHtml::encode($data->basic->count); ?> <?echo CHtml::textField('LibOrder[count]', $data->basic->count, array('size'=>'5')); ?> <?echo t('pcs')?>
					<?php echo CHtml::ajaxSubmitButton(($data->basic->count ? t('Change Order') : t('Add To Order')), array('saveSheet', 'id'=>$data->basic->id), array('type'=>'POST', 'dataType'=>'json', 'beforeSend'=>'function(){$("#book_'.$data->id.'").addClass("loading");}', 'success'=>'function(data) {$("#book_'.$data->id.'").removeClass("loading"); jQuery("#liborder_total").replaceWith(data.total); if (data.status == "OK") {jQuery("#book_'.$data->id.'").replaceWith(data.val); if (data.msg != "") {jQuery("#flash-common-success div").html(data.msg); jQuery("#flash-common-success").dialog({show:"fade", hide:"puff", modal:true, title:"'.Yii::t('app', 'Information').'", autoOpen:true, width:400, minHeight:50, buttons:{"Ok":function(){$(this).dialog("close");}}});}} else {if (data.msg != "") {jQuery("#flash-common-error div").html(data.msg); jQuery("#flash-common-error").dialog({show:"fade", hide:"puff", modal:true, title:"'.Yii::t('app', 'Error').'", autoOpen:true, width:400, minHeight:50, buttons:{"Ok":function(){$(this).dialog("close");}}});}}}'), array('id'=>'submit_book_basic_'.$data->id))?>
					</form>
					<?
				}
				?>
			</td>
		</tr>
		<tr>
			<td style="text-align:right"><?echo t('Reserve Count')?>: </td>
			<td>
				<?
				if (user()->library_order_placed)
				{
					echo ($data->reserve ? $data->reserve->count : '0').' '.t('pcs');
				}
				else 
				{
					?>
					<form name='f_book_reserve_<?echo $data->id?>'>
					<?echo CHtml::hiddenField('LibOrder[book_id]', $data->id)?>
					<?echo CHtml::hiddenField('LibOrder[type]', 'R')?>
					<?php echo CHtml::encode($data->reserve->count); ?> <?echo CHtml::textField('LibOrder[count]', $data->reserve->count, array('size'=>'5')); ?> <?echo t('pcs')?>
					<?php echo CHtml::ajaxSubmitButton(($data->reserve->count ? t('Change Order') : t('Add To Order')), array('saveSheet', 'id'=>$data->reserve->id), array('type'=>'POST', 'dataType'=>'json', 'beforeSend'=>'function(){$("#book_'.$data->id.'").addClass("loading");}', 'success'=>'function(data) {$("#book_'.$data->id.'").removeClass("loading"); jQuery("#liborder_total").replaceWith(data.total); if (data.status == "OK") {jQuery("#book_'.$data->id.'").replaceWith(data.val); if (data.msg != "") {jQuery("#flash-common-success div").html(data.msg); jQuery("#flash-common-success").dialog({show:"fade", hide:"puff", modal:true, title:"'.Yii::t('app', 'Information').'", autoOpen:true, width:400, minHeight:50, buttons:{"Ok":function(){$(this).dialog("close");}}});}} else {if (data.msg != "") {jQuery("#flash-common-error div").html(data.msg); jQuery("#flash-common-error").dialog({show:"fade", hide:"puff", modal:true, title:"'.Yii::t('app', 'Error').'", autoOpen:true, width:400, minHeight:50, buttons:{"Ok":function(){$(this).dialog("close");}}});}}}'), array('id'=>'submit_book_reserve_'.$data->id))?>
					</form>
					<?
				}
				?>
			</td>
		</tr>
	</table>
	
</div>