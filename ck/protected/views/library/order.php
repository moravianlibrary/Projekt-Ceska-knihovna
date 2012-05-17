<?php
$this->pageTitle = Yii::app()->name.' - '.$title;
?>

<h1><?php echo $title; ?></h1>

<?echo $model->name?><br />
<?echo $model->organisation->address?><br />
<?echo t('Region')?>: <?echo $model->organisation->region_c?><br />
<?echo t('Telephone')?>: <?echo $model->organisation->telephone?><br />
<?echo t('Fax')?>: <?echo $model->organisation->fax?><br />
<?echo t('E-mail')?>: <?echo $model->organisation->email?>

<?php $this->renderPartial('/libOrder/_order', array('dataProvider'=>$dataProvider)); ?>
<?php $this->renderPartial('/libOrder/_total_'.$type, array('model'=>$model)); ?>


