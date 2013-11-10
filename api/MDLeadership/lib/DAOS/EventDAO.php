<?php

namespace MDLeadership\lib\DAOS;

use MDLeadership\lib\utils\DAOUtils;
use MDLeadership\lib\resources\Event;

class EventDAO {
	const EVENT_FILE_PATH = "data/AllEvents.json";

	private $events;

	public function __construct() {
		$eventJson = DAOUtils::deserialize(self::EVENT_FILE_PATH);

		foreach ($eventJson as $json) {
			$this->events[] = new Event($json);
		} // foreach
	} // construct

	public function getEventByID($eventID) {
		foreach ($this->events as $event) {
			if ($event->get("id") === $eventID) {
				return $event;
			} // if
		} // foreach

		throw new \Exception("Event not found", 404);
	} // getEventByID


	public function getAllEvents() {
		return $this->events;
	} // getAllEvents

	public function addEvent(Event $event) {
		if ($this->hasEvent($event) < 0) {
			$this->events[] = $event;
			$this->updateEvents();
		} else {
			throw new \Exception("Event already exists", 409);
		}
	} // addEvent

	public function removeEvent(Event $event) {
		$eventIndex = $this->hasEvent($event);

		if ($eventIndex >= 0) {
			array_splice($this->events, $eventIndex, 1);
			$this->updateEvents();
		} else {
			throw new \Exception("event not found", 404);
		} // else
	} // removeEvent

	public function updateEvent(Event $event) {
		$eventIndex = $this->hasEvent($event, array("id"));

		if ($eventIndex >= 0) {
			$this->events[$eventIndex] = $event;
			$this->updateEvents();
		} else {
			throw new \Exception("event not found");
		} // else
	} // updateEvent

	public function hasEvent(Event $event, $matchAttrs=array()) {
		$events = $this->events;

		if (!empty($matchAttrs)) {
			$eventCount = count($events);

			for ($i=0; $i < $eventCount; $i++) {
				foreach ($matchAttrs as $attr) {
					if ($events[$i]->get($attr) === $event->get($attr)) {
						return $i;
					} // if
				} // foreach
			} // for

			return -1;
		}

		$result = array_search($event, $this->events);

		return $result === false ? -1 : $result;
	} // hasEvent

	private function updateEvents() {
		DAOUtils::serialize($this->events,
				self::EVENT_FILE_PATH, function(Event $event) {
			return $event->toJson();
		});
	} // updateEvents

} // EventDAO