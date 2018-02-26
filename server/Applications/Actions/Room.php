<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 6/2/18
 * Time: 12:04 AM
 */

namespace APP\Actions;

use App\IM\Events;
use App\Model\User;
use GatewayWorker\Lib\Gateway;
use App\Model\Room as ModelRoom;

class Room
{
    /**
     * @param $data
     * @param $client_id
     */
    public static function createdRoom($client_id, $data) {
        $time = date('Y-m-d H:i:s');

        if (!isset($_SESSION['uid'])) { // not login
            Msg::error($client_id, '创建房间失败， 请重试!');
            return;
        }

        $insert_data = [
            'title'     => $data['room_name'],
            'status'    => '1',
            'owner'     => $_SESSION['uid'],
            'if_pwd'    => isset($data['if_pwd'])?$data['if_pwd']:'0',
            'if_show'   => isset($data['if_show'])?$data['if_show']:'1',
            'if_match'  => isset($data['if_match'])?$data['if_match']:'1',
            'created_at'=> $time,
            'updated_at'=> $time
        ];
        $insert_id = ModelRoom::create($insert_data);

        if (!$insert_id) {
            Msg::error($client_id, '创建房间失败， 请重试!');
        }else {
            $room_id = $insert_id;
            $send_data = [
                'code' => 0,
                'msg'  => 'success',
                'type' => 'create_room',
                'data' => [
                    'msg' => '房间创建成功， 邀请好友一起来玩吧!',
                    'room_id' => $insert_id
                ]
            ];
            Gateway::joinGroup($client_id, $room_id);
            Gateway::sendToClient($client_id, json_encode($send_data));
        }
        return;
    }

    /**
     * @param $data
     * @param $client_id
     * @return bool
     * @throws \Exception
     */
    public static function inRoom($client_id, $data) {
        $room_id = $data['room_id'];
        $room    = ModelRoom::findOne($room_id);
        if (!$room) {
            Msg::error($client_id, '');
            return false;
        }

        // TODO 检查房间密码是否正确
        if ($room['if_pwd'] && $room['pwd'] != $data['pwd']) {
            Msg::room_pwd_error($client_id, '房间密码错误!', $room_id);
            return false;
        }

        $user_list = Gateway::getClientSessionsByGroup($room_id);
        $client_count = Gateway::getClientCountByGroup($room_id);

        $users = [];
        foreach ($user_list as $item) {
            $users[] = $item;
        }

        $send_data = [
            'code' => 0,
            'msg'  => 'success',
            'type' => 'in_room',
            'data' => [
                'users'  => $users,
                'count'  => $client_count,
                'room_id'=> $room_id,
                'status' => $room['status'], // 1:准备中 2:游戏中 3:XXXX 4:XXXX
                'data'   => []   //
            ]
        ];
        Gateway::sendToGroup($room_id, json_encode($send_data));
    }

    public static function outRoom($client_id, $data) {
        $room_id = $data['data']['room_id'];
        $room    = ModelRoom::findOne($room_id);
        if (!$room) {
            Msg::error($client_id, '');
            return;
        }

        // TODO 检查用户是否是游戏参与者
        $user = User::findOne($_SESSION['uid']);

        $send_data = [
            'code' => 0,
            'msg'  => 'success',
            'type' => 'create_room',
            'data' => [
                'msg' => '已离开房间[' . $room['title'] . ']'
            ]
        ];
        Gateway::leaveGroup($client_id, $room_id);
        Gateway::sendToClient($client_id, json_encode($send_data));
    }

    /**
     * 准备
     * @param $data
     * @param $client_id
     */
    public static function ready($client_id, $data) {
        // 检查自己是参与游戏者还是观众
    }

    /**
     * 开始游戏
     * @param $data
     * @param $client_id
     */
    public static function startGame($client_id, $data) {
        // 检查4位玩家是否都已经处于准备状态
    }
}