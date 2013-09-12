<?php
	require "vendor/autoload.php";

	spl_autoload_register(function($class) {
		require "{$class}.php";
	});

	$routeIncludes = array("user", "event");

	$app = new \Slim\Slim(array(
		"debug" => true
	));

	foreach ($routeIncludes as $routeInclude) {
		require "routes/{$routeInclude}Routes.php";
	}

	$app->add(new MDLeadership\lib\BasicAuthMiddleware());

	$app->response->headers->set('Content-Type', 'application/json');
	$app->run();
?>