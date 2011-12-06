<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Duns');
?>

<div class="form">

<?
foreach ($publishers as $publisher)
{
	echo '<strong>'.$publisher->name.'</strong>';
	echo '<ul>';
	foreach ($publisher->books as $book)
	{
		echo '<li>'.$book->author.': '.$book->title.' - ';
		$a = array();
		foreach ($book->pub_orders as $pubOrder)
			$a[] = $pubOrder->remaining.' '.t('pcs').' ('.$pubOrder->type_c.', '.$pubOrder->date.')';
		echo implode(', ', $a);
		echo '</li>';
	}
	echo '</ul>';
	echo t('Dun date').': <span id="dun_date_'.$publisher->id.'">'.$publisher->dun_date.'</span><br />';
	echo CHtml::ajaxButton(t('Create Dun'), url('pubOrder/saveDun', array('publisher_id'=>$publisher->id)), array('update'=>'#dun_date_'.$publisher->id));
	echo '<br /><br />';
}
?>

</div>
