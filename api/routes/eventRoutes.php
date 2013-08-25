<?php 
use MDLeadership\lib\DAOS\EventDAO;
use Slim\Slim;

$eventDAO = new EventDAO();
$app->eventDAO = $eventDAO;

$app->group("/events", function() use ($app) {
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
		// $app->get("/", "getEvent");

		/**
		 * PUT event
		 */
		// $app->put("/", "putEvent");
		
		/**
		 * DELETE event
		 */
		// $app->delete("/", "deleteEvent");

		/**
		 * event users
		 */
		$app->group("/users", function() use ($app) {
			// global event-user operations

			/**
			 * GET event users
			 */
			// $app->get("/", "getEventUsers");

			/**
			 * POST event user
			 */
			// $app->post("/", "postEventUser");

			// event-user specific operations

			/**
			 * GET event user
			 */
			// $app->get("/:userID", "getEventUser");

			/**
			 * PUT event user
			 */
			// $app->put("/:userID", "putEventUser");

			/**
			 * DELETE event user
			 */
			// $app->delete("/:userID", "deleteEventUser");
		});
	});
});

function getEvents() {
	$token = ")]}',\n";
	$app = Slim::getInstance();
	$users = $app->eventDAO->getAllEvents();

	$jsonedEvents = array_map(function($event) {
		return $event->toJson();
	}, $users);

	echo json_encode($jsonedEvents);
}

function postEvent() {

}