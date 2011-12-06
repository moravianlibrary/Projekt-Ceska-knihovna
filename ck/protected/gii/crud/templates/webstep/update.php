<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php
\$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Update ".$this->class2name($this->modelClass)."');
?>\n"; ?>

<?php echo "<?php"; ?> $this->insertDialog(); ?>

<h1><?php echo "<?php echo Yii::t('app', 'Update ".$this->class2name($this->modelClass)."') . Yii::t('app', ' #') . \$model->{$this->tableSchema->primaryKey}; ?>"; ?></h1>

<?php echo "<?php echo \$this->renderPartial('_form', array('model'=>\$model)); ?>"; ?>
