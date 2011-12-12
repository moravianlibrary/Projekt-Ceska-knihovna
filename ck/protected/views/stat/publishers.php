<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Publishers');
?>

<?php $this->insertDialog(); ?>

<h1><?php echo Yii::t('app', 'Publishers'); ?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'id'=>'publishers-list',
	'dataProvider'=>$dataProvider,
	'template'=>'{summary}{sorter}<table class="blackborder"><tr><th>'.t('Publisher').'</th><th>'.t('Delivered Titles Count').'</th><th>'.t('Undelivered Titles Count').'</th><th>'.t('Delivered Books Count').'</th><th>'.t('Undelivered Books Count').'</th><th>'.t('Total Price').'</th></tr>{items}</table>{pager}',
	'itemView'=>'_publishers',
	'sortableAttributes'=>array(
		'name'=>t('Publisher'),
		'sum_dod_tituly'=>t('Delivered Titles Count'),
		'sum_nedod_tituly'=>t('Undelivered Titles Count'),
		'sum_dod_svazky'=>t('Delivered Books Count'),
		'sum_nedod_svazky'=>t('Undelivered Books Count'),
		'sum_cena'=>t('Total Price'),
	),
)); ?>
