<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php
\$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', '".$this->pluralize($this->class2name($this->modelClass))."');
?>\n"; ?>

<?php echo "<?php"; ?> $this->insertDialog(); ?>

<h1><?php echo "<?php echo Yii::t('app', '".$this->pluralize($this->class2name($this->modelClass))."'); ?>"; ?></h1>

<?php echo "<?php"; ?> $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_item',
)); ?>
