<?php

namespace Minestrap\Rankers\Events;

use Minestrap\Rankers\Main;
use pocketmine\event\Listener;

use pocketmine\event\player\PlayerJoinEvent;

class StatsAdd implements Listener {

    /** @var Main */
    private $main;

    /** @var Config */
    private $config;

    /** @var Config */
    private $players;

    //==============================
    //     LISTENER CONSTRUCTOR
    //==============================        

    public function __construct(Main $main) {
        $this->main = $main;
        $this->config = $this->main->getConfig();
        $this->players = $this->main->getPlayers();
    }

    //==============================
    //      JOIN ADD PLUGIN.YML
    //==============================        

    public function onJoin(PlayerJoinEvent $event) {
        $player = $event->getPlayer();
        $name = $player->getName();

        if(!$this->players->exists($name)) {
            $config = [
                "# $name Stats" "\n",
                "total-kills" => 0,
                "total-deaths" => 0,
                "blocks-placed" => 0,
                "blocks-breaked" => 0,
                "\n"
            ];
            
            $this->players->set($name, $config);
            $this->players->save();
        }
    }
}