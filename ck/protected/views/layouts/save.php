<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?echo app()->language?>" lang="<?echo app()->language?>">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="<?echo app()->language?>" />
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<style type="text/css">
		<?php readfile('/opt/ck2017/www'  . '/css/blank.css'); ?>
	</style>
	<style type="text/css" media="print">
		<?php readfile('/opt/ck2017/www' . '/css/blank_print.css'); ?>
	</style>
</head>

<body>

<div class="container" id="page">

	<?php echo $content; ?>

</div><!-- page -->

</body>
</html>
