<?php
namespace MDLeadership\lib\utils;

class JsonEncoder {
	const JSONP_TOKEN = ")]}',\n";

	public static function encode($json, $useToken=true) {
		$encodedJson = json_encode($json);

		if ($useToken) {
			$encodedJson = self::JSONP_TOKEN.$encodedJson;
		}

		return $encodedJson;
	}
}