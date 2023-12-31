<?php

declare(strict_types=1);

namespace Lee1387\WorldProtection\commands\subcmds;

use pocketmine\command\CommandSender;
use pocketmine\Server;
use Lee1387\WorldProtection\language\KnownTranslations;
use Lee1387\WorldProtection\language\LanguageManager;
use Lee1387\WorldProtection\language\TranslationKeys;
use Lee1387\WorldProtection\world\WorldManager;
use Lee1387\WorldProtection\world\WorldProperty;

class KeepExperience
{

    public function __construct(CommandSender $sender, array $args){
        $this->execute($sender, $args);
    }

    protected function execute(CommandSender $sender, array $args): void {
        $world = Server::getInstance()->getWorldManager()->getWorldByName($args[0] ?? "");
        $value = $args[1] ?? null;
        if ($value === "true") {
            $value = true;
        } elseif ($value === "false") {
            $value = false;
        }
        if ($world === null) {
            $sender->sendMessage(
                LanguageManager::getTranslation(
                    KnownTranslations::COMMAND_WORLD_NOT_FOUND,
                    [
                        TranslationKeys::WORLD => $args[0] ?? ""
                    ]
                )
            );
            return;
        }
        if ($value === null) {
            $sender->sendMessage(
                LanguageManager::getTranslation(
                    KnownTranslations::COMMAND_KEEP_EXPERIENCE_USAGE
                )
            );
            return;
        }
        WorldManager::setProperty(
            world: $world,
            property: WorldProperty::KEEP_EXPERIENCE,
            value: $value
        );
        $sender->sendMessage(
            LanguageManager::getTranslation(
                KnownTranslations::COMMAND_KEEP_EXPERIENCE_SUCCESS,
                [
                    TranslationKeys::WORLD => $world->getDisplayName(),
                    TranslationKeys::VALUE => $value ? "true" : "false"
                ]
            )
        );
    }
}