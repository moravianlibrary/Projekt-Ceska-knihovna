<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Order');

$this->renderPartial('/library/request', array('model'=>$model))
?>

<div class="break"></div>

<table width="100%" class="nomargin">
	<tr>
		<td width="60%">&nbsp;</td>
		<td width="40%">
			Moravská zemská knihovna<br />
			Technické ústředí knihoven<br />
			Kounicova 65a<br />
			601 87 &nbsp; Brno
		</td>
	</tr>
</table>

<strong>Věc: Objednávka</strong><br /><br />

<p>Vážení,</p>

<p>v rámci projektu &quot;Česká knihovna <?echo param('projectYear')?>&quot; u vás objednáváme následující tituly:</p>

<br />
Základní objednávka:
<br />

<table>
	<tr>
		<th><?echo t('Publisher')?></th>
		<th><?echo t('Author')?></th>
		<th><?echo t('Book')?></th>
		<th><?echo t('Issue Year')?></th>
		<th><?echo t('Price')?></th>
		<th><?echo t('Count')?></th>
	</tr>
	<?php $this->widget('zii.widgets.CListView', array(
		'id'=>'basic-list',
		'dataProvider'=>$basicProvider,
		'itemView'=>'_order_item',
		'emptyText'=>'',
		'template'=>'{items}',
	)); ?>
</table>
	
<br />
Rezerva:
<br />

<table>
	<tr>
		<th><?echo t('Publisher')?></th>
		<th><?echo t('Author')?></th>
		<th><?echo t('Book')?></th>
		<th><?echo t('Issue Year')?></th>
		<th><?echo t('Price')?></th>
		<th><?echo t('Count')?></th>
	</tr>
	<?php $this->widget('zii.widgets.CListView', array(
		'id'=>'reserve-list',
		'dataProvider'=>$reserveProvider,
		'itemView'=>'_order_item',
		'emptyText'=>'',
		'template'=>'{items}',
	)); ?>
</table>

<br />
<br />

<?echo $model->organisation->city?>, <?echo DT::locToday()?>

<br /><br /><br /><br />

<table width="100%" class="nomargin">
	<tr>
		<td width="60%">&nbsp;</td>
		<td width="40%" style="text-align: center;">........................................<br />podpis statutárního zástupce<br />nebo fyzické osoby
		</td>
	</tr>
	<tr>
		<td width="60%">&nbsp;</td>
		<td width="40%">
			<br /><br />
			<?echo implode('<br />', $model->organisation->fullAddress)?>
		</td>
	</tr>
</table>
	