<?php

declare(strict_types=1);

namespace Lee1387\WorldProtection\event;

use pocketmine\event\block\BlockBreakEvent as PMBlockBreakEvent;
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

class BlockBreakEvent implements Listener {

    /**
     * @throws ReflectionException
     */
    public function __construct(Loader $plugin) {
        $plugin->getServer()->getPluginManager()->registerEvent(
            PMBlockBreakEvent::class,
            $this->onBreakBlock(...),
            EventPriority::HIGHEST,
            $plugin
        );
        $plugin->getServer()->getPluginManager()->registerEvent(
            PMBlockPlaceEvent::class,
            $this->onPlaceBlock(...),
            EventPriority::HIGHEST,
            $plugin
        );
    }

    public function onBreakBlock(PMBlockBreakEvent $event): void {
        $this->checkBreak($event);
    }

    public function onPlaceBlock(PMBlockPlaceEvent $event): void
    {
        $this->checkBreak($event);
    }

    protected function checkBreak(PMBlockBreakEvent|PMBlockPlaceEvent $event): void {
        $world = $event->getPlayer()->getWorld();
        $isLock = WorldManager::getProperty(
            world: $world, 
            property: WorldProperty::BUILD
        ) ?? false;
        $player = $event->getPlayer();
        if (
            $isLock &&
            !$player->hasPermission("worldprotection.build.bypass")
        ) {
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