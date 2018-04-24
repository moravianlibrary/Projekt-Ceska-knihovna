<?php

class PubOrderController extends Controller
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
		$model=new PubOrder;

		if (req()->isAjaxRequest && !isset($_GET['ajax']))
		{
			$this->ajaxEditForm($model, array());
		}
		else
		{
			if(isset($_POST['PubOrder']))
			{
				$model->attributes=$_POST['PubOrder'];
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
			if(isset($_POST['PubOrder']))
			{
				$model->attributes=$_POST['PubOrder'];
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
		$dataProvider=new CActiveDataProvider('PubOrder');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	public function actionAdmin()
	{
		$model=new PubOrder('search');
		$model->unsetAttributes();
		if(isset($_GET['PubOrder']))
			$model->attributes=$_GET['PubOrder'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function loadModel($id)
	{
		$model=PubOrder::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,Yii::t('app', 'The requested page does not exist.'));
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='pub-order-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionPlaceOrder()
	{
		$sp = Yii::app()->getStatePersister();
		$state = $sp->load();
		if (@$state['puborder_generated'])
		{
			user()->setFlash('error.generate_puborder', 'Objednávky byly již dříve vygenerovány.');
		}
		else
		{
			$books = Book::model()->thisYear()->selected()->findAll();
			foreach ($books as $book)
			{
				$basics = $book->sumLibBasics;
				if ($basics > 0)
				{
					$pubOrder = new PubOrder();
					$pubOrder->user_id = user()->id;
					$pubOrder->book_id = $book->id;
					$pubOrder->date = DT::locToday();
					$pubOrder->count = $basics;
					$pubOrder->price = $book->project_price;
					$pubOrder->type = PubOrder::BASIC;
					$pubOrder->save();
				}
			}
			user()->setFlash('success.generate_puborder', 'Objednávky byly úspěšně vygenerovány.');
			$state['puborder_generated'] = true;
			$sp->save($state);
		}
		$this->redirect(array('admin'));
	}

	public function actionPlaceOrderReserves()
	{
		$sp = Yii::app()->getStatePersister();
		$state = $sp->load();
		if (@$state['puborder_reserves_generated'])
		{
			user()->setFlash('error.generate_puborder', 'Objednávky byly již dříve vygenerovány.');
		}
		else
		{
			$books = Book::model()->selected()->findAll(); // ->thisYear()
			foreach ($books as $book)
			{
				$reserves = $book->sumLibReserves;
				if ($reserves > 0)
				{
					$pubOrder = new PubOrder();
					$pubOrder->user_id = user()->id;
					$pubOrder->book_id = $book->id;
					$pubOrder->date = DT::locToday();
					$pubOrder->count = $reserves;
					$pubOrder->price = $book->project_price;
					$pubOrder->type = PubOrder::RESERVE;
					$pubOrder->save();
				}
			}
			user()->setFlash('success.generate_puborder', 'Objednávky byly úspěšně vygenerovány.');
			$state['puborder_reserves_generated'] = true;
			$sp->save($state);
		}
		$this->redirect(array('admin'));
	}

	public function actionPrintOrder()
	{
		$this->layout = '//layouts/blank';

		$orderProviders = $publishers = $attributes = array();

		$criteria=new CDbCriteria;
		if (isset($_GET['publisher_id']) && is_numeric($_GET['publisher_id']))
			$criteria->compare('t.id', $_GET['publisher_id']);
		$criteria->order = 'organisation.name';

		$models = Publisher::model()->with(array('organisation'))->orderPlaced()->findAll($criteria, array('order'=>'name'));

		foreach ($models as $publisher)
		{
			$criteria=new CDbCriteria;
			if (isset($_GET['puborder_id']))
				$criteria->compare('t.id', $_GET['puborder_id']);
			if (isset($_GET['puborder_type']))
				$criteria->compare('t.type', $_GET['puborder_type']);
			$criteria->compare('book.publisher_id', $publisher->id);
			$criteria->with = array('book');
			$criteria->together = true;

			$pubOrders = new CActiveDataProvider('PubOrder', array(
				'criteria'=>$criteria,
				'sort'=>array(
					'defaultOrder'=>'book.selected_order',
					),
				'pagination'=>false,
			));

			if ($pubOrders->totalItemCount)
			{
				$publishers[$publisher->id] = $publisher;
				$orderProviders[$publisher->id] = $pubOrders;
			}
		}

		$this->render('order',array(
			'publishers'=>$publishers,
			'orderProviders'=>$orderProviders,
			//'useFilter'=>(!isset($_GET['puborder_id'])),
			'useFilter'=>true,
			'type'=>@$_GET["puborder_type"],
		));
	}

	public function actionDun()
	{
		$criteria=new CDbCriteria;
		$criteria->compare('(pub_orders.count-pub_orders.delivered)', '>0');

		$publishers = Publisher::model()->with(array('organisation', 'books'=>array('joinType'=>'INNER JOIN'),'books.pub_orders'=>array('joinType'=>'INNER JOIN')))->findAll($criteria);

		$this->render('dun',array(
			'publishers'=>$publishers,
		));
	}

	public function actionSaveDun($publisher_id)
	{
		if(Yii::app()->request->isAjaxRequest)
		{
			$model=Publisher::model()->findByPk($publisher_id);
			$model->dun_date = DT::isoToday();
			$model->save();

			$this->ajaxEditFormNoScript();
			echo DT::locToday();
			Yii::app()->end();
		}
		else
			throw new CHttpException(400,Yii::t('app', 'Invalid request. Please do not repeat this request again.'));
	}

	public function actionFindBook()
	{
		$this->autoCompleteFind('Book', 'title', 'title', array(), array('project_price'));
	}
}
