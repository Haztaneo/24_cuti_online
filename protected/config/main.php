<?php
// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
Yii::setPathOfAlias('booster', dirname(__FILE__).'/../extensions/yiibooster');
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'PT K-24 Indonesia - Cuti Online',
	'theme'=>'adminAbound',
	'defaultController' => 'izin/create', 
	
	// preloading 'log' component
	'preload'=>array(
		'log',
		// 'booster',	
	),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.helpers.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool		
		/*
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'black',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		*/
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'urlFormat'=>'path',
            'showScriptName'=>false,
			'rules'=>array(
				'userLdap/update/<id>' => 'userLdap/update',
				'userAdmin/update/<id>' => 'userAdmin/update',
				'userAdmin/delete/<id>' => 'userAdmin/delete',
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
			),
		),
		
		'booster'=>array(
		   'class'=>'ext.yiibooster.components.Booster',
		    /*'coreCss'=>false,
		   'bootstrapCss'=>false,
		   'yiiCss'=>false,
		   'jqueryCss'=>false,
		   'enableJS'=>true,
		   'enableBootboxJS'=>false,
		   'responsiveCss'=>false,
		   'minify'=>false,*/
	    ),
		
		/*'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),*/
			
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=k24_cuti_online',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
		),
		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
		'image'=>array(
			  'class'=>'application.extensions.image.CImageComponent', // GD or ImageMagick
			  'driver'=>'GD',  // ImageMagick setup path
			  'params'=>array('directory'=>'/opt/local/bin'),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		'adminEmail'=>'webmaster@example.com',
		'keyA'=>'1ffd9e753c8054cc61456ac7fac1ac89',
		'keyB'=>'d508fe45cecaf653904a0e774084bb5c',
		'key'=>'iTk24@2015',
		'sessionTimeoutSeconds'=> 1800, // in secondes
		'upload_path' => realpath(dirname(__FILE__) . '/../..').'/uploads',		
		'ldap_host'=>'mail.k24.co.id',		
		'ldap_port'=>'389',
		'ldap_domain'=>'@k24.co.id',
		'ldap_dn'=>'ou=people,dc=k24,dc=co,dc=id',
		'ldap_exclude'=>'(!(uid=*office*)) (!(displayname=*apotek*)) (!(uid=*helpdesk*)) (!(uid=*jogja*)) (!(uid=*vpn*)) (!(uid=*xantonin4fsor*)) (!(displayname=*hrd*)) (!(displayname=*obat*)) (!(displayname=*informasi*))',//(!(uid=*test*))
	),
);