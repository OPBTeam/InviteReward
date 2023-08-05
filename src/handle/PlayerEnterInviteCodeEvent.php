<?php

declare(strict_types=1);

namespace phuongaz\inviterewards\handle;

use pocketmine\event\CancellableTrait;
use pocketmine\event\player\PlayerEvent;
use pocketmine\player\Player;

class PlayerEnterInviteCodeEvent extends PlayerEvent {
    use CancellableTrait;

    private string $code;

    public function __construct(Player $player, string $code) {
        $this->player = $player;
        $this->code = $code;
    }

    public function getCode() : string{
        return $this->code;
    }
}