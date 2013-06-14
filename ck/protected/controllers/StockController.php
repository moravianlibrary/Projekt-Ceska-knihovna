<?php

class StockController extends Controller
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
		$model=new Stock;

		if (req()->isAjaxRequest && !isset($_GET['ajax']))
		{
			$this->ajaxEditForm($model, array());
		}
		else
		{
			if(isset($_POST['Stock']))
			{
				$model->attributes=$_POST['Stock'];
				$model->user_id = user()->id;
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
			if(isset($_POST['Stock']))
			{
				$model->attributes=$_POST['Stock'];
				if($model->save())
					$this->redirect(array('admin'));
				else
					$model->viewAttributes();
			}

			$criteria = new CDbCriteria;
			$criteria->compare('stock_id', $id);
			$criteria->with = array('lib_order', 'lib_order.library', 'lib_order.library.organisation'=>array('alias'=>'lib_org'), 'pub_order', 'pub_order.book', 'pub_order.book.publisher', 'pub_order.book.publisher.organisation'=>array('alias'=>'pub_org'));

			$stockActivityProvider = new CActiveDataProvider('StockActivity', array(
				'criteria'=>$criteria,
				'sort'=>array(
					'defaultOrder'=>'t.date DESC, t.id DESC',
					'attributes'=>array(
						'*',
						'lib_order.library.name'=>array(
							'asc'=>'lib_org.name',
							'desc'=>'lib_org.name DESC',
							),
						'pub_order.book.publisher.name'=>array(
							'asc'=>'pub_org.name',
							'desc'=>'pub_org.name DESC',
							),
						),
					),
				'pagination'=>array(
					'pageSize'=>20,
				),
			));

			$this->render('update',array(
				'model'=>$model,
				'stockActivityProvider'=>$stockActivityProvider,
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
		$criteria->compare('t.count','>0');
		$criteria->with = array('book');
		$criteria->order = 'book.selected_order';

		$dataProvider=new CActiveDataProvider('Stock', array(
			'criteria'=>$criteria,
		));
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	public function actionAdmin()
	{
		$model=new Stock('search');
		$model->unsetAttributes();
		if(isset($_GET['Stock']))
			$model->attributes=$_GET['Stock'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function loadModel($id)
	{
		$model=Stock::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,Yii::t('app', 'The requested page does not exist.'));
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='stock-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionBillOfDelivery()
	{
		$this->layout = '//layouts/blank';

		$dataProviders = array();
		$despatch = null;

		$criteria=new CDbCriteria;
		if (isset($_GET['library_id']) && is_numeric($_GET['library_id']))
			$criteria->compare('orders.library_id', $_GET['library_id']);
		else
			$criteria->compare('orders.library_id', '>0');

		if (isset($_GET['contact_place_id']) && is_numeric($_GET['contact_place_id']))
			$criteria->compare('t.contact_place_id', $_GET['contact_place_id']);

		if (!isset($_GET['date_from']) || !DT::testIso(DT::toIso($_GET['date_from'])))
			$dateFrom = DT::isoToday();
		else
			$dateFrom = DT::toIso($_GET['date_from']);

		if (!isset($_GET['date_to']) || !DT::testIso(DT::toIso($_GET['date_to'])))
			$dateTo = DT::isoToday();
		else
			$dateTo = DT::toIso($_GET['date_to']);
		$criteria->compare('stock_activities.date', '>='.$dateFrom);
		$criteria->compare('stock_activities.date', '<='.$dateTo);

		$criteria->order = 'book.selected_order';

		if (!isset($_GET['bill_count']) || !is_numeric($_GET['bill_count']))
			$billCount = 3;
		else
			$billCount = $_GET['bill_count'];

		if (isset($_GET['print_address']) && $_GET['print_address'] != '1')
			$printAddress = false;
		else
			$printAddress = true;

		$libraries = Library::model()->with(array('contact_place', 'orders'=>array('joinType'=>'INNER JOIN'), 'orders.stock_activities'=>array('joinType'=>'INNER JOIN'), 'orders.stock_activities.stock', 'orders.stock_activities.stock.book', 'orders.stock_activities.stock.book.publisher', 'orders.stock_activities.stock.book.publisher.organisation'=>array('alias'=>'pub_org'), 'organisation'=>array('alias'=>'lib_org'), 'contact_place.organisation'=>array('alias'=>'cont_org')))->together()->findAll($criteria);

		if (isset($_GET['generate']) && $_GET['generate'] == t('Generate Despatch'))
		{
			$despatch = new Despatch;
			if (isset($_GET['library_id']) && is_numeric($_GET['library_id']))
				$despatch->library_id = $_GET['library_id'];
			if (isset($_GET['contact_place_id']) && is_numeric($_GET['contact_place_id']))
				$despatch->contact_place_id = $_GET['contact_place_id'];
			$despatch->date_from = DT::toLoc($dateFrom);
			$despatch->date_to = DT::toLoc($dateTo);
			$despatch->bill_count = $billCount;
			$despatch->print_address = ($printAddress ? 1 : 0);

			ylog($despatch->attributes);

			$despatch->save();

			foreach ($libraries as $library)
			{
				foreach ($library->orders as $order)
				{
					foreach ($order->stock_activities as $stockActivity)
					{
						$dhsa = new DespatchHasStockActivity;
						$dhsa->despatch_id = $despatch->id;
						$dhsa->stock_activity_id = $stockActivity->id;
						$dhsa->save();
					}
				}
			}
		}

		$this->render('billOfDelivery',array(
			'libraries'=>$libraries,
			'dateFrom'=>$dateFrom,
			'dateTo'=>$dateTo,
			'billCount'=>$billCount,
			'printAddress'=>$printAddress,
			'despatch'=>$despatch,
		));
	}

	public function actionSaveDun()
	{

	}

	public function actionFindBook()
	{
		$this->autoCompleteFind('Book', 'title');
	}
}
