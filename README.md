Cake-Sentry
===========

**Cake-Sentry** is an error handler plugged on [Sentry](http://www.getsentry.com)

Installation
------------

1. Install Sentry Plugin into your CakePHP project :

	git submodule add http://github.com/Sandreu/cake-sentry app/Plugin/Sentry
	cd app/Plugin/Sentry
	git submodule init
	git submodule update

2. Configure the error handler in your *core.php* :

```php
	App::uses('SentryErrorHandler', 'Sentry.Lib');

	Configure::write('Sentry', array(
		'app_name' => 'Application Name',
		'php_server' => 'http://your-php-sentry-dns',
		'js_server' => 'http://your-javascript-sentry-dns'
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
