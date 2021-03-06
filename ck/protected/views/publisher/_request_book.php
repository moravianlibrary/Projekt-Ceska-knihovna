<div class="clear"></div>

<h2>Nabídka titulu</h2>

<strong><?echo t('Author')?>:</strong> <?echo $data->author?><br />
<strong><?echo t('Title')?>:</strong> <?echo $data->title?><br />
<strong><?echo t('ISBN')?>:</strong> <?echo $data->isbn?><br />
<strong><?echo t('Editor')?>:</strong> <?echo $data->editor?><br />
<strong><?echo t('Illustrator')?>:</strong> <?echo $data->illustrator?><br />
<strong><?echo t('Preamble')?>:</strong> <?echo $data->preamble?><br />
<strong><?echo t('Epilogue')?>:</strong> <?echo $data->epilogue?><br />
<strong><?echo t('Issue Year')?>:</strong> <?echo $data->issue_year?><br />
<strong><?echo t('Issue Order')?>:</strong> <?echo $data->issue_order?><br />
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
<strong><?echo t('Redactors')?>:</strong> <?echo $data->redactors?><br />
<strong><?echo t('Reviewer')?>:</strong> <?echo $data->reviewer?><br />

<br />
<strong><?echo t('Annotation')?>:</strong><br />
<?echo $data->annotation?>
