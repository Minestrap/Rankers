<?php

namespace Minestrap\Rankers\Commands;

use Minestrap\Rankers\Main;
use pocketmine\event\Listener;

use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use Minestrap\Rankers\Forms\StatsMenuForm;

class StatsCommand extends Command {

    /** @var Main */
    private $main;

    /** @var Config */
    private $config;

    /** @var Players */
    private $players;

    //==============================
    //      BASE CONSTRUCTOR
    //==============================

    public function __construct(Main $main) {
        parent::__construct("stats", "Show the stats of a player", "/stats <player>");
        $this->setPermission("rankers.command.stats");
        $this->main = $main;
        $this->config = $this->main->getConfig();
        $this->players = $this->main->getPlayers();
    }

    //==============================
    //     COMMAND BASE CREATOR
    //==============================
    
    public function execute(CommandSender $sender, string $label, array $args) {
        if(!$sender instanceof Player) {
            $sender->sendMessage($this->config->get("in-game-message"));
            return;
        }

        if(!$this->testPermission($sender)) {
            $sender->sendMessage($this->config->get("no-permission-message"));
            return;
        }

        if($this->config->get("commands-by-forms")) {
            $statsMenuForm = new StatsMenuForm();
            $sender->sendForm($statsMenuForm);
        
        } else {
            // TO DO
        }
    }
}