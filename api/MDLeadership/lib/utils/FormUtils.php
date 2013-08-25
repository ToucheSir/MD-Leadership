<?php

namespace MDLeadership\lib\utils;

class FormUtils {
	static function arrayStripIndices($array) {
		$keyValueArray = array();

		foreach ($array as $element) {
			$keyValueArray[$element["name"]] = $element["value"];
		}

		return $keyValueArray;
	}
}