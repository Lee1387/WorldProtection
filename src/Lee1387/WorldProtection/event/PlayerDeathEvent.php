<?php

declare(strict_types=1);

namespace Lee1387\WorldProtection\event;

use pocketmine\event\EventPriority;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent as PMPlayerDeathEvent;
use Lee1387\WorldProtection\language\KnownTranslations;
use Lee1387\WorldProtection\language\LanguageManager;
use Lee1387\WorldProtection\language\TranslationKeys;
use Lee1387\WorldProtection\Loader;
use Lee1387\WorldProtection\world\WorldManager;
use Lee1387\WorldProtection\world\WorldProperty;

class PlayerDeathEvent implements Listener {

    public function __construct(Loader $plugin) {
        $plugin->getServer()->getPluginManager()->registerEvent(
            PMPlayerDeathEvent::class,
            \Closure::fromCallable([$this, "onDeath"]),
            EventPriority::HIGH,
            $plugin
        );
    }

    public function onDeath(PMPlayerDeathEvent $event): void {
        $player = $event->getPlayer();
        $world = $player->getWorld();
        $keepInventory = WorldManager::getProperty(
            world: $world,
            property: WorldProperty::KEEP_INVENTORY
        );
        $keepExperience = WorldManager::getProperty(
            world: $world,
            property: WorldProperty::KEEP_EXPERIENCE
        );
        if ($keepInventory) {
            $event->setKeepInventory(true);
            $player->sendMessage(
                message: LanguageManager::getTranslation(
                    key: KnownTranslations::WORLD_KEEP_INVENTORY,
                    replacements: [
                        TranslationKeys::WORLD => $world->getDisplayName()
                    ]
                )
            );
        }
        if ($keepExperience) {
            $event->setKeepXp(true);
            $player->sendMessage(
                message: LanguageManager::getTranslation(
                    key: KnownTranslations::WORLD_KEEP_EXPERIENCE,
                    replacements: [
                        TranslationKeys::WORLD => $world->getDisplayName()
                    ]
                )
            );
        }
    }
}