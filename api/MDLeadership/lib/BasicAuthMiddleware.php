<?php
namespace MDLeadership\lib;

use DAOS\GroupUserDAO as Groups;
use AuthRoles as Auth;

class BasicAuthMiddleware extends \Slim\Middleware {
	private $realm = "Requires Authorization";

	public function call() {
		$app = $this->app;
		$authenticated = false;

		try {
			$authHeader = $app->request->headers->get("Authorization");

			if (!$authHeader) {
				throw new \Exception("User authentication failed", 401);
			}

			list($authUserID, $authUserPass) = explode(":", base64_decode(substr($authHeader, 6)));

			$user = $app->userDAO->getUserByID($authUserID);

			if ($user->get("password") === sha1($authUserPass)) {
				$authenticated = true;
				$app->currentUser = $user;
			} else {
				throw new \Exception("User authentication failed", 401);
			}
		} catch (\Exception $e) {
			echo $e->getMessage();
			$app->response->header('WWW-Authenticate', sprintf('Basic realm="%s"', $this->realm));
			$app->halt(401);
		}

		if ($authenticated) {
			findUserGroups($app->currentUser->get("id"));
			$this->next->call();
		}
	}

	private function findUserGroups($userID) {
		$groupFinder = new Groups();

		if ($groupFinder->userInGroup($userID, Groups::COUNCIL_MEMBERS)) {
		}
	}
}
