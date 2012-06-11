<?php

class StockActivityController extends Controller
{
	/*
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

	public function actionCreate($stock_id)
	{
		$model=new StockActivity;

		if(isset($_POST['StockActivity']))
		{
			$model->stock_id = (int) $stock_id;
			$model->user_id = user()->id;
		}

		if (req()->isAjaxRequest && !isset($_GET['ajax']))
		{
			$this->ajaxEditForm($model, array('stock.count'));
		}
		else
		{
			if(isset($_POST['StockActivity']))
			{
				$model->attributes=$_POST['StockActivity'];
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
	*/

	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		if (req()->isAjaxRequest && !isset($_GET['ajax']))
		{
			$this->ajaxEditForm($model, array('stock.count'));
		}
		else
		{
			if(isset($_POST['StockActivity']))
			{
				$model->attributes=$_POST['StockActivity'];
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
			$model = $this->loadModel($id);
			$stock_id = $model->stock_id;
			$model->delete();

			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
			else
			{
				$stock = Stock::model()->findByPk($stock_id);
				echo CJSON::encode(array('model'=>array('stock'=>array('count'=>$stock->count)), 'success'=>(Yii::app()->getUser()->hasFlash('success.deleterecord')?Yii::app()->getUser()->getFlash('success.deleterecord'):''), 'error'=>(Yii::app()->getUser()->hasFlash('error.deleterecord')?Yii::app()->getUser()->getFlash('error.deleterecord'):''), 'notice'=>(Yii::app()->getUser()->hasFlash('notice.deleterecord')?Yii::app()->getUser()->getFlash('notice.deleterecord'):'')));
				Yii::app()->end();
			}
		}
		else
			throw new CHttpException(400,Yii::t('app', 'Invalid request. Please do not repeat this request again.'));
	}

	/*
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('StockActivity');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	public function actionAdmin()
	{
		$model=new StockActivity('search');
		$model->unsetAttributes();
		if(isset($_GET['StockActivity']))
			$model->attributes=$_GET['StockActivity'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	*/

