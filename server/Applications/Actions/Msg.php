<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 5/2/18
 * Time: 11:20 PM
 */

namespace APP\Actions;


use GatewayWorker\Lib\Gateway;

class Msg
{
    public static function error($client_id, $msg_data) {
        $msg = [
            'code' => 1230,
            'type' => 'error',
            'msg'  => 'error',
            'data' => [
                'msg' => $msg_data
            ]
        ];
        Gateway::sendToClient($client_id, json_encode($msg, true));
    }

    public static function message($client_id, $code = 1230, $type = 'error', $msg = 'error', $msg_data) {
        $msg = [
            'code' => $code,
            'type' => $type,
            'msg'  => $msg,
            'data' => [
                'msg' => $msg_data
            ]
        ];
        Gateway::sendToClient($client_id, json_encode($msg, true));
    }

    /**
     * 房间密码错误
     * @param $client_id
     * @param $msg_data
     * @param $room_id
     */
    public static function room_pwd_error($client_id, $msg_data, $room_id) {
        $msg = [
            'code' => 2001,
            'type' => 'room_pwd_error',
            'msg'  => '房间密码错误!',
            'data' => [
                'msg' => $msg_data,
                'room_id' => $room_id
            ]
        ];
        Gateway::sendToClient($client_id, json_encode($msg, true));
    }
}