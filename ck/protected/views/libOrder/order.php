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
<strong>Základní objednávka:</strong>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'basic-list',
	'dataProvider'=>$basicProvider,
	'template'=>'{items}',
	'emptyText'=>'',
	'enableSorting'=>false,
	'itemsCssClass'=>'blackborder',
	'columns'=>array(
		'book.name',
		'book.author',
		array(
			'header'=>t('Book'),
			'name'=>'book.title',
			),
		'book.issue_year',
		array(
			'header'=>t('Price'),
			'name'=>'book.project_price',
			'type'=>'czk',
			'htmlOptions'=>array(
				'align'=>'right',
				),
			),
		'count',
		),
	)); ?>
<strong>Celková cena základní objednávky: </strong><?echo currf($model->basicPrice);?>
<br />
<strong>Celkový počet svazků základní objednávky: </strong><?echo app()->format->formatPcs($model->basicCount)?>

<br />
<br />

<strong>Rezerva:</strong>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'basic-list',
	'dataProvider'=>$reserveProvider,
	'template'=>'{items}',
	'emptyText'=>'',
	'enableSorting'=>false,
	'itemsCssClass'=>'blackborder',
	'columns'=>array(
		'book.name',
		'book.author',
		array(
			'header'=>t('Book'),
			'name'=>'book.title',
			),
		'book.issue_year',
		array(
			'header'=>t('Price'),
			'name'=>'book.project_price',
			'type'=>'czk',
			'htmlOptions'=>array(
				'align'=>'right',
				),
			),
		'count',
		),
	)); ?>
<strong>Celková cena rezervy: </strong><?echo currf($model->reservePrice);?>
<br />
<strong>Celkový počet svazků rezervy: </strong><?echo app()->format->formatPcs($model->reserveCount)?>

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
	