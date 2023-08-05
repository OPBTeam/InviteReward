<?php

declare(strict_types=1);

namespace phuongaz\inviterewards\form;

use jojoe77777\FormAPI\CustomForm;

class TopInvitesForm extends CustomForm {
    public function __construct() {
        parent::__construct($this->getCallable());
        $this->setTitle("§l§aTop Invites");
        $this->addLabel("§l§aTop Invites");
    }
}