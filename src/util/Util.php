<?php

declare(strict_types=1);

namespace phuongaz\inviterewards\util;

use DateTime;
use phuongaz\inviterewards\InviteRewards;
use pocketmine\player\Player;
use pocketmine\Server;

class Util {

    public static function getInviterFromCode(string $code) : Player|string{
        $inviter = substr($code, 0, -4);
        if(($player = Server::getInstance()->getPlayerExact($inviter)) instanceof Player) {
            return $player;
        }
        return substr($code, 0, -4);
    }

    /**
     * Get message from language
     *
     * @param string $message
     * @param array $replace<key, value>
     *
     * @return string
     */
    public static function getMessage(string $message, array $replace = []) : string{
        $lang = InviteRewards::getInstance()->getLanguage();
        return str_replace(array_keys($replace), array_values($replace), $lang[$message]);
    }

    /**
     * Check online player time
     *
     * @param DateTime $createAt
     * @return bool
     */
    public static function checkCreateAt(DateTime $createAt) : bool{
        $config = InviteRewards::getInstance()->getConfig();
        $onlineTimeLimit = $config->get("online-time-limit");
        $now = new DateTime();
        $diff = $now->diff($createAt);
        $minutes = $diff->days * 24 * 60;
        $minutes += $diff->h * 60;
        $minutes += $diff->i;
        return $minutes >= $onlineTimeLimit;
    }

    public static function generateUserInfo(Player $player) :string {
        $data = [];
        $data["name"] = $player->getName();
        $data["ip"] = $player->getNetworkSession()->getIp();
        $data["ClientId"] = $player->getPlayerInfo()->getExtraData()['ClientId'];
        $data["DeviceId"] = $player->getPlayerInfo()->getExtraData()['DeviceId'];
        return base64_encode(json_encode($data));
    }

    public static function decodeUserInfo(string $data) : array {
        return json_decode(base64_decode($data), true);
    }

}