<?php

namespace BlockCommand;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as C;

class one extends PluginBase implements Listener
{

    public $command;

    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->alert("BlockCommandir Armed");
        $this->getLogger()->notice("Created by princepines, contributed by many.");
        $this->getLogger()->info(C::GREEN . "This version of this plugin is exclusive for PMnS only, for public repository check: https://github.com/lycol50/BlockCommandir");
    }

    public function onCommand(CommandSender $sender, Command $cd, string $label, array $args): bool
    {
        switch ($cd->getName()) {
            case "bcinfo":
                if ($sender instanceof Player) {
                    $api = $this->getServer()->getApiVersion();
                    $sender->sendMessage(C::YELLOW . "The BlockCommandir is created by princepines and contributed by many.");
                    $sender->sendMessage(C::AQUA . "BlockCommand Version: v1.0-PMnS, currently using " . $api . " API.");
                }
                break;
            default:
                break;
        }
        return true;
    }

    public function onMove(PlayerMoveEvent $event): void
    {
        $player = $event->getPlayer();
        $name = $player->getName();
        $level = $player->getLevel();
        $block = $player->getLevel()->getBlock($player->subtract(0, 1, 0));
        if ($block->getId() === 0) return;
        if ($level->getName() == "POTPVP") {
            if ($block->getId() === 182) {
                if (empty($this->cooldown[$player->getName()])) {
                    $this->cooldown[$player->getName()] = time() + 20; // 20 is a second of cooldown
                    $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "rca " . $name . " " ."kit kit");
                    $player->sendTip(C::BOLD . C::YELLOW . "Hey " . $name . ", Your are no longer on the Safe Zone, Be Careful!");
                } else {
                    if (time() < $this->cooldown[$player->getName()]) {

                    } else {
                        unset($this->cooldown[$player->getName()]);
                    }
                }
            }
        }
    }

    public function onDisable()
    {
        $this->getLogger()->alert("BlockCommandir Disarmed");
        $this->getLogger()->notice("Created by princepines, contributed by many.");
    }
}
