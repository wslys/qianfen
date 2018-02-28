<?php
namespace App\Actions;

use App\Game\Stage;
use App\IM\Events;
use GatewayWorker\Lib\Gateway;

/**
 * 玩家操类
 * Class Player
 * @package App\Actions
 */
class Player {
    /**
     * 准备
     * @param $client_id
     * @param $data
     * @throws \Exception
     */
	public static function ready($client_id, $data) {
	    $group = $data['data']['group'];
        $group_num = Gateway::getClientCountByGroup($group);

        if ($group_num > 4) {
            Gateway::sendToClient($client_id, json_encode([
                "act" => "max_client_is_4",
                "msg" => "Each room supports up to 4 people, Please change the room",
                "data"=> [
                    "val"=>"max_client_is_4"
                ]
            ]));
            return;
        }

        $_SESSION['player_status'] = 3;
        Gateway::joinGroup($client_id, $group);

        $room_clients = Gateway::getClientSessionsByGroup($group);
        $tag = true;
        foreach ($room_clients as $client => $session) {
            if ($session['player_status'] != 3) {
                $tag = false;
                break;
            }
        }

        if ($group_num != 4) $tag = false;
        // $tag==true   the room player all is readying
        if ($tag) {
            Gateway::sendToGroup($group, json_encode([
                "act"  => "all_readyed",
                "data" => [
                    "val"=>"all_readyed",
                    "player"=>$room_clients
                ]
            ]));

            // created Stage TODO 初始化一个舞台
            Events::$game->stage_list[$group] = new Stage($room_clients);
        }else {
            Gateway::sendToClient($client_id, json_encode([
                "act"=>"you_readyed",
                "data"=>[
                    "val"=>"you_readyed"
                ]
            ]));
        }
    }

    /**
     * 开始游戏
     * @param $client_id
     * @param $data
     */
    public static function startGame($client_id, $data) {

    }

    /**
     * 结束游戏
     * @param $client_id
     * @param $data
     */
    public static function endGame($client_id, $data) {

    }

    /**
     * 叫牌
     * @param $client_id
     * @param $data
     * @throws \Exception
     */
    public static function callPoker($client_id, $data) {
        $group = $data['data']['group'];

        $room_clients = Gateway::getClientSessionsByGroup($group);
        $rand = rand(1, 4);

        $kty = 1;
        $client_key = $client_id;
        foreach ($room_clients as $key=>$player) {
            if ($kty == $rand) {
                $client_key = $key;
                break;
            }
            $kty++;
        }

        Events::$game->stage_list[$group]->first_call_poker = $client_key;
        Gateway::sendToGroup($group, json_encode([
            "act"=>"call_poker",
            "data"=>[
                "val"=>$client_key,
                "self"=> $client_id
            ]
        ]));
    }

    /**
     * 拿牌
     * @param $client_id
     * @param $data
     * @throws \Exception
     */
    public static function getPoker($client_id, $data) {
        $group = $data['data']['group'];
        $stage = Events::$game->stage_list[$group];

        Gateway::sendToGroup($group, json_encode($stage->players));
    }

    /**
     * 甩二
     * @param $client_id
     * @param $data
     */
    public static function ThrowTwo($client_id, $data) {

    }

    /**
     * 出牌
     * @param $client_id
     * @param $data
     */
    public static function play($client_id, $data) {

    }
}