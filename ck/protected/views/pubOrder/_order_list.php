<?
global $order_number;
if (!isset($order_number)) {
   $order_number = 1;
} else {
   $order_number+=1;
}
?>

<table width="100%" class="nomargin">
	<tr>
		<td width="60%">&nbsp;</td>
		<td width="40%"><?echo implode('<br />', $publisher->organisation->fullAddress)?></td>
	</tr>
</table>

<br /><br />

<strong>Věc: Objednávka č. <input class="nomargin b" size="50" value="<?echo $number.'/'.param('projectYear'); /*if ($type == 'R') echo "-REZERVA"*/?>" /></strong>

<br /><br />

<table width="100%" class="nomargin">
	<tr>
		<td width="33%" style="vertical-align: top;">
			<strong>Fakturujte na:</strong><br />
			Moravská zemská knihovna v Brně<br />
			Česká knihovna<br />
			Kounicova 65a<br />
			601 87 &nbsp; Brno<br />
			IČ: 00094943<br />
			DIČ: CZ00094943<br />
			banka: Česká národní banka<br />
			č. účtu:<br />
                        197638621/0710
		</td>
		<td width="34%" style="vertical-align: top;">
			<strong>Příjemce zboží:</strong><br />
			Moravská zemská knihovna<br />
			Česká knihovna<br />
			Kounicova 65a<br />
			601 87 &nbsp; Brno
		</td>
		<td width="33%" style="vertical-align: top;">
			<strong>Vyřizuje:</strong><br />
			Z. Machková<br />
			tel/fax: 541 646 301/300<br />
			e-mail: ceskaknihovna@mzk.cz<br /><br />
			Dne: <?echo param('printOrderDate')?> <!-- <?echo DT::locToday()?> -->
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
		<th><?echo t('Název')?></th>
		<th><?echo t('ISBN')?></th>
		<th><?echo t('Počet kusů')?></th>
		<th><?echo t('Cena za kus vč. DPH')?></th>
		<th><?echo t('Cena celkem vč. DPH')?></th>
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
		<td colspan="6"><strong>Celkem:</strong></td>
		<td><strong><?php echo CHtml::encode(currf($total)); ?></strong></td>
	</tr>
</table>
