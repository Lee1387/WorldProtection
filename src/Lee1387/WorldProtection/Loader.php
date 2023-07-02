<?php

declare(strict_types=1);

namespace Lee1387\WorldProtection;

use pocketmine\plugin\PluginBase;
use Lee1387\WorldProtection\commands\WorldProtection;
use Lee1387\WorldProtection\event\BlockBreakEvent;
use Lee1387\WorldProtection\event\CommandEvent;
use Lee1387\WorldProtection\event\EntityDamageEvent;
use Lee1387\WorldProtection\event\EntityTrampleEvent;
use Lee1387\WorldProtection\event\PlayerDeathEvent;
use Lee1387\WorldProtection\event\PlayerItemUseEvent;
use Lee1387\WorldProtection\language\LanguageManager;
use Lee1387\WorldProtection\world\WorldManager;

class Loader extends PluginBase {

    protected const EVENTS = [
        BlockBreakEvent::class,
        EntityDamageEvent::class,
        EntityTrampleEvent::class,
        PlayerDeathEvent::class,
        PlayerItemUseEvent::class,
        CommandEvent::class
    ];

    protected function onEnable(): void 
    {
        $this->saveDefaultConfig();
        LanguageManager::init($this, $this->getConfig()->get("defaultLanguage", null));
        WorldManager::init();
        foreach (self::EVENTS as $event) {
            new $event($this);
        }
        $this->getServer()->getCommandMap()->register(
            fallbackPrefix: "worldprotection",
            command: new WorldProtection($this)
        );
    }
}