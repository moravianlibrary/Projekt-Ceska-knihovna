<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Bills of Delivery');
?>

<div class="noprint">
<fieldset>
	<legend><?echo t('Filter')?></legend>
	<?
	echo CHtml::beginForm(url('stock/billOfDelivery'), 'get');
	echo t('Library').': '.CHtml::dropDownList('library_id', @$_GET['library_id'], CHtml::listData(Library::model()->with('organisation')->orderPlaced()->findAll(array('order'=>'organisation.city, t.internal_number')), 'id', 'cityAndIntNum'), array('prompt'=>'&lt;'.t('libraries').'&gt;'));
	echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
	echo t('Contact Place').': '.CHtml::dropDownList('contact_place_id', @$_GET['contact_place_id'], CHtml::listData(Library::model()->with('organisation')->contactPlaces()->findAll(array('order'=>'organisation.name')), 'id', 'name'), array('prompt'=>'&lt;'.t('contact places').'&gt;'));
	echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
	echo t('Date From').': ';
	$this->widget('zii.widgets.jui.CJuiDatePicker', array(
		'name'=>'date_from',
		'language'=>'cs',
		'value'=>@$_GET['date_from'],
		'options'=>array(
			'dateFormat'=>'d.m.yy',
		),
		'htmlOptions'=>array(
			'size'=>8,
		),
	));
	echo '&nbsp;';
	echo t('Date To').': ';
	$this->widget('zii.widgets.jui.CJuiDatePicker', array(
		'name'=>'date_to',
		'language'=>'cs',
		'value'=>@$_GET['date_to'],
		'options'=>array(
			'dateFormat'=>'d.m.yy',
		),
		'htmlOptions'=>array(
			'size'=>8,
		),
	));
	echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
	echo t('Počet výdejek na knihovnu').': '.CHtml::textField('bill_count', $billCount, array('size'=>1));
	echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
	echo t('Tisknout adresu').': '.CHtml::checkBox('print_address', $printAddress, array('uncheckValue'=>0));
	echo "&nbsp;&nbsp;&nbsp;".CHtml::submitButton(t('Apply'));
	echo CHtml::endForm();
	?>
</fieldset>
</div>

<?
$printPageBreak = false;
foreach ($libraries as $library)
{
	$bills = array();
	foreach ($library->orders as $order)
	{
		foreach ($order->stock_activities as $stockActivity)
		{
			$bills[$stockActivity->stock_id]['selected_order'] = $stockActivity->stock->book->selected_order;
			$bills[$stockActivity->stock_id]['title'] = $stockActivity->stock->book->title;
			$bills[$stockActivity->stock_id]['author'] = $stockActivity->stock->book->author;
			$bills[$stockActivity->stock_id]['name'] = $stockActivity->stock->book->publisher->organisation->name;
			$bills[$stockActivity->stock_id]['price'] = $stockActivity->stock->book->project_price;
			$bills[$stockActivity->stock_id]['count'] += $stockActivity->count;
			$bills[$stockActivity->stock_id]['date'] = $stockActivity->date;
			$bills[$stockActivity->stock_id]['type'] = $stockActivity->stock->type_c;
		}
	}
	for ($i = 1; $i <= $billCount; $i++)
	{
		if ($printPageBreak) echo '<div class="break"></div>';
		$printPageBreak = true;
		?>
		<strong>Moravská zemská knihovna</strong>
		<hr style="border-top: 1px solid black;"/>
		<table width="100%" class="nomargin">
			<tr>
				<td width="33%" style="vertical-align: top;">IČ: <strong>00094943</strong><br />295 - Výdej ČK <?echo param('projectYear')?></td>
				<td width="34%" align="center" class="large" style="vertical-align: top;">Výdejka</td>
				<td width="33%" align="right" style="vertical-align: top;"><?echo t('Date of Issue').': '.DT::locToday();?></td>
			</tr>
		</table>

		<br />

		<table width="100%">
			<tr>
				<th width="33%">Středisko</th>
				<th width="33%">Evidenční číslo</th>
				<th width="33%">Kontaktní místo</th>
			</tr>
			<tr>
				<td align="center"><?echo $library->libraryName?></td>
				<td align="center"><?echo $library->number?></td>
				<td align="center"><?echo implode(', ', $library->contact_place->fullAddress)?></td>
			<tr>
		</table>

		<br />

		<table class="noborder">
			<tr>
				<th>#</th>
				<th colspan="4" style="text-align:left"><?echo t('Name')?></th>
				<th rowspan="2" style="text-align:center; vertical-align: middle;"><?echo t('Inc Number')?></th>
			</tr>
			<tr style='border-bottom: 1px solid black;'>
				<th>&nbsp;</th>
				<th style="text-align:left"><?echo t('Šarže')?></th>
				<th style="text-align:left"><?echo t('Price')?></th>
				<th style="text-align:left"><?echo t('Count')?></th>
				<th style="text-align:left"><?echo t('Total Price')?></th>
			</tr>
			<?
			$j = 1;
			$totalPrice = 0;
			$totalCount = 0;
			foreach ($bills as $id=>$bill)
			{
				echo '<tr><td>'.$bill['selected_order'].'</td><td colspan="4"><strong>'.$bill['author'].': '.$bill['title'].'</strong></td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>';
				echo '<tr style="border-bottom: 1px dashed black;"><td>&nbsp;</td><td>'.$bill['date'].' ('.$bill['type'].')</td><td>'.currf($bill['price']).'</td><td>'.$bill['count'].' '.t('pcs').'</td><td>'.currf($bill['count']*$bill['price']).'</td><td>&nbsp;</td></tr>';
				$totalPrice += $bill['count']*$bill['price'];
				$totalCount += $bill['count'];
			}
			?>
			<tr style='border-top: 1px solid black;'>
				<th colspan="3" style="text-align: right"><?echo t('Total')?></th>
				<th style="text-align:left"><?echo $totalCount.' '.t('pcs')?></th>
				<th style="text-align:left"><?echo currf($totalPrice)?></th>
				<th style="text-align:left">&nbsp;</th>
			</tr>
		</table>

		<br />
		Přijal:
		<br /><br /><br /><br />

			<table width="100%" class="nomargin">
				<tr>
					<td width="50%" style="text-align: center;">...............................................................<br />podpis</td>
					<td width="50%" style="text-align: center;">...............................................................<br />razítko</td>
				</tr>
			</table>
		<?

	}

	if ($printAddress)
	{
		if ($printPageBreak) echo '<div class="break"></div>';
		$printPageBreak = true;
		echo implode('<br />', $library->fullAddress);
	}
}
?>