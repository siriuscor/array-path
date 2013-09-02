array-path
==========

easy to access element of a structure regardless of nesting levels

array_path use '/' as default path seperator,if you want use other character,
define ARRAY_PATH_SEPERATOR before include array_path

sample:

    <?php
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

    array_path_get($player, 'level'); // return 10
    array_path_get($player, 'building/blacksmith/level'); // return 2
    //combine with any params
    array_path_get($player, 'building', 'blacksmith/level'); // return 2
    array_path_get($player, 'building/blacksmith', 'level'); // return 2
    array_path_get($player, 'building', 'blacksmith', 'level'); // return 2
    array_path_get($player, 'heroes', 0, 'attr'); // return array('str' => 10, 'def' => 20)

    //last param is the value been set
    array_path_set($player, 'heroes/0/attr/str', 15);
    array_path_set($player, 'heroes', 0, 'attr', 'str', 15);

    array_path_set($player, 'heroes', 2, array('id' => 3));

    array_path_unset($player, 'heroes', 1); //this will make heroes node a dict

    array_path_walk($player, function($key, $value) {
        echo $key . ":" . $value . "\n";
    });
    //will output as follow:
    
    // level:10
    // silver:20
    // building:
    // heroes/0/id:1
    // heroes/0/name:jack
    // heroes/0/attr/str:15
    // heroes/0/attr/def:20
    // heroes/1/id:2
    // heroes/1/name:jane
    // heroes/1/attr/str:13
    // heroes/1/attr/def:26
    // heroes/2/id:3