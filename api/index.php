<?php
require "vendor/autoload.php";

spl_autoload_register(function($class) {
	$fileName = $class.".php";

	if (file_exists($fileName)) {
		require $fileName;
	}
});

$app = new \Slim\Slim(array(
	"debug" => true
));

require "routes/userRoutes.php";
require "routes/eventRoutes.php";

$app->add(new \MDLeadership\lib\BasicAuthMiddleware());
$app->add(new \Slim\middleware\ContentTypes());
$app->add(new \MDLeadership\lib\ServerErrorHandler());

$app->response->headers->set('Content-Type', 'application/json');
$app->run();
