<?php
class ArrayComponent extends Object {
	
	/**
	 * Check array for given keys. They need to be set and not empty.
	 *
	 * @param array $keys
	 * @param array $array
	 * @return bool
	 */
	function hasNonEmptyKeys($keys = array(), &$array) {
		foreach ($keys as $key) {
			if (!isset($array[$key])) {
				return false;
			}
			if (empty($array[$key])) {
				return false;
			}
		}
		return true;
	}
	
}
