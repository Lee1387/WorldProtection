<?php

declare(strict_types=1);

namespace Lee1387\WorldProtection\event;

use pocketmine\event\block\BlockPlaceEvent as PMBlockPlaceEvent;
use pocketmine\event\EventPriority;
use pocketmine\event\Listener;
use ReflectionException;
use Lee1387\WorldProtection\language\KnownTranslations;
use Lee1387\WorldProtection\language\LanguageManager;
use Lee1387\WorldProtection\language\TranslationKeys;
use Lee1387\WorldProtection\Loader;
use Lee1387\WorldProtection\world\WorldManager;
use Lee1387\WorldProtection\world\WorldProperty;

class BlockPlaceEvent implements Listener {

    /**
     * @throws ReflectionException
     */
    public function __construct(Loader $plugin){
        $plugin->getServer()->getPluginManager()->registerEvent(
            PMBlockPlaceEvent::class,
            $this->onPlaceBlock(...),
            EventPriority::HIGHEST,
            $plugin
        );
    }

    public function onPlaceBlock(PMBlockPlaceEvent $event): void{
        $world = $event->getPlayer()->getWorld();
        $isLock = WorldManager::getProperty(
            world: $world,
            property: WorldProperty::BUILD
        );
        $player = $event->getPlayer();
        if($isLock) {
            $player->sendMessage(
                message: LanguageManager::getTranslation(
                    key: KnownTranslations::WORLD_BUILD,
                    replacements: [
                        TranslationKeys::WORLD => $world->getDisplayName()
                    ]
                )
            );
            $event->cancel();
        }
    }
}