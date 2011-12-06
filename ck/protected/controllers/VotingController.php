<?php

class VotingController extends Controller
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
		$model=new Voting;

		if (req()->isAjaxRequest && !isset($_GET['ajax']))
		{
			$this->ajaxEditForm($model, array());
		}
		else
		{
			if(isset($_POST['Voting']))
			{
				$model->attributes=$_POST['Voting'];
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
			$this->ajaxEditForm($model, array());
		}
		else
		{		
			if(isset($_POST['Voting']))
			{
				$model->attributes=$_POST['Voting'];
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
		$dataProvider=new CActiveDataProvider(Voting::model()->my());
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	public function actionAdmin()
	{
		$model=new Voting('search');
		$model->unsetAttributes();
		if(isset($_GET['Voting']))
			$model->attributes=$_GET['Voting'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function loadModel($id)
	{
		$model=Voting::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,Yii::t('app', 'The requested page does not exist.'));
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='voting-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionRating()
	{
		$this->layout = '//layouts/column1';

		$dataProvider=new CActiveDataProvider(Book::model()->thisYear()->accepted()->unSelected()->with(array('publisher', 'publisher.organisation', 'rating'))->together(), array(
			'sort'=>array(
				'defaultOrder'=>'organisation.name, t.title',
				'attributes'=>array(
					'*',
					'name'=>array(
						'asc'=>'organisation.name',
						'desc'=>'organisation.name desc',
						),
					),
				),
			'pagination'=>array('pageSize'=>100,),
		));
		
		$this->render('rating',array(
			'dataProvider'=>$dataProvider,
		));
	}

	public function actionSaveRating($book_id)
	{
		if (req()->isAjaxRequest)
		{
			$status = 'ERR'; $msg = t('Record cannot be saved.');
			
			$book = Book::model()->thisYear()->accepted()->unSelected()->findByPk($book_id);
			if ($book === null)
				throw new CHttpException(400,Yii::t('app', 'Invalid request. Please do not repeat this request again.'));
			
			if(isset($_POST['Voting']))
			{
				$_POST['Voting']['book_id'] = $book_id;
				$model = Voting::model()->my()->findByAttributes(array('book_id'=>$book_id, 'type'=>Voting::RATING));
				if ($model === null)
				{
					$model = new Voting();
					$model->user_id = user()->id;
					$model->attributes = $_POST['Voting'];
				}
				else
				{
					$model->points = $_POST['Voting']['points'];
				}
				
				if ($model->save())
				{
					$status = 'OK';
					$msg = '';					
				}
				else
				{
					foreach ($model->getErrors() as $attr=>$errs)
						foreach ($errs as $err)
							$msg .= '<br />'.$err;
					foreach (user()->getFlashes() as $name=>$flash)
						$msg .= '<br />'.$flash;
				}
			}
		
			$this->ajaxEditFormNoScript();
			echo CJSON::encode(array('status'=>$status, 'val'=>$this->renderPartial('_rating', array('data'=>$book), true, true), 'model'=>array(), 'msg'=>$msg));
			Yii::app()->end();
		}
		else
			throw new CHttpException(400,Yii::t('app', 'Invalid request. Please do not repeat this request again.'));
	}
	
	public function actionRatingResults()
	{
		$this->layout = '//layouts/column1';

		$dataProvider=new CActiveDataProvider(Book::model()->thisYear()->accepted()->with(array('publisher', 'publisher.organisation'))->together(), array(
			'sort'=>array(
				'defaultOrder'=>'t.points DESC, organisation.name, t.title',
				),
			'pagination'=>array('pageSize'=>1000,),
		));
		
		$this->render('rating_results',array(
			'dataProvider'=>$dataProvider,
		));
	}	
	
	public function actionPoll()
	{
		$this->layout = '//layouts/column1';

		$dataProvider=new CActiveDataProvider(Book::model()->thisYear()->accepted()->notSelected()->with(array('publisher', 'publisher.organisation', 'poll'))->together(), array(
			'sort'=>array(
				'defaultOrder'=>'organisation.name, t.title',
				'attributes'=>array(
					'*',
					'name'=>array(
						'asc'=>'organisation.name',
						'desc'=>'organisation.name desc',
						),
					),
				),
			'pagination'=>array('pageSize'=>100,),
		));
		
		$this->render('poll',array(
			'dataProvider'=>$dataProvider,
		));
	}

	public function actionSavePoll($book_id)
	{
		if (req()->isAjaxRequest)
		{
			$status = 'ERR'; $msg = t('Record cannot be saved.');
			
			$book = Book::model()->thisYear()->accepted()->notSelected()->findByPk($book_id);
			if ($book === null)
				throw new CHttpException(400,Yii::t('app', 'Invalid request. Please do not repeat this request again.'));
			
			if(isset($_POST['Voting']))
			{
				$_POST['Voting']['book_id'] = $book_id;
				$model = Voting::model()->my()->findByAttributes(array('book_id'=>$book_id, 'type'=>Voting::POLL));
				if ($model === null)
				{
					$model = new Voting();
					$model->user_id = user()->id;
					$model->attributes = $_POST['Voting'];
				}
				else
				{
					$model->points = $_POST['Voting']['points'];
				}
				
				if ($model->save())
				{
					$status = 'OK';
					$msg = '';					
				}
				else
				{
					foreach ($model->getErrors() as $attr=>$errs)
						foreach ($errs as $err)
							$msg .= '<br />'.$err;
					foreach (user()->getFlashes() as $name=>$flash)
						$msg .= '<br />'.$flash;
				}
			}
		
			$this->ajaxEditFormNoScript();
			echo CJSON::encode(array('status'=>$status, 'val'=>$this->renderPartial('_poll', array('data'=>$book), true, true), 'model'=>array(), 'msg'=>$msg));
			Yii::app()->end();
		}
		else
			throw new CHttpException(400,Yii::t('app', 'Invalid request. Please do not repeat this request again.'));
	}
	
	public function actionFindUser()
	{
		$this->autoCompleteFind('User', 'username');
	}	
	
	public function actionFindBook()
	{
		$this->autoCompleteFind('Book', 'title');
	}	
}
