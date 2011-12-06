<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'formatter'=>new Formatter,
	'attributes'=>array(
		'stock_id',
		'date',
		'count',
	),
)); ?>
