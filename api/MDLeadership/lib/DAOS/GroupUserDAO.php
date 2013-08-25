<?php

namespace MDLeadership\lib\DAOS;

require_once "DAOUtils.php";

class GroupUserDAO {
	const GROUP_USER_FILE_PATH = "../data/AllGroupUsers.json";
	const COUNCIL_MEMBERS = 2048;
	const ADMIN_MEMBERS = 1024;	

	private $allGroupUsers;

	public function __construct() {
		$this->allGroupUsers = DAOUtils::deserialize(self::GROUP_USER_FILE_PATH);
	} // construct

	public function getUsersInGroup($groupID) {
		$userIDs = array();

		foreach ($this->allGroupUsers as $groupUser) {
			if(intval($groupUser["groupID"]) === intval($groupID)) {
				array_push($userIDs, $groupUser["userID"]);
			} // if
		} // foreach

		return $userIDs;
	} // getUsersInGroup

	public function getUserGroups($userID) {
		$groupIDs = array();

		foreach ($this->allGroupUsers as $groupUser) {
			if(intval($groupUser["userID"]) === ($userID)) {
				array_push($groupIDs, intval($groupUser["groupID"]));
			} // if
		} // foreach

		return $groupIDs;
	} // getUserGroups

	private function updateGroupUsers() {
		DAOUtils::serialize($this->allGroupUsers, self::GROUP_USER_FILE_PATH);
	} // updateGroupUsers

	public function addUserToGroup($groupID, $userID) {
		if($this->userInGroup($groupID, $userID) < 0) {
			array_push($this->allGroupUsers,
				array("userID" => intval($userID), "groupID" => intval($groupID)));
			$this->updateGroupUsers();
		} // if
	} // addUserToGroup

	public function removeUserFromGroup($groupID, $userID) {
		$groupUserIndex = $this->userInGroup($groupID, $userID);
		
		if($groupUserIndex >= 0) {
			array_splice($this->allGroupUsers, $groupUserIndex, 1);
			$this->updateGroupUsers();
		} // if
	} // removeUserFromGroup
	
	public function userInGroup($groupID, $userID) {

		for($i = 0; $i < count($this->allGroupUsers); $i++) {
			$groupUser = $this->allGroupUsers[$i];
				
			if(intval($groupUser["userID"]) === intval($userID) &&
			intval($groupUser["groupID"]) === intval($groupID)) {
				return $i;
			} // if
		} // for

		return -1;
	} // userInGroup
} // groupUserDAO
