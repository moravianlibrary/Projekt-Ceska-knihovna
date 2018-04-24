<table width="100%" class="nomargin">
	<tr>
		<td width="60%">&nbsp;</td>
		<td width="40%"><?echo implode('<br />', $publisher->organisation->fullAddress)?></td>
	</tr>
</table>

Věc: Projekt Česká knihovna <?echo DT::toLoc(param('projectYear'))?>

<br /><br />

<p><?echo ($publisher->organisation->salutation != null) ? $publisher->organisation->salutation : 'Vážení nakladatelé'?>,</p>

<p>Literární rada Moravské zemské knihovny v Brně posoudila na svém zasedání dne <?echo DT::toLoc(param('councilDate'))?> Vaše nabídky přihlášené do konkurzu v rámci projektu Česká knihovna <?echo param('projectYear')?>, programu na podporu odkoupení komerčně problematických uměleckých děl české literatury, české ilustrované beletrie pro děti a mládež a vědy o literatuře. Z Vaší přihlášené produkce byly vybrány pro knihovny tyto tituly:</p>

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

<p>
Organizaci nákupu a distribuci provádí Moravská zemská knihovna - oddělení Česká knihovna, 
Kounicova 65a, 601 87, Brno (závaznou objednávku Vám zašleme cca do tří měsíců).
</p>

<!-- <p>V Brně dne <?echo DT::locToday()?>.</p> -->
<p>V Brně dne <?echo DT::toLoc(param('letterDate'))?> </p>

<p>S pozdravem</p>

<br /><br /><br />

<table width="100%" class="nomargin">
	<tr>
		<td width="60%">&nbsp;</td>
		<td width="40%">Zdeňka Machková<br />tajemnice Literární rady MZK <br /> <br />
                Moravská zemská knihovna v Brně<br />
                oddělení Česká knihovna<br />
                Kounicova 65a<br />
                601 87 Brno<br />
                <br />
                tel.č.: 541 646 301<br />
                e-mail.: ceskaknihovna@mzk.cz<br />
		</td>
	</tr>
</table>

