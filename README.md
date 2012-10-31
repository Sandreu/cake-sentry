Cake-Sentry
===========

**Cake-Sentry** is an error handler plugged on [Sentry](http://www.getsentry.com)

Installation
------------

1. Install Sentry Plugin :
```
git submodule add http://github.com/Sandreu/cake-sentry app/Plugin/Sentry
```

2. Configure the error handler in *core.php*
```php
	App::uses('SentryErrorHandler', 'Sentry.Lib');
	
	Configure::write('Sentry', array(
		'server' => 'http://your-sentry-dns',
		'app_name' => 'Application Name',
		'logger' => 'PHP'
	));
	
	Configure::write('Error', array(
		'handler' => 'SentryErrorHandler::handleError',
		'level' => E_ALL & ~E_DEPRECATED,
		'trace' => true
	));
	
	Configure::write('Exception', array(
		'handler' => 'SentryErrorHandler::handleException',
		'renderer'=>'ExceptionRenderer'
	));
```