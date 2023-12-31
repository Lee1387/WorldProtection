<?php

declare(strict_types=1);

namespace Lee1387\WorldProtection\event;

use pocketmine\event\EventPriority;
use pocketmine\event\Listener;
use pocketmine\event\server\CommandEvent as PMCommandEvent;
use pocketmine\player\Player;
use ReflectionException;
use Lee1387\WorldProtection\language\KnownTranslations;
use Lee1387\WorldProtection\language\LanguageManager;
use Lee1387\WorldProtection\language\TranslationKeys;
use Lee1387\WorldProtection\Loader;
use Lee1387\WorldProtection\world\WorldManager;
use Lee1387\WorldProtection\world\WorldProperty;

class CommandEvent implements Listener {

    /**
     * @throws ReflectionException
     */
    public function __construct(Loader $plugin) {
        $plugin->getServer()->getPluginManager()->registerEvent(
            PMCommandEvent::class,
            $this->onCommand(...),
            EventPriority::HIGHEST,
            $plugin
        );
    }

    public function onCommand(PMCommandEvent $event): void {
        $player = $event->getSender();
        if (!$player instanceof Player) return;
        $world = $player->getWorld();
        $command = $event->getCommand();
        $worldCommandBanned = WorldManager::getProperty(
            world: $world,
            property: WorldProperty::BAN_COMMAND
        ) ?? [];
        $commandMap = $player->getServer()->getCommandMap()->getCommand($command);
        if($commandMap === null) return;
        $permissions = $commandMap->getPermissions();
        foreach ($permissions as $permission) {
            if (in_array($permission, $worldCommandBanned)) {
                $player->sendMessage(
                    message: LanguageManager::getTranslation(
                        key: KnownTranslations::WORLD_BAN_COMMAND,
                        replacements: [
                            TranslationKeys::WORLD => $world->getDisplayName(),
                            TranslationKeys::COMMAND => $command
                        ]
                    )
                );
                $event->cancel();
            }
            break;
        }
    }
}