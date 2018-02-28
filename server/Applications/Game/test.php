<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18-2-27
 * Time: 下午8:08
 */

use App\Game\Poker;

require_once 'SV.php';
require_once 'Poker.php';

$poker_list = [];
for ($i=1; $i<=54; $i++) {
    array_push($poker_list, new Poker($i));
}

//$i=1;
//while ($i<=54) {
//    array_push($poker_list, new Poker($i));
//    $i++;
//}

var_dump($poker_list);

shuffle($poker_list);

var_dump($poker_list);


echo json_encode($poker_list);