<?php
namespace MDLeadership\lib\resources;

class Group {
	const ADMINGROUPID = 1;
	const COUNCILGROUPID = 2;

	private $groupID;
	private $groupName;

	public function __construct($groupID, $groupName) {
		$this->groupID = $groupID;
		$this->groupName = $groupName;
	}
	
	public function getGroupID() {
		return $this->groupID;
	}

	public function  getGroupName() {
	}
}