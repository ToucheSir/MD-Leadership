<?php

namespace MDLeadership\lib\utils;

class DAOUtils {
	public static function serialize($data, $filePath, $serializer=null, $writeMode="w") {
		if ($serializer !== null) {
			$data = array_map($serializer, $data);
		}

		if (!file_put_contents($filePath, json_encode($data), LOCK_EX)) {  // acquire an exclusive lock
			throw new \RuntimeException("File already locked at ".time());
		}
	}

	public static function deserialize($filePath, $asJson=true) {
		$data = json_decode(file_get_contents($filePath), $asJson);
		return $data;
	}
}