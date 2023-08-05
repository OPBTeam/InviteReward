<?php

declare(strict_types=1);

namespace phuongaz\inviterewards\handle;

use phuongaz\inviterewards\invite\Session;
use phuongaz\inviterewards\InviteRewards;
use phuongaz\inviterewards\util\Util;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\player\Player;

class EventHandle implements Listener {

    public function onJoin(PlayerJoinEvent $event) : void{
        $player = $event->getPlayer();
        $provider = InviteRewards::getInstance()->getProvider();
        if(!$player->hasPlayedBefore()) {
            $createAt = new \DateTime();
            $provider->insert($player, $createAt->format("Y-m-d H:i:s"));
        }
        $provider->session($player);
    }

    public function onEnterCode(PlayerEnterInviteCodeEvent $event) :void{
        $player = $event->getPlayer();
        $provider = InviteRewards::getInstance()->getProvider();
        $session = Session::get($player);
        $timeLimit = InviteRewards::getInstance()->getConfig()->get("online-time-limit");
        if(!Util::checkCreateAt($session->getCreatedAt())){
            $player->sendMessage(Util::getMessage("online-time-limit", ["time" => $timeLimit]));
            $event->cancel();
        }
        $inviter = Util::getInviterFromCode($event->getCode());
        $provider->get($inviter, function (array $data) use ($provider, $inviter, $event, $session) : void{
            $player = $event->getPlayer();
            if(count($data) === 0){
                $player->sendMessage(Util::getMessage("invalid-invite-code", ["code" => $event->getCode()]));
                return;
            }
            $userInfo = Util::decodeUserInfo($data["user_info"]);
            if($session->compareUserInfo($userInfo)) {
                $player->sendMessage(Util::getMessage("enter-your-code-another-account", ["account" => ($inviter instanceof Player ? $inviter->getName() : $inviter)]));
                $event->cancel();
                return;
            }
            if($inviter instanceof Player) {
                $inviter->sendMessage(Util::getMessage("add-invite", ["inviter" => $player->getName(), "invites" => $data["invites"] + 1]));
            }
            $provider->add($inviter);
        });
    }
}