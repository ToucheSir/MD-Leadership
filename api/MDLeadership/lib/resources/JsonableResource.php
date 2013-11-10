<?php
namespace MDLeadership\lib\resources;

use MDLeadership\jsonSerializable;

abstract class JsonableResource implements \ArrayAccess, jsonSerializable {
	protected $data = array();

	public function __construct($data=false) {
		$this->data = $this->sanitizeAttributes($data);
	}

	public function get($key) {
		if ($this->isValidAttribute($key)) {
			return $this->data[$key];
		}

		throw new \InvalidArgumentException("Invalid Attribute");
	}

	public function set($key, $value) {
		if ($this->isValidAttribute($key)) {
			$this->data[$key] = $value;
		}
	}

	public function offsetExists($offset) {
		return array_key_exists($offset, $this->data);
	}

	public function offsetGet($offset) {
		return $this->get($offset);
	}

	public function offsetSet($offset, $value) {
		$this->set($offset, $value);
	}

	public function offsetUnset($offset) {
		if ($this->isValidAttribute($offset)) {
			unset($this->data[$offset]);
		}
	}

	public function fromJson($json) {
		foreach ($json as $key => $value) {
			$this->data[$key] = $value;
		}
	}

	public function toJson() {
		return $this->data;
	}

	protected abstract function isValidAttribute($attribute);
	protected abstract function sanitizeAttributes($attributes);
}