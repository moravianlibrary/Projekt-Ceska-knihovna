<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Order');
?>

<h1><?php echo Yii::t('app', 'Order'); ?></h1>

<?
if (user()->library_order_placed)
{
	echo "<div class=\"flash-notice\">";
	echo t('Objednávka je již uzavřena, již ji není možné měnit. ');
	if ($library->order_date != '')
		echo t('Písemná objednávka byla doručena na MZK dne ').DT::toLoc($library->order_date).'.';
	else
		echo t('Písemná objednávka dosud nebyla doručena na MZK.');
	echo "</div>";
}
?>

<?php $this->renderPartial('_order_total', array('basicPrice'=>$basicPrice, 'reserveCount'=>$reserveCount)); ?>

<div class="form">

<?php $this->widget('zii.widgets.CListView', array(
	'id'=>'lib-order-list',
	'dataProvider'=>$dataProvider,
	'itemView'=>'_sheet_item',
	'template'=>"{pager}\n{summary}\n{sorter}\n{items}\n{pager}",
	'sortableAttributes'=>array(
        'name',
        'title',
        'author',
    ),
)); ?>

</div>

<?php Yii::app()->getClientScript()->registerScript('libOrder_submit', '
	jQuery(document).off("click", ".libOrder").on("click", ".libOrder", function() {
		var book_id = jQuery(this).attr("rel");
		var url = jQuery(this).attr("href");
		jQuery.ajax({
			"type":"POST",
			"dataType":"json",
			"beforeSend":function() {jQuery("#book_"+book_id).addClass("loading");},
			"success":function(data) {jQuery("#book_"+book_id).removeClass("loading"); jQuery("#liborder_total").replaceWith(data.total); if (data.status == "OK") {jQuery("#book_"+book_id).replaceWith(data.val); if (data.msg != "") {jQuery("#flash-common-success div").html(data.msg); jQuery("#flash-common-success").dialog({show:"fade", hide:"puff", modal:true, title:"'.Yii::t('app', 'Information').'", autoOpen:true, width:400, minHeight:50, buttons:{"Ok":function(){jQuery(this).dialog("close");}}});}} else {if (data.msg != "") {jQuery("#flash-common-error div").html(data.msg); jQuery("#flash-common-error").dialog({show:"fade", hide:"puff", modal:true, title:"'.Yii::t('app', 'Error').'", autoOpen:true, width:400, minHeight:50, buttons:{"Ok":function(){jQuery(this).dialog("close");}}});}}},
			"url":url,
			"cache":false,
			"data":jQuery(this).parents("form").serialize()
		});
		return false;
	});', CClientScript::POS_READY
);
?>
