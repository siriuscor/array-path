<?php
include 'array_path.php';

assert_options(ASSERT_ACTIVE, 1);
assert_options(ASSERT_WARNING, 0);
assert_options(ASSERT_QUIET_EVAL, 1);

// Create a handler function
function my_assert_handler($file, $line, $code)
{
    echo "<hr>Assertion Failed:
        File '$file'<br />
        Line '$line'<br />
        Code '$code'<br /><hr />";
}

// Set up the callback
assert_options(ASSERT_CALLBACK, 'my_assert_handler');

$player = array(
	'level' => 10,
	'silver' => 20,
	'building' => array(
		'blacksmith' => array(
			'level' => 2,
			'store' => array(
				'sword' => 2,
			),
		),
	),
	'heroes' => array(
		array(
			'id' => 1,
			'name' => 'jack',
			'attr' => array(
				'str' => 10,
				'def' => 20,
			),
		),
		array(
			'id' => 2,
			'name' => 'jane',
			'attr' => array(
				'str' => 13,
				'def' => 26,
			),
		),
	),
);

assert('array_path_get($player, "level") === 10'); // return 10
assert('array_path_get($player, "building/blacksmith/level") === 2'); // return 2
//combine with any params
assert('array_path_get($player, "building", "blacksmith/level") === 2'); // return 2
assert('array_path_get($player, "building/blacksmith", "level") === 2'); // return 2
assert('array_path_get($player, "building", "blacksmith", "level") === 2'); // return 2
assert('array_path_get($player, "heroes", 0, "attr") === $player["heroes"][0]["attr"]'); // return array('str' => 10, 'def' => 20)
assert('array_path_get($player, "heroes/0/attr") === $player["heroes"][0]["attr"]'); // return array('str' => 10, 'def' => 20)

array_path_set($player, 'heroes/0/attr/str', 15);
assert('$player["heroes"][0]["attr"]["str"] === 15');

array_path_set($player, 'heroes', 2, array('id' => 3));
assert('$player["heroes"][2]["id"] === 3');

array_path_unset($player, 'building/blacksmith');
assert('!isset($player["building"]["blacksmith"])');

array_path_unset($player, 'heroes', 0, 'attr');
assert('!isset($player["heroes"][0]["attr"])');
