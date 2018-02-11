<?php

    $client = new Raven_Client(Configure::read('Sentry.PHP.server'));
    $error_handler = new Raven_ErrorHandler($client);
    $error_handler->registerExceptionHandler();
    $error_handler->registerErrorHandler();
    $error_handler->registerShutdownFunction();
?>