<?php
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * 用于检测业务代码死循环或者长时间阻塞等问题
 * 如果发现业务卡死，可以将下面declare打开（去掉//注释），并执行php start.php reload
 * 然后观察一段时间workerman.log看是否有process_timeout异常
 */
//declare(ticks=1);

/**
 * 聊天主逻辑
 * 主要是处理 onMessage onClose
 */
namespace App\IM;

use Clue\React\Redis\Factory;
use Clue\React\Redis\Client;
use Config\Db as DbConfig;
use GatewayWorker\Lib\Gateway;
use Workerman\MySQL\Connection;
use GatewayWorker\Lib\Db;
use App\Actions\Login;
use Workerman\Worker;

class EventsCopy
{
    /**
     *  实例化数据库
     */
    public static $db = null;

    /**
     * @var null
     */
    public static $db_conf = null;

    /**
     * @var null
     */
    public static $factory = null;

    /**
     * @var null
     */
    public static $redis_client = null;

    /**
     * 不在线直接则停止
     **/
    public static function onConnect($client_id) {
        if (!Gateway::isOnline($client_id)) { 
            echo $client_id . 'connect offline!--' . microtime(true) . PHP_EOL;
            return;
        }
        Gateway::sendToClient($client_id, "hello");
    }

    /**
     * 进程启动后初始化数据库连接
     */
    public static function onWorkerStart($worker)
    {
        //实例化Db
        // self::$db = Db::instance('chat');
        if (!isset(DbConfig::$db_conf)) {
            echo "\\Config\\Db::\$db_conf not set\n";
            throw new Exception("\\Config\\Db::\$db_conf not set\n");
        }
        self::$db_conf = DbConfig::$db_conf;
        self::$db = new Connection(self::$db_conf['host'], self::$db_conf['port'], self::$db_conf['user'], self::$db_conf['password'], self::$db_conf['dbname'], self::$db_conf['charset']);

        $loop    = Worker::getEventLoop();
        self::$factory = new Factory($loop);

        // TODO
        self::$factory->createClient('localhost:6379')->then(function ($client) {
            self::$redis_client = $client;
        });
        // self::$db = new Connection('host', 'port', 'user', 'password', 'db_name');
        // 开启一个内部端口，方便内部系统推送数据，Text协议格式 文本+换行符
        /*$inner_text_worker = new Worker('Text://0.0.0.0:5678');
         $inner_text_worker->onMessage = function($connection, $buffer)
         {
             global $worker;
             // $data数组格式，里面有uid，表示向那个uid的页面推送数据
             $data = json_decode($buffer, true);
             $uid = $data['uid'];
             // 通过workerman，向uid的页面推送数据
             $ret = sendMessageByUid($uid, $buffer);
             // 返回推送结果
             $connection->send($ret ? 'ok' : 'fail');
         };
         $inner_text_worker->listen();*/
    }

