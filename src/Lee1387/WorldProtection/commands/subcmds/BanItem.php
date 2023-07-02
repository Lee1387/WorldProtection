<?php

declare(strict_types=1);

namespace Lee1387\WorldProtection\commands\subcmds;

use pocketmine\command\CommandSender;
use pocketmine\item\StringToItemParser;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\Server;
use Lee1387\WorldProtection\language\KnownTranslations;
use Lee1387\WorldProtection\language\LanguageManager;
use Lee1387\WorldProtection\language\TranslationKeys;
use Lee1387\WorldProtection\world\WorldManager;
use Lee1387\WorldProtection\world\WorldProperty;

class BanItem
{

    public function __construct(CommandSender $sender, array $args, string $type){
        $this->execute($sender, $args, $type);
    }

    protected function execute(CommandSender $sender, array $args, string $type): void {
        $world = Server::getInstance()->getWorldManager()->getWorldByName($args[0] ?? "");
        $value = $args[1] ?? "";
        if ($sender instanceof Player) {
            $item = $sender->getInventory()->getItemInHand();
            if (!$item->equals(VanillaItems::AIR())) {
                $value = StringToItemParser::getInstance()->lookupAliases($item);
            }
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
        if ($value === "") {
            $sender->sendMessage(
                LanguageManager::getTranslation(
                    KnownTranslations::COMMAND_BAN_ITEM_USAGE
                )
            );
            return;
        }
        if ($type === "ban") {
            foreach ($value as $item) {
                WorldManager::addProperty(
                    world: $world,
                    property: WorldProperty::BAN_ITEM,
                    value: $item
                );
            }
        } elseif ($type === "unban") {
            foreach ($value as $item) {
                WorldManager::removeProperty(
                    world: $world,
                    property: WorldProperty::BAN_ITEM,
                    value: $item
                );
            }
        }
        $sender->sendMessage(
            LanguageManager::getTranslation(
                ($type === "ban") ? KnownTranslations::COMMAND_BAN_ITEM_SUCCESS : KnownTranslations::COMMAND_UNBAN_ITEM_SUCCESS,
                [
                    TranslationKeys::WORLD => $world->getDisplayName(),
                    TranslationKeys::ITEM => implode(", ", $value)
                ]
            )
        );
    }
}