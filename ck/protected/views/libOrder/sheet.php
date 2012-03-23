<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Order');
?>

<?php $this->insertDialog(); ?>

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
	'sortableAttributes'=>array(
        'name',
        'title',
        'author',
    ),
)); ?>

</div>