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

namespace App\IM;

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

use App\Actions\Player;
use APP\Actions\Room;
use App\Game\Game;
use App\Model\Init;
use Config\Db as DbConfig;
use Couchbase\Exception;
use GatewayWorker\Lib\Gateway;
use Workerman\Lib\Timer;
use Workerman\MySQL\Connection;
use GatewayWorker\Lib\Db;
use App\Actions\Login;

class Events
{
    /**
     * 游戏空间
     * @var null
     */
    public static $game = null;

    /**
     *  实例化数据库
     */
    public static $db = null;

    /**
     * @var null
     */
    public static $db_conf = null;

    /**
     * 不在线直接则停止
     * @param $client_id
     */
    public static function onConnect($client_id) {
        // 不在线直接则停止
        if (!Gateway::isOnline($client_id)) { 
            echo $client_id . 'connect offline!--' . microtime(true) . PHP_EOL;
            return;
        }
        Gateway::sendToClient($client_id, "hello");
    }

    /**
     * 进程启动后初始化数据库连接
     * @param $worker
     * @throws Exception
     */
    public static function onWorkerStart($worker)
    {
        self::$game = new Game(); // 实例化游戏空间

        // 实例化Db
        // self::$db = Db::instance('chat');
        if (!isset(DbConfig::$db_conf)) {
            echo "\\Config\\Db::\$db_conf not set\n";
            throw new Exception("\\Config\\Db::\$db_conf not set\n");
        }
        self::$db_conf = DbConfig::$db_conf;
        self::$db = new Connection(self::$db_conf['host'], self::$db_conf['port'], self::$db_conf['user'], self::$db_conf['password'], self::$db_conf['dbname'], self::$db_conf['charset']);

        // init model TODO 初始化Model
        Init::init();

        // TODO 监控游戏空间的状态：如匹配，开始，通知等......
        Timer::add(3, function(){
            self::$game->start();
        });
    }

    /**
     * 有消息时
     * @param int $client_id
     * @param mixed $message
     * @throws \Exception
     */
    public static function onMessage($client_id, $message)
    {
        $data = json_decode($message, true);

        switch ($data['act']) {
            case 'login':
                Login::login($client_id, ['open_id'=>$data['data']['open_id']]);
                break;
            case 'logout':
                Login::logout($client_id);
                break;
            case 'register':
                Login::register($client_id, $data['data']);
                break;
            case 'create_room':
                Room::createdRoom($client_id, $data);
                break;
            case 'in_room':
                Room::inRoom($client_id, $data);
                break;
            case 'out_room':
                Room::outRoom($client_id, $data);
                break;
            case 'ready':
                Player::ready($client_id, $data);;
                break;
            case 'start_game':
                Player::startGame($client_id, $data);
                break;
            case 'end_game':
                Player::endGame($client_id, $data);
                break;
        }
    }

    /**
     * 当客户端断开连接时
     * @param $client_id
     * @throws \Exception
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

    /**
     * 针对uid推送数据
     * @param $uid
     * @param $message
     * @return bool
     */
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

    /**
     * 设备信息绑定
     * @param string $client
     * @param string $device
     */
    public function bindDevice($client = '', $device = '')
    {
        //设备是否绑定其他的设备
        Gateway::bindUid($client, $device);
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
