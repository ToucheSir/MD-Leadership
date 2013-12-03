<?php
namespace MDLeadership\lib;

class AuthRoles {
	const AUTHENTICATED = 1;
	const IS_COUNCIL = 2;
	const IS_ADMIN = 4;
	const VIEWING_SELF = 8;

	public function __construct(array $roles = array()) {
		$this->roles = $roles;
	}

	public function authorized() {
		$leftoverRoles = array_diff(func_get_args(), $this->roles);
		return empty($leftoverRoles);
	}

	public function addRole($role) {
		if (array_search($role, $this->roles) !== false) {
			$this->roles[] = $role;
		}
	}

	public function removeRole($role) {
		$index = array_search($role, $this->roles);

		if ($index !== false) {
			array_splice($this->roles, $index, 1);
		}
	}
}
