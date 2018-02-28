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

    public $players = []; // 4 名玩家

    public $first_call_poker = null; // 首次叫牌确定的初始拿牌者

    public $banker_player = null; // 庄家

    public $start_getpoker_player = 0;

    // 牌池
    public $poker_list = [];

    // 拿牌列表
    public $take_poker_list = [];

    // 出牌列表
    public $out_poker_list = [];

    public function __construct($players)
    {
        $this->players = $players;
        $this->init();
    }

    private function init() {
        for ($i=1; $i<=54; $i++) {
            array_push($this->poker_list, new Poker($i));
        }

        shuffle($this->poker_list);
    }

    // xiayiju
    public function next_game() {

    }
}