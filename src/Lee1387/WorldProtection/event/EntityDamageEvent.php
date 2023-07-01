<?php

declare(strict_types=1);

namespace Lee1387\WorldProtection\event;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent as PMEntityDamageEvent;
use pocketmine\player\Player;
use Lee1387\WorldProtection\language\KnownTranslations;
use Lee1387\WorldProtection\language\LanguageManager;
use Lee1387\WorldProtection\language\TranslationKeys;
use Lee1387\WorldProtection\world\WorldManager;

class EntityDamageEvent
{

    public function onEntityDamage(PMEntityDamageEvent $event): void {
        if ($event->isCancelled()) return;
        if (!$event instanceof EntityDamageByEntityEvent) return;
        $player = $event->getEntity();
        $damage = $event->getDamager();
        if (
            !$player instanceof Player &&
            !$damage instanceof Player 
        ) return;
        $world = $player->getWorld();
        $pvp = WorldManager::getProperty(
            world: $world,
            property: "pvp"
        );
        if ($pvp) {
            $player->sendMessage(
                message: LanguageManager::getTranslation(
                    key: KnownTranslations::WORLD_PVP,
                    replacements: [
                        TranslationKeys::WORLD => $world->getDisplayName()
                    ]
                )
            );
            $event->cancel();
        }
    }
}