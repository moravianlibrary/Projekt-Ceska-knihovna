<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'stock-activity-form',
)); ?>

	<p class="note"><?php echo Yii::t('app', 'Fields with <span class="required">*</span> are required.'); ?></p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo CHtml::label(t('Publisher'), 'publisher_name'); ?>
		<?php echo CHtml::link(CHtml::encode($model->pub_order->book->publisher->name), '#', array('id'=>'publisher_name', 'onclick'=>CHtml::ajax(array('url'=>url('publisher/view',array('id'=>$model->pub_order->book->publisher_id)), 'success'=>'function(data){$("#publisher-juidialog-content").html(data);$("#publisher-juidialog").dialog("open");return false;}')))); ?>
	</div>

	<div class="row">
		<?php echo CHtml::label(t('Library'), 'library_name'); ?>
		<?php echo CHtml::link(CHtml::encode($model->lib_order->library->name), '#', array('id'=>'library_name', 'onclick'=>CHtml::ajax(array('url'=>url('library/view',array('id'=>$model->lib_order->library_id)), 'success'=>'function(data){$("#library-juidialog-content").html(data);$("#library-juidialog").dialog("open");return false;}')))); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'date'); ?>
		<?
		$htmlOptions = array();
		if ($model->hasErrors('date')) $htmlOptions = array('class'=>'error');
		$this->widget('zii.widgets.jui.CJuiDatePicker', array(
			'name'=>'StockActivity[date]',
			'language'=>'cs',
			'value'=>$model->date,
			'flat'=>true,
			'options'=>array(
				'showAnim'=>'fold',
				'dateFormat'=>'d.m.yy',
			),
			'htmlOptions'=>$htmlOptions,
		));
		?>
		<?php echo $form->error($model,'date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'count'); ?>
		<?php echo $form->textField($model,'count'); ?> <?echo t('pcs')?>
		<?php echo $form->error($model,'count'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->