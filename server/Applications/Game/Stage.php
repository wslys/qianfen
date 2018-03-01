<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18-2-27
 * Time: 下午7:02
 */

namespace App\Game;

/**
 * 舞台，用于进行游戏
 * Class Stage
 * @package App\Game
 */
class Stage
{
    // blue team score
    public $blue_score = 0;
    // red team score
    public $red_score  = 0;

    // 4 名玩家
    public $players = [];
    // 4 名玩家拿牌顺序
    public $take_the_order_players = [];
    // 4 名玩家 客户端ID
    public $players_client_id = [];
    // 首次叫牌确定的初始拿牌者
    public $first_call_poker = null;
    // 庄家
    public $banker_player    = null;
    // 当前拿牌者
    public $get_poker_player = 0;
    // 游标
    public $cursor = 0;


    // 牌池
    public $poker_list = [];
    // 拿牌列表
    public $take_poker_list = [];
    // 出牌列表
    public $out_poker_list = [];

    public function __construct($players)
    {
        $this->players = $players;
        foreach ($this->players as $key => $player) {
            array_push($this->players_client_id, $key);
        }
        $this->init();
    }

    private function init() {
        for ($i=1; $i<=54; $i++) {
            array_push($this->poker_list, new Poker($i));
        }

        shuffle($this->poker_list);
    }

    // xiayiju
    public function next() {

    }

    /**
     * 拿牌顺序
     */
    public function take_the_order() {
        $fist_client = '';
        if ($this->banker_player) {
            $fist_client = $this->banker_player;
        }
        $this->take_the_order_players = [];
    }
}

