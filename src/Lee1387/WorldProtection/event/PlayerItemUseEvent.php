<?php

declare(strict_types=1);

namespace Lee1387\WorldProtection\event;

use pocketmine\event\player\PlayerItemUseEvent as PMPlayerItemUseEvent;
use Lee1387\WorldProtection\language\KnownTranslations;
use Lee1387\WorldProtection\language\LanguageManager;
use Lee1387\WorldProtection\language\TranslationKeys;
use Lee1387\WorldProtection\world\WorldManager;

class PlayerItemUseEvent
{

    public function onItemUse(PMPlayerItemUseEvent $event): void 
    {
        $player = $event->getPlayer();
        $world = $player->getWorld();
        $itemTypeId = $event->getItem()->getTypeId();
        $worldItemBanned = WorldManager::getProperty(
            world: $world,
            property: "item-ban"
        );
        if (is_array($worldItemBanned)) {
            if (in_array($itemTypeId, $worldItemBanned)) {
                $player->sendMessage(
                    message: LanguageManager::getTranslation(
                        key: KnownTranslations::WORLD_BAN_ITEM,
                        replacements: [
                            TranslationKeys::WORLD => $world->getDisplayName(),
                            TranslationKeys::ITEM => $event->getItem()->getName()
                        ]
                    )
                );
                $event->cancel();
            }
        }
    }
}