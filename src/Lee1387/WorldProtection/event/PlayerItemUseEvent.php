<?php

declare(strict_types=1);

namespace Lee1387\WorldProtection\event;

use pocketmine\event\EventPriority;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemUseEvent as PMPlayerItemUseEvent;
use Lee1387\WorldProtection\language\KnownTranslations;
use Lee1387\WorldProtection\language\LanguageManager;
use Lee1387\WorldProtection\language\TranslationKeys;
use Lee1387\WorldProtection\Loader;
use Lee1387\WorldProtection\world\WorldManager;
use Lee1387\WorldProtection\world\WorldProperty;

class PlayerItemUseEvent implements Listener {

    public function __construct(Loader $plugin) {
        $plugin->getServer()->getPluginManager()->registerEvent(
            PMPlayerItemUseEvent::class,
            \Closure::fromCallable([$this, "onItemUse"]),
            EventPriority::HIGH,
            $plugin
        );
    }

    public function onItemUse(PMPlayerItemUseEvent $event): void 
    {
        $player = $event->getPlayer();
        $world = $player->getWorld();
        $itemTypeId = $event->getItem()->getTypeId();
        $worldItemBanned = WorldManager::getProperty(
            world: $world,
            property: WorldProperty::BAN_ITEM
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