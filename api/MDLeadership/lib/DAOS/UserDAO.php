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
			if((int)$user->id === (int)$userID) {
				return $user;
			} // if
		} // foreach
		throw new \Exception("user does not exist");
	} // getUserByID

	public function getAllUsers() {
		return $this->users;
	} // getAllUsers

	public function addUser(User $user) {
		if($this->hasUser($user) < 0) {
			array_push($this->users, $user);
			$this->updateUsers();
		}
	} // addUser

	public function removeUser(User $user) {
		$userIndex = $this->hasUser($user);

		if($userIndex >= 0) {
			array_splice($this->users, $userIndex, 1);
			$this->updateUsers();
		} else {
			throw new \Exception("user not found");
		} // else
	} // removeUser

	public function updateUser(User $user) {
		$userIndex = $this->hasUser($user, function($users, $user) {
			for ($i=0; $i < count($users); $i++) { 
				if((int)$users[$i]->id === (int)$user->id) {
					return $i;
				} // if
			} // for

			return -1;
		});

		if($userIndex >= 0) {
			$users[$userIndex] = $user;
			$this->updateUsers();
		} else {
			throw new \Exception("user not found");
		} // else
	} // updateUser

	public function hasUser(User $user, $customCallback=false) {

		if($customCallback) {
			return $customCallback($this->users, $user);
		}

		$result = array_search($user, $this->users);

		return $result === false ? result : -1;
	} // hasUser

	private function updateUsers() {
		DAOUtils::serialize($this->users, self::USER_FILE_PATH, function(User $user) {				
			return $user->toJson();
		});
	} // updateUsers

} // UserDAO