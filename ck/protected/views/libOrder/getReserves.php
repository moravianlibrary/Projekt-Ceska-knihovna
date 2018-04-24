<?php
$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Reserves');
?>

<?php $this->insertDialog(); ?>

<h1><?php echo Yii::t('app', 'Reserves'); ?></h1>

<div class='form'>

<table class='border'>
	<tr>
		<th><?php echo t('Title'); ?></th>
		<th><?php echo t('Author'); ?></th>
		<th><?php echo t('Publisher'); ?></th>
		<th><?php echo t('Count'); ?></th>
		<th><?php echo t('Total Price'); ?></th>
		<th><?php echo t('Delivered'); ?></th>
		<th><?php echo t('Remaining'); ?></th>
	</tr>
	<?
	foreach ($reserves as $reserve)
	{
		echo "<tr><td>".CHtml::ajaxLink($reserve["title"], array('book/view', 'id'=>$reserve['book_id']), array('success'=>'function(data){$("#book-juidialog-content").html(data);$("#book-juidialog").dialog("option", "modal", false).dialog("open");return false;}'))."</td><td>{$reserve["author"]}</td><td>".CHtml::ajaxLink($reserve["name"], array('publisher/view', 'id'=>$reserve['publisher_id']), array('success'=>'function(data){$("#publisher-juidialog-content").html(data);$("#publisher-juidialog").dialog("option", "modal", false).dialog("open");return false;}'))."</td><td>{$reserve["sum_count"]}</td><td>".currf($reserve["total_price"])."</td><td>".DropDownItem::item('YesNo', $reserve["delivered"])."</td><td>".DropDownItem::item('YesNo', $reserve["remaining"])."</td></tr>";
	}
	?>
</table>

</div>