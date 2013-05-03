<?php
/**
 * Sentry Storage stream for Logging
 */
App::import('Vendor', 'Sentry.Raven/lib/Raven/Autoloader');
App::uses('BaseLog', 'Log/Engine');
App::uses('CakeRavenClient', 'Sentry.Lib');

class SentryLog extends BaseLog {

/**
 * Client used to log information
 *
 * @var CakeRavenClient
 */
	private $__client;

/**
 * Constructs a new Sentry Logger.
 *
 * Config
 *
 * - `server` string, url of the Sentry server [default: Configure::read('Sentry.PHP.server')]
 * - `clientOptions` array, Options to pass to the CakeRavenClient constructor
 *
 * @param array $options Options for the SentryLog, see above.
 */
	public function __construct($config = array()) {
		$config = Hash::merge(array(
			'server' => Configure::read('Sentry.PHP.server'),
			'clientOptions' => array('auto_log_stacks' => true),
		), $config);

		parent::__construct($config);

		if (Configure::read('debug') == 0 || !Configure::read('Sentry.production_only')) {
			Raven_Autoloader::register();
			$this->__client = new CakeRavenClient($this->_config['server'], $this->_config['clientOptions']);
		}
	}

/**
 * Implements writing to sentry server.
 *
 * @param string $type The type of log you are making.
 * @param string $message The message you want to log.
 * @return boolean success of write
 */
	public function write($type, $message) {
		if (is_null($this->__client)) {
			return false;
		}

		$_typesMapping = array(
			'notice' => CakeRavenClient::INFO,
			'info' => CakeRavenClient::INFO,
			'debug' => CakeRavenClient::DEBUG,
			'error' => CakeRavenClient::ERROR,
			'warning' => CakeRavenClient::WARNING
		);
		$level = array_key_exists($type, $_typesMapping) ? $_typesMapping[$type] : CakeRavenClient::INFO;

		$this->__client->captureMessage($message, array(), $level);
		return true;
	}

}
