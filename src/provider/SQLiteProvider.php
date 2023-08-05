<?php

declare(strict_types=1);

namespace phuongaz\inviterewards\provider;

use Closure;
use Exception;
use phuongaz\inviterewards\invite\Invite;
use phuongaz\inviterewards\invite\Session;
use phuongaz\inviterewards\util\Util;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\promise\Promise;
use pocketmine\promise\PromiseResolver;
use poggit\libasynql\DataConnector;
use poggit\libasynql\SqlError;

enum QueryString : string {
    case INIT = "invite.init";
    case ADD = "invite.add";
    case GET = "invite.get";
    case TOP = "invite.top";
    case RESET = "invite.reset";
    case INSERT = "invite.insert";
}

class SQLiteProvider {

    public function __construct(
        private PluginBase $plugin,
        private DataConnector $connector
    ) {
        $this->connector->executeGeneric(QueryString::INIT->value);
    }

    public function add(string|Player $player, ?Closure $onSuccess = null) : void{
        $this->connector->executeGeneric(QueryString::ADD->value, [
            "player" => ($player instanceof Player) ? $player->getName() : $player
        ], $onSuccess, fn (SqlError $error) => $this->plugin->getLogger()->error($error->getMessage()));
    }

    public function insert(Player $player, string $createAt) : void{
        $this->connector->executeGeneric(QueryString::INSERT->value, [
            "player" => $player->getName(),
            "invites" => 0,
            "createAt" => $createAt,
            "user_info" => Util::generateUserInfo($player)
        ], null, fn (SqlError $error) => $this->plugin->getLogger()->error($error->getMessage()));
    }

    public function get(string $username, ?Closure $closure = null) :Promise {
        $resolver = new PromiseResolver();
        $this->connector->executeSelect(QueryString::GET->value, [
            "player" => $username
        ], function (array $rows) use ($resolver, $closure) {
            if(count($rows) === 0){
                $resolver->resolve(null);
                return;
            }
            if(!is_null($closure)){
                $closure($rows);
            }
            $resolver->resolve($rows);
        });
        return $resolver->getPromise();
    }

    public function top(int $limit, \Closure $closure) : Promise{
        $resolver = new PromiseResolver();
        $this->connector->executeSelect(QueryString::TOP->value, [
            "limit" => $limit
        ], function (array $rows) use ($resolver, $closure) {
            $resolver->resolve($closure($rows));
        });
        return $resolver->getPromise();
    }

    public function reset(string $uniqueId) : void{
        $this->connector->executeGeneric(QueryString::RESET->value, [
            "player" => $uniqueId
        ], null, fn (SqlError $error) => $this->plugin->getLogger()->error($error->getMessage()));
    }

    public function session(Player $player) :Promise {
        $resolver = new PromiseResolver();
        $this->connector->executeSelect(QueryString::GET->value, [
            "player" => $player->getName()
        ], function (array $rows) use ($resolver) {
            $resolver->resolve($rows);
        });
        $resolver->getPromise()->onCompletion(
            function (array $rows) use ($player) {
            Session::create($player, Invite::fromArray($rows[0]));
        }, fn() => null);
        return $resolver->getPromise();
    }
}