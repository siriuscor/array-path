<?php
if (!defined('ARRAY_PATH_SEPERATOR')) {
	define('ARRAY_PATH_SEPERATOR', '/');
}
/**
 * 	mixed array_path_get ( array &$array, string $path1 [, string $path2 ...])
 *  get value for nested array via array path
 */
function array_path_get(&$stack, $path) {
	$args = func_get_args();
	if (count($args) < 2) {
		throw new Exception('function need 2 arguments');
	}
	array_shift($args);
	if (!is_array($stack)) {
		throw new Exception('argument 1 need to be array');
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
function array_path_set(&$stack, $path, $value) {
	$args = func_get_args();
	if (count($args) < 3) {
		throw new Exception('function need 3 arguments');
	}
	array_shift($args);
	$value = array_pop($args);
	if (!is_array($stack)) {
		throw new Exception('argument 1 need to be array');
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
function array_path_unset(&$stack, $path) {
	$args = func_get_args();
	if (count($args) < 2) {
		throw new Exception('function need 2 arguments');
	}
	$stack = & array_shift($args);
	if (!is_array($stack)) {
		throw new Exception('argument 1 need to be array');
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
function array_path_isset(&$stack, $path) {
	$args = func_get_args();
	if (count($args) < 2) {
		throw new Exception('function need 2 arguments');
	}
	$stack = & array_shift($args);
	if (!is_array($stack)) {
		throw new Exception('argument 1 need to be array');
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
 * 	void array_path_walk ( array &$array, callable $func)
 *  Applies the user-defined function funcname to each element of the array.
 *  recursivly walk the whole array, pass the array_path key and value to func
 *  note:dont modify array structure in func,or the result may be unpredictable
 */
function array_path_walk(&$array, $func, $prefix='') {
	if (!is_array($array) || !is_callable($func)) {
		throw new Exception('param error');
	}

	if (empty($array)) $func($prefix, null);
	foreach($array as $key => $value) {
		if ($prefix !== '') $key = $prefix . ARRAY_PATH_SEPERATOR . $key;
		if (is_array($value)) {
			array_path_walk($value, $func, $key);
		} else {
			$func($key, $value);
		}
	}
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