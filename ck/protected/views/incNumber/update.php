<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Inc Numbers');
?>

<h1><?php echo Yii::t('app', 'Inc Numbers'); ?></h1>

<?
if (sizeof($incNumbers)) 
{
?>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'incNumbers-form',
)); ?>

	<table class="border">
		<tr>
			<th><?echo t('Title')?></th>
			<th><?echo t('Author')?></th>
			<th><?echo t('Inc Number')?></th>
		</tr>
	<?
	foreach($incNumbers as $i=>$incNumber)
	{
		?>
		<tr>
			<td><?php echo $incNumber->lib_order->book->title; ?></td>
			<td><?php echo $incNumber->lib_order->book->author; ?></td>
			<td><?php echo $form->textField($incNumber,"[$i]number"); ?></td>
		</tr>
		<?
	}
	?>
	</table>

	<div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('app', 'Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<?
}
else 
	echo t('Record not found.');
?>
