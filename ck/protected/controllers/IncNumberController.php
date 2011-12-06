<?php

class IncNumberController extends Controller
{
	public function actionUpdate()
	{
		$incNumbers = IncNumber::model()->my()->findAll();
		if(isset($_POST['IncNumber']))
		{
			$valid=true;
			foreach ($incNumbers as $i=>$incNumber)
			{
				if(isset($_POST['IncNumber'][$i]))
					$incNumber->attributes=$_POST['IncNumber'][$i];
				$valid=$incNumber->validate() && $valid;
			}
			if ($valid)
			{			
				foreach ($incNumbers as $i=>$incNumber)
				{
					$incNumber->save(false);
				}
				user()->setFlash('success.inc_numbers',t('Records successfully saved.'));
			}
		}
		$this->render('update',array('incNumbers'=>$incNumbers));
	}
	
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='stock-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
