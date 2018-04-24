<?php

// change the following paths if necessary
$yii=dirname(__FILE__).'/../yii-1.1/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';
$globals=dirname(__FILE__).'/../yii-1.1/globals.php';

// remove the following lines when in production mode
// defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
// defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

ini_set('sendmail_path', '/usr/bin/msmtp -C /opt/msmtp/config -t');

require_once($globals);
require_once($yii);
Yii::createWebApplication($config)->run();
