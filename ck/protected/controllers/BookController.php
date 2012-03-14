<?php

class BookController extends Controller
{
	protected function beforeAction($action)
    {
		$params = array('enable'=>true);	

		if ($action->id == 'view')
		{
			if (user()->publisher_id)
			{
				$model=Book::model()->my()->findByPk($_GET['id']);
				if($model===null)
					$params['enable'] = false;
			}
			elseif (user()->library_id)
				{
					$model=Book::model()->selected()->findByPk($_GET['id']);
					if($model===null)
						$params['enable'] = false;
				}
		}

		if (user()->publisher_id && (param('pubBookDate') < DT::isoToday() || user()->publisher_offer_id))
		{
			$error = false;
			switch ($action->id)
			{
				case 'create': case 'update':
					$error = true;
					break;
				 case 'delete':	
					if(!isset($_GET['ajax']))
						$error = true;
					else
					{
						echo CJSON::encode(array('success'=>'', 'error'=>t('V nabídce titulů již nelze provádět změny.'), 'notice'=>''));
						Yii::app()->end();
					}
					break;
			}
			if ($error)
			{
				user()->setFlash('error.updatebook', t('V nabídce titulů již nelze provádět změny.'));
				$this->redirect(array('admin'));
				Yii::app()->end();			
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
		$model=new Book;

		if(isset($_POST['Book']))
		{
			if (user()->checkAccess('BackOffice'))
			{
				$model->publisher_id = (int) $_POST['Book']['publisher_id'];
				$model->user_id = $model->publisher->user_id;
			}
			else
			{
				$model->publisher_id = user()->publisher_id;
				$model->user_id = user()->id;
				$model->project_price = $_POST['Book']['offer_price'];
			}
			$model->project_year = param('projectYear');
		}

		if (req()->isAjaxRequest && !isset($_GET['ajax']))
		{
			$this->ajaxEditForm($model, array());
		}
		else
		{
			if(isset($_POST['Book']))
			{
				$model->attributes=$_POST['Book'];
				if($model->save())
					$this->redirect(array('create'));
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
		
		if(isset($_POST['Book']))
		{
			if (user()->checkAccess('BackOffice'))
			{
				$model->publisher_id = (int) $_POST['Book']['publisher_id'];
				$model->user_id = $model->publisher->user_id;
			}
			else
			{
				$model->project_price = $_POST['Book']['offer_price'];
			}
		}

		if (req()->isAjaxRequest && !isset($_GET['ajax']))
		{
			$this->ajaxEditForm($model, array());
		}
		else
		{
			if(isset($_POST['Book']))
			{
				$model->attributes=$_POST['Book'];
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
		$criteria->with = array('publisher','publisher.organisation');
		
		$dataProvider=new CActiveDataProvider(Book::model()->my(), array(
			'criteria'=>$criteria,
			'sort'=>array(
				'attributes'=>array(
					'*',
					),
				),
		));
		
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
			'separator'=>'',
		));
	}

	public function actionAdmin()
	{
		$this->setGridViewParams();

		$model=new Book('search');
		$model->unsetAttributes();
		if(isset($_GET['Book']))
			$model->attributes=$_GET['Book'];
			
		if (user()->publisher_id)
			$publisher = Publisher::model()->findByPk(user()->publisher_id);
		else 
			$publisher = null;

		$this->render('admin',array(
			'model'=>$model,
			'publisher'=>$publisher,
		));
	}
	
	public function loadModel($id)
	{
		$model=Book::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,Yii::t('app', 'The requested page does not exist.'));
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='book-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionGeneratePoll()
	{
		$sp = Yii::app()->getStatePersister();
		$state = $sp->load();
		if (@$state['poll_generated'])
		{
			user()->setFlash('error.generatepoll', t('Seznam byl již dříve vygenerován.'));
		}
		else
		{	
			foreach (db()->createCommand("SELECT SUM(points) AS sum_points, book_id FROM {{voting}} WHERE type='".Voting::RATING."' GROUP BY book_id")->queryAll() as $rs)
			{
				$book = Book::model()->updateByPk($rs['book_id'], array('points'=>$rs['sum_points']));
			}
			$state['poll_generated'] = true;
			$sp->save($state);
			user()->setFlash('success.generateselected', t('Seznam byl úspešně vygenerován.'));
			/*
			if (!param('pointsMinLimit') || !param('selectedLimit'))
			{
				user()->setFlash('error.generatepoll', t('Nelze vygenerovat seznam, protože nejsou nastaveny parametry.'));
				// $books = Book::model()->thisYear()->updateAll(array('selected'=>null, 'points'=>null));
			}
			else
			{
				foreach (db()->createCommand("SELECT SUM(points) AS sum_points, book_id FROM {{voting}} WHERE type='".Voting::RATING."' GROUP BY book_id")->queryAll() as $rs)
				{
					$book = Book::model()->updateByPk($rs['book_id'], array('points'=>$rs['sum_points']));
				}

				$scopes = Book::model()->scopesWoAlias();
				
				$books = Book::model()->updateAll(array('selected'=>1), array('condition'=>$scopes['thisYear']['condition'].' AND '.$scopes['accepted']['condition'].' AND points>='.param('pointsMinLimit'), 'order'=>'points DESC', 'limit'=>param('selectedLimit')));

				$books = Book::model()->updateAll(array('selected'=>0), array('condition'=>$scopes['thisYear']['condition'].' AND '.$scopes['accepted']['condition'].' AND '.$scopes['unSelected']['condition']));
				
				$state['poll_generated'] = true;
				$sp->save($state);
				user()->setFlash('success.generateselected', t('Seznam byl úspešně vygenerován.'));
			}
			*/
		}
		$this->redirect(array('admin'));
	}
	
	public function actionPoll()
	{
		$criteria=new CDbCriteria;
		$criteria->with = array('publisher', 'publisher.organisation', 'poll');
		$criteria->compare('t.status', '0');
		$criteria->compare('t.project_year', param('projectYear'));
		$criteria->together = true;

		$dataProvider=new CActiveDataProvider('Book', array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'t.points DESC, organisation.name, t.title',
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
		
	public function actionSavePoll($id)
	{
		$book = $this->loadModel($id);
		$book->scenario = 'poll';
		$book->attributes = $_POST['Book'];
		if ($book->save(true, true, array('votes_yes', 'votes_no', 'votes_withheld', 'selected')))
		{
			$status = 'OK';
			$msg = '';
		}
		else
		{
			$status = 'ERR';
			$msg = t('Record cannot be saved.');
			foreach ($book->getErrors() as $attr=>$errs)
				foreach ($errs as $err)
					$msg .= '<br />'.$err;
			foreach (user()->getFlashes() as $name=>$flash)
				$msg .= '<br />'.$flash;
		}
		$book->scenario = 'update';
		
		$this->ajaxEditFormNoScript();
		echo CJSON::encode(array('status'=>$status, 'val'=>$this->renderPartial('_poll', array('data'=>$book), true, true), 'model'=>array(), 'msg'=>$msg));
		Yii::app()->end();
	}
		
	public function actionSavePollSelect($id)
	{
		$book = $this->loadModel($id);
		$book->scenario = 'poll_select';
		$book->attributes = $_POST['Book'];
		if ($book->save(true, true, array('selected')))
		{
			$status = 'OK';
			$msg = '';
		}
		else
		{
			$status = 'ERR';
			$msg = t('Record cannot be saved.');
			foreach ($book->getErrors() as $attr=>$errs)
				foreach ($errs as $err)
					$msg .= '<br />'.$err;
			foreach (user()->getFlashes() as $name=>$flash)
				$msg .= '<br />'.$flash;
		}
		$book->scenario = 'update';
		
		$this->ajaxEditFormNoScript();
		echo CJSON::encode(array('status'=>$status, 'val'=>$this->renderPartial('_poll', array('data'=>$book), true, true), 'model'=>array(), 'msg'=>$msg));
		Yii::app()->end();
	}
	
	public function actionGenerateSelected()
	{
		$sp = Yii::app()->getStatePersister();
		$state = $sp->load();
		if (@$state['selected_generated'])
		{
			user()->setFlash('error.generateselected', t('Seznam byl již dříve potvrzen.'));
		}
		else
		{
			$selected = Book::model()->thisYear()->accepted()->selected()->findAll();
			foreach ($selected as $selBook)
				$publisher = Publisher::model()->updateByPk($selBook->publisher_id, array('selected'=>1));

			$state['selected_generated'] = true;
			$sp->save($state);
		}
		$this->redirect(array('admin'));
	}
	
	/*
	public function actionGenerateSelected()
	{
		$sp = Yii::app()->getStatePersister();
		$state = $sp->load();
		if (@$state['selected_generated'])
		{
			user()->setFlash('error.generateselected', t('Seznam byl již dříve vygenerován.'));
		}
		else
		{
			if (!param('selectedLimit'))
			{
				user()->setFlash('error.generateselected', t('Nelze vygenerovat seznam, protože nejsou nastaveny parametry.'));
			}
			else
			{
				$scopes = Book::model()->scopesWoAlias();
				
				$selected = Book::model()->thisYear()->accepted()->selected()->count();

				$books = Book::model()->updateAll(array('selected'=>1), array('condition'=>$scopes['thisYear']['condition'].' AND '.$scopes['accepted']['condition'].' AND '.$scopes['notSelected']['condition'].' AND votes_yes > (votes_no + votes_withheld)', 'order'=>'votes_yes DESC, votes_withheld DESC', 'limit'=>(param('selectedLimit')-$selected)));
				
				$selected = Book::model()->thisYear()->accepted()->selected()->findAll();
				foreach ($selected as $selBook)
					$publisher = Publisher::model()->updateByPk($selBook->publisher_id, array('selected'=>1));

				$state['selected_generated'] = true;
				$sp->save($state);
				user()->setFlash('success.generateselected', t('Seznam byl úspešně vygenerován.'));
			}
		}
		$this->redirect(array('admin'));
	}
	*/
	
	public function actionCheckHistory()
	{
		$model=new Book('search');
		$model->unsetAttributes();
		if(isset($_GET['Book']))
			$model->attributes=$_GET['Book'];

		$this->ajaxEditFormNoScript();
		$this->renderPartial('_check',array(
			'model'=>$model,
			'book_id'=>$_GET['book_id'],
		));		
	}
	
	public function actionPrintIndex()
	{
		$this->layout = '//layouts/blank';

		$criteria=new CDbCriteria;
		$criteria->with = array('publisher','publisher.organisation');
		
		$dataProvider=new CActiveDataProvider(Book::model()->my(), array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'organisation.name ASC, t.title ASC',
				'attributes'=>array(
					'*',
					),
				),
			'pagination'=>false,
		));
		
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
			'separator'=>'<hr />',
		));
	}

	
	public function actionFindPublisher()
	{
		$this->autoCompleteFind('Publisher', 'name', null, array('with'=>array('organisation')));
	}
}