    /**
     * 有消息时
     * @param int $client_id
     * @param mixed $message
     */
    public static function onMessage($client_id, $message)
    {
        //设备信息上报
        $data = json_decode($message, true);

        switch ($data['act']) {
            case 'login':
                Login::login(['username'=>$data['data']['username'], 'password'=>$data['data']['password']], $client_id);
                break;
            case 'logout':
                Login::logout($client_id);
                break;
            case 'create_room':
                break;
            case 'in_room':
                break;
            case 'out_room':
                break;
            case 'ready':
                break;
            case 'start_game':
                break;
        }


        /*$data = [
            'data' => [
                'username' => 'test',
                'password' => 'test'
            ]
        ];
        Login::login(['username'=>$data['data']['username'], 'password'=>$data['data']['password']], $client_id);
        var_dump(Gateway::getClientSessionsByGroup(1));*/

        /*self::$factory->createClient('localhost:6379')->then(function ($client) {
            $client->set('greeting', 'Hello world');
            $client->append('greeting', '!');

            $client->get('greeting')->then(function ($greeting) {
                // Hello world!
                echo $greeting . PHP_EOL;
            });

            $client->incr('invocation')->then(function ($n) {
                echo 'This is invocation #' . $n . PHP_EOL;
            });
        });*/



        // debug
        /*echo "DEBUG :>>>>>>>>|||||| client:{$_SERVER['REMOTE_ADDR']}:{$_SERVER['REMOTE_PORT']} gateway:{$_SERVER['GATEWAY_ADDR']}:{$_SERVER['GATEWAY_PORT']}  client_id:$client_id session:" . json_encode($_SESSION) . " onMessage:" . $message . "\n";

        // 客户端传递的是json数据
        $message_data = json_decode($message, true);
        if (!$message_data) {
            return;
        }
        $insert_id = self::$db->insert('test')->cols(array('test' => 13))->query();

        // 根据类型执行不同的业务
        switch ($message_data['type']) {
            // 客户端回应服务端的心跳
            case 'pong':
                return;
            case 'admin':
                return Gateway::sendToGroup(1, json_encode(['type' => 'admin', 'msg' => '我事服务端推送过来得']));
            // 客户端登录 message格式: {type:login, name:xx, room_id:1} ，添加到客户端，广播给所有客户端xx进入聊天室
            case 'login':
                // 判断是否有房间号
                if (!isset($message_data['room_id'])) {
                    throw new \Exception("\$message_data['room_id'] not set. client_ip:{$_SERVER['REMOTE_ADDR']} \$message:$message");
                }

                // 把房间号昵称放到session中
                $room_id = $message_data['room_id'];
                $client_name = htmlspecialchars($message_data['client_name']);
                $_SESSION['room_id'] = $room_id;
                $_SESSION['client_name'] = $client_name;

                // 获取房间内所有用户列表
                $clients_list = Gateway::getClientSessionsByGroup($room_id);
                foreach ($clients_list as $tmp_client_id => $item) {
                    $clients_list[$tmp_client_id] = $item['client_name'];
                }
                $clients_list[$client_id] = $client_name;

                // 转播给当前房间的所有客户端，xx进入聊天室 message {type:login, client_id:xx, name:xx}
                $new_message = array('type' => $message_data['type'], 'client_id' => $client_id, 'client_name' => htmlspecialchars($client_name), 'time' => date('Y-m-d H:i:s'));
                Gateway::sendToGroup($room_id, json_encode($new_message));
                Gateway::joinGroup($client_id, $room_id); // 将client_id加入某个组 TODO [ 注意， 此处曾掉坑了 ]

                // 给当前用户发送用户列表
                $new_message['client_list'] = $clients_list;
                Gateway::sendToCurrentClient(json_encode($new_message));
                return;

            // 客户端发言 message: {type:say, to_client_id:xx, content:xx}
            case 'say':
                // 非法请求
                if (!isset($_SESSION['room_id'])) {
                    throw new \Exception("\$_SESSION['room_id'] not set. client_ip:{$_SERVER['REMOTE_ADDR']}");
                }
                $room_id = $_SESSION['room_id'];
                $client_name = $_SESSION['client_name'];

                // 私聊
                if ($message_data['to_client_id'] != 'all') {
                    $new_message = array(
                        'type' => 'say',
                        'from_client_id' => $client_id,
                        'from_client_name' => $client_name,
                        'to_client_id' => $message_data['to_client_id'],
                        'content' => "<b>对你说: </b>" . nl2br(htmlspecialchars($message_data['content'])),
                        'time' => date('Y-m-d H:i:s'),
                    );
                    Gateway::sendToClient($message_data['to_client_id'], json_encode($new_message));
                    $new_message['content'] = "<b>你对" . htmlspecialchars($message_data['to_client_name']) . "说: </b>" . nl2br(htmlspecialchars($message_data['content']));
                    return Gateway::sendToCurrentClient(json_encode($new_message));
                }

                $new_message = array(
                    'type' => 'say',
                    'from_client_id' => $client_id,
                    'from_client_name' => $client_name,
                    'to_client_id' => 'all',
                    'content' => nl2br(htmlspecialchars($message_data['content'])),
                    'time' => date('Y-m-d H:i:s'),
                );
                return Gateway::sendToGroup($room_id, json_encode($new_message));
        }*/

    }

