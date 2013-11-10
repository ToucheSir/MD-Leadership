<?php

namespace MDLeadership\lib\DAOS;

use MDLeadership\lib\utils\DAOUtils;
use MDLeadership\lib\resources\User;

class UserDAO {

	const USER_FILE_PATH = "data/AllUsers.json";

	private $users;

	public function __construct() {
		$userJson = DAOUtils::deserialize(self::USER_FILE_PATH);

		foreach ($userJson as $json) {
			$this->users[] = new User($json);
		} // foreach
	} // construct

	public function getUserByID($userID) {
		foreach ($this->users as $user) {
			if ($user->get("id") === (int)$userID) {
				return $user;
			} // if
		} // foreach

		throw new \Exception("user does not exist");
	} // getUserByID

	public function getAllUsers() {
		return $this->users;
	} // getAllUsers

	public function addUser(User $user) {
		$userIndex = $this->hasUser($user, array("id", "email"));

		if ($userIndex < 0) {
			$this->users[] = $user;
			$this->updateUsers();
		} else {
			throw new \Exception("User conflict", 409);
		} // else
	} // addUser

	public function removeUser(User $user) {
		$userIndex = $this->hasUser($user);

		if ($userIndex >= 0) {
			array_splice($this->users, $userIndex, 1);
			$this->updateUsers();
		} else {
			throw new \Exception("user not found", 404);
		} // else
	} // removeUser

	public function updateUser(User $user) {
		$userIndex = $this->hasUser($user, array("id"));

		if ($userIndex >= 0) {
			$this->users[$userIndex] = $user;
			$this->updateUsers();
		} else {
			throw new \Exception("user not found", 404);
		} // else
	} // updateUser

	public function hasUser(User $user, $matchAttrs=array()) {
		$users = $this->users;

		if (!empty($matchAttrs)) {
			$userCount = count($users);

			for ($i=0; $i < $userCount; $i++) {
				foreach ($matchAttrs as $attr) {
					if ($users[$i]->get($attr) === $user->get($attr)) {
						return $i;
					} // if
				} // foreach
			} // for

			return -1;
		}

		$result = array_search($user, $users);

		return $result === false ? result : -1;
	} // hasUser

	private function updateUsers() {
		DAOUtils::serialize($this->users,
				self::USER_FILE_PATH, function(User $user) {
			return $user->toJson();
		});
	} // updateUsers

} // UserDAO