	public function loadModel($id)
	{
		$model=StockActivity::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,Yii::t('app', 'The requested page does not exist.'));
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='stock-activity-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionPubActivity()
	{
		$criteria=new CDbCriteria;

		if (isset($_GET['publisher_id']) && is_numeric($_GET['publisher_id']))
			$criteria->compare('book.publisher_id', $_GET['publisher_id']);
		if (isset($_GET['book_id']) && is_numeric($_GET['book_id']))
			$criteria->compare('t.book_id', $_GET['book_id']);
		if (isset($_GET['type']) && $_GET['type'] != '')
			$criteria->compare('t.type', $_GET['type']);
		$criteria->with = array('book', 'book.stock_basic', 'book.stock_reserve', 'book.publisher', 'book.publisher.organisation');
		$criteria->together = true;

		$dataProvider=new CActiveDataProvider('PubOrder', array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'organisation.name, book.author, book.title',
				),
			'pagination'=>false,
		));

		$this->render('pubactivity',array(
			'dataProvider'=>$dataProvider,
		));
	}

	public function actionSavePubActivity($puborder_id)
	{
		if(Yii::app()->request->isAjaxRequest)
		{
			$status = 'ERR'; $msg = t('Record cannot be saved.');
			$puborder = PubOrder::model()->findByPk($puborder_id);

			if ($puborder !== null)
			{
				$stock = Stock::model()->findByAttributes(array('book_id'=>$puborder->book_id, 'type'=>$puborder->type));

				$stockActivity = new StockActivity('stock');
				$stockActivity->user_id = user()->id;
				$stockActivity->stock_id = $stock->id;
				$stockActivity->pub_order_id = $puborder_id;
				$stockActivity->date = DT::locToday();
				$stockActivity->count = abs($_POST['StockActivity']['count']);
				$stockActivity->invoice = $_POST['StockActivity']['invoice'];
				$stockActivity->price = $_POST['StockActivity']['price'];

				if ($stockActivity->save())
				{
					$status = 'OK';
					$msg = '';
				}
				else
				{
					foreach ($stockActivity->getErrors() as $attr=>$errs)
						foreach ($errs as $err)
							$msg .= '<br />'.$err;
					foreach (user()->getFlashes() as $name=>$flash)
						$msg .= '<br />'.$flash;
				}

				$puborder=PubOrder::model()->with(array('book', 'book.publisher', 'book.publisher.organisation'))->findByPk($puborder_id);

				$this->ajaxEditFormNoScript();
				echo CJSON::encode(array('status'=>$status, 'val'=>$this->renderPartial('_pubactivity', array('data'=>$puborder), true, true), 'model'=>array(), 'msg'=>$msg));
				Yii::app()->end();
			}
			else
				throw new CHttpException(400,Yii::t('app', 'Invalid request. Please do not repeat this request again.'));
		}
		else
			throw new CHttpException(400,Yii::t('app', 'Invalid request. Please do not repeat this request again.'));
	}

	public function actionLibActivity()
	{
		$criteria=new CDbCriteria;

		if ((isset($_GET['library_id']) && is_numeric($_GET['library_id'])) || (isset($_GET['book_id']) && is_numeric($_GET['book_id'])) || (isset($_GET['type']) && $_GET['type'] != ''))
		{
			if (isset($_GET['library_id']) && is_numeric($_GET['library_id']))
				$criteria->compare('t.library_id', $_GET['library_id']);
			if (isset($_GET['book_id']) && is_numeric($_GET['book_id']))
				$criteria->compare('t.book_id', $_GET['book_id']);
			if (isset($_GET['type']) && $_GET['type'] != '')
				$criteria->compare('t.type', $_GET['type']);
		}
		else
			$criteria->compare('t.id', '-');

		$criteria->with = array('book', 'book.stock_basic', 'book.stock_reserve', 'library', 'library.organisation');
		$criteria->together = true;

		$dataProvider=new CActiveDataProvider('LibOrder', array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'organisation.name, book.author, book.title',
				),
			'pagination'=>false,
		));

		$this->render('libactivity',array(
			'dataProvider'=>$dataProvider,
		));
	}

	public function actionSaveLibActivity($liborder_id)
	{
		if(Yii::app()->request->isAjaxRequest)
		{
			$status = 'ERR'; $msg = t('Record cannot be saved.');
			$liborder = LibOrder::model()->findByPk($liborder_id);

			if ($liborder !== null)
			{
				$stock = Stock::model()->findByAttributes(array('book_id'=>$liborder->book_id, 'type'=>$liborder->type));

				$stockActivity = new StockActivity('stock');
				$stockActivity->user_id = user()->id;
				$stockActivity->stock_id = $stock->id;
				$stockActivity->lib_order_id = $liborder_id;
				$stockActivity->date = DT::locToday();
				$stockActivity->count = (-1)*abs($_POST['StockActivity']['count']);

				if ($stockActivity->save())
				{
					$status = 'OK';
					$msg = '';
				}
				else
				{
					foreach ($stockActivity->getErrors() as $attr=>$errs)
						foreach ($errs as $err)
							$msg .= '<br />'.$err;
					foreach (user()->getFlashes() as $name=>$flash)
						$msg .= '<br />'.$flash;
				}

				$liborder=LibOrder::model()->with(array('book', 'library', 'library.organisation'))->findByPk($liborder_id);

				$this->ajaxEditFormNoScript();
				echo CJSON::encode(array('status'=>$status, 'val'=>$this->renderPartial('_libactivity', array('data'=>$liborder), true, true), 'model'=>array(), 'msg'=>$msg));
				Yii::app()->end();
			}
			else
				throw new CHttpException(400,Yii::t('app', 'Invalid request. Please do not repeat this request again.'));
		}
		else
			throw new CHttpException(400,Yii::t('app', 'Invalid request. Please do not repeat this request again.'));
	}

	public function actionLibDelivery()
	{
		if (isset($_GET['book_id']) && is_numeric($_GET['book_id']) && $_GET['book_id']>0 && isset($_GET['type']) && $_GET['type']!='' && isset($_GET['count']) && is_numeric($_GET['count']) && $_GET['count']>0)
		{
			$stock = Stock::model()->findByAttributes(array('book_id'=>$_GET['book_id'], 'type'=>$_GET['type']));

			if ($stock->count >= $_GET['count'])
			{
				$criteria=new CDbCriteria;
				$criteria->compare('t.book_id', $_GET['book_id']);
				$criteria->compare('t.type', $_GET['type']);
				$criteria->compare('(t.count-t.delivered)', '>0');
				$criteria->compare('library.order_placed', 1);
				$criteria->compare('library.order_date', ">'0000-00-00'");
				$criteria->order = 'library.order_date';

				$msq = '';
				$count = $_GET['count'];
				$remaining = 0;

				$libOrders = LibOrder::model()->with('library')->findAll($criteria);
				if ($libOrders !== null)
				{

					foreach ($libOrders as $libOrder)
					{
						$send = 0;
						if ($count > $libOrder->remaining)
						{
							$count -= $libOrder->remaining;
							$send = -$libOrder->remaining;
						}
						else
						{
							$send = -$count;
							$remaining += $libOrder->remaining - $count;
							$count = 0;
						}

						if ($send)
						{
							$stockActivity = new StockActivity('delivery');
							$stockActivity->user_id = user()->id;
							$stockActivity->stock_id = $stock->id;
							$stockActivity->lib_order_id = $libOrder->id;
							$stockActivity->date = DT::locToday();
							$stockActivity->count = $send;

							if (!$stockActivity->save())
							{
								$msg = t('Record cannot be saved.');
								foreach ($stockActivity->getErrors() as $attr=>$errs)
									foreach ($errs as $err)
										$msg .= '<br />'.$err;
								foreach (user()->getFlashes() as $name=>$flash)
									$msg .= '<br />'.$flash;
							}
						}
					}
				}
			}
			else
			{
				$msg = 'Na skladě není dostatečné množství položek (maximální hodnota je '.$stock->count.').';
			}
			if ($msg == '')
			{
				$msg = t('Delivery was successfully divided.');
				if ($remaining)
					$msg .= t(' {n} copy remaining for later delivery.| {n} copies remaining for later delivery.', 'app', $remaining);
				if ($count)
					$msg = t('Delivery was divided, but it contained {n} copy more than was required.| Delivery was divided, but it contained {n} copies more than was required.', 'app', $count);
				Yii::app()->getUser()->setFlash('success.libdelivery', $msg);
			}
			else
			{
				Yii::app()->getUser()->setFlash('error.libdelivery', $msg);
			}
		}
		else
			Yii::app()->getUser()->setFlash('error.libdelivery', 'Nesprávně zadané parametry.');

		$this->redirect(array('stockActivity/libActivity', 'library_id'=>$_GET['library_id'], 'book_id'=>$_GET['book_id'], 'type'=>$_GET['type']));
	}
}
