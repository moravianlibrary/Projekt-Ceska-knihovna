<table width="100%" class="nomargin">
	<tr>
		<td width="60%">&nbsp;</td>
		<td width="40%"><?echo implode('<br />', $publisher->organisation->fullAddress)?></td>
	</tr>
</table>

<br /><br />

<strong>Věc: Objednávka č. <?echo $number.'/'.param('projectYear')?></strong>

<br /><br />

<table width="100%" class="nomargin">
	<tr>
		<td width="33%" style="vertical-align: top;">
			<strong>Fakturujte na:</strong><br />
			Moravská zemská knihovna<br />
			(Česká knihovna)<br />
			Kounicova 65a<br />
			601 87 &nbsp; Brno<br />
			IČ: 00094943<br />
			DIČ: CZ00094943<br />
			banka: Komerční banka Brno-město<br />
			č. účtu: 98832-621/0100
		</td>
		<td width="34%" style="vertical-align: top;">
			<strong>Příjemce zboží:</strong><br />
			Moravská zemská knihovna<br />
			Technické ústředí knihoven<br />
			Kounicova 65a<br />
			601 87 &nbsp; Brno
		</td>
		<td width="33%" style="vertical-align: top;">
			<strong>Vyřizuje:</strong><br />
			D. Perstická<br />
			tel/fax: 541 646 301/300<br />
			e-mail: tuk@mzk.cz<br /><br />
			Dne: <?echo DT::locToday()?>
		</td>
	</tr>
</table>

<br /><br />

Objednáváme u vás:

<br /><br />

<table>
	<tr>
		<th><?echo t('Order nr.')?></th>
		<th><?echo t('Author')?></th>
		<th><?echo t('Book')?></th>
		<th><?echo t('Count')?></th>
		<th><?echo t('Price').'/'.t('pcs')?></th>
		<th><?echo t('Price')?></th>
	</tr>
	<?php $this->widget('zii.widgets.CListView', array(
		'id'=>'order-list',
		'dataProvider'=>$orderProvider,
		'itemView'=>'_order_item',
		'emptyText'=>'',
		'template'=>'{items}',
	));
	$total = 0;
	$orders = $orderProvider->getData();
	foreach ($orders as $order)
		$total += $order->total_price;
	?>
	<tr>
		<td colspan="5"><strong>Celkem:</strong></td>
		<td><strong><?php echo CHtml::encode(currf($total)); ?></strong></td>
	</tr>
</table>
