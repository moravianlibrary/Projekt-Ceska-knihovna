<div class="view" id="book_<?echo $data->id?>">

	<form name='f_book_<?echo $data->id?>'>

	<?echo CHtml::hiddenField('Voting[type]', Voting::RATING)?>
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('publisher_id')); ?>:</b>
        <!--
	<?php echo CHtml::link(CHtml::encode($data->name), '#', array('onclick'=>CHtml::ajax(array('url'=>url('publisher/view',array('id'=>$data->publisher_id)), 'success'=>'function(data){$("#publisher-juidialog-content").html(data);$("#publisher-juidialog").dialog("option", "modal", false).dialog("open");return false;}')))); ?>
	<br />
        -->
        <?php echo $data->name; ?>
        <br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<!--
	<?php echo CHtml::link(CHtml::encode($data->title), '#', array('onclick'=>CHtml::ajax(array('url'=>url('book/view',array('id'=>$data->id)), 'success'=>'function(data){$("#book-juidialog-content").html(data);$("#book-juidialog").dialog("option", "modal", false).dialog("open");return false;}')))); ?>
	<br />
        -->
        <?php echo $data->title; ?>
        <br />

        <?php foreach(array('author', 'editor', 'illustrator', 'preamble', 'epilogue', 'issue_year', 'available', 'pages_printed', 'pages_other', 'format_width', 'format_height',
		'binding', 'retail_price', 'offer_price', 'project_price', 'isbn', 'comment', 'redactors', 'reviewer') as $item) { ?>
	<b><?php echo CHtml::encode($data->getAttributeLabel($item)); ?>:</b>
        <?php echo $data->$item; ?>
        <br />
        <?php } ?>

	<b><?php echo CHtml::encode($data->getAttributeLabel('annotation')); ?>:</b><br />
	<?php echo $data->annotation; ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('council_comment')); ?>:</b><br />
	<?php echo $data->council_comment; ?>
	<br />


	<?php if (isset($data->manuscript)): ?>
	<b><?php echo CHtml::encode($data->getAttributeLabel('manuscript')); ?>:</b>
	<b><?php echo CHtml::link($data->manuscript, array('book/download', 'id' => $data->id))?></b>
        <br/>
	<?php endif; ?>

	<b><?php echo t('Points'); ?>:</b>
	<?
		/*
		echo CHtml::dropDownList('Voting[points]', (isset($data->rating) ? $data->rating->points : 0), Voting::$ratingOptions, array('prompt'=>'N - nehlasováno', 'onchange'=>CHtml::ajax(array('url'=>url('voting/saveRating',array('book_id'=>$data->id)), 'type'=>'POST', 'dataType'=>'json', 'beforeSend'=>'function(){$("#book_'.$data->id.'").addClass("loading");}', 'success'=>'function(data) {$("#book_'.$data->id.'").removeClass("loading"); if (data.status == "OK") {jQuery("#book_'.$data->id.'").replaceWith(data.val); if (data.msg != "") {jQuery("#flash-common-success div").html(data.msg); jQuery("#flash-common-success").dialog({show:"fade", hide:"puff", modal:true, title:"'.Yii::t('app', 'Information').'", autoOpen:true, width:400, minHeight:50, buttons:{"Ok":function(){$(this).dialog("close");}}});}} else {if (data.msg != "") {jQuery("#flash-common-error div").html(data.msg); jQuery("#flash-common-error").dialog({show:"fade", hide:"puff", modal:true, title:"'.Yii::t('app', 'Error').'", autoOpen:true, width:400, minHeight:50, buttons:{"Ok":function(){$(this).dialog("close");}}});}}}'))));
		*/
		echo CHtml::dropDownList('Voting[points]', (isset($data->rating) ? $data->rating->points : null), Voting::$ratingOptions, array('onchange'=>CHtml::ajax(array('url'=>url('voting/saveRating',array('book_id'=>$data->id)), 'type'=>'POST', 'dataType'=>'json', 'beforeSend'=>'function(){$("#book_'.$data->id.'").addClass("loading");}', 'success'=>'function(data) {$("#book_'.$data->id.'").removeClass("loading"); if (data.status == "OK") {jQuery("#book_'.$data->id.'").replaceWith(data.val); if (data.msg != "") {jQuery("#flash-common-success div").html(data.msg); jQuery("#flash-common-success").dialog({show:"fade", hide:"puff", modal:true, title:"'.Yii::t('app', 'Information').'", autoOpen:true, width:400, minHeight:50, buttons:{"Ok":function(){$(this).dialog("close");}}});}} else {if (data.msg != "") {jQuery("#flash-common-error div").html(data.msg); jQuery("#flash-common-error").dialog({show:"fade", hide:"puff", modal:true, title:"'.Yii::t('app', 'Error').'", autoOpen:true, width:400, minHeight:50, buttons:{"Ok":function(){$(this).dialog("close");}}});}}}'))));
	?>
	<br />

	</form>

</div>
