<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php
\$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Manage ".$this->pluralize($this->class2name($this->modelClass))."');
?>\n"; ?>

<?php echo "<?php"; ?> $this->insertDialog(); ?>

<h1><?php echo "<?php echo Yii::t('app', 'Manage ".$this->pluralize($this->class2name($this->modelClass))."'); ?>"; ?></h1>

<?php echo "<?php"; ?> $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'<?php echo $this->class2id($this->modelClass); ?>-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'header'=>Yii::t('app', 'Actions'),
			'class'=>'ButtonColumn',
		),
<?php
foreach($this->tableSchema->columns as $column)
{
	if($column->isPrimaryKey || $column->dbType == 'text' || $column->name == 'create_time' || $column->name == 'modify_time' || $column->name == 'ip_address')
		continue;
	echo "\t\t'".$column->name."',\n";
}
?>
	),
)); ?>
