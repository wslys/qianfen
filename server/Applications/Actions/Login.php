<?php
namespace App\Actions;

use App\IM\Events;
use App\Model\User;
use GatewayWorker\Lib\Gateway;

class Login {
	public static function login($data, $client_id) {
		$user = User::findOne(['open_id'=>$data['open_id']]);
		if (!$user) {
			Msg::error($client_id, '登陆失败,请重试！');
            return;
		}
        self::success($user, $client_id);
		return;
	}

	public static function logout($client_id) {
        Gateway::closeClient($client_id);
    }

	public static function register($data, $client_id) {
        $user = User::findOne(['open_id'=>$data['open_id']]);
        if ($user) {
            self::success($user, $client_id);
            return;
        }

        $insert_id = self::insert_user($data);
        if (!$insert_id) {
            Msg::error($client_id, '注册失败， 请重试!');
        }else {
            $user = User::findOne(['open_id'=>$data['open_id']]);
            self::success($user, $client_id);
        }
        return;
    }

    public static function auth_login($data, $client_id) {
        // 检查是否受过权

        // 未授权需要添加用户信息

        // 绑定登录
    }


    private static function success($user, $client_id) {
        Gateway::bindUid($client_id, $user['id']);

        $_SESSION['uid']        = $user['id'];
        $_SESSION['user_id']    = $user['id'];
        $_SESSION['open_id']    = $user['open_id'];
        $_SESSION['nick_name']  = $user['nick_name'];
        $_SESSION['avatar_url'] = $user['avatar_url'];
        $_SESSION['gender']     = $user['gender'];
        $_SESSION['city']       = $user['city'];
        $_SESSION['province']   = $user['province'];
        $_SESSION['country']    = $user['country'];
        $_SESSION['language']   = $user['language'];
        $_SESSION['create_at']  = $user['create_at'];
        $_SESSION['update_at']  = $user['update_at'];

        // 玩家状态, 1：登陆游戏、 2：匹配中、 3：准备中、 4：游戏中
        $_SESSION['player_status'] = 0;

        Gateway::sendToUid($user['id'], json_encode([
            "code" => 0,
            "type" => "login",
            "msg"  => "success",
            "data" => [
                "msg" => "登陆成功！可以继续啦."
            ]
        ]));
    }

    private static function insert_user($data) {
        $time = time();

        return User::create([
            'open_id'    => isset($data['open_id'])?$data['open_id']:'',      // OpenID
            'nick_name'  => isset($data['nick_name'])?$data['nick_name']:'',  // 用户昵称
            'avatar_url' => isset($data['avatar_url'])?$data['avatar_url']:'',// 用户头像
            'gender'     => isset($data['gender'])?$data['gender']:'',        // 用户 男女
            'city'       => isset($data['city'])?$data['city']:'',            // 城市
            'province'   => isset($data['province'])?$data['province']:'',    // 省份
            'country'    => isset($data['country'])?$data['country']:'',      // 国家
            'language'   => isset($data['language'])?$data['language']:'',    // 语言
            'create_at'  => $time, // 创建时间
            'update_at'  => $time  // 修改时间
        ]);
    }
}