    public static function onMessage22($client_id, $data)
    {
        $message = json_decode($data, true);
        $message_type = $message['type'];
        switch ($message_type) {
            case 'init':
                // uid
                $uid = $message['id'];
                // 设置session
                $_SESSION = array(
                    'username' => $message['username'],
                    'avatar' => $message['avatar'],
                    'id' => $uid,
                    'sign' => $message['sign']
                );
                // 将当前链接与uid绑定
                Gateway::bindUid($client_id, $uid);
                // 通知当前客户端初始化
                $init_message = array(
                    'message_type' => 'init',
                    'id' => $uid,
                );
                Gateway::sendToClient($client_id, json_encode($init_message));
                // 通知所有客户端添加一个好友
                $reg_message = array('message_type' => 'addList', 'data' => array(
                    'type' => 'friend',
                    'username' => $message['username'],
                    'avatar' => $message['avatar'],
                    'id' => $uid,
                    'sign' => $message['sign'],
                    'groupid' => 1
                ));
                Gateway::sendToAll(json_encode($reg_message), null, $client_id);
                // 让当前客户端加入群组101
                Gateway::joinGroup($client_id, 101);
                return;
            case 'chatMessage':
                // 聊天消息
                $type = $message['data']['to']['type'];
                $to_id = $message['data']['to']['id'];
                $uid = $_SESSION['id'];
                $chat_message = array(
                    'message_type' => 'chatMessage',
                    'data' => array(
                        'username' => $_SESSION['username'],
                        'avatar' => $_SESSION['avatar'],
                        'id' => $type === 'friend' ? $uid : $to_id,
                        'type' => $type,
                        'content' => htmlspecialchars($message['data']['mine']['content']),
                        'timestamp' => time() * 1000,
                    )
                );
                switch ($type) {
                    // 私聊
                    case 'friend':
                        return Gateway::sendToUid($to_id, json_encode($chat_message));
                    // 群聊
                    case 'group':
                        return Gateway::sendToGroup($to_id, json_encode($chat_message), $client_id);
                }
                return;
            case 'hide':
            case 'online':
                $status_message = array(
                    'message_type' => $message_type,
                    'id' => $_SESSION['id'],
                );
                $_SESSION['online'] = $message_type;
                Gateway::sendToAll(json_encode($status_message));
                return;
            case 'ping':
                return;
            default:
                echo "unknown message $data";
        }
    }

    /**
     * 当客户端断开连接时
     * @param integer $client_id 客户端id
     */
    public static function onClose($client_id)
    {
        // debug
        echo "client:{$_SERVER['REMOTE_ADDR']}:{$_SERVER['REMOTE_PORT']} gateway:{$_SERVER['GATEWAY_ADDR']}:{$_SERVER['GATEWAY_PORT']}  client_id:$client_id onClose:''\n";

        // 从房间的客户端列表中删除
        if (isset($_SESSION['room_id'])) {
            $room_id = $_SESSION['room_id'];
            $new_message = array('type' => 'logout', 'from_client_id' => $client_id, 'from_client_name' => $_SESSION['client_name'], 'time' => date('Y-m-d H:i:s'));
            Gateway::sendToGroup($room_id, json_encode($new_message));
        }
    }

    // 针对uid推送数据
    public function sendMessageByUid($uid, $message)
    {
        global $worker;
        if (isset($worker->uidConnections[$uid])) {
            $connection = $worker->uidConnections[$uid];
            $connection->send($message);
            return true;
        }
        return false;
    }

    //设备信息绑定
    public function bindDevice($client = '', $device = '')
    {
        //设备是否绑定其他的设备
        Gateway::bindUid($client_id, $uid);
    }

    /**
     * 当businessWorker进程退出时触发。每个进程生命周期内都只会触发一次。
     * 可以在这里为每一个businessWorker进程做一些清理工作，例如保存一些重要数据等。
     * @param $businessWorker
     */
    public static function onWorkerStop($businessWorker)
    {
        echo "WorkerStop\n";
    }
}
