<?php
namespace MDLeadership\lib\utils;

class JsonEncoder {
	const JSONP_TOKEN = ")]}',\n";

	public static function encodeJson($data, $useToken = true) {
		$responseBody = $data ? $data : \Slim\Slim::getInstance()->response->getBody();

		if(is_array($responseBody)) {
			$responseBody = json_encode($responseBody);
			$responseBody = $useToken ? self::JSONP_TOKEN.$responseBody : $responseBody;
		}

		return $responseBody;
	}
}
?>