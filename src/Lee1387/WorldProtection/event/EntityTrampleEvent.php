<?php

declare(strict_types=1);

namespace Lee1387\WorldProtection\event;

use pocketmine\event\entity\EntityTrampleFarmlandEvent;
use Lee1387\WorldProtection\world\WorldManager;

class EntityTrampleEvent {

    public function onEntityTrample(EntityTrampleFarmlandEvent $event): void {
        $player = $event->getEntity();
        $world = $player->getWorld();
        $trample = WorldManager::getProperty(
            world: $world,
            property: "no-decay"
        );
        if ($trample) {
            $event->cancel();
        }
    }
}