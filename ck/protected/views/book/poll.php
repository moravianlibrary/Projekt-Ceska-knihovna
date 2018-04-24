<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Voting');
?>

<?php $this->insertDialog(); ?>

<h1><?php echo Yii::t('app', 'Voting'); ?></h1>

<div class="form">

<?php $this->widget('zii.widgets.CListView', array(
	'id'=>'poll-list',
	'dataProvider'=>$dataProvider,
	'itemView'=>'_poll',
	'sortableAttributes'=>array(
        'title',
        'name',
    ),
)); ?>

</div>

<?php Yii::app()->getClientScript()->registerScript('bookVotes_submit', '
	jQuery(document).on("click", ".bookVotes", function() {
		var book_id = jQuery(this).attr("rel");
		var url = jQuery(this).attr("href");
		jQuery.ajax({
			"type":"POST",
			"dataType":"json",
			"beforeSend":function() {jQuery("#book_"+book_id).addClass("loading");},
			"success":function(data) {jQuery("#book_"+book_id).removeClass("loading"); if (data.status == "OK") {jQuery("#book_"+book_id).replaceWith(data.val); if (data.msg != "") {jQuery("#flash-common-success div").html(data.msg); jQuery("#flash-common-success").dialog({show:"fade", hide:"puff", modal:true, title:"'.Yii::t('app', 'Information').'", autoOpen:true, width:400, minHeight:50, buttons:{"Ok":function(){jQuery(this).dialog("close");}}});}} else {if (data.msg != "") {jQuery("#flash-common-error div").html(data.msg); jQuery("#flash-common-error").dialog({show:"fade", hide:"puff", modal:true, title:"'.Yii::t('app', 'Error').'", autoOpen:true, width:400, minHeight:50, buttons:{"Ok":function(){jQuery(this).dialog("close");}}});}}},
			"url":url,
			"cache":false,
			"data":jQuery(this).parents("form").serialize()
		});
		return false;
	});', CClientScript::POS_READY
);
?>
