<?php if (Configure::read('debug')==0 || !Configure::read('Sentry.production_only')) { ?>
	Raven.config('<?php echo Configure::read('Sentry.javascript.server'); ?>').install();
	
	if (typeof(_) != 'undefined') {
		var oldBind = _.bind;

		_.bind = function () {
			var binded = oldBind.apply(this, arguments);
			return function () {
				try {
					return binded.apply(this, arguments);
				} catch (e) {
					Raven.captureException(e);
				}
			}
		}
	}
	if (typeof($) != 'undefined') {
		var oldBind = $.proxy;

		$.proxy = function () {
			var binded = oldBind.apply(this, arguments);
			return function () {
				try {
					return binded.apply(this, arguments);
				} catch (e) {
					Raven.captureException(e);
				}
			}
		}
	}
<?php } ?>
