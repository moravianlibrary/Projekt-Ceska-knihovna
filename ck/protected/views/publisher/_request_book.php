<div class="right"><?
//$this->widget('ext.EBarcode.EBarcode', array('code'=>'1-'.$offerID.'-'.($index+2), 'mode'=>'div', 'encoding'=>'39', 'height'=>40, 'htmlOptions'=>array('alt'=>'Ev. č.: 1-'.$offerID.'-'.($index+2))));
$this->widget('ext.EBarcode.EBarcode', array('code'=>'1-'.$offerID.'-'.($index+2), 'mode'=>$barcode, 'encoding'=>'39', 'height'=>40));
?></div>

Příloha č. 1 k žádosti o zařazení do projektu Česká knihovna

<div class="clear"></div>

<h2>Nabídka titulu</h2>

<strong><?echo t('Author')?>:</strong> <?echo $data->author?><br />
<strong><?echo t('Title')?>:</strong> <?echo $data->title?><br />
<strong><?echo t('Editor')?>:</strong> <?echo $data->editor?><br />
<strong><?echo t('Illustrator')?>:</strong> <?echo $data->illustrator?><br />
<strong><?echo t('Preamble')?>:</strong> <?echo $data->preamble?><br />
<strong><?echo t('Epilogue')?>:</strong> <?echo $data->epilogue?><br />
<strong><?echo t('Issue Year')?>:</strong> <?echo $data->issue_year?><br />
<strong><?echo t('Publisher')?>:</strong> <?echo $publisher->name?><br />

<br />

<strong><em><u>Technické údaje o publikaci:</u></em></strong><br />

<br />

<strong><?echo t('Available')?>:</strong> <?echo $data->available?><br />
<strong><?echo t('Pages Printed')?>:</strong> <?echo $data->pages_printed?><br />
<strong><?echo t('Pages Other')?>:</strong> <?echo $data->pages_other?><br />
<strong><?echo t('Format')?>:</strong> <?echo $data->format_width?> mm x <?echo $data->format_height?> mm<br />
<strong><?echo t('Binding')?>:</strong> <?echo $data->binding?><br />
<strong><?echo t('Retail Price')?>:</strong> <?echo currf($data->retail_price)?><br />
<strong><?echo t('Offer Price')?>:</strong> <?echo currf($data->offer_price)?><br />

<br />
<strong><?echo t('Annotation')?>:</strong><br />
<?echo $data->annotation?>

<br /><br />

Prohlašuji, že tento titul splňuje podmínky pro přijetí do projektu Česká knihovna.

<br /><br /><br /><br />

<table width="100%" class="nomargin">
	<tr>
		<td width="60%">Dne <?echo DT::locToday()?></td>
		<td width="40%" style="text-align: center;">...............................................................<br />podpis statutárního zástupce<br />nebo fyzické osoby</td>
	</tr>
</table>
