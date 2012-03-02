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

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.extensions.*',
		'wsext.*',
		'wsext.cmpdate.cmpdate',
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
		// uncomment the following to enable URLs in path-format
		/*
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		*/
		/*
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),
		*/
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
            ),
        ),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		'adminEmail'=>'karel.vasicek@webstep.net',
		'projectYear'=>2011, // rok projektu
		'pubBookDate'=>'2011-11-25', // finální datum změn publikací
		'pubFinalDate'=>'2011-12-09', // dodání objednaných knih i faktur (tisk Objednávky)
		'councilDate'=>'2011-10-09', // datum zasedání literární rady (tisk vybraným/nevybraným nakladatelům)
		'libBasicLimit'=>2000, // maximální cena základní objednávky
		'libReserveLimit'=>5, // maximální počet svazků do rezervy
		'isbnPrefix'=>'978-80', // prefix automaticky přidávaný před ISBN
		'registerAs'=>(date('Y-m-d') < '2012-01-02' ? 'publisher' : (date('Y-m-d') > '2012-01-05' ? 'library' : '')),
		// 'selectedLimit'=>2, // celkový počet vybraných publikací
		// 'pointsMinLimit'=>6, // počet bodů pro přijetí publikací bez hlasování
	),
);