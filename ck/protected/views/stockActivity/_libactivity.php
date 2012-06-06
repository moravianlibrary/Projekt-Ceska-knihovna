<div class="view" id="liborder_<?echo $data->id?>">

	<b><?php echo CHtml::encode(t('Library')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->library->name), '#', array('onclick'=>CHtml::ajax(array('url'=>url('library/view',array('id'=>$data->library_id)), 'success'=>'function(data){$("#library-juidialog-content").html(data);$("#library-juidialog").dialog("option", "modal", false).dialog("open");return false;}')))); ?>
	<br />

	<b><?php echo CHtml::encode(t('Title')); ?>:</b>
	<?php
		if ($data->type == LibOrder::BASIC)
			$stock_id = $data->book->stock_basic->id;
		else
			$stock_id = $data->book->stock_reserve->id;
		echo CHtml::link(CHtml::encode($data->book->title), url('stock/update',array('id'=>$stock_id)));
		//echo CHtml::link(CHtml::encode($data->book->title), '#', array('onclick'=>CHtml::ajax(array('url'=>url('book/view',array('id'=>$data->book->id)), 'success'=>'function(data){$("#book-juidialog-content").html(data);$("#book-juidialog").dialog("option", "modal", false).dialog("open");return false;}'))));
	?>
	<br />

	<b><?php echo CHtml::encode(t('Type')); ?>:</b>
	<?php echo CHtml::encode($data->type_c); ?>
	<br />

	<b><?php echo CHtml::encode(t('Count')); ?>:</b>
	<?php echo CHtml::encode($data->count); ?> <?echo t('pcs')?>
	<br />

	<b><?php echo CHtml::encode(t('Delivered')); ?>:</b>
	<?php echo CHtml::encode($data->delivered); ?> <?echo t('pcs')?>
	<br />

	<b><?php echo CHtml::encode(t('Remaining')); ?>:</b>
	<?php echo CHtml::encode($data->remaining); ?> <?echo t('pcs')?>
	<br />

	<?if ($data->remaining != 0) {?>
		<form name='f_liborder_<?echo $data->id?>'>
		<b><?php echo CHtml::encode(t('Actual Delivery')); ?>:</b>
		<?echo CHtml::textField('StockActivity[count]', '', array('size'=>'5')); ?> <?echo t('pcs')?>
		<?php echo CHtml::ajaxSubmitButton(t('Save'), array('saveLibActivity', 'liborder_id'=>$data->id), array('type'=>'POST', 'dataType'=>'json', 'success'=>'function(data) {if (data.status == "OK") {jQuery("#liborder_'.$data->id.'").replaceWith(data.val); if (data.msg != "") {jQuery("#flash-common-success div").html(data.msg); jQuery("#flash-common-success").dialog({show:"fade", hide:"puff", modal:true, title:"'.Yii::t('app', 'Information').'", autoOpen:true, width:400, minHeight:50, buttons:{"Ok":function(){$(this).dialog("close");}}});}} else {if (data.msg != "") {jQuery("#flash-common-error div").html(data.msg); jQuery("#flash-common-error").dialog({show:"fade", hide:"puff", modal:true, title:"'.Yii::t('app', 'Error').'", autoOpen:true, width:400, minHeight:50, buttons:{"Ok":function(){$(this).dialog("close");}}});}}}'), array('id'=>'submit_liborder_'.$data->id))?>
		</form>
	<?}?>

</div>