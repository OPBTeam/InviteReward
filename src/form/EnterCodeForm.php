<?php

declare(strict_types=1);

namespace phuongaz\inviterewards\form;

use jojoe77777\FormAPI\CustomForm;
use phuongaz\inviterewards\handle\PlayerEnterInviteCodeEvent;
use phuongaz\inviterewards\invite\Session;
use phuongaz\inviterewards\util\Util;
use pocketmine\player\Player;

class EnterCodeForm extends CustomForm {

    public function __construct() {
        parent::__construct($this->getCallable());
        $this->setTitle(Util::getMessage("enter-code-form-title"));
        $this->addInput(Util::getMessage("enter-code-form-label"), Util::getMessage("enter-code-form-input-placeholder"));
    }

    public function getCallable(): ?callable{
        return function(Player $player, ?array $data) {
            if(is_null($data)) return;
            if(strlen($data[0]) < 1) {
                $player->sendForm(new MainForm(Session::get($player), Util::getMessage("enter-code-form-input-error-empty")));
            }
            $event = new PlayerEnterInviteCodeEvent($player, $data[0]);
            $event->call();
        };
    }
}