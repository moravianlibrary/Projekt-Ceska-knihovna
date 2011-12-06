<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Lib Orders');
?>

<h1><?php echo Yii::t('app', 'Lib Orders');?></h1>

<div class="noprint">
<fieldset>
	<legend><?echo t('Filter')?></legend>
	<?
	echo CHtml::beginForm(url('stockActivity/libActivity'), 'get');
	echo t('Library').': '.CHtml::dropDownList('library_id', @$_GET['library_id'], CHtml::listData(Library::model()->with('organisation')->orderPlaced()->findAll(array('order'=>'organisation.name')), 'id', 'name'), array('prompt'=>'&lt;'.t('libraries').'&gt;'));
	echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
	echo t('Book').': '.CHtml::dropDownList('book_id', @$_GET['book_id'], CHtml::listData(Book::model()->selected()->findAll(array('order'=>'title')), 'id', 'title'), array('prompt'=>'&lt;'.t('books').'&gt;'));
	echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
	echo t('Type').': '.CHtml::dropDownList('type', @$_GET['type'], DropDownItem::items('LibOrder.type'), array('prompt'=>'&lt;'.t('types').'&gt;'));
	echo "&nbsp;&nbsp;&nbsp;".CHtml::submitButton(t('Apply'));
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
