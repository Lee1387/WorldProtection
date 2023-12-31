<?php

declare(strict_types=1);

namespace Lee1387\WorldProtection\event;

use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\EventPriority;
use pocketmine\event\player\PlayerGameModeChangeEvent;
use pocketmine\player\GameMode;
use pocketmine\player\Player;
use pocketmine\world\World;
use ReflectionException;
use Lee1387\WorldProtection\Loader;
use Lee1387\WorldProtection\world\WorldManager;
use Lee1387\WorldProtection\world\WorldProperty;

class WorldPerGModeEvent
{
    /**
     * @throws ReflectionException
     */
    public function __construct(Loader $plugin)
    {
        $plugin->getServer()->getPluginManager()->registerEvent(
            EntityTeleportEvent::class,
            $this->onEntityTeleport(...),
            EventPriority::HIGHEST,
            $plugin
        );
        $plugin->getServer()->getPluginManager()->registerEvent(
            PlayerGameModeChangeEvent::class,
            $this->onGMChange(...),
            EventPriority::HIGHEST,
            $plugin
        );
    }

    public function onEntityTeleport(EntityTeleportEvent $event): void
    {
        $player = $event->getEntity();
        $world = $player->getWorld();
        $GameMode = WorldManager::getProperty(
            world: $world,
            property: WorldProperty::GAMEMODE
        ) ?? false;
        if ($player instanceof Player) {
            if ($this->isChange($player, $world) && is_string($GameMode)) {
                $GameMode = GameMode::fromString($GameMode);
                $player->setGamemode($GameMode);
            }
        }
    }

    public function onGMChange(PlayerGameModeChangeEvent $event): void {
        $player = $event->getPlayer();
        $world = $player->getWorld();
        if (!$this->isChange($player, $world)) {
            $event->cancel();
        }
    }

    protected function isChange(Player $player, World $world): bool {
        $GameMode = WorldManager::getProperty(
            world: $world,
            property: WorldProperty::GAMEMODE
        );
        if (is_string($GameMode)) {
            $GameMode = GameMode::fromString($GameMode);
            return !$player->getGamemode()->equals($GameMode);
        }
        return true;
    }
}