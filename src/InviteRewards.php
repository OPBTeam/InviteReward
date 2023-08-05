<?php

declare(strict_types=1);

namespace phuongaz\inviterewards;

use phuongaz\inviterewards\command\InviteCommand;
use phuongaz\inviterewards\invite\Session;
use phuongaz\inviterewards\provider\SQLiteProvider;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use poggit\libasynql\DataConnector;
use poggit\libasynql\libasynql;

class InviteRewards extends PluginBase {
    use SingletonTrait;

    private DataConnector $connector;

    private SQLiteProvider $provider;
    private array|false $lang;


    public function onLoad(): void {
        self::setInstance($this);
    }

    public function onEnable() : void{
        $this->saveDefaultConfig();
        $this->connector = libasynql::create($this, $this->getConfig()->get("database"), [
            "sqlite" => "sqlite.sql"
        ]);
        $this->provider = new SQLiteProvider($this, $this->connector);
        $this->lang = parse_ini_file($this->getDataFolder() . "language.ini");
        $this->getServer()->getCommandMap()->register("inviterewards", new InviteCommand());
        Session::init();
    }

    public function getProvider() : SQLiteProvider{
        return $this->provider;
    }

    public function getLanguage() : array|false{
        return $this->lang;
    }
}