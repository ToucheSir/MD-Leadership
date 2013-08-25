<?php
namespace MDLeadership\lib\resources;

use MDLeadership\jsonSerializable;

class User implements jsonSerializable {
	private static $allowedAttributes = array(
		"id", 
		"name", 
		"grade", 
		"email", 
		"password"
	);

	private $data;

	function __construct($data=false) {
		$this->data = $data ? array_intersect_key(
			$data, 
			array_flip(self::$allowedAttributes)
		) : array();
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

} // User