<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18-2-7
 * Time: 下午11:18
 */

namespace App\Game;


use GatewayWorker\Lib\Gateway;

class Game
{
    /**
     * 大厅
     * @var
     */
    public $hall = null;

    public $matching = null;

    /**
     * 舞台列表
     * @var array
     */
    public $stage_list = [
        [
            'yysy','sghsdg','dshdyehs'
        ]
    ];

    /**
     * 正在游戏中的玩家
     * @var array
     */
    public $player_user_list = [];

    /**
     * 准备中的玩家
     * @var array
     */
    public $ready_user_list = [];

    /**
     * 普通的玩家
     * @var array
     */
    public $ordinary_user_list = [];

    /**
     * 匹配的玩家
     * @var array
     */
    public $matching_user_list = [];

    /**
     * 正在进行游戏的房间
     * @var array
     */
    public $is_line_room_list = [];

    /**
     * 蓝队 The blue team
     * 红队 The red team
     * Game constructor.
     */
    public function __construct() {
        $this->hall = new Hall();
        $this->matching = new Matching();
    }

    public function start() {
        echo "\n game start go go go go >>>>>> \n";
        $this->get_matching_user_list();
        //$this->matching();
    }

    public function go() {
        echo "\n game start go go go go >>>>>> \n";
        // 获取所有在线玩家信息
        $this->hall->players = Gateway::getAllClientSessions();

        // TODO 风云榜 [查询数据库]
        $this->hall->FY_LIST = [];

        // 1. 匹配
        $this->get_matching_user_list();

        // TODO
        // $this->matching();

        // 2. 清理

        // 3.

        // 4.
    }

    /**
     * TODO 需要细细构思
     * @return bool
     */
    private function matching() {
        if ($this->matching_user_list < 4) return false;

        $len = count($this->matching_user_list);
        $len = floor($len); // 取整

        $group_arr = [];
        for ($i=0; $i<$len; $i++) {
            $arr = [];
            for ($j=$i*4; $j<($i+1)*4; $j++) {
                $arr[] = $this->matching_user_list[$j];
            }
            $group_arr[] = $arr;
        }

        // 匹配组已分配好

        // 写入redis中

        //
    }

    /**
     * 获取处于正在匹配状态中的玩家
     */
    private function get_matching_user_list() {
        $this->matching_user_list = [];
        $all_client_sessions = Gateway::getAllClientSessions();

        foreach ($all_client_sessions as $client_id=>$client_session) {
            if ($client_session['player_status'] == 2) { // 玩家状态, 1：登陆游戏、 2：匹配中、 3：准备中、 4：游戏中
                $this->matching_user_list[$client_id] = $client_session;
            }
        }

        var_dump($this->matching_user_list);
    }
}

