<?php


App::import('Vendor', 'Sentry.Raven/lib/Raven/Autoloader');
/**
 * Description of SentryErrorHandler
 *
 * @author Sandreu
 */
class SentryErrorHandler extends ErrorHandler {

    protected static function sentryLog(Exception $exception) {
        if (Configure::read('debug')==0) {
            $options = Configure::read('Sentry');
            $defaults = array(
                'server' => null,
                'app_name' => 'Application name',
                'logger' => 'PHP'
            );
            if ($options == null) throw new Exception('Configuration Sentry non présente');
            
            $options = array_merge($defaults, $options);

            Raven_Autoloader::register();

            $client = new Raven_Client($options['server']);
            $client->captureException($exception, $options['app_name'], $options['logger']);
        }
    }

    public static function handleException(Exception $exception) {
        try {
            self::sentryLog($exception);

            return parent::handleException($exception);
        } catch (Exception $e) {
            return parent::handleException($e);
        }
    }

    public static function handleError($code, $description, $file = null, $line = null, $context = null) {
        try {
            $e = new ErrorException($description, 0, $code, $file, $line);
            self::sentryLog($e);

            return parent::handleError($code, $description, $file, $line, $context);
        } catch (Exception $e) {
            self::handleException($e);
        }
    }
}

?>
