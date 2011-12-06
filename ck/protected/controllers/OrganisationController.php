<?php

class OrganisationController extends Controller
{
	protected function beforeAction($action)
	{
		$params = array('enable'=>true);	
		
		if ($action->id == 'view')
		{		
			if (user()->publisher_id)
			{
				$model=Organisation::model()->my()->findByPk($_GET['id']);
				if($model===null)
					$params['enable'] = false;
			}
			elseif (user()->library_id)
				{
					if ($_GET['id'] != user()->organisation_id)
					{
						$model=Publisher::model()->selected()->findByAttributes(array('organisation_id'=>$_GET['id']));
						if($model===null)
							$params['enable'] = false;						
					}
				}
		}
		
		if (parent::beforeAction($action, $params))
			return true;
		else
			return false;
	}

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
		$model=new Organisation;

		if(isset($_POST['Organisation']))
		{
			if (!user()->checkAccess('BackOffice'))
				$model->user_id = user()->id;
		}
		
		if (req()->isAjaxRequest && !isset($_GET['ajax']))
		{
			$this->ajaxEditForm($model, array('name', 'city'));
		}
		else
		{
			if(isset($_POST['Organisation']))
			{
				$model->attributes=$_POST['Organisation'];
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

		if(isset($_POST['Organisation']))
		{
			if (!user()->checkAccess('BackOffice'))
				$model->user_id = user()->id;
		}
		
		if (req()->isAjaxRequest && !isset($_GET['ajax']))
		{
			$this->ajaxEditForm($model, array('name', 'city'));
		}
		else
		{
			if(isset($_POST['Organisation']))
			{
				$model->attributes=$_POST['Organisation'];
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

		$dataProvider=new CActiveDataProvider(Organisation::model()->my(), array(
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
		$model=new Organisation('search');
		$model->unsetAttributes();
		if(isset($_GET['Organisation']))
			$model->attributes=$_GET['Organisation'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function loadModel($id)
	{
		$model=Organisation::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,Yii::t('app', 'The requested page does not exist.'));
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='organisation-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionFindUser()
	{
		$this->autoCompleteFind('User', 'username');
	}	
}
