<div class="admin_form">
	<div class="row">
		<?php
		if ($model->order_placed)
		{
			echo CHtml::htmlButton(t('Odblokovat objednávku knihovny'), array('confirm'=>t('Pozor! Operace je nevratná. Opravdu si přejete objednávku odblokovat?'), 'onclick'=>'location.href=\''.url('library/unBlock', array('id'=>$model->id)).'\''));
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
			'autoCompleteLength'=>255,
			'options'=>array(
				'minLength'=>3, 
			),
		));
		?>
		<?php $this->widget('wsext.JuiDialogCreateButton', array('model'=>'Organisation'));?>
		<?php echo $form->error($model,'organisation_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'internal_number'); ?>
		<?php echo $form->textField($model,'internal_number',array('size'=>16,'maxlength'=>16)); ?>
		<?php echo $form->error($model,'internal_number'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'order_date'); ?>
		<?
		$htmlOptions = array();
		if ($model->hasErrors('order_date')) $htmlOptions = array('class'=>'error');
		$this->widget('zii.widgets.jui.CJuiDatePicker', array(
			'name'=>'Library[order_date]',
			'language'=>'cs',
			'value'=>$model->order_date,
			'flat'=>true,
			'options'=>array(
				'showAnim'=>'fold',
				'dateFormat'=>'d.m.yy',
			),
			'htmlOptions'=>$htmlOptions,
		));
		?>
		<?php echo $form->error($model,'order_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'is_contact_place'); ?>
		<?php echo $form->radioButtonList($model, 'is_contact_place', array('0'=>t('No'), '1'=>t('Yes')), array('separator'=>'&nbsp;|&nbsp;', 'template'=>'{label} {input}', 'labelOptions'=>array('class'=>'inline'))); ?>
		<?php echo $form->error($model,'is_contact_place'); ?>
	</div>

	<div class="row">
		<label for="selfContactPlace"><?php echo t('Self Contact Place?'); ?> <span class="required">*</span></label>
		<?php echo CHtml::radioButtonList('selfContactPlace', $model->selfContactPlace, array('0'=>t('No'), '1'=>t('Yes')), array('onchange'=>'jQuery(\'#contact_places\').toggle()', 'separator'=>'&nbsp;|&nbsp;', 'template'=>'{label} {input}', 'labelOptions'=>array('class'=>'inline'))); ?>
	</div>
	
	<div id="contact_places" <?if ($model->selfContactPlace) echo "style='display: none;';"?>>
	
		<div class="row">
			<?php echo $form->labelEx($model,'contact_place_id'); ?>
			<?echo $form->dropDownList($model, 'contact_place_id', CHtml::listData(Library::model()->contactPlaces()->with('organisation')->findAll(array('order'=>'name')), 'id', 'longName'), array('prompt'=>'&lt;'.t('contact places').'&gt;'));?>
			<?php echo $form->error($model,'contact_place_id'); ?>
		</div>
		
	</div>

</div>
