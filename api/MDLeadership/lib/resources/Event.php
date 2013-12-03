<?php
namespace MDLeadership\lib\resources;

class Event extends JsonableResource {
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

	protected function sanitizeAttributes($attributes) {
		$timeNow = new \DateTime();
		$timeNowISO = $timeNow->format("Y-m-dTH:i:s");

		$attributes = $attributes ? array_intersect_key(
			$attributes,
			array_flip(self::$allowedAttributes)
		) : array(
			"id" => md5(time()),
			"creator" => "no creator",
			"name" => "Untitled Event",
			"description" => "no description",
			"timerange" => array(
				"start" => $timeNow,
				"end" => $timeNow
			),
			"location" => "no location specified",
			"isPrivate" => false,
			"maxUsers" => self::NO_USER_LIMIT,
		);

		return $attributes;
	}

	protected function isValidAttribute($attribute) {
		return in_array($attribute, self::$allowedAttributes);
	}
} // Event
