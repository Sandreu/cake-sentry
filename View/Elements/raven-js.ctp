
Raven.config('<?php echo Configure::read('Sentry.javascript.server'); ?>');
window.onerror = Raven.process;