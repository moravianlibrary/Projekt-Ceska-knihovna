<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<div class="view">

<?php
foreach($this->tableSchema->columns as $column)
{
	if($column->isPrimaryKey || $column->name == 'create_time' || $column->name == 'modify_time' || $column->name == 'ip_address')
		continue;
	echo "\t<b><?php echo CHtml::encode(\$data->getAttributeLabel('{$column->name}')); ?>:</b>\n";
	echo "\t<?php echo CHtml::encode(\$data->{$column->name}); ?>\n\t<br />\n\n";
}
?>
</div>