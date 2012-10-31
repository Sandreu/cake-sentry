<script type="text/javascript">
if (typeof(require) != 'undefined') {
	require(['libs/raven-js'], function () {
		Raven.config('<?php echo Configure::read('Sentry.js_server'); ?>');
		window.onerror = Raven.process;
	});
} else if (typeof(Raven) != 'undefined') {
	Raven.config('<?php echo Configure::read('Sentry.js_server'); ?>');
	window.onerror = Raven.process;
} else {
	throw new Error('Impossible de charger raven-js');
}
</script>