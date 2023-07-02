<?php

declare(strict_types=1);

namespace Lee1387\WorldProtection\event;

use pocketmine\event\entity\EntityTrampleFarmlandEvent;
use pocketmine\event\EventPriority;
use pocketmine\event\Listener;
use Lee1387\WorldProtection\Loader;
use Lee1387\WorldProtection\world\WorldManager;
use Lee1387\WorldProtection\world\WorldProperty;

class EntityTrampleEvent implements Listener {

    public function __construct(Loader $plugin) {
        $plugin->getServer()->getPluginManager()->registerEvent(
            EntityTrampleFarmlandEvent::class,
            \Closure::fromCallable([$this, "onEntityTrample"]),
            EventPriority::HIGH,
            $plugin
        );
    }

    public function onEntityTrample(EntityTrampleFarmlandEvent $event): void {
        $player = $event->getEntity();
        $world = $player->getWorld();
        $trample = WorldManager::getProperty(
            world: $world,
            property: WorldProperty::NO_DECAY
        );
        if ($trample) {
            $event->cancel();
        }
    }
}