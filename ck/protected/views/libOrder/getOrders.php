<?php
$this->pageTitle = Yii::app()->name.' - '.$title;
?>

<?php $this->insertDialog(); ?>

<h1><?php echo $title; ?></h1>

<div class='form'>

<table class='border'>
	<tr>
		<th><?php echo t('Title'); ?></th>
		<th><?php echo t('Author'); ?></th>
		<th><?php echo t('Publisher'); ?></th>
		<th><?php echo t('Count'); ?></th>
		<th><?php echo t('Total Price'); ?></th>
		<th><?php echo t('Delivered by Publisher'); ?></th>
		<th><?php echo t('Remaining from Publisher'); ?></th>
	</tr>
	<?
	foreach ($items as $item)
	{
		echo "<tr><td>".CHtml::ajaxLink($item["title"], array('book/view', 'id'=>$item['book_id']), array('success'=>'function(data){$("#book-juidialog-content").html(data);$("#book-juidialog").dialog("option", "modal", false).dialog("open");return false;}'))."</td><td>{$item["author"]}</td><td>".CHtml::ajaxLink($item["name"], array('publisher/view', 'id'=>$item['publisher_id']), array('success'=>'function(data){$("#publisher-juidialog-content").html(data);$("#publisher-juidialog").dialog("option", "modal", false).dialog("open");return false;}'))."</td><td>{$item["sum_count"]}</td><td>".currf($item["total_price"])."</td><td>{$item["delivered"]}</td><td>{$item["remaining"]}</td></tr>";
	}
	?>
</table>

</div>