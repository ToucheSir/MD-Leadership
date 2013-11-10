<?php
namespace MDLeadership\lib\resources;

class User extends JsonableResource {
	private static $allowedAttributes = array(
		"id",
		"name",
		"grade",
		"email",
		"password"
	);

	protected function sanitizeAttributes($attributes) {
		$attributes = $attributes ? array_intersect_key(
			$attributes,
			array_flip(self::$allowedAttributes)
		) : array();

		return $attributes;
	}

	protected function isValidAttribute($attribute) {
		return in_array($attribute, self::$allowedAttributes);
	}

} // User