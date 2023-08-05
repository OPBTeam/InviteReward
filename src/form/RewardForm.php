<?php

declare(strict_types=1);

namespace phuongaz\inviterewards\form;

use jojoe77777\FormAPI\SimpleForm;
use phuongaz\inviterewards\invite\Session;
use pocketmine\player\Player;

class RewardForm extends SimpleForm {

    public function __construct(Player $player) {
        parent::__construct($this->getCallable());
        $session = Session::get($player);
        //TODO: Add reward form
    }

    public function getCallable(): ?callable{
        //TODO: Add reward handler
        return function ($player, $data) {
            if (is_null($data)) return;
        };
    }
}