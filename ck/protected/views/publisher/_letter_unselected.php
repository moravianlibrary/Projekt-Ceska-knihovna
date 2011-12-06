<table width="100%" class="nomargin">
	<tr>
		<td width="60%">&nbsp;</td>
		<td width="40%"><?echo implode('<br />', $publisher->organisation->fullAddress)?></td>
	</tr>
</table>

<br /><br />

<p><?echo ($publisher->organisation->salutation != null) ? $publisher->organisation->salutation : 'Vážení'?>,</p>

<p>Literární rada Moravské zemské knihovny v Brně posoudila na svém zasedání dne <?echo DT::toLoc(param('councilDate'))?> žádosti vydavatelů, kteří se přihlásili v letošním roce se svými projekty do programu Česká knihovna <?echo param('projectYear')?>.

<p>Celkem bylo doporučeno knihovnám k nákupu <?php echo Yii::t('app', '{n} non-commercial title|{n} non-commercial titles', $selected)?> uměleckých děl české literatury, děl literární vědy, kritiky a věd příbuzných, avšak Vámi nabízené publikace nebyly bohužel vybrány.</p>

<p>Lituji, že Vám nemohu podat příznivější zprávu.</p>

<p>V Brně dne <?echo DT::locToday()?>.</p>

<p>S pozdravem</p>

<br /><br /><br />

<table width="100%" class="nomargin">
	<tr>
		<td width="60%">&nbsp;</td>
		<td width="40%">Dagmar Perstická<br />Tajemnice Literární rady MZK</td>
	</tr>
</table>
    