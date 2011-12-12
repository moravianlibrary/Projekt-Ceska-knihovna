<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Libraries');
?>

<?php $this->insertDialog(); ?>

<h1><?php echo Yii::t('app', 'Libraries'); ?></h1>

<div class="noprint">
<fieldset>
	<legend><?echo t('Filter')?></legend>
	<?
	echo CHtml::beginForm(url('stat/libraries'), 'get');
	echo t('Region').': '.CHtml::dropDownList('region', @$_GET['region'], DropDownItem::items('Organisation.region'), array("prompt"=>"&lt;".t('regions')."&gt;"));
	echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
	echo t('Type').': '.CHtml::dropDownList('type', @$_GET['type'], $types, array("prompt"=>"&lt;".t('types')."&gt;"));
	echo "&nbsp;&nbsp;&nbsp;".CHtml::submitButton(t('Apply'));
	echo CHtml::endForm();
	?>
</fieldset>
</div>

<?php $this->widget('zii.widgets.CListView', array(
	'id'=>'libraries-list',
	'dataProvider'=>$dataProvider,
	'template'=>'{summary}{sorter}<table class="blackborder"><tr><th>'.t('Name').'</th><th>'.t('Region').'</th><th>'.t('City').'</th><th>'.t('Type').'</th></tr>{items}</table>{pager}',
	'itemView'=>'_libraries',
	'sortableAttributes'=>array(
		'name'=>t('Name'),
		'region'=>t('Region'),
		'city'=>t('City'),
		'type'=>t('Type'),
	),
)); ?>
