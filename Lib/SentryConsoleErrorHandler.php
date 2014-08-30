<?php


App::import('Vendor', 'Sentry.Raven/lib/Raven/Autoloader');
App::uses('ConsoleErrorHandler', 'Console');
/**
 * Description of SentryConsoleErrorHandler
 *
 * @author Sandreu
 */
class SentryConsoleErrorHandler extends ConsoleErrorHandler {

	protected static function sentryLog(Exception $exception) {
		if (Configure::read('debug')==0 || !Configure::read('Sentry.production_only')) {
			Raven_Autoloader::register();
			App::uses('CakeRavenClient', 'Sentry.Lib');

			$client = new CakeRavenClient(Configure::read('Sentry.PHP.server'));
			$client->captureException($exception, get_class($exception), 'PHP');
		}
	}

	public function handleException(Exception $exception) {
		try {
			// Avoid bot scan errors
			if (Configure::read('Sentry.avoid_bot_scan_errors') && ($exception instanceof MissingControllerException || $exception instanceof MissingPluginException) && Configure::read('debug')==0) {
                		echo Configure::read('Sentry.avoid_bot_scan_errors');
                		exit(0);
            		}

			self::sentryLog($exception);

			return parent::handleException($exception);
		} catch (Exception $e) {
			return parent::handleException($e);
		}
	}

	public function handleError($code, $description, $file = null, $line = null, $context = null) {
		try {
			$e = new ErrorException($description, 0, $code, $file, $line);
			self::sentryLog($e);

			return parent::handleError($code, $description, $file, $line, $context);
		} catch (Exception $e) {
			self::handleException($e);
		}
	}
}
