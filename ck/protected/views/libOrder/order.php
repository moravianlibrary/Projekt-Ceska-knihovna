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

<p>v rámci projektu Česká knihovna <?echo param('projectYear')?> objednáváme následující tituly:</p>

<br />
<strong>Základní objednávka:</strong>
<?php $this->renderPartial('_order', array('dataProvider'=>$basicProvider)); ?>
<?php $this->renderPartial('_total_basics', array('model'=>$model)); ?>

<br />
<br />

<strong>Rezerva:</strong>
<?php $this->renderPartial('_order', array('dataProvider'=>$reserveProvider)); ?>
<?php $this->renderPartial('_total_reserves', array('model'=>$model)); ?>

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
