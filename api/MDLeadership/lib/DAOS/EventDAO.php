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
			if($event->id === $eventID) {
				return $event;
			} // if
		} // foreach
		
		throw new \Exception("Event not found");
	} // getEventByID
	

	public function getAllEvents() {
		return $this->events;
	} // getAllEvents

	public function addEvent(Event $event) {
		if($this->hasEvent($event) < 0) {
			array_push($this->events, $event);
			$this->updateEvents();
		} // if
	} // addEvent

	public function removeEvent(Event $event) {
		$eventIndex = $this->hasEvent($event);
		
		if($eventIndex >= 0) {
			array_splice($this->events, $eventIndex, 1);
			$this->updateEvents();
		} else {
			throw new \Exception("event not found");
		} // else
	} // removeEvent

	public function updateEvent(Event $event) {
		$eventIndex = $this->hasEvent($event, function($events, $event) {
			for ($i=0; $i < count($events); $i++) { 
				if($events[$i]->id === $event->id) {
					return $i;
				} // if
			} // for

			return -1;
		});

		if($eventIndex >= 0) {
			$events[$eventIndex] = $event;
			$this->updateEvents();
		} else {
			throw new \Exception("event not found");
		} // else
	} // updateEvent

	public function hasEvent(Event $event, $customCallback=false) {

		if($customCallback) {
			return $customCallback($this->events, $event);
		}

		$result = array_search($event, $this->events);

		return $result === false ? result : -1;
	} // hasEvent
	
	private function updateEvents() {
		DAOUtils::serialize($this->events, self::EVENT_FILE_PATH, function(Event $event) {
			return $event->toJson();
		});
	} // updateEvents
	
} // EventDAO