<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'timeZone' => 'Asia/Shanghai',
	'basePath' => dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name' => 'scw',
    'language' => 'zh_cn',

	// preloading 'log' component
	'preload' => array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.services.*',
        'application.modules.srbac.controllers.SBaseController'
	),

	'defaultController'=>'site',
    'modules' => array(
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'admin',
            //ipFilters用于所在服务器不在本机的情况需开启
            'ipFilters' => array('127.0.0.1', '*', '::1'),
        ),
        'srbac' => array(
            'userclass' => 'GovUser', //可选,默认是 User
            'userid' => 'id', //可选,默认是 userid
            'username' => 'username', //可选，默认是 username
            'delimeter' => '/', //可选，默认是 -
            'debug' => true, //可选,默认是 false
            'pageSize' => 10, //可选，默认是 15
            'superUser' => 'Authorizer', //可选，默认是 Authorizer
            'layout' => 'application.views.layouts.srbac', //可选,默认是 application.views.layouts.main, 必须是一个存在的路径别名
            'notAuthorizedView' => 'srbac.views.authitem.unauthorized',  //可选,默认是srbac.views.authitem.unauthorized, 必须是一个存在的路径别名
            'alwaysAllowed' => array(//default: array()
                'SiteLogin', 'SiteLogout', 'SiteIndex', 'SiteAdmin',
                'SiteError', 'SiteContact',
            ),
            'userActions' => array('Show', 'View', 'List'), //可选,默认是 array()
            'listBoxNumberOfLines' => 15, //可选,默认是 10
            'imagesPath' => 'srbac.images', //可选,默认是 srbac.images
            'imagesPack' => 'tango', //default noia
            'iconText' => true, ///可选,默认是 srbac.images]
            'header' => 'srbac.views.authitem.header', //
            'footer' => 'srbac.views.authitem.footer', //
            'showHeader' => true, //default: false
            'showFooter' => true, //default: false
            'alwaysAllowedPath' => 'srbac.components', //efault: srbac.components must be an existing alias ַ
        ),
        'user' => array(),
        'problem' => array(),
        'image' => array()
    ),

	// application components
	'components'=>array(
		// uncomment the following to use a MySQL database
		'db'=>array(
			'connectionString' => 'mysql:host=139.196.59.18;dbname=poodle_test',
			'emulatePrepare' => true,
			'username' => 'poodle',
			'password' => '',
			'charset' => 'utf8',
			'tablePrefix' => 'tbl_',
		),
        'user'=>array(
            'class'=>'PDUser',
            // enable cookie-based authentication
            'allowAutoLogin'=>true,
            'loginUrl'=>array('site/login'),
        ),
        'authManager' => array(
            'class' => 'srbac.components.SDbAuthManager', //认证类名称
            'connectionID' => 'db',
            'defaultRoles' => array(), //默认角色
            'itemTable' => 'auth_item', //认证项表名称
            'itemChildTable' => 'auth_item_child', //认证项父子关系
            'assignmentTable' => 'auth_assignment', //认证项赋权关系
        ),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'urlManager'=>array(
            'urlFormat'=>'path',
		    'showScriptName'=>false,    // 这一步是将代码里链接的index.php隐藏掉。
            'rules'=>array(
                '<controller:\w+>/<id:\d+>'=>'<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>' 
            )
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
// 				array(
// 				 	'class'=>'CWebLogRoute',
// 				 	'levels'=>'trace',     //级别为trace
//                     'categories'=>'system.db.*' //只显示关于数据库信息,包括数据库连接,数据库执行语句
// 				),
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>require(dirname(__FILE__).'/params.php'),
);