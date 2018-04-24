<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Scoring Result');
?>

<?php $this->insertDialog(); ?>

<h1><?php echo Yii::t('app', 'Rating Order'); ?></h1>

<span class="noprint"><button id="poll-hide-button">Skrýt sloupce s hlasováním</button><br /><br /></span>

<table>
	<tr>		
		<th>Poř. č.</th>
		<th>Nakladatelství</th>
		<th>Autor</th>
		<th>Název</th>
		<th>Počet<br />bodů</th>
		<th>Rok<br />vyd.</th>
		<th>Pozn.</th>
		<th>VOC</th>
		<th>MOC</th>
		<th class="voting">PRO</th>
		<th class="voting">PROTI</th>
		<th class="voting">ZDR</th>
	</tr>
	<?php $this->widget('zii.widgets.CListView', array(
		'id'=>'rating-order-list',
		'dataProvider'=>$dataProvider,
		'itemView'=>'_rating_order',
		'template'=>'{items}',
	)); ?>
</table>
