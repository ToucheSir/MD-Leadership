<?php
namespace MDLeadership\lib;

class BasicAuthMiddleware extends \Slim\Middleware {
	private $realm = "Requires Authorization";

	public function call() {
		$app = $this->app;
		$authenticated = false;

		try {
			$authHeader = $app->request->headers->get("Authorization");

			if(!$authHeader) new \Exception("User authentication failed", 401);

			list($authUserID, $authUserPass) = explode(":", base64_decode(substr($authHeader, 6)));

			$user = $app->userDAO->getUserByID($authUserID);

			if($user->password === sha1($authUserPass)) {
				$authenticated = true;
			} else {
				throw new \Exception("User authentication failed", 401);
			}
		} catch(\Exception $e) {
			$app->response->status(401);
			$app->response->header('WWW-Authenticate', sprintf('Basic realm="%s"', $this->realm));
		}

		if($authenticated) {
			$this->next->call();
		}
	}
}
?>