<?php

echo '<h1>' . t('Order preview') . '</h1>';

$this->pageTitle = Yii::app()->name.' - '.Yii::t('app', 'Order');

$this->renderPartial('/library/request', array('model'=>$model));

include 'order.php';
?>
