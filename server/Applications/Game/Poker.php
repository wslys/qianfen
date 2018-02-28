<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18-2-27
 * Time: 下午7:11
 */

namespace App\Game;


class Poker
{
    // heart hongtao
    // club  heitao
    // spade meihua
    // block fangkuai

    // jokers xiaowang
    // jokerl dawang

    // poker type [heart, club, spade, block, jokers, jokerl]
    public $type = null;

    public $id   = null; // 1, 2, 3 ...... 53, 54

    public $val  = null; // A, 2, 3, 4 ...... J, Q, K

    public $player = ''; // player client id

    public $status = 1; // 1:还未被拿, 2:被拿走， 所属玩家 xxxxx, 3:已经出牌, 4:为分

    public function __construct($id)
    {
        $this->create($id);
    }

    public function reset() {
        $this->player = '';
        $this->status = 1;
    }

    private function create($id) {
        $this->id  = $id;
        $this->val = $id;

        switch ($id) {
            // ========HEART===========
            case 1:
                $this->val = "A";
                $this->type = SV::HEART;
                break;
            case 2:
                $this->val = "2";
                $this->type = SV::HEART;
                break;
            case 3:
                $this->val = "3";
                $this->type = SV::HEART;
                break;
            case 4:
                $this->val = "4";
                $this->type = SV::HEART;
                break;
            case 5:
                $this->val = "5";
                $this->type = SV::HEART;
                break;
            case 6:
                $this->val = "6";
                $this->type = SV::HEART;
                break;
            case 7:
                $this->val = "7";
                $this->type = SV::HEART;
                break;
            case 8:
                $this->val = "8";
                $this->type = SV::HEART;
                break;
            case 9:
                $this->val = "9";
                $this->type = SV::HEART;
                break;
            case 10:
                $this->val = "10";
                $this->type = SV::HEART;
                break;
            case 11:
                $this->val = "J";
                $this->type = SV::HEART;
                break;
            case 12:
                $this->val = "Q";
                $this->type = SV::HEART;
                break;
            case 13:
                $this->val = "K";
                $this->type = SV::HEART;
                break;


            // =============CLUB======================
            case 14:
                $this->val = "A";
                $this->type = SV::CLUB;
                break;
            case 15:
                $this->val = "2";
                $this->type = SV::CLUB;
                break;
            case 16:
                $this->val = "3";
                $this->type = SV::CLUB;
                break;
            case 17:
                $this->val = "4";
                $this->type = SV::CLUB;
                break;
            case 18:
                $this->val = "5";
                $this->type = SV::CLUB;
                break;
            case 19:
                $this->val = "6";
                $this->type = SV::CLUB;
                break;
            case 20:
                $this->val = "7";
                $this->type = SV::CLUB;
                break;
            case 21:
                $this->val = "8";
                $this->type = SV::CLUB;
                break;
            case 22:
                $this->val = "9";
                $this->type = SV::CLUB;
                break;
            case 23:
                $this->val = "10";
                $this->type = SV::CLUB;
                break;
            case 24:
                $this->val = "J";
                $this->type = SV::CLUB;
                break;
            case 25:
                $this->val = "Q";
                $this->type = SV::CLUB;
                break;
            case 26:
                $this->val = "K";
                $this->type = SV::CLUB;
                break;

            // =============SPADE=============
            case 27:
                $this->val = "A";
                $this->type = SV::SPADE;
                break;
            case 28:
                $this->val = "2";
                $this->type = SV::SPADE;
                break;
            case 29:
                $this->val = "3";
                $this->type = SV::SPADE;
                break;
            case 30:
                $this->val = "4";
                $this->type = SV::SPADE;
                break;
            case 31:
                $this->val = "5";
                $this->type = SV::SPADE;
                break;
            case 32:
                $this->val = "6";
                $this->type = SV::SPADE;
                break;
            case 33:
                $this->val = "7";
                $this->type = SV::SPADE;
                break;
            case 34:
                $this->val = "8";
                $this->type = SV::SPADE;
                break;
            case 35:
                $this->val = "9";
                $this->type = SV::SPADE;
                break;
            case 36:
                $this->val = "10";
                $this->type = SV::SPADE;
                break;
            case 37:
                $this->val = "J";
                $this->type = SV::SPADE;
                break;
            case 38:
                $this->val = "Q";
                $this->type = SV::SPADE;
                break;
            case 39:
                $this->val = "K";
                $this->type = SV::SPADE;
                break;

            // =============BLOCK=============
            case 40:
                $this->val = "A";
                $this->type = SV::BLOCK;
                break;
            case 41:
                $this->val = "2";
                $this->type = SV::BLOCK;
                break;
            case 42:
                $this->val = "3";
                $this->type = SV::BLOCK;
                break;
            case 43:
                $this->val = "4";
                $this->type = SV::BLOCK;
                break;
            case 44:
                $this->val = "5";
                $this->type = SV::BLOCK;
                break;
            case 45:
                $this->val = "6";
                $this->type = SV::BLOCK;
                break;
            case 46:
                $this->val = "7";
                $this->type = SV::BLOCK;
                break;
            case 47:
                $this->val = "8";
                $this->type = SV::BLOCK;
                break;
            case 48:
                $this->val = "9";
                $this->type = SV::BLOCK;
                break;
            case 49:
                $this->val = "10";
                $this->type = SV::BLOCK;
                break;
            case 50:
                $this->val = "J";
                $this->type = SV::BLOCK;
                break;
            case 51:
                $this->val = "Q";
                $this->type = SV::BLOCK;
                break;
            case 52:
                $this->val = "K";
                $this->type = SV::BLOCK;
                break;

            // ===========JOKERS=========
            case 53:
                $this->val = "JOKERS";
                $this->type = SV::JOKERS;
                break;

            // ===========JOKERL=========
            case 54:
                $this->val = "JOKERL";
                $this->type = SV::JOKERL;
                break;
        }

        /*if ($id >= 1 && $id <= 13) {
            $this->type = SV::HEART;
        }else if ($id >= 14 && $id <= 26) {
            $this->type = SV::CLUB;
        }else if ($id >= 27 && $id <= 39) {
            $this->type = SV::SPADE;
        }else if ($id >= 40 && $id <= 52) {
            $this->type = SV::BLOCK;
        }else if ($id == 53) {
            $this->type = SV::JOKERS;
        }else if ($id == 54) {
            $this->type = SV::JOKERL;
        }*/
    }
}