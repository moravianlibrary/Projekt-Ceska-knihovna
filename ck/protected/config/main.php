<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Česká knihovna',
	'sourceLanguage'=>'en',
	'language'=>'cs',

	'aliases'=>array(
		'wsext'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'yii-1.1'.DIRECTORY_SEPARATOR.'extensions',
	),

	/*
	'mail' => array(
                'class' => 'ext.yii-mail.YiiMail',
                'transportType'=>'smtp',
                'transportOptions'=>array(
                        'host'=>'smtp.mzk.cz',
                        'port'=>'25',
                ),
                'viewPath' => 'application.views.mail',
        ),
	*/

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.extensions.*',
		'wsext.*',
		'wsext.cmpdate.cmpdate',
		'zii.widgets.jui.*',
		// 'ext.yii-mail.YiiMail',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'legend',
		 	// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('84.242.101.55','::1'),
			'generatorPaths'=>array(
				'application.gii',   // a path alias
				),
		),
		'rbam'=>array(
			// RBAM Configuration
			// 'initialise'=>true,
			'development'=>true,
			'applicationLayout'=>'application.views.layouts.column2',
			'showMenu'=>false,
		),
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>false,
		),
		// uncomment the following to use a MySQL database
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=ck',
			'emulatePrepare' => true,
			'username' => '',
			'password' => '',
			'charset' => 'utf8',
			'tablePrefix' => 'yii_',
			'schemaCachingDuration' => 3600,
		),
		'authManager'=>array(
			'class'=>'CDbAuthManager',
			'connectionID'=>'db',
			'assignmentTable'=>'yii_auth_assignment',
			'itemChildTable'=>'yii_auth_itemchild',
			'itemTable'=>'yii_auth_item',
	),
	'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
        ),
        'format'=>array(
			'class'=>'wsext.Formatter',
			'numberFormat'=>array('decimals'=>2, 'decimalSeparator'=>',', 'thousandSeparator'=>' '),
        ),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning, info',
				),
				/*
				array(
						'class'=>'CProfileLogRoute',
						'report'=>'summary',
				),
				*/
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
		'file'=>array(
			'class'=>'wsext.file.CFile',
		),
		'widgetFactory'=>array(
			'widgets'=>array(
	                	'CGridView'=>array(
					'ajaxUpdate'=>false,
                		),
                	)
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		'adminEmail'=>'ceskaknihovna@mzk.cz',
		'projectYear'=>2018, // rok projektu
		'pubBookDate'=>'2018-02-28', // finální datum změn publikací
		'pubFinalDate'=>'2018-11-30', // dodání objednaných knih i faktur (tisk Objednávky)
		// 'selectedLimit'=>2, // celkový počet vybraných publikací
		// 'pointsMinLimit'=>6, // počet bodů pro přijetí publikací bez hlasování
		'councilDate'=>'2018-03-22', // datum zasedání literární rady (tisk vybraným/nevybraným nakladatelům)
		'letterDate'=>'2018-04-24', // dopis vybraným/nevybraným nakladatelům
		'libBasicLimit'=>6500, // maximální cena základní objednávky
		'libReserveLimit'=>20, // maximální počet svazků do rezervy
		'isbnPrefix'=>'978-80-', // prefix automaticky přidávaný před ISBN
		'printOrderDate' => '23. 6. 2018',
		'registerAs'=>'', //(date('Y-m-d') < '2015-03-01' ? 'publisher' : (date('Y-m-d') > '2015-04-01' ? 'library' : '')),
	),
);

