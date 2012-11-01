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
		'production_only' => false, // true is default value -> no error in sentry when debug
		'User' => array(
			'model' => 'SpecialUser', // 'User' is default value
			'email_field' => 'special_email' // default checks 'email' and 'mail' fields
		),
		'PHP' => array(
			'server'=>'http://your-sentry-dns-for-PHP'
		),
		'javascript' => array(
			'server'=>'http://your-sentry-dns-for-javascript'
		)
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

3. include ravenjs and in the default layout :

```html
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/raven-0.5.3.min.js"></script>
	<script type="text/javascript">
		<?php echo $this->element('Sentry.raven-js'); ?>
	</script>
```
