<?php

namespace Minestrap\Rankers;

use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

use Minestrap\Rankers\Events\PlayerStats;

class Main extends PluginBase {

    /** @var Config */
    private $config;

    /** @var Config */
    private $players;

    // General plugin enable function
    public function onEnable(): void {

        // Plugin resources save
        $this->saveDefaultConfig();
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        $this->players = new Config($this->getDataFolder() . "players.yml", Config::YAML);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerStats($this), $this);
    }
    
    // General plugin disable function
    public function onDisable(): void {
        $this->players->save();
    }

    // Get players folder config
    public function getPlayers(): Config {
        return $this->players;
    }

    // Get config folder
    public function getConfig(): Config {
        return $this->config;
    }
}