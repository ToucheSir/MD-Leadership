<?php

namespace MDLeadership\lib\DAOS;

use MDLeadership\lib\utils\DAOUtils;

class EventUserDAO {
	const EVENT_USER_FILE_PATH = "data/AllEventUsers.json";

	private $allEventUsers;

	public function __construct() {
		$this->allEventUsers = DAOUtils::deserialize(self::EVENT_USER_FILE_PATH);
	} // construct

	public function getEventUsers($eventID) {
		$userIDs = array();

		foreach ($this->allEventUsers as $eventUser) {
			if($eventUser["eventID"] === $eventID) {
				$userIDs[] = $eventUser["userID"];
			}
		}

		return $userIDs;
	} // getUsersInEvent

	public function getUserEvents($userID) {
		$eventIDs = array();

		foreach ($this->allEventUsers as $eventUser) {
			if((int)$eventUser["userID"] === (int)$userID) {
				$eventIDs[] = $eventUser["eventID"];
			}
		}

		return $eventIDs;
	} // getUserEvents

	private function updateEventUsers() {
		DAOUtils::serialize($this->allEventUsers, self::EVENT_USER_FILE_PATH);
	} // updateEventUsers

	public function addUserToEvent($userID, $eventID) {
		if($this->userInEvent($eventID, $userID) < 0) {
			$this->allEventUsers[] = array(
				"userID" => $userID, 
				"eventID" => $eventID
			);
			$this->updateEventUsers();
		}
	} // addUserToEvent

	public function removeUserFromEvent($userID, $eventID) {
		$eventUserIndex = $this->userInEvent($eventID, $userID);

		if($eventUserIndex >= 0) {
			array_splice($this->allEventUsers, $eventUserIndex, 1);
			$this->updateEventUsers();
		} else {
			throw new \Exception("User not atteding event.");
		}
	} // removeUserFromEvent

	private function userInEvent($eventID, $userID) {

		for($i = 0; $i < count($this->allEventUsers); $i++) {
			$eventUser = $this->allEventUsers[$i];
				
			if((int)$eventUser["userID"] === (int)$userID &&
					$eventUser["eventID"] === $eventID) {
				return $i;
			} // if
		} // for

		return -1;
	} // userInEvent 

} // EventUserDAO


