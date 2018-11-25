<?php


    /**
     * Description of SentryErrorHandler
     *
     * @author Sandreu
     */
    class SentryErrorHandler extends ErrorHandler
    {

        protected static function sentryLog($exception)
        {
            if (Configure::read('debug') == 0 || !Configure::read('Sentry.production_only')) {
                Raven_Autoloader::register();
                App::uses('CakeRavenClient', 'Sentry.Lib');

                $client = new Raven_Client(Configure::read('Sentry.PHP.server'));
                if (class_exists('AuthComponent')) {
                    $model = Configure::read('Sentry.User.model');
                    if (empty($model)) {
                        $model = 'User';
                    }
                    $User = ClassRegistry::init($model);
                    $mail = Configure::read('Sentry.User.email_field');
                    if (empty($mail)) {
                        if ($User->hasField('email')) {
                            $mail = 'email';
                        } else {
                            if ($User->hasField('mail')) {
                                $mail = 'mail';
                            }
                        }
                    }
                    $client->user_context(array(
                        "is_authenticated" => AuthComponent::user($User->primaryKey) ? true : false,
                        "id" => AuthComponent::user($User->primaryKey),
                        "username" => AuthComponent::user($User->displayField),
                        "email" => AuthComponent::user($mail)
                    ));
                }
                $client->captureException($exception, array(
                    'extra' => array(
                        'php_version' => phpversion(),
                        'class' => get_class($exception)
                    ),
                ));
            }
        }

        public static function handleException($exception)
        {
            try {
                // Avoid bot scan errors
                if (Configure::read('Sentry.avoid_bot_scan_errors') && ($exception instanceof MissingControllerException || $exception instanceof MissingPluginException) && Configure::read('debug') == 0) {
                    echo Configure::read('Sentry.avoid_bot_scan_errors');
                    exit(0);
                }

                self::sentryLog($exception);

                return parent::handleException($exception);
            } catch (Exception $e) {
                return parent::handleException($e);
            }
        }

        public static function handleError($code, $description, $file = null, $line = null, $context = null)
        {
            try {
                $e = new ErrorException($description, 0, $code, $file, $line);
                self::sentryLog($e);

                return parent::handleError($code, $description, $file, $line, $context);
            } catch (Exception $e) {
                self::handleException($e);
            }
        }
    }
