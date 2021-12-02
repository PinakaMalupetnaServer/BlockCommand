<?php

namespace BlockCommand;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\VanillaItems;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as C;
use function pocketmine\inventory\transaction\getItem;

class one extends PluginBase implements Listener
{

    public $command;

    protected function onEnable() : void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getLogger()->notice("Created by princepines, contributed by many.");
        @mkdir($this->getDataFolder());
        $this->saveResource("config.yml");

    }


    public function onRun(PlayerMoveEvent $event): void
    {
        $player = $event->getPlayer();
        $inv = $player->getInventory();
        $armor = $player->getArmorInventory();
        $name = $player->getName();
        //$level = $player->getLevel();
        $level = $player->getWorld();
        //$block = $player->getLevel()->getBlock($player->subtract(0, 1, 0));
        $block = $player->getWorld()->getBlock($player->getPosition()->down());
        $fileConfig = new Config($this->getDataFolder() . "config.yml", Config::YAML);


        // contains items and armors
        $fhotbarItem = VanillaItems::DIAMOND_SWORD(); //Item::get(276, 0, 1); // usually swords
        $items = [$fhotbarItem, VanillaItems::STEAK()->setCount(32), VanillaItems::ENDER_PEARL()->setCount(16), VanillaItems::GOLDEN_APPLE()->setCount(32), VanillaItems::REGENERATION_SPLASH_POTION()->setCount(1), VanillaItems::REGENERATION_SPLASH_POTION()->setCount(1), VanillaItems::REGENERATION_SPLASH_POTION()->setCount(1), VanillaItems::REGENERATION_SPLASH_POTION()->setCount(1), VanillaItems::REGENERATION_SPLASH_POTION()->setCount(1), VanillaItems::REGENERATION_SPLASH_POTION()->setCount(1), VanillaItems::REGENERATION_SPLASH_POTION()->setCount(1), VanillaItems::REGENERATION_SPLASH_POTION()->setCount(1), VanillaItems::REGENERATION_SPLASH_POTION()->setCount(1), VanillaItems::REGENERATION_SPLASH_POTION()->setCount(1), VanillaItems::REGENERATION_SPLASH_POTION()->setCount(1), VanillaItems::REGENERATION_SPLASH_POTION()->setCount(1), VanillaItems::REGENERATION_SPLASH_POTION()->setCount(1), VanillaItems::REGENERATION_SPLASH_POTION()->setCount(1), VanillaItems::REGENERATION_SPLASH_POTION()->setCount(1), VanillaItems::REGENERATION_SPLASH_POTION()->setCount(1), VanillaItems::REGENERATION_SPLASH_POTION()->setCount(1), VanillaItems::REGENERATION_SPLASH_POTION()->setCount(1), VanillaItems::REGENERATION_SPLASH_POTION()->setCount(1), VanillaItems::REGENERATION_SPLASH_POTION()->setCount(1), VanillaItems::REGENERATION_SPLASH_POTION()->setCount(1), VanillaItems::REGENERATION_SPLASH_POTION()->setCount(1), VanillaItems::REGENERATION_SPLASH_POTION()->setCount(1), VanillaItems::REGENERATION_SPLASH_POTION()->setCount(1), VanillaItems::REGENERATION_SPLASH_POTION()->setCount(1), VanillaItems::REGENERATION_SPLASH_POTION()->setCount(1), VanillaItems::REGENERATION_SPLASH_POTION()->setCount(1), VanillaItems::REGENERATION_SPLASH_POTION()->setCount(1), VanillaItems::REGENERATION_SPLASH_POTION()->setCount(1), VanillaItems::REGENERATION_SPLASH_POTION()->setCount(1), VanillaItems::REGENERATION_SPLASH_POTION()->setCount(1), VanillaItems::REGENERATION_SPLASH_POTION()->setCount(1), VanillaItems::REGENERATION_SPLASH_POTION()->setCount(1), VanillaItems::REGENERATION_SPLASH_POTION()->setCount(1), VanillaItems::REGENERATION_SPLASH_POTION()->setCount(1)]; // the rest of items
        $setHelmet = VanillaItems::DIAMOND_HELMET(); //Item::get(310, 0, 1);
        $setChestplate = VanillaItems::DIAMOND_CHESTPLATE(); //Item::get(311, 0, 1);
        $setLeggings = VanillaItems::DIAMOND_LEGGINGS(); //Item::get(312, 0, 1);
        $setBoots = VanillaItems::DIAMOND_BOOTS(); //Item::get(313, 0, 1);

        // contains enchant arrays
        $fhotbarEnchant = [new EnchantmentInstance(VanillaEnchantments::FIRE_ASPECT(), 2),
            new EnchantmentInstance(VanillaEnchantments::SHARPNESS(),3),
            new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3)];
        $helmetEnchant = [new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3),
            new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 2)];
        $chestEnchant = [new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3),
            new EnchantmentInstance(VanillaEnchantments::FIRE_PROTECTION(), 2)];
        $leggingsEnchant = [new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 3)];
        $bootsEnchant = [new EnchantmentInstance(VanillaEnchantments::UNBREAKING(),3)];

        // contains foreach
        foreach ($fhotbarEnchant as $enchant) {
            $fhotbarItem->addEnchantment($enchant);
        }

        foreach ($helmetEnchant as $enchant) {
            $setHelmet->addEnchantment($enchant);
        }

        foreach ($chestEnchant as $enchant) {
            $setChestplate->addEnchantment($enchant);
        }

        foreach ($leggingsEnchant as $enchant) {
            $setLeggings->addEnchantment($enchant);
        }

        foreach ($bootsEnchant as $enchant) {
            $setBoots->addEnchantment($enchant);
        }

        if ($block->getId() === 0) return;
        if ($level->getFolderName() === $fileConfig->get('world')) {
            if ($block->getId() === $fileConfig->get('block-id')) {
                if (empty($this->cooldown[$player->getName()])) {
                    $this->cooldown[$player->getName()] = time() + 20; // 20 is a second of cooldown
                    //$this->getServer()->dispatchCommand(new ConsoleCommandSender(), "rca " . $name . " " ."kit kit");
                    $inv->clearAll();
                    foreach ($items as $item) {
                        $inv->addItem($item); // returns all items to player
                    }
                    $armor->setHelmet($setHelmet);
                    $armor->setChestplate($setChestplate);
                    $armor->setLeggings($setLeggings);
                    $armor->setBoots($setBoots);
                } else {
                    if (time() < $this->cooldown[$player->getName()]) {

                    } else {
                        unset($this->cooldown[$player->getName()]);
                    }
                }
            }
        }
    }

    protected function onDisable() : void {
        $this->getServer()->getLogger()->notice("Created by princepines, contributed by many.");
    }
}
