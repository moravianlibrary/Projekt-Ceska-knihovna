<?php

class SiteController extends Controller
{
	public $defaultAction = 'login';
	
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

	public function actionLogin()
	{
		if (!user()->isGuest)
		{
			if (am()->checkAccess('PublisherRole', user()->id))
				$this->redirect(array('/book/admin'));
			elseif (am()->checkAccess('LibraryRole', user()->id))
				$this->redirect(array('/libOrder/sheet'));
			else 
				$this->redirect(array('/site/page', 'view'=>'about'));			
		}
		
		$this->layout = '//layouts/column1';

		$model=new LoginForm;

		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			if($model->validate() && $model->login())
			{
				if (am()->checkAccess('Administrator', user()->id))
				{
					sess()->add('PMA_single_signon_user', 'ckadmin');
					sess()->add('PMA_single_signon_password', 'OhPe3zai');
				}
				//if (am()->checkAccess('PublisherRole', user()->id))
				if (user()->publisher_id)
					$this->redirect(array('/book/admin'));
				//elseif (am()->checkAccess('LibraryRole', user()->id))
				elseif (user()->library_id)
					{
						$library = Library::model()->my()->find();
						if ($library->organisation->city == '')
						{
							user()->setFlash('error.updateorg', t('Nejdříve prosím doplňte potřebné údaje ve Vaší žádosti.'));
							$this->redirect(array('/library/clientUpdate'));
						}
						else
							$this->redirect(array('/libOrder/sheet'));
					}
					else 
						$this->redirect(array('/site/page', 'view'=>'about'));
			}
		}
		$this->render('login',array('model'=>$model));
	}

	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
	
	public function actionResetPassword()
	{
		user()->logout(false);

		$model = User::model()->findByAttributes(array('username'=>$_POST['resetmail']));
		if ($model === null)
			throw new CHttpException(400,Yii::t('app', 'Invalid request. Please do not repeat this request again.'));
		else
		{
			$newPass = uniqid();
			$model->password = $newPass;
			$model->save();
			
			$headers  = "MIME-Version: 1.0\n";
			$headers .= "Content-type: text/plain; charset=utf-8\n";
			$headers .= "X-Priority: 3\n";
			$headers .= "X-MSMail-Priority: Normal\n";
			$headers .= "X-Mailer: Yii Framework\n";
			$headers .= "From: ".param('adminEmail')."\n";
			$headers .= "Return-Path: ".param('adminEmail')."\n";
			$headers .= "Reply-To: ".param('adminEmail')."\n";

			mail($_POST['resetmail'], '=?utf-8?b?'.base64_encode(Yii::t('app', 'Czech Library new password')).'?=', Yii::t('app', 'Hello, your new password to log into Czech Library system is: ').$newPass, $headers, "-f".param('adminEmail'));
			
			user()->setFlash('success.resetpass', Yii::t('app', 'New password has been sent to your e-mail address.'));
			$this->redirect(array('login'));
		}
	}

	public function actionSetting()
	{
		$this->render('setting');
	}
}