<?php

declare(strict_types=1);

namespace Lee1387\WorldProtection\event;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use Lee1387\WorldProtection\language\KnownTranslations;
use Lee1387\WorldProtection\language\LanguageManager;
use Lee1387\WorldProtection\language\TranslationKeys;
use Lee1387\WorldProtection\world\WorldManager;

class BreakBlockEvent implements Listener 
{

    public function onBreakBlock(BlockBreakEvent $event): void 
    {
        $world = $event->getBlock()->getPosition()->getWorld();
        $isLock = WorldManager::getProperty(
            world: $world, 
            property: "lock"
        );
        $player = $event->getPlayer();
        if($isLock) {
            $player->sendMessage(
                message: LanguageManager::getTranslation(
                    key: KnownTranslations::WORLD_LOCKED,
                    replacements: [
                        TranslationKeys::WORLD => $world->getDisplayName()
                    ]
                )
            );
            $event->cancel();
        }
    }
}