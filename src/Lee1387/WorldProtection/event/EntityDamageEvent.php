<?php

declare(strict_types=1);

namespace Lee1387\WorldProtection\event;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent as PMEntityDamageEvent;
use pocketmine\event\EventPriority;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use ReflectionException;
use Lee1387\WorldProtection\language\KnownTranslations;
use Lee1387\WorldProtection\language\LanguageManager;
use Lee1387\WorldProtection\language\TranslationKeys;
use Lee1387\WorldProtection\Loader;
use Lee1387\WorldProtection\world\WorldManager;
use Lee1387\WorldProtection\world\WorldProperty;

class EntityDamageEvent implements Listener {

    /**
     * @throws ReflectionException
     */
    public function __construct(Loader $plugin) {
        $plugin->getServer()->getPluginManager()->registerEvent(
            PMEntityDamageEvent::class,
            $this->onEntityDamage(...),
            EventPriority::HIGHEST,
            $plugin
        );
    }

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
            property: WorldProperty::PVP
        ) ?? false;
        if ($pvp) {
            $damage->sendMessage(
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