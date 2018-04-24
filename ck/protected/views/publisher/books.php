<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Request');
?>

<?php $this->widget('zii.widgets.CListView', array(
	'id'=>'book-list',
	'dataProvider'=>$bookProvider,
	'itemView'=>'_request_book',
	'emptyText'=>'',
	'template'=>'{items}',
	'separator'=>'<div class="break"></div>',
	'viewData'=>array(
		'publisher'=>$publisher,
	),
));

?>
