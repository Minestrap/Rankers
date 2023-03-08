<?php

namespace Minestrap\Rankers\Events;

use Minestrap\Rankers\Main;
use pocketmine\event\Listener;

use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;

class PlayerStats implements Listener {

    /** @var Main */
    private $main;

    /** @var Config */
    private $players;

    /** @var Config */
    private $config;

    // general listener constructor 
    public function __construct(Main $main) {
        $this->main = $main;
        $this->players = $this->main->getPlayers();
        $this->config = $this->main->getConfig();
    }

    // on join player yml actions
    public function onJoin(PlayerJoinEvent $event) {
        $player = $event->getPlayer();
        $playername = $player->getName();

        if(!$this->players->exists($playername)) {
            $default = [
                "total-kills" => 0,
                "total-deaths" => 0,
                "blocks-breaked" => 0,
                "blocks-placed" => 0
            ];
            $this->players->setNested($playername, $default);
            $this->players->save();
        }
    }

    // player break block event counter
    public function onBreak(BlockBreakEvent $event) {
        $player = $event->getPlayer();
        $playername = $player->getName();
        $worldName = $player->getWorld()->getFolderName();

        if($this->config->get("break-counter")) {
            if(in_array($worldName, $this->config->get("break-counter-worlds", []))) {
                
                // increment on player status
                $bb = $this->players->getNested("$playername.blocks-breaked", 0);
                $this->players->setNested("$playername.blocks-breaked", $bb + 1);
                $this->players->save();            
            }
        }
    }

    // player place block event counter
    public function onPlace(BlockPlaceEvent $event) {
        $player = $event->getPlayer();
        $playername = $player->getName();
        $worldName = $player->getWorld()->getFolderName();

        if($this->config->get("place-counter")) {
            if(in_array($worldName, $this->config->get("place-counter-worlds", []))) {
                
                // increment on player status
                $bp = $this->players->getNested("$playername.blocks-placed", 0);
                $this->players->setNested("$playername.blocks-placed", $bp + 1);
                $this->players->save();
            }
        }
    }
}