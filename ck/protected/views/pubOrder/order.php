<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Order');

if ($useFilter) $this->renderPartial('_filter');

$printPageBreak = false;

$number = 1;
foreach ($publishers as $publisher_id=>$publisher)
{
	if ($printPageBreak)
		$this->renderPartial('_page_break');
	$printPageBreak = true;

	$this->renderPartial('_order_letter',array(
		'publisher'=>$publisher,
	));

	$this->renderPartial('_page_break');

	$this->renderPartial('_order_list',array(
		'publisher'=>$publisher,
		'orderProvider'=>$orderProviders[$publisher_id],
		'number'=>$number++,
	));
}
?>
