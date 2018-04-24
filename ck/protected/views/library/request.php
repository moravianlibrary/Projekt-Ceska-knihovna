<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Request');
?>

<h1>Projekt Česká knihovna</h1>

<p>
Moravská zemská knihovna v Brně z pověření Ministerstva kultury ČR vyhlašuje <strong>pro rok <?echo param('projectYear')?> projekt na podporu nákupu</strong>
nekomerčních titulů uměleckých <strong>děl české literatury</strong>, české ilustrované beletrie pro děti a mládež, vědy o literatuře <strong>pro profesionální
veřejné knihovny a knihovny vybraných vysokých škol evidovaných dle zákona č. 257/2001 Sb., o knihovnách a podmínkách provozování
veřejných knihovnických a informačních služeb (knihovní zákon).</strong>
</p>

<h2>Ž Á D O S T<br />o poskytnutí dotace z projektu Česká knihovna</h2>

<strong>1. Název žadatele:</strong><br />
<?echo $model->name?>

<br /><br />

<strong>2. Adresa žadatele:</strong><br />
<?echo $model->organisation->address?><br />
<?echo t('Region')?>: <?echo $model->organisation->region_c?><br />
<?echo t('Telephone')?>: <?echo $model->organisation->telephone?><br />
<?echo t('Fax')?>: <?echo $model->organisation->fax?><br />
<?echo t('E-mail')?>: <?echo $model->organisation->email?>

<br /><br />

<strong>3. Jméno statutárního zástupce:</strong><br />
<?echo $model->organisation->representative?>

<br /><br />

<?
if ($model->libname != null) 
{
	echo "<strong>4. Název a adresa  knihovny, která se přihlašuje do projektu Česká knihovna ".param('projectYear').":</strong><br />";
	echo implode('<br />', $model->fullAddress);
}
?>

<br /><br />

<strong>5. Evidenční číslo knihovny na MK ČR:</strong><br />
<?echo $model->number?>

<br /><br />

<strong>6. Pracovník odpovídající za projekt v dané  knihovně:</strong><br />
Jméno: <?echo $model->organisation->worker_name?><br />
Telefon: <?echo $model->organisation->worker_telephone?><br />
Fax: <?echo $model->organisation->worker_fax?><br />
E-mail: <?echo $model->organisation->worker_email?>
<br /><br />

<strong>7. Základní údaje o žadateli (knihovně v místě realizace):</strong><br />
<?echo $model->getAttributeLabel('type')?>: <?echo $model->type?><br />
<?echo $model->getAttributeLabel('headcount')?>: <?echo $model->headcount?><br />
<?echo $model->getAttributeLabel('units_total')?>: <?echo $model->units_total?><br />
<?echo $model->getAttributeLabel('units_new')?>: <?echo $model->units_new?><br />
<?echo $model->getAttributeLabel('budget')?>: <?echo currf($model->budget)?><br />
<?echo $model->getAttributeLabel('budget_czech')?>: <?echo currf($model->budget_czech)?><br />
<br /><br />

<p><strong>Potvrzuji, že údaje uvedené v žádosti jsou správné, pravdivé a jsme profesionální veřejnou knihovnou.</strong></p>

<br /><br />

<?echo $model->organisation->city?>, <?echo DT::locToday()?>

<br /><br /><br /><br />

<table width="100%" class="nomargin">
	<tr>
		<td width="60%">&nbsp;</td>
		<td width="40%" align="center">........................................<br />Podpis statutárního zástupce<br />žádajícího subjektu</td>
	</tr>
</table>
        
