<?php

declare(strict_types=1);

namespace phuongaz\inviterewards\invite;

use pocketmine\player\Player;
use WeakMap;

final class Session {

    /**
     * @var WeakMap<Player, Invite>
     */
    private static WeakMap $sessions;

    public static function init() : void{
        self::$sessions = new WeakMap();
    }

    public static function create(Player $player, Invite $invite) : void{
        self::$sessions[$player] = $invite;
    }

    public static function get(Player $player) : ?Invite{
        return self::$sessions[$player] ?? null;
    }

    public static function remove(Player $player) : void{
        unset(self::$sessions[$player]);
    }

    public static function has(Player $player) : bool{
        return isset(self::$sessions[$player]);
    }

    public static function getAll() : WeakMap{
        return self::$sessions;
    }

    public static function count() : int{
        return count(self::$sessions);
    }
}