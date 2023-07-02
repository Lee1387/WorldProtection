<?php

declare(strict_types=1);

namespace Lee1387\WorldProtection\event;

use pocketmine\event\server\CommandEvent as PMCommandEvent;
use pocketmine\player\Player;
use Lee1387\WorldProtection\language\KnownTranslations;
use Lee1387\WorldProtection\language\LanguageManager;
use Lee1387\WorldProtection\language\TranslationKeys;
use Lee1387\WorldProtection\world\WorldManager;
use Lee1387\WorldProtection\world\WorldProperty;

class CommandEvent 
{

    public function onCommand(PMCommandEvent $event): void {
        $player = $event->getSender();
        if (!$player instanceof Player) return;
        $world = $player->getWorld();
        $commandLine = trim($event->getCommand());
        if ($commandLine === "") return;
        $commandLine = preg_split("/\s+/", $commandLine);
        $command = strtolower($commandLine[0] ?? "");
        $worldCommandBanned = WorldManager::getProperty(
            world: $world,
            property: WorldPropety::BAN_COMMAND
        );
        if (is_array($worldCommandBanned)) {
            if (in_array($command, $worldCommandBanned)) {
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
        }
    }
}