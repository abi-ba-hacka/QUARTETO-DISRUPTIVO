<?php
Flight::route('GET /logout', function() {
	session_destroy();
	$_SESSION = array();
	Flight::set('response', array('logout'=>'ok'));
	Flight::redirect('.');
});

Flight::route('POST /login', function(){

	$email = Flight::request()->data['email'];
	$password = Flight::request()->data['password'];

	$st = Flight::db()->prepare('SELECT * FROM users 
		WHERE email = :email 
		AND password = :password');
	$st->bindParam(':email', $email);
	$st->bindParam(':password', $password);
	$st->execute();
	$data = array();
	$result = $st->fetchAll(PDO::FETCH_ASSOC);

    if (count($result)) {
		$_SESSION['valid'] = true;
		$_SESSION['user'] = $result[0];
		Flight::redirect('.');
	} else {
		App::$flash_message->error('Wrong email or password');
    }
    Flight::set('response_data', array('login'=>true));
    Flight::set('redirect', 'https://www.facebook.com/patagoniacerveza/?fref=ts');
});