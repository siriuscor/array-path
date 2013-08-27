<?php
if (!defined('ARRAY_PATH_SEPERATOR')) {
	define('ARRAY_PATH_SEPERATOR', '/');
}
/**
 * 	mixed array_path_get ( array &$array, string $path1 [, string $path2 ...])
 *  get value for nested array via array path
 */
function array_path_get(&$stack) {
	$args = func_get_args();
	if (count($args) < 2) {
		throw new Exception('need 2 params in array_path_set');
	}
	array_shift($args);
	if (!is_array($stack)) {
		throw new Exception('args 0 not array in array_path_get');
	}

	$path = array_path_parse($args);
	foreach($path as $seg) {
		if (isset($stack[$seg])) {
			$stack = & $stack[$seg];
		} else {
			return null;
		}
	}
	return $stack;
}

/**
 * 	void array_path_set ( array &$array, string $path1 [, string $path2 ...] , $value)
 *  set value for nested array via array path
 *  last param is the value been set
 *  it will create any node through the path if not exist
 */
function array_path_set(&$stack) {
	$args = func_get_args();
	if (count($args) < 3) {
		throw new Exception('need 3 params in array_path_set');
	}
	array_shift($args);
	$value = array_pop($args);
	if (!is_array($stack)) {
		throw new Exception('args 0 not array in array_path_set');
	}

	$path = array_path_parse($args);
	foreach($path as $seg) {
		if (isset($stack[$seg])) {
			$stack = & $stack[$seg];
		} else {
			$stack[$seg] = array();
			$stack = & $stack[$seg];
		}
	}
	$stack = $value;
}

/**
 * 	void array_path_unset ( array &$array, string $path1 [, string $path2 ...])
 *  unset value for nested array via array path
 *  if middle node of path not exist,it will simply return void
 */
function array_path_unset(&$stack) {
	$args = func_get_args();
	if (count($args) < 2) {
		throw new Exception('need 2 params in array_path_set');
	}
	$stack = & array_shift($args);
	if (!is_array($stack)) {
		throw new Exception('args 0 not array in array_path_set');
	}

	$path = array_path_parse($args);
	$last = array_pop($path);
	foreach($path as $seg) {
		if (isset($stack[$seg])) {
			$stack = & $stack[$seg];
		} else {
			return;
		}
	}
	unset($stack[$last]);
}

/**
 * 	void array_path_isset ( array &$array, string $path1 [, string $path2 ...])
 *  check value for nested array via array path
 *  if middle node of path not exist,it returns false
 */
function array_path_isset(&$stack) {
	$args = func_get_args();
	if (count($args) < 2) {
		throw new Exception('need 2 params in array_path_set');
	}
	$stack = & array_shift($args);
	if (!is_array($stack)) {
		throw new Exception('args 0 not array in array_path_set');
	}

	$path = array_path_parse($args);
	$last = array_pop($path);
	foreach($path as $seg) {
		if (isset($stack[$seg])) {
			$stack = & $stack[$seg];
		} else {
			return false;
		}
	}
	return isset($stack[$last]);
}

/**
 *  mixed array_path_parse(array $args)
 *  create array path from arguments
 */
function array_path_parse($args) {
	$path = array();
	foreach($args as $arg) {
		$path = array_merge($path, explode(ARRAY_PATH_SEPERATOR, $arg));
	}
	return $path;
}