<?php

declare(strict_types=1);

namespace phuongaz\inviterewards\form;

use jojoe77777\FormAPI\SimpleForm;
use phuongaz\inviterewards\invite\Invite;
use phuongaz\inviterewards\util\Util;

class MainForm extends SimpleForm {

    public function __construct(Invite $session, string $notice = "") {
        parent::__construct($this->getCallable());
        $this->setTitle(Util::getMessage("main-form-title"));
        $this->setContent((strlen($notice) > 0 ? $notice . "\n" : "") . Util::getMessage("main-form-content", ["{code}" => $session->getInviteCode()]));
        $this->addButton(Util::getMessage("main-form-button-invite"));
        $this->addButton(Util::getMessage("main-form-button-reward"));
    }

    public function getCallable(): ?callable{
        return function($player, $data) {
            if(is_null($data)) return;
            match ($data) {
                0 => $player->sendForm(new EnterCodeForm()),
                1 => $player->sendForm(new RewardForm($player))
            };
        };
    }
}