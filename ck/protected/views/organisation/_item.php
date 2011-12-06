<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('street')); ?>:</b>
	<?php echo CHtml::encode($data->street); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('land_registry_number')); ?>:</b>
	<?php echo CHtml::encode($data->land_registry_number); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('house_number')); ?>:</b>
	<?php echo CHtml::encode($data->house_number); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('postal_code')); ?>:</b>
	<?php echo CHtml::encode($data->postal_code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('city')); ?>:</b>
	<?php echo CHtml::encode($data->city); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('region')); ?>:</b>
	<?php echo CHtml::encode(DropDownItem::item('Organisation.region', $data->region)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('company_id_number')); ?>:</b>
	<?php echo CHtml::encode($data->company_id_number); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('vat_id_number')); ?>:</b>
	<?php echo CHtml::encode($data->vat_id_number); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('representative')); ?>:</b>
	<?php echo CHtml::encode($data->representative); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('email')); ?>:</b>
	<?php echo CHtml::mailto($data->email); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('telephone')); ?>:</b>
	<?php echo CHtml::encode($data->telephone); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fax')); ?>:</b>
	<?php echo CHtml::encode($data->fax); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('www')); ?>:</b>
	<?php echo CHtml::link($data->www); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bank_account_number')); ?>:</b>
	<?php echo CHtml::encode($data->bank_account_number); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('revenue_authority')); ?>:</b>
	<?php echo CHtml::encode($data->revenue_authority); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('worker_name')); ?>:</b>
	<?php echo CHtml::encode($data->worker_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('worker_telephone')); ?>:</b>
	<?php echo CHtml::encode($data->worker_telephone); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('worker_fax')); ?>:</b>
	<?php echo CHtml::encode($data->worker_fax); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('worker_email')); ?>:</b>
	<?php echo CHtml::mailto($data->worker_email); ?>
	<br />

</div>