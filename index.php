<?php

session_cache_limiter(false);
session_start();

require_once 'vendor/autoload.php';
require_once 'utils.php';

use iio\libmergepdf\Merger;
use iio\libmergepdf\Pages;

$app = new \Slim\Slim();

$app->config(array(
	'templates.path' => './templates'
));

// initialize a client with application credentials and required scopes.
$client = new Google_Client();

$client->setApplicationName("Jubal");
$client->setClientId('');
$client->setClientSecret('');
$client->setRedirectUri('');

$client->setScopes(array(
	'https://www.googleapis.com/auth/drive'
));

// if there is an existing session, set the access token
if ($user = get_user()) {
	$client->setAccessToken($user->tokens);
}

$service = new Google_Service_Drive($client);

$app->get('/', function() use ($app, $client, $user, $service) {
	// handle OAuth2 callback if code is set.
	if ($code = $app->request()->get('code')) {
		// handle code, retrieve credentials.
		$client->authenticate($code);
		$tokens = $client->getAccessToken();
		set_user($tokens);
		$app->redirect('/');
	}
	if ($user) {
		// if there is a user in the session
		$q = 'mimeType = \'application/pdf\' and trashed != true';
		$files = $service->files->listFiles(array('q' => $q, 'maxResults' => 1000))->getItems();;
        // sort by title
        usort($files, function($a, $b) {
            return strcasecmp($a->title, $b->title);
        });
		$app->render('index.php', array('files' => $files));
	} else {
		// redirect to the auth page
		$app->redirect($client->createAuthUrl());
	}
});

$app->post('/generate', function() use ($app, $client, $user, $service) {
	// checkUserAuthentication($app);

	$files = json_decode($app->request->post('files'));

	foreach ($files as $file) {
		if(!file_exists('./tmp/'.$file->id.'.pdf')) {
			$request = new Google_Http_Request($file->pdf);
			$request = $client->getAuth()->sign($request);
			$response = $client->getIo()->makeRequest($request);

			file_put_contents('./tmp/'.$file->id.'.pdf', $response->getResponseBody());
		}
	}

	$m = new Merger();

	$m->addFromFile('./blank.pdf');
	foreach ($files as $file) {
		$m->addFromFile('./tmp/'.$file->id.'.pdf');
		$m->addFromFile('./blank.pdf');
	}

	$name = 'generated/'.time().'.pdf';
	file_put_contents('./'.$name, $m->merge());

	print $name;
});

$app->get('/logout', function() use ($app) {
	delete_user();
	$app->redirect('/');
});

$app->run();
