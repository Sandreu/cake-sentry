<?php

App::uses('ClassRegistry', 'Utility');

class CakeRavenClient extends Raven_Client {
    public function capture($data, $stack=null, $vars = null) {
    	if (class_exists('AuthComponent')) {
    		$model= Configure::read('Sentry.User.model');
    		if (empty($model)) $model = 'User';

    		$User = ClassRegistry::init($model);

    		$mail = Configure::read('Sentry.User.email_field');
    		if (empty($mail)) {
	    		if ($User->hasField('email')) $mail = 'email';
	    		else if ($User->hasField('mail')) $mail = 'mail';
	    	}

    		$data['sentry.interfaces.User'] = array(
				"is_authenticated" => AuthComponent::user($User->primaryKey) ? true : false,
				"id" => AuthComponent::user($User->primaryKey),
				"username" => AuthComponent::user($User->displayField),
				"email" => AuthComponent::user($mail)
    		);
    	}

        $eventId = parent::capture($data, $stack,$vars);
    	CakeSession::write('sentry_event_id',$eventId);
    	return $eventId;
	}
}
