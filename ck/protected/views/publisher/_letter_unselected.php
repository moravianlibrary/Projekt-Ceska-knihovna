<table width="100%" class="nomargin">
	<tr>
		<td width="60%">&nbsp;</td>
		<td width="40%"><?echo implode('<br />', $publisher->organisation->fullAddress)?></td>
	</tr>
</table>

Věc: Projekt Česká knihovna <?echo DT::toLoc(param('projectYear'))?>

<br /><br />

<p><?echo ($publisher->organisation->salutation != null) ? $publisher->organisation->salutation : 'Vážení nakladatelé'?>,</p>

<p>Literární rada Moravské zemské knihovny v Brně posoudila na svém zasedání dne <?echo DT::toLoc(param('councilDate'))?> žádosti vydavatelů, kteří se přihlásili v letošním roce se svými projekty do programu Česká knihovna <?echo param('projectYear')?>.

<p>Celkem bylo doporučeno knihovnám k nákupu <?php echo Yii::t('app', '{n} non-commercial title|{n} non-commercial titles', $selected)?> uměleckých děl české literatury, české ilustrované beletrie pro děti a mládež, vědy o literatuře, avšak Vámi nabízené publikace nebyly bohužel vybrány.</p>

<p>Lituji, že Vám nemohu podat příznivější zprávu.</p>

<p>V Brně dne <?echo DT::toLoc(param('letterDate'))?></p>

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
 
