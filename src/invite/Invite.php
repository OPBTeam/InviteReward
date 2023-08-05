<?php

declare(strict_types=1);

namespace phuongaz\inviterewards\invite;

use DateTime;
use Exception;
use phuongaz\inviterewards\util\Util;

class Invite {

    public function __construct(
        private string $username,
        private int $invites,
        private DateTime $createdAt,
        private string $userInfo,
        private string $inviteCode = ""
    ) {
        $this->generateInviteCode();
    }

    public function addInvites(int $invites = 1) : void{
        $this->invites += $invites;
    }

    public function getUsername() : string{
        return $this->username;
    }

    public function getUserInfo() : array{
        return Util::decodeUserInfo($this->userInfo);
    }

    public function getInvites() : int{
        return $this->invites;
    }

    public function getCreatedAt() : DateTime{
        return $this->createdAt;
    }

    public function getInviteCode() : string{
        return $this->inviteCode;
    }

    public function generateInviteCode() : void{
        $this->inviteCode = Util::getMessage("prefix-code") . $this->getUsername();
    }

    public function compareUserInfo(array $userInfo) : bool{
        return array_diff($this->getUserInfo(), $userInfo) === [];
    }

    public function toArray() : array{
        return [
            "username" => $this->username,
            "invites" => $this->invites,
            "createAt" => $this->createdAt->format("Y-m-d H:i:s"),
            "inviteCode" => $this->inviteCode,
            "UserInfo" => Util::decodeUserInfo($this->userInfo)
        ];
    }

    /**
     * @throws Exception
     */
    public static function fromArray(array $data) : self{
        return new self(
            $data["username"],
            $data["invites"],
            new DateTime($data["createAt"]),
            $data["inviteCode"],
            $data["UserInfo"]
        );
    }
}