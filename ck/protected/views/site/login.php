<?php
$this->pageTitle=Yii::app()->name . ' - ' . Yii::t('app', 'User Login');
?>

<h1><?php echo Yii::t('app', 'User Login'); ?></h1>

<p><?php echo Yii::t('app', 'Please fill out the following form with your login credentials'); ?>:</p>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
)); ?>

	<p class="note"><?php echo Yii::t('app', 'Fields with <span class="required">*</span> are required.'); ?></p>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username'); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password'); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('app', 'Login')); ?>
	</div>

<?php $this->endWidget(); ?>

<br />

<!--
<?php echo t('You can register ')?> <?php echo CHtml::link(t('here'), array('/user/registration'))?>.<br />
-->

<br /><hr /><br />

<h2><?php echo Yii::t('app', 'Forgotten Password'); ?></h2>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'resetpass-form',
	'action'=>array('resetPassword'),
)); ?>

	<?php echo Yii::t('app', 'If you forgot your password, enter your e-mail address you use to login and click &quot;Reset Password&quot;.'); ?>
	
	<div class="row">
		<?php echo CHtml::label(Yii::t('app', 'E-mail'),'resetmail'); ?>
		<?php echo CHtml::textField('resetmail'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('app', 'Reset Password')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
