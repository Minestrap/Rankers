<?php

namespace Minestrap\Rankers\Events;

use Minestrap\Rankers\Main;
use pocketmine\player\Player;
use pocketmine\event\Listener;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;

class StatsCounter implements Listener {

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
    //     BLOCK BREAK COUNTER
    //==============================

    public function onBreak(BlockBreakEvent $event) {
        $player = $event->getPlayer();
        $name = $player->getName();
        $worldName = $player->getWorld()->getFolderName();

        if(!$this->config->get("break-counter")) {
            return;
        }

        if(!in_array($worldName, $this->config->get("break-counter-worlds", []))) {
            return;
        }

        if($this->players->exists($name)) {
            $this->players->setNested($name . "blocks-breaked", $this->players->getNested($name . ".blocks-breaked") + 1);
            $this->players->save();
        }
    }

    //==============================
    //     BLOCK PLACE COUNTER
    //==============================    

    public function onPlace(BlockPlaceEvent $event) {
        $player = $event->getPlayer();
        $name = $player->getName();
        $worldName = $player->getWorld()->getFolderName();

        if(!$this->config->get("place-counter")) {
            return;
        }

        if(!in_array($worldName, $this->config->get("place-counter-worlds", []))) {
            return;
        }

        if($this->players->exists($name)) {
            $this->players->setNested($name . "blocks-placed", $this->players->getNested($name . ".blocks-placed") + 1);
            $this->players->save();
        }
    }

    //==============================
    //    KILLS & DEATHS COUNTER
    //==============================    

    public function onDeath(PlayerDeathEvent $event) {
        $player = $event->getPlayer();
        $name = $player->getName();
        $cause = $player->getLastDamageCause();

        if($this->config->get("kill-counter")) {
            if(in_array($worldName, $this->config->get("kill-counter-worlds", []))) {
                if($cause instanceof EntityDamageByEntityEvent) {
                    $killer = $cause->getDamager();
                    if($killer instanceof Player) {
                        $killerName = $killer->getName();
                        $worldName = $player->getWorld()->getFolderName();

                        if($this->players->exists($killerName)) {
                            $this->players->setNested($killerName . ".total-kills", $this->players->getNested($killerName . ".total-kills") + 1);
                            $this->players->save();
                        }
                    }
                }
            }
        }

        if($this->config->get("death-counter") == true) {
            if(in_array($worldName, $this->config->get("death-counter-worlds", []))) {
                return;
            }

            if($this->players->exists($name)) {
                $this->players->setNested($name . ".total-deaths", $this->players->getNested($name . ".total-deaths") + 1);
                $this->players->save();
            }
        }
    }    
}