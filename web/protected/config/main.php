<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'scw',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.services.*'
	),

	'defaultController'=>'site',

	// application components
	'components'=>array(
	    'modules' => array(
            'gii' => array(
                'class' => 'system.gii.GiiModule',
                'password' => 'admin',
                //ipFilters用于所在服务器不在本机的情况需开启
                'ipFilters' => array('192.168.1.10', '*', '::1'),
            ),
            'user' => array(),
	        'image' => array()
	    ),
		// uncomment the following to use a MySQL database
		/*
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=blog',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
			'tablePrefix' => 'tbl_',
		),
		*/
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'urlManager'=>array(
		    'showScriptName'=>false,    // 这一步是将代码里链接的index.php隐藏掉。  
            'urlFormat'=>'path',  
            'rules'=>array(
                '<controller:\w+>/<id:\d+>'=>'<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>' 
            )
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
// 				array(
// 					'class'=>'CFileLogRoute',
// 					'levels'=>'error, warning',
// 				),
// 				array(
// 					'class'=>'CWebLogRoute',
// 					'levels'=>'trace',     //级别为trace
//                     'categories'=>'system.db.*' //只显示关于数据库信息,包括数据库连接,数据库执行语句
// 				),
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>require(dirname(__FILE__).'/params.php'),
);