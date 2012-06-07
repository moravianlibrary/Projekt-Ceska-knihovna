<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Lib Orders');
?>

<?php $this->insertDialog(); ?>

<h1><?php echo Yii::t('app', 'Lib Orders');?></h1>

<div class="noprint">
<fieldset>
	<legend><?echo t('Filter')?></legend>
	<?
	echo CHtml::beginForm(url('stockActivity/libActivity'), 'get', array('id'=>'filter_libactivity'));
	echo t('Library').': '.CHtml::dropDownList('library_id', @$_GET['library_id'], CHtml::listData(Library::model()->with('organisation')->orderPlaced()->findAll(array('order'=>'organisation.name')), 'id', 'cityAndName'), array('prompt'=>'&lt;'.t('libraries').'&gt;'));
	echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
	echo t('Book').': '.CHtml::dropDownList('book_id', @$_GET['book_id'], CHtml::listData(Book::model()->selected()->findAll(array('order'=>'title')), 'id', 'title'), array('prompt'=>'&lt;'.t('books').'&gt;'));
	echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
	echo t('Type').': '.CHtml::dropDownList('type', @$_GET['type'], DropDownItem::items('LibOrder.type'), array('prompt'=>'&lt;'.t('types').'&gt;'));
	echo "&nbsp;&nbsp;&nbsp;".CHtml::submitButton(t('Apply'));
	echo CHtml::endForm();
	?>
</fieldset>

<fieldset>
	<legend><?echo t('Divide delivery according to filter above')?></legend>
	<?
	echo CHtml::beginForm(url('stockActivity/libDelivery'), 'get', array('id'=>'filter_libdelivery'));
	echo CHtml::hiddenField('library_id', @$_GET['library_id'], array('id'=>'deliv_library_id')).CHtml::hiddenField('book_id', @$_GET['book_id'], array('id'=>'deliv_book_id')).CHtml::hiddenField('type', @$_GET['type'], array('id'=>'deliv_type'));
	echo t('Count').': '.CHtml::textField('count', '', array('size'=>5));
	echo "&nbsp;&nbsp;&nbsp;".CHtml::submitButton(t('Divide'));
	echo CHtml::endForm();
	?>
</fieldset>
</div>

<?php $this->widget('zii.widgets.CListView', array(
	'id'=>'libactivity-list',
	'dataProvider'=>$dataProvider,
	'itemView'=>'_libactivity',
	'emptyText'=>'',
	'template'=>'{items}',
));
?>

<?php Yii::app()->getClientScript()->registerScript('libActivity_change', '
	jQuery("form#filter_libactivity select").change(function() {
		jQuery("#deliv_" + this.id).val(this.value);
	});', CClientScript::POS_READY
);
?>
