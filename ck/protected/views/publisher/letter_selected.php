<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Letter');
?>

<div class="noprint">
<fieldset>
	<legend><?echo t('Filter')?></legend>
	<?
	echo CHtml::beginForm(url('publisher/letterSelected'), 'get');
	echo t('Publisher').': '.CHtml::dropDownList('publisher_id', @$_GET['publisher_id'], CHtml::listData(Publisher::model()->with('organisation')->selected()->findAll(array('order'=>'organisation.name')), 'id', 'name'), array('prompt'=>'&lt;'.t('publishers').'&gt;'));
	echo "&nbsp;&nbsp;&nbsp;".CHtml::submitButton(t('Apply'));
	echo CHtml::endForm();
	?>
</fieldset>
</div>

<?
$printPageBreak = false;

foreach ($publishers as $publisher_id=>$publisher)
{
	if ($printPageBreak)
		$this->renderPartial('_page_break');
	$printPageBreak = true;
	
	$this->renderPartial('_letter_selected',array(
		'publisher'=>$publisher,
		'bookProvider'=>$bookProviders[$publisher_id],
	));
}
?>
