<div class="admin_form">
	<div class="row">
		<?php
		if ($model->offer_id)
		{
			echo CHtml::htmlButton(t('Odblokovat žádost nakladatele'), array('confirm'=>t('Pozor! Operace je nevratná. Opravdu si přejete žádost odblokovat?'), 'onclick'=>'location.href=\''.url('publisher/unBlock', array('id'=>$model->id)).'\''));
		}
		?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'organisation_id'); ?>
		<?
		$this->widget('wsext.EJuiAutoCompleteFkField', array(
			'model'=>$model, 
			'attribute'=>'organisation_id',
			'sourceUrl'=>array('findOrganisation'), 
			'relName'=>'organisation',
			'displayAttr'=>'name',
			'autoCompleteLength'=>60,
			'options'=>array(
				'minLength'=>3, 
			),
		));
		?>
		<?php $this->widget('wsext.JuiDialogCreateButton', array('model'=>'Organisation'));?>
		<?php echo $form->error($model,'organisation_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'request_date'); ?>
		<?
		$htmlOptions = array();
		if ($model->hasErrors('request_date')) $htmlOptions = array('class'=>'error');
		$this->widget('zii.widgets.jui.CJuiDatePicker', array(
			'name'=>'Publisher[request_date]',
			'language'=>'cs',
			'value'=>$model->request_date,
			'flat'=>true,
			'options'=>array(
				'showAnim'=>'fold',
				'dateFormat'=>'d.m.yy',
			),
			'htmlOptions'=>$htmlOptions,
		));
		?>
		<?php echo $form->error($model,'request_date'); ?>
	</div>

</div>
