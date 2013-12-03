<?php
use MDLeadership\lib\DAOS;
use MDLeadership\lib\utils\JsonEncoder;
use MDLeadership\lib\resources\Event;

use Slim\Slim;

$app->eventDAO = new DAOS\EventDAO();
$app->userDAO = new DAOS\UserDAO();
$app->eventUserDAO = new DAOS\EventUserDAO();

// $app->add(new MDLeadership\lib\BasicAuthMiddleware());

$app->group("/events", "assignRoles", function() use ($app) {
	// global event operations

	/**
	 * GET all events
	 */
	$app->get("/", "getEvents");

	/**
	 * POST a event
	 */
	$app->post("/", "postEvent");

	// event-specific operations
	$app->group("/:eventID", function() use ($app) {
		/**
		 * GET event
		 */
		$app->get("/", "getEvent");

		/**
		 * PUT event
		 */
		$app->put("/", "putEvent");

		/**
		 * DELETE event
		 */
		$app->delete("/", "deleteEvent");

		/**
		 * event users
		 */
		$app->group("/users", function() use ($app) {
			// global event-user operations

			/**
			 * GET event users
			 */
			$app->get("/", "getEventUsers");

			// event-user specific operations

			/**
			 * GET event user
			 */
			$app->get("/:userID", "getEventUser");

			/**
			 * PUT event user
			 */
			$app->put("/:userID", "putEventUser");

			/**
			 * DELETE event user
			 */
			$app->delete("/:userID", "deleteEventUser");
		});
	});
});

function assignRoles(Route $route) {
	$app = Slim::getInstance();
	$auth = new Auth;
	$groupDAO = new DAOS\GroupUserDAO();

	if ($route->getParam("userID") === $app->currentUser->get("id")) {
		// user is viewing self
		$app->auth->addRole(Auth::VIEWING_SELF);
	}
	if ($groupDAO->userInGroup()) {
		echo "oui";
	}
}

function getEvents() {
	$app = Slim::getInstance();
	$events = $app->eventDAO->getAllEvents();

	$events = array_map(function($event) {
		return $event->toJson();
	}, $events);

	echo JsonEncoder::encode($events);
}

function postEvent() {
	$app = Slim::getInstance();

	try {
		$eventBody = json_decode($app->request->getBody(), true);
		$event = new Event($eventBody);
		$event->id = md5(time());
		$app->eventDAO->addEvent($event);

		$app->response->setStatus(201);
		$app->response->headers->set("location", "events/{$event->id}");
	} catch (Exception $e) {
		$app->response->setStatus(409);
	}
}

function getEvent($eventID) {
	$app = Slim::getInstance();

	try {
		$event = $app->eventDAO->getEventByID($eventID);
		echo JsonEncoder::encode($event->toJson());
	} catch (Exception $e) {
		$app->response->setStatus(404);
	}
}

function putEvent($eventID) {
	$app = Slim::getInstance();

	try {
		$eventBody = $app->request->getBody();
		$event = new Event($eventBody);
		$app->eventDAO->updateEvent($event);
	} catch (Exception $e) {
		$app->response->setStatus(404);
	}
}

function deleteEvent($eventID) {
	$app = Slim::getInstance();

	try {
		$event = $app->eventDAO->getEventByID($eventID);
		$app->$eventDAO->removeEvent($event);
	} catch (Exception $e) {
		$app->response->setStatus(404);
	}
}

function getEventUsers($eventID) {
	$app = Slim::getInstance();

	$userIDs = $app->eventUserDAO->getEventUsers($eventID);

	$eventUserJson = array("eventID" => $eventID, "userIDs" => $userIDs);
	echo JsonEncoder::encode($eventUserJson);
}

function getEventUser($eventID, $userID) {
	$app = Slim::getInstance();

	// TODO is this really needed?
}

function putEventUser($eventID, $userID) {
	$app = Slim::getInstance();

	$app->eventUserDAO->addUserToEvent($userID, $eventID);
}

function deleteEventUser($eventID, $userID) {
	$app = Slim::getInstance();

	try {
		$app->eventUserDAO->removeUserFromEvent($userID, $eventID);
	} catch (Exception $e) {
		$app->response->setStatus(404);
	}
}
