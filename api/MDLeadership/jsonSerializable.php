<?php
namespace MDLeadership;

/**
 * custom implementation for php 5.3 support
 * not to be confused with php 5.4 native interface
 */
interface jsonSerializable {
	function toJson();
	function fromJson($json);
}