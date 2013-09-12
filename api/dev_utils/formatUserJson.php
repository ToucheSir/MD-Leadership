<?php
$fileName = "../data/AllUsers.json";

$userFileContents = file_get_contents($fileName);

$userJson = json_decode($userFileContents, true);

$userJson = array_map(function($user) {
	$user["password"] = sha1($user["password"]);

	return $user;
}, $userJson);

var_dump($userJson);

$newFileContents = json_encode($userJson);

file_put_contents($fileName, $newFileContents);
?>