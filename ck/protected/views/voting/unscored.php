<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Unscored Result');
?>

<?php $this->insertDialog(); ?>

<h1><?php echo Yii::t('app', 'Unscored Result'); ?></h1>

<table>
	<tr>
		<th>Uživatel</th>
		<th>Vydavatel</th>
		<th>Autor</th>
		<th>Název</th>
		<th>Body</th>
	</tr>
	<?php $this->widget('zii.widgets.CListView', array(
		'dataProvider'=>$dataProvider,
		'itemView'=>'_unscored',
		'id'=>'postListView',
		'enablePagination'=>false,
		'enableSorting'=>false,
	)); ?>
</table>
