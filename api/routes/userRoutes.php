<?php
use MDLeadership\lib\DAOS;
use MDLeadership\lib\utils\JsonEncoder;
use MDLeadership\lib\AuthRoles as Auth;

use Slim\Slim, Slim\Route;

$app->userDAO = new DAOS\UserDAO();
$app->eventUserDAO = new DAOS\EventUserDAO();
/**
 * [$app->eventDAO description]
 * @var DAOS\EventDAO
 */
$app->eventDAO = new DAOS\EventDAO();
$app->eventDAO->

$app->group("/users", "assignRoles", function() use ($app) {
	// global user operations

	/**
	 * GET all users
	 */
	$app->get("/", "getUsers");

	// user-specific operations
	$app->group("/:userID", function() use ($app) {
		/**
		 * GET user
		 */
		$app->get("/", "getUser");

		/**
		 * PUT user
		 */
		$app->put("/", "putUser");

		/**
		 * DELETE user
		 */
		$app->delete("/", "deleteUser");

		/**
		 * user events
		 */
		$app->group("/events", function() use ($app) {
			// global user-event operations

			/**
			 * GET user events
			 */
			$app->get("/", "getUserEvents");


			// user-event specific operations

			/**
			 * GET user event
			 */
			$app->get("/:eventID", "getUserEvent");

			/**
			 * PUT user event
			 */
			$app->put("/:eventID", "putUserEvent");

			/**
			 * DELETE user event
			 */
			$app->delete("/:eventID", "deleteUserEvent");
		});

		/**
		 * user groups
		 */
		$app->group("/groups", function() use ($app) {
			// global user-group operations

			/**
			 * GET user groups
			 */
			// $app->get("/", "getUserGroups");

			/**
			 * POST user group
			 */
			// $app->post("/", "postUserGroup");

			// user-group specific operations

			/**
			 * GET user group
			 */
			// $app->get("/:eventID", "getUserGroup");

			/**
			 * PUT user group
			 */
			// $app->put("/:eventID", "putUserGroup");

			/**
			 * DELETE user group
			 */
			// $app->delete("/:eventID", "deleteUserGroup");
		});
	});
});

function assignRoles(Route $route) {
	$app = Slim::getInstance();
	$auth = new Auth;

	if ($route->getParam("userID") === $app->currentUser->get("id")) {
		// user is viewing self
		$app->auth->addRole(Auth::VIEWING_SELF);
	}
}

function getUsers() {
	$app = Slim::getInstance();
	$users = $app->userDAO->getAllUsers();

	$jsonedUsers = array_map(function($user) {
		$user = $user->toJson();
		unset($user["password"]);

		return $user;
	}, $users);

	echo JsonEncoder::encode($jsonedUsers);
}

function getUser($userID) {
	$app = Slim::getInstance();

	try {
		$user = $app->userDAO->getUserByID($userID);
		echo JsonEncoder::encode($user->toJson());
	} catch (Exception $e) {
		$app->response->setStatus(404);
	}
}

function putUser($userID) {
	$app = Slim::getInstance();
	$userBody = $app->request->getBody();

	try {
		$user = $app->userDAO->getUserByID($userBody["id"]);
		$user = new User($userBody);
		$app->$userDAO->updateUser($user);
	} catch (Exception $e) {
		$app->response->setStatus(404);
	}
}

function deleteUser($userID) {
	$app = Slim::getInstance();

	try {
		$user = $app->userDAO->getUserByID($userID);
		$app->$userDAO->removeUser($user);
	} catch (Exception $e) {
		$app->response->setStatus(404);
	}
}

function getUserEvents($userID) {
	$app = Slim::getInstance();

	$eventIDs = $app->eventUserDAO->getUserEvents($userID);
	$userEventJson = array("userID" => $userID, "eventIDs" => $eventIDs);

	echo JsonEncoder::encode($userEventJson);
}

function getUserEvent($userID, $eventID) {
	$app = Slim::getInstance();

	// TODO is this really needed?
}

function putUserEvent($userID, $eventID) {
	$app = Slim::getInstance();

	$app->eventUserDAO->addUserToEvent($userID, $eventID);
}

function deleteUserEvent($userID, $eventID) {
	$app = Slim::getInstance();

	try {
		$app->eventUserDAO->removeUserFromEvent($userID, $eventID);
	} catch (Exception $e) {
		$app->response->setStatus(404);
	}
}