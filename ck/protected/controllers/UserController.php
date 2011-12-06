<?php

class UserController extends Controller
{
	public function actionView($id)
	{
		$model = $this->loadModel($id);
		
		if (req()->isAjaxRequest)
		{
			$this->renderPartial('_view', array('model'=>$model));
		}
		else
		{
			$this->render('view',array('model'=>$model));
		}
	}

	public function actionCreate()
	{
		$model=new User('set_password');
		
		if (req()->isAjaxRequest && !isset($_GET['ajax']))
		{
			$this->ajaxEditForm($model, array('username'));
		}
		else
		{
			if(isset($_POST['User']))
			{
				$model->attributes=$_POST['User'];
				if($model->save())
					$this->redirect(array('admin'));
				else
					$model->viewAttributes();
			}

			$this->render('create',array(
				'model'=>$model,
			));
		}
	}

	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		if (req()->isAjaxRequest && !isset($_GET['ajax']))
		{
			$this->ajaxEditForm($model, array('username'));
		}
		else
		{
			if(isset($_POST['User']))
			{
				if ($model->_oldPassword != $_POST['User']['password']) $model->scenario = 'set_password';
				$model->attributes=$_POST['User'];
				if($model->save())
					$this->redirect(array('admin'));
				else
					$model->viewAttributes();
			}

			$this->render('update',array(
				'model'=>$model,
			));
		}
	}

	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			$this->loadModel($id)->delete();

			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
			else
			{
				echo CJSON::encode(array('success'=>(Yii::app()->getUser()->hasFlash('success.deleterecord')?Yii::app()->getUser()->getFlash('success.deleterecord'):''), 'error'=>(Yii::app()->getUser()->hasFlash('error.deleterecord')?Yii::app()->getUser()->getFlash('error.deleterecord'):''), 'notice'=>(Yii::app()->getUser()->hasFlash('notice.deleterecord')?Yii::app()->getUser()->getFlash('notice.deleterecord'):'')));
				Yii::app()->end();
			}
		}
		else
			throw new CHttpException(400,Yii::t('app', 'Invalid request. Please do not repeat this request again.'));
	}

	public function actionIndex()
	{
		$criteria=new CDbCriteria;
		$criteria->with = array();

		$dataProvider=new CActiveDataProvider(User::model()->my(), array(
			'criteria'=>$criteria,
			'sort'=>array(
				'attributes'=>array(
					'*',
					),
				),
		));
		
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	public function actionAdmin()
	{
		$model=new User('search');
		$model->unsetAttributes();
		if(isset($_GET['User']))
			$model->attributes=$_GET['User'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function loadModel($id)
	{
		$model=User::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,Yii::t('app', 'The requested page does not exist.'));
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionRegistration()
	{
		user()->logout(false);

		$model=new User('registration');
		$model->register_as = param('registerAs');
		if ($model->register_as == '')
			throw new CHttpException(400,Yii::t('app', 'Invalid request. Please do not repeat this request again.'));
					
		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			if ($model->validate())
			{
				$login = new LoginForm;
				$login->username = $model->username;
				$login->password = $model->password;
				$user=User::model()->findByAttributes(array('username'=>$model->username));
				if ($user === null || ($user !== null && $login->validate()))
				{
					if ($user === null)
					{
						$model->save();
						$user_id = $model->id;
					}
					else
						$user_id = $user->id;
						
					$login->login();
					
					if (am()->checkAccess(ucfirst($model->register_as).'Role', $user_id))
					{
						switch ($model->register_as)
						{
							case 'publisher':
								$this->redirect(array('/book/admin'));
								break;
							case 'library':
								$this->redirect(array('/libOrder/sheet'));
								break;
						}
					}
					else
						$this->redirect(array($model->register_as.'/registration'));
				}
				else
					$model->addError('username', t('E-mailová adresa již byla použita při dřívější registraci a heslo není platné.'));
			}
		}
		$this->render('registration',array(
			'model'=>$model,
		));
	}
}
