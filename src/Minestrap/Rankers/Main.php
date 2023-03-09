<?php

namespace Minestrap\Rankers;

use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

use Minestrap\Rankers\Events\StatsAdd;
use Minestrap\Rankers\Events\StatsCounter;

use Minestrap\Rankers\Commands\StatsCommand;

class Main extends PluginBase {

    /** @var Config */
    private $config;

    /** @var Config */
    private $players;

    //==============================
    //       ENABLE FUNCTION
    //==============================        

    public function onEnable(): void {
        $this->saveDefaultConfig();
        $this->config = new Config($this->getDataFolder() . "config.yml" . Config::YAML);
        $this->players = new Config($this->getDataFolder() . "players.yml" . Config::YAML);

        $this->getServer()->getPluginManager()->registerEvents(new StatsAdd($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new StatsCounter($this), $this);
    }

    //==============================
    //      DISABLE FUNCTION
    //==============================        

    public function onDisable(): void {
        $this->players->save();
    }

    //==============================
    //      GET PLAYERS CONFIG
    //==============================        

    public function getPlayers(): Config {
        return $this->players;
    }

    //==============================
    //      GET GENERAL CONFIG
    //==============================        

    public function getConfig(): Config  {
        return $this->config;
    }
}