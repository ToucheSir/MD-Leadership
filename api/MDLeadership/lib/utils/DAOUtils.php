<?php

namespace MDLeadership\lib\utils;

class DAOUtils {
	public static function serialize($data, $filePath, $serializer=null, $writeMode="w") {
		if($serializer !== null) {
			$serialized = array();
			foreach ($data as $singleEntry) {
				$serializedEntry = $serializer($singleEntry);
				$serialized[] = $serializedEntry;
			}
				
			$data = $serialized;
		}

		$file = fopen($filePath, $writeMode);
		$toWrite = json_encode($data);

		if (flock($file, LOCK_EX)) {  // acquire an exclusive lock
			fwrite($file, $toWrite);
			fflush($file);            // flush output before releasing the lock
			flock($file, LOCK_UN);    // release the lock
		} else {
			echo "File already locked at ".time();
		}

		fclose($file);
	}

	public static function deserialize($filePath, $asJson=true) {
		$file = fopen($filePath, "r");

		if (flock($file, LOCK_SH)) {  // acquire an exclusive lock
			$contents = fread($file, filesize($filePath));
				
			flock($file, LOCK_UN);    // release the lock
		} else {
			echo "File already locked at ".time();
		}

		fclose($file);
		
		$data = json_decode($contents, $asJson);

		return $data;
	}
}