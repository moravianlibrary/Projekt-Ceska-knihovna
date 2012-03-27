<?php

class LibOrderController extends Controller
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
		$model=new LibOrder;

		if (req()->isAjaxRequest && !isset($_GET['ajax']))
		{
			$this->ajaxEditForm($model, array());
		}
		else
		{
			if(isset($_POST['LibOrder']))
			{
				$model->attributes=$_POST['LibOrder'];
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
			if(isset($_POST['LibOrder']))
			{
				$model->attributes=$_POST['LibOrder'];
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

		$dataProvider=new CActiveDataProvider(LibOrder::model()->my(), array(
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
		$model=new LibOrder('search');
		$model->unsetAttributes();
		if(isset($_GET['LibOrder']))
			$model->attributes=$_GET['LibOrder'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function loadModel($id)
	{
		$model=LibOrder::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,Yii::t('app', 'The requested page does not exist.'));
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='lib-order-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	protected function beforeAction($action)
    {
		$params = array();
		$params['order_placed'] = user()->library_order_placed;
		if (parent::beforeAction($action, $params))
			return true;
		else
			return false;
	}
	
	public function actionSheet()
	{
		if (user()->library_id)
		{
			$library = Library::model()->my()->find();
			if ($library->organisation->city == '')
			{
				user()->setFlash('error.updateorg', t('Nejdříve prosím vyplňte Žádost o poskytnutí dotace z projektu Česká knihovna.'));
				$this->redirect(array('/library/clientUpdate'));
			}
		}
		else 
			$library = null;
		
		$criteria=new CDbCriteria;
		$criteria->with = array('publisher', 'publisher.organisation', 'basic', 'reserve');
		$criteria->compare('t.selected', '1');
		$criteria->compare('project_year', param('projectYear'));
		$criteria->together = true;
		
		$dataProvider=new CActiveDataProvider('Book', array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'organisation.name, t.title',
				'attributes'=>array(
					'*',
					'name'=>array(
						'asc'=>'organisation.name',
						'desc'=>'organisation.name desc',
						),
					),
				'multiSort'=>true,
				),
			'pagination'=>array('pageSize'=>100,),
		));
				
		$this->render('sheet',array(
			'dataProvider'=>$dataProvider,
			'library'=>$library,
			'basicPrice'=>$library->basicPrice,
			'reserveCount'=>$library->reserveCount,
		));
	}

	public function actionSaveSheet($id)
	{
		if(Yii::app()->request->isAjaxRequest && !user()->library_order_placed)
		{
			if ($id == '')
			{
				$model=new LibOrder;
				$model->user_id = user()->id;
				$model->library_id = user()->library_id;
				$model->date = DT::locToday();
			}
			else $model=$this->loadModel($id);
			$model->attributes = $_POST['LibOrder'];
			
			if (is_numeric($model->count) && $model->count == 0)
			{
				if ($model->delete())
				{
					$status = 'OK';
					$msg = '';
				}
				else
				{
					$status = 'ERR';
					$msg = t('Record cannot be deleted.');
					foreach ($model->getErrors() as $attr=>$errs)
						foreach ($errs as $err)
							$msg .= '<br />'.$err;
					foreach (user()->getFlashes() as $name=>$flash)
						$msg .= '<br />'.$flash;
				}
			}
			else
			{				
				if ($model->save())
				{
					$status = 'OK';
					$msg = '';					
				}
				else
				{
					$status = 'ERR';
					$msg = t('Record cannot be saved.');
					foreach ($model->getErrors() as $attr=>$errs)
						foreach ($errs as $err)
							$msg .= '<br />'.$err;
					foreach (user()->getFlashes() as $name=>$flash)
						$msg .= '<br />'.$flash;
				}
			}
			
			$model=Book::model()->thisYear()->selected()->with(array('publisher', 'publisher.organisation', 'basic', 'reserve'))->findByPk($_POST['LibOrder']['book_id']);
			
			$library = Library::model()->my()->find();
			
			$this->ajaxEditFormNoScript();
			echo CJSON::encode(array('status'=>$status, 'val'=>$this->renderPartial('_sheet_item', array('data'=>$model), true, true), 'model'=>array(), 'msg'=>$msg, 'total'=>$this->renderPartial('_order_total', array('basicPrice'=>$library->basicPrice, 'reserveCount'=>$library->reserveCount), true)));
			Yii::app()->end();
		}
		else
			throw new CHttpException(400,Yii::t('app', 'Invalid request. Please do not repeat this request again.'));
	}
	
	public function actionPlaceOrder()
	{
		if (!user()->library_order_placed)
		{
			$library = Library::model()->updateByPk(user()->library_id, array('order_placed'=>1));
			user()->setState('library_order_placed', 1);
			user()->setFlash('success.placeorder', t('Objednávka byla úspěšně vygenerována. Vytisknete ji pomocí položky &quot;Objednávka&quot; v pravém menu.'));
		}
		else
			user()->setFlash('error.placeorder', t('Nelze opětovně vytvořit objednávku.'));
		$this->redirect(array('sheet'));
	}
	
	public function actionPrintOrder()
	{
		$this->layout = '//layouts/blank';

		$criteria=new CDbCriteria;
		$criteria->with = array('book', 'book.publisher', 'book.publisher.organisation');
		$criteria->together = true;
		
		$library = Library::model()->with(array('organisation'))->my()->find();
		
		$b = new LibOrder;
		$basicProvider=new CActiveDataProvider($b->my()->basic(), array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'book.selected_order',
				),
			'pagination'=>false,
		));

		$r = new LibOrder;
		$reserveProvider=new CActiveDataProvider($r->my()->reserve(), array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'book.selected_order',
				),
			'pagination'=>false,
		));

		$this->render('order',array(
			'model'=>$library,
			'basicProvider'=>$basicProvider,
			'reserveProvider'=>$reserveProvider,
		));
	}
	
	public function actionGetReserves()
	{
		$reserves = array();
		foreach (db()->createCommand("SELECT SUM({{lib_order}}.count) AS sum_count, book_id, title, author, SUM({{lib_order}}.count)*project_price AS total_price, publisher_id, name FROM (((({{lib_order}} LEFT JOIN {{library}} ON {{lib_order}}.library_id={{library}}.id) LEFT JOIN {{book}} ON {{lib_order}}.book_id={{book}}.id) LEFT JOIN {{publisher}} ON {{book}}.publisher_id={{publisher}}.id) LEFT JOIN {{organisation}} ON {{publisher}}.organisation_id={{organisation}}.id) WHERE {{lib_order}}.type='".LibOrder::RESERVE."' AND {{library}}.order_placed=1 GROUP BY book_id ORDER BY sum_count DESC")->queryAll() as $rs)
		{
			if (db()->createCommand("SELECT SUM(delivered) AS sum_delivered FROM {{pub_order}} WHERE book_id={$rs["book_id"]}")->queryScalar()) $rs['delivered'] = '1'; else $rs['delivered'] = '0';
			if (db()->createCommand("SELECT SUM({{pub_order}}.count-{{pub_order}}.delivered) AS sum_remaining FROM ({{pub_order}} LEFT JOIN {{book}} ON {{pub_order}}.book_id={{book}}.id) WHERE publisher_id={$rs["publisher_id"]}")->queryScalar()) $rs['remaining'] = '1'; else $rs['remaining'] = '0';
			$reserves[] = $rs;
		}

		$this->render('getReserves',array(
			'reserves'=>$reserves,
		));
	}

	public function actionFindBook()
	{
		$this->autoCompleteFind('Book', 'title');
	}	
	
	public function actionFindLibrary()
	{
		$this->autoCompleteFind('Library', 'name', null, array('with'=>array('organisation')));
	}
}
