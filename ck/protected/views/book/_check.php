<table class="blackborder">
	<tr>
		<th><?echo t('Name')?></th>
		<th><?echo t('Author')?></th>
		<th><?echo t('Publisher')?></th>
		<th><?echo t('Project Year')?></th>
	</tr>
	<?php $this->widget('zii.widgets.CListView', array(
		'id'=>'check-book-list',
		'dataProvider'=>$model->search(),
		'itemView'=>'_check_item',
		'viewData'=>array('book_id'=>$book_id),
		'template'=>'{items}',
	)); ?>
</table>
