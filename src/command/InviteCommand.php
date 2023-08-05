<?php

declare(strict_types=1);

namespace phuongaz\inviterewards\command;

use phuongaz\inviterewards\form\MainForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class InviteCommand extends Command {

    public function __construct()
    {
        parent::__construct("inviterewards", "InviteRewards command", "/inviterewards", ["ir", "invite"]);
        $this->setPermission("inviterewards.command");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) :bool{
        if($sender instanceof Player) {
            $sender->sendForm(new MainForm());
        }
        return false;
    }
}