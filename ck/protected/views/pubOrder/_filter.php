<div class="noprint">
<fieldset>
	<legend><?echo t('Filter')?></legend>
	<?
	echo CHtml::beginForm(url('pubOrder/printOrder'), 'get');
	echo CHtml::hiddenField('puborder_id', @$_GET['puborder_id']);
	//echo t('Publisher').': '.CHtml::dropDownList('publisher_id', @$_GET['publisher_id'], CHtml::listData(Publisher::model()->with('organisation')->orderPlaced()->findAll(array('order'=>'organisation.name')), 'id', 'name'), array('prompt'=>'&lt;'.t('publishers').'&gt;'));
	echo t('Type').': '.CHtml::dropDownList('puborder_type', @$_GET['puborder_type'], DropDownItem::items('PubOrder.type'));
	echo "&nbsp;&nbsp;&nbsp;".CHtml::submitButton(t('Apply'));
	echo CHtml::endForm();
	?>
</fieldset>
</div>
