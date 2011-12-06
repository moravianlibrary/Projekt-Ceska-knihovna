<table width="100%" class="nomargin">
	<tr>
		<td width="60%">&nbsp;</td>
		<td width="40%"><?echo implode('<br />', $publisher->organisation->fullAddress)?></td>
	</tr>
</table>

<br /><br />

<p><?echo ($publisher->organisation->salutation != null) ? $publisher->organisation->salutation : 'Vážení'?>,</p>

<p>Literární rada Moravské zemské knihovny v Brně posoudila na svém zasedání dne <?echo DT::toLoc(param('councilDate'))?> Vaše nabídky přihlášené do konkurzu v rámci projektu Česká knihovna <?echo param('projectYear')?>, programu na podporu odkoupení komerčně problematických uměleckých děl české literatury a děl literární vědy či kritiky pro profesionální veřejné knihovny. Z Vaší přihlášené produkce byly vybrány pro knihovny tyto tituly:</p>

<br />

<table>
	<tr>
		<th><?echo t('Nr.')?></th>
		<th><?echo t('Author')?></th>
		<th><?echo t('Book')?></th>
	</tr>
	<?php $this->widget('zii.widgets.CListView', array(
		'id'=>'order-list',
		'dataProvider'=>$bookProvider,
		'itemView'=>'_letter_selected_item',
		'emptyText'=>'',
		'template'=>'{items}',
	));
	?>
</table>

<br />

<p>Organizaci nákupu a distribuce provádí Moravská zemská knihovna - Technické ústředí knihoven, Kounicova 65a, 601 87, Brno.</p>

<p>V Brně dne <?echo DT::locToday()?>.</p>

<p>S pozdravem</p>

<br /><br /><br />

<table width="100%" class="nomargin">
	<tr>
		<td width="60%">&nbsp;</td>
		<td width="40%">Dagmar Perstická<br />Tajemnice Literární rady MZK</td>
	</tr>
</table>
