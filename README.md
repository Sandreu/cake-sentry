Cake-Sentry
===========

**Cake-Sentry** is an error handler plugged on [Sentry](http://www.getsentry.com) - [docs](http://sentry.readthedocs.org/en/latest/quickstart/index.html#setting-up-an-environment)

Installation
------------

1. Install Sentry Plugin into your CakePHP project :
<pre>
	git submodule add http://github.com/Sandreu/cake-sentry app/Plugin/Sentry
	cd app/Plugin/Sentry
	git submodule init
	git submodule update
</pre>

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

3. Use Sentry as logger:

```php
	CakePlugin::load(array('Sentry'));
	CakeLog::config('default', array('engine' => 'Sentry.SentryLog'));
```

4. include ravenjs and init script in the default layout :

```php
	<?php
	echo $this->Html->script('jquery');
	echo $this->Html->script('ravenjs-min');
	?>
	<script type="text/javascript">
		$.(function () {
			<?php echo $this->element('Sentry.raven-js'); ?>
		});
	</script>
```
