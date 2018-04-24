<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Request');
?>

<table width="100%" class="nomargin">
	<tr>
		<td width="60%" style="vertical-align: top;"><strong>Název poskytovatele dotace: Ministerstvo kultury<br /><br />Číslo tematického okruhu : 5  Česká knihovna</strong></td>
		<td width="15" style="vertical-align: top;" align="right">
			<strong>Evidenční číslo: <?echo $offerID?></strong><br />
		</td>
		<td width="25%" style="vertical-align: top;" align="right">
			<?
			//$this->widget('ext.EBarcode.EBarcode', array('code'=>'1-'.$offerID.'-1', 'mode'=>'inline', 'encoding'=>'39', 'height'=>40));
			//$this->widget('ext.EBarcode.EBarcode', array('code'=>'1-'.$offerID.'-1', 'mode'=>$barcode, 'encoding'=>'39', 'height'=>40));
			?>
		</td>
	</tr>
</table>

<br />

<h1>ŽÁDOST</h1>
<h2>o zařazení do projektu na podporu nákupu nekomerčních titulů<br />české literatury pro profesionální veřejné knihovny v r. <?echo param('projectYear')?></h2>

<br />

<strong>Žadatel:</strong><br />
Název a kontaktní adresa nakladatelství, hlavního realizátora projektu:<br />
<?echo $publisher->organisation->original_name?><br />
<?echo $publisher->organisation->address?><br />
<br />
<?echo t('Publisher Code')?>: <?echo $publisher->code?><br />
<?echo t('Representative')?>: <?echo $publisher->organisation->representative?><br />
<?echo t('Telephone')?>: <?echo $publisher->organisation->telephone?><br />
<?echo t('Fax')?>: <?echo $publisher->organisation->fax?><br />
<?echo t('E-mail')?>: <?echo $publisher->organisation->email?><br />
<?echo t('WWW')?>: <?echo $publisher->organisation->www?><br />
<br />
<?echo t('Company Identification Number')?>: <?echo $publisher->organisation->company_id_number?><br />
<?echo t('VAT Identification Number')?>: <?echo $publisher->organisation->vat_id_number?><br />
<?echo t('Revenue Authority')?>: <?echo $publisher->organisation->revenue_authority?><br />
<?echo t('Bank Account Number')?>: <?echo $publisher->organisation->bank_account_number?><br />

<br />

<h2>Nabídka vydavatele</h2>

Počet přihlášených titulů z produkce r. <?echo param('projectYear') - 1?>: <?echo $publisher->count_last_year_books?><br />
Počet přihlášených titulů z produkce r.  <?echo param('projectYear')?>: <?echo $publisher->count_this_year_books?><br />
<?echo t('Worker Name')?>: <?echo $publisher->organisation->worker_name?><br />
<?echo t('Telephone')?>: <?echo $publisher->organisation->worker_telephone?><br />
<?echo t('Fax')?>: <?echo $publisher->organisation->worker_fax?><br />
<?echo t('E-mail')?>: <?echo $publisher->organisation->worker_email?><br />

<br />

Přílohy k žádosti:
<ol>
	<li>Nabídka titulů</li>
	<li>Fotokopie dokladu o právní subjektivitě</li>
</ol>

<div class="clear"></div>
<div class="break"></div>

Příloha č. 1 k žádosti o zařazení do projektu Česká knihovna

<h2>Nabídka titulu</h2>

<table>
	<tr>
		<th><?echo t('Author')?></th>
		<th><?echo t('Title')?></th>
		<th><?echo t('Issue Year')?></th>
	</tr>
	<?php $this->widget('zii.widgets.CListView', array(
		'id'=>'book-list',
		'dataProvider'=>$bookProvider,
		'itemView'=>'_request_book_row',
		'emptyText'=>'',
		'template'=>'{items}',
		'viewData'=>array(
			'offerID'=>$offerID,
			'barcode'=>$barcode,
			'publisher'=>$publisher,
		),
	));
	?>
</table>

<p><strong>Prohlašuji, že přihlášené tituly splňují podmínky pro přijetí do projektu Česká knihovna.</strong></p>
<p><strong>Žadatel o dotaci potvrzuje správnost uvedených údajů a prohlašuje, že nemá žádné nevyrovnané závazky vůči státnímu rozpočtu.</strong></p>


<br />

<?echo $publisher->organisation->city?>, <?echo DT::locToday()?>

<br /><br />

<table width="100%" class="nomargin">
	<tr>
		<td width="60%">&nbsp;</td>
		<td width="40%" style="text-align: center;">...............................................................<br />podpis statutárního zástupce<br />nebo fyzické osoby</td>
	</tr>
</table>
