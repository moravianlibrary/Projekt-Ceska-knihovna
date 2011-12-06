<?php

class PublisherController extends Controller
{
	protected function beforeAction($action)
	{
		$params = array('enable'=>true);	
		
		switch ($action->id)
		{
			case 'registration':
				if (!user()->isGuest && !user()->publisher_id)
				{
					$this->menu = new Menu($this->route);
					return true;
				}
				else
					$params['enable'] = false;
				break;
			case 'clientUpdate':
				if (user()->publisher_offer_id)
					$params['enable'] = false;
				break;
			case 'view':
				if (user()->publisher_id)
				{
					$model=Publisher::model()->my()->findByPk($_GET['id']);
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
		$model=new Publisher;

		if(isset($_POST['Publisher']))
		{
			if (user()->checkAccess('BackOffice'))
			{
				$model->organisation_id = (int) $_POST['Publisher']['organisation_id'];
				$model->user_id = $model->organisation->user_id;
			}
		}

		if (req()->isAjaxRequest && !isset($_GET['ajax']))
		{
			$this->ajaxEditForm($model, array('name'));
		}
		else
		{
			if(isset($_POST['Publisher']))
			{
				$model->attributes=$_POST['Publisher'];
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

		if(isset($_POST['Publisher']))
		{
			if (user()->checkAccess('BackOffice'))
			{
				$model->organisation_id = (int) $_POST['Publisher']['organisation_id'];
				$model->user_id = $model->organisation->user_id;
			}
		}

		if (req()->isAjaxRequest && !isset($_GET['ajax']))
		{
			$this->ajaxEditForm($model, array('name'));
		}
		else
		{
			if(isset($_POST['Publisher']))
			{
				$model->attributes=$_POST['Publisher'];
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
		
		$dataProvider=new CActiveDataProvider(Publisher::model()->my(), array(
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
		$model=new Publisher('search');
		$model->unsetAttributes();
		if(isset($_GET['Publisher']))
			$model->attributes=$_GET['Publisher'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function loadModel($id)
	{
		$model=Publisher::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,Yii::t('app', 'The requested page does not exist.'));
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='publisher-form')
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
			$organisation=new Organisation('publisher');
			$organisation->worker_email = user()->name;
		}
		else
		{
			$organisation->scenario = 'publisher';
			user()->setState('organisation_id', $organisation->id);
		}
		
		$publisher = Publisher::model()->my()->find();
		if ($publisher === null)
			$publisher=new Publisher;
		else
			user()->setState('publisher_id', $publisher->id);
		
		if(isset($_POST['Organisation'], $_POST['Publisher']))
		{
			$organisation->attributes=$_POST['Organisation'];
			$publisher->attributes=$_POST['Publisher'];

			$valid=$organisation->validate();
			$valid=$publisher->validate() && $valid;

			if($valid)
			{
				$organisation->user_id = user()->id;
				$organisation->save(true, false);
				
				$publisher->user_id = user()->id;
				$publisher->organisation_id = $organisation->id;
				$publisher->save(true, false);
				
				user()->setState('organisation_id', $organisation->id);
				user()->setState('publisher_id', $publisher->id);

				$this->redirect(array('book/admin'));
			}
			else
			{
				$organisation->viewAttributes();
				$publisher->viewAttributes();
			}
		}
		
		$this->render('registration', array(
			'organisation'=>$organisation,
			'publisher'=>$publisher,
		));
	}
	
	public function actionClientUpdate()
	{
		$organisation = Organisation::model()->my()->find();
		$organisation->scenario = 'publisher';
		
		$publisher = Publisher::model()->my()->find();
		
		if(isset($_POST['Organisation'], $_POST['Publisher']))
		{
			$organisation->attributes=$_POST['Organisation'];
			$publisher->attributes=$_POST['Publisher'];

			$valid=$organisation->validate();
			$valid=$publisher->validate() && $valid;

			if($valid)
			{
				$organisation->save(true, false);
				$publisher->save(true, false);

				$this->redirect(array('book/admin'));
			}
			else
			{
				$organisation->viewAttributes();
				$publisher->viewAttributes();
			}
		}
		
		$this->render('clientUpdate', array(
			'organisation'=>$organisation,
			'publisher'=>$publisher,
		));
	}
	
	public function actionPrintRequest()
	{
		$publisher = Publisher::model()->my()->find();
		if (!user()->publisher_offer_id)
		{
			$transaction = Yii::app()->db->beginTransaction();
			
			$doc = new Document;
			$doc->type = 'publisher_request';
			$doc->mime = 'text/html';
			$doc->save(false);
			$offerID = $doc->id;
			$doc->name = '1-'.$offerID;
			$doc->file_name = '1-'.$offerID.'.html';
			$doc->save(false);

			$publisher->offer_id = $offerID;
			$publisher->save(false);
			
			$transaction->commit();
			
			user()->setState('publisher_offer_id', $offerID);
			$saveFile = true;
		}
		else
		{
			$offerID = user()->publisher_offer_id;
			$saveFile = false;
		}
		
		$scopes = Book::model()->scopesWoAlias();
		$book = Book::model()->updateAll(array('offered'=>1), array('condition'=>$scopes['my']['condition']));
		
		$bookProvider=new CActiveDataProvider(Book::model()->my(), array(
			'sort'=>array(
				'defaultOrder'=>'t.author, t.title',
				),
			'pagination'=>false,
		));
		
		if ($saveFile)
		{
			$this->layout = '//layouts/save';
			$request = $this->render('request',array(
				'publisher'=>$publisher,
				'bookProvider'=>$bookProvider,
				'offerID'=>$offerID,
				'barcode'=>'inline',
				),
				true
			);
			
			$reqFile = cfile()->set('files/documents/'.$doc->file_name);
			$reqFile->contents = $request;
		}
		
		$this->layout = '//layouts/blank';
		$this->render('request',array(
			'publisher'=>$publisher,
			'bookProvider'=>$bookProvider,
			'offerID'=>$offerID,
			'barcode'=>'htm',
			)
		);
	}
	
	public function actionUnBlock($id)
	{
		$model=$this->loadModel($id);
		$model->offer_id = NULL;
		$model->request_date = NULL;
		$model->save();
		
		$scopes = Book::model()->scopesWoAlias();	
		$books = Book::model()->updateAll(array('offered'=>0), array('condition'=>$scopes['thisYear']['condition'].' AND publisher_id='.$model->id));
		
		$this->redirect(array('admin'));
	}
	
	public function actionLetterSelected()
	{
		$this->layout = '//layouts/blank';

		$bookProviders = $publishers = $attributes = array();

		if (isset($_GET['publisher_id']) && is_numeric($_GET['publisher_id']))
			$attributes = array('id'=>$_GET['publisher_id']);
		
		$models = Publisher::model()->with(array('organisation'))->selected()->findAllByAttributes($attributes);
		
		foreach ($models as $publisher)
		{
			$publishers[$publisher->id] = $publisher;

			$criteria=new CDbCriteria;
			$criteria->compare('publisher_id', $publisher->id);
			$criteria->compare('selected', 1);
			
			$bookProviders[$publisher->id]=new CActiveDataProvider('Book', array(
				'criteria'=>$criteria,
				'sort'=>array(
					'defaultOrder'=>'t.author, t.title',
					),
				'pagination'=>false,
			));
		}	

		$this->render('letter_selected',array(
			'publishers'=>$publishers,
			'bookProviders'=>$bookProviders,
		));
	}	

	public function actionLetterUnselected()
	{
		$this->layout = '//layouts/blank';

		$bookProviders = $publishers = $attributes = array();

		if (isset($_GET['publisher_id']) && is_numeric($_GET['publisher_id']))
			$attributes = array('id'=>$_GET['publisher_id']);
		
		$models = Publisher::model()->with(array('organisation'))->unSelected()->findAllByAttributes($attributes);
		
		foreach ($models as $publisher)
		{
			$publishers[$publisher->id] = $publisher;
		}
		
		$selected = Book::model()->selected()->count();
		
		$this->render('letter_unselected',array(
			'publishers'=>$publishers,
			'selected'=>$selected,
		));
	}	

	public function actionFindOrganisation()
	{
		$this->autoCompleteFind('Organisation', 'name');
	}	
}
