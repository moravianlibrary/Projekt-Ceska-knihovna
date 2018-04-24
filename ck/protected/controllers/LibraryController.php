<?php

class LibraryController extends Controller
{
	protected function beforeAction($action)
	{
		$params = array('enable'=>true);

		switch ($action->id)
		{
			case 'registration':
				if (!user()->isGuest && !user()->library_id)
				{
					$this->menu = new Menu($this->route);
					return true;
				}
				else
					$params['enable'] = false;
				break;
			case 'clientUpdate':
				if (user()->library_order_placed)
					$params['enable'] = false;
				break;
			case 'view':
				if (user()->library_id)
				{
					$model=Library::model()->my()->findByPk($_GET['id']);
					if($model===null)
						$params['enable'] = false;
				}
				break;
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
		$model=new Library;

		if(isset($_POST['Library']))
		{
			if (user()->checkAccess('BackOffice'))
			{
				$model->organisation_id = (int) $_POST['Library']['organisation_id'];
				$model->user_id = $model->organisation->user_id;
				$model->selfContactPlace = $_POST['selfContactPlace'];
			}
		}

		if (req()->isAjaxRequest && !isset($_GET['ajax']))
		{
			$this->ajaxEditForm($model, array());
		}
		else
		{
			if(isset($_POST['Library']))
			{
				$model->attributes=$_POST['Library'];
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

		if(isset($_POST['Library']))
		{
			if (user()->checkAccess('BackOffice'))
			{
				$model->organisation_id = (int) $_POST['Library']['organisation_id'];
				$model->user_id = $model->organisation->user_id;
				$model->selfContactPlace = $_POST['selfContactPlace'];
			}
		}

		if (req()->isAjaxRequest && !isset($_GET['ajax']))
		{
			$this->ajaxEditForm($model, array());
		}
		else
		{
			if(isset($_POST['Library']))
			{
				$model->attributes=$_POST['Library'];
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
		$criteria->with = array('organisation');

		$dataProvider=new CActiveDataProvider(Library::model()->my(), array(
			'criteria'=>$criteria,
			'sort'=>array(
				'attributes'=>array(
					'*',
					'name'=>array(
						'asc'=>'organisation.name',
						'desc'=>'organisation.name desc',
						),
					),
				),
		));

		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	public function actionAdmin()
	{
		if (user()->checkAccess('PublisherRole')) {
			$publisher = Publisher::model()->my()->find();
			if (!$publisher->confirmation) {
				user()->setFlash('error.updateorg', t('Nejdříve prosím vyplňte Žádost o poskytnutí dotace z projektu Česká knihovna.'));
				$this->redirect(array('/publisher/clientUpdate'));
			}
		}
		$this->setGridViewParams();

		$model=new Library('search');
		$model->unsetAttributes();
		if(isset($_GET['Library']))
			$model->attributes=$_GET['Library'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function loadModel($id)
	{
		$model=Library::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,Yii::t('app', 'The requested page does not exist.'));
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='library-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionRegistration()
	{
		$organisation = Organisation::model()->my()->find();
		if ($organisation === null)
		{
			$organisation=new Organisation('library');
			$organisation->worker_email = user()->name;
		}
		else
		{
			$organisation->scenario = 'library';
			user()->setState('organisation_id', $organisation->id);
		}

		$library = Library::model()->my()->find();
		if ($library === null)
			$library=new Library;
		else
			user()->setState('publisher_id', $library->id);

		if(isset($_POST['Organisation'], $_POST['Library']))
		{
			$organisation->attributes=$_POST['Organisation'];
			$library->attributes=$_POST['Library'];

			$valid=$organisation->validate();
			$valid=$library->validate() && $valid;

			if($valid)
			{
				$organisation->user_id = user()->id;
				$organisation->save(true, false);

				$library->user_id = user()->id;
				$library->organisation_id = $organisation->id;
				$library->save(true, false);

				user()->setState('organisation_id', $organisation->id);
				user()->setState('library_id', $library->id);

				$this->redirect(array('libOrder/sheet'));
			}
			else
			{
				$organisation->viewAttributes();
				$library->viewAttributes();
			}
		}

		$this->render('registration', array(
			'organisation'=>$organisation,
			'library'=>$library,
		));
	}

	public function actionClientUpdate()
	{
		$organisation = Organisation::model()->my()->find();
		$organisation->scenario = 'library';

		$library = Library::model()->my()->find();

		if(isset($_POST['Organisation'], $_POST['Library']))
		{
			$organisation->attributes=$_POST['Organisation'];
			$library->attributes=$_POST['Library'];

			$valid=$organisation->validate();
			$valid=$library->validate() && $valid;

			if($valid)
			{
				$organisation->save(true, false);
				$library->save(true, false);

				$this->redirect(array('libOrder/sheet'));
			}
			else
			{
				$organisation->viewAttributes();
				$library->viewAttributes();
			}
		}

		$this->render('clientUpdate', array(
			'organisation'=>$organisation,
			'library'=>$library,
		));
	}

	public function actionUnBlock($id)
	{
		$model=$this->loadModel($id);
		$model->order_placed = 0;
		$model->save();
		$this->redirect(array('admin'));
	}

	public function actionFindOrganisation()
	{
		$this->autoCompleteFind('Organisation', 'name');
	}

	public function actionOrder($id, $type)
	{
		$library = Library::model()->with(array('organisation'))->findByPk($id);

		$criteria=new CDbCriteria;
		$criteria->with = array('book', 'book.publisher', 'book.publisher.organisation');
		$criteria->together = true;
		$criteria->compare('t.library_id', $id);
		$criteria->compare('t.type', $type);

		$dataProvider=new CActiveDataProvider('LibOrder', array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'organisation.name, book.title',
				),
			'pagination'=>false,
		));

		$this->render('order',array(
			'model'=>$library,
			'dataProvider'=>$dataProvider,
			'title'=>($type == LibOrder::BASIC ? Yii::t('app', 'Basic Order') : Yii::t('app', 'Reserve')),
			'type'=>($type == LibOrder::BASIC ? 'basics' : 'reserves'),
		));
	}
}
