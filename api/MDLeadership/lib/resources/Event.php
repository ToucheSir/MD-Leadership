<?php

namespace MDLeadership\lib\resources;

use MDLeadership\jsonSerializable;

class Event implements jsonSerializable {
	const NO_USER_LIMIT = -1; // if no limit set
	
	private static $allowedAttributes = array(
		"id", 
		"creator", 
		"name", 
		"description", 
		"timerange",
		"location",
		"isPrivate",
		"maxUsers"
	);

	private $data;

	function __construct($data=false) {
		$this->data = $data ? array_intersect_key(
			$data, 
			array_flip(self::$allowedAttributes)
		) : array(
			"id" => md5(time()),
			"description" => "no description",
			"isPrivate" => false,
			"maxUsers" => self::NO_USER_LIMIT
		);
	}

	function __set($fieldName, $newValue) {
		if(in_array($fieldName, self::$allowedAttributes)) {
			$this->data[$fieldName] = $newValue;
		}
	}

	function __get($fieldName) {
		if(in_array($fieldName, self::$allowedAttributes)) {
			return $this->data[$fieldName];
		}
	}

	function fromJson($json) {
		foreach ($json as $key => $value) {
			$this->data[$key] = $value;
		}
	}

	function toJson() {
		return $this->data;
	}

} // Event