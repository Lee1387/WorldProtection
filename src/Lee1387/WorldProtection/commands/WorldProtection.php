<?php 

declare(strict_types=1);

namespace Lee1387\WorldProtection\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use Lee1387\WorldProtection\commands\subcmds\AntiDecay;
use Lee1387\WorldProtection\commands\subcmds\BanCommand;
use Lee1387\WorldProtection\commands\subcmds\BanItem;
use Lee1387\WorldProtection\commands\subcmds\Build;
use Lee1387\WorldProtection\commands\subcmds\KeepExperience;
use Lee1387\WorldProtection\commands\subcmds\KeepInventory;
use Lee1387\WorldProtection\commands\subcmds\PvP;
use Lee1387\WorldProtection\language\KnownTranslations;
use Lee1387\WorldProtection\language\LanguageManager;
use Lee1387\WorldProtection\Loader;

class WorldProtection extends Command implements PluginOwned {

    public function __construct(protected Loader $plugin) {
        parent::__construct(
            name: "worldprotection",
            description:"WorldProtection command",
            usageMessage: "/worldprotection",
            aliases: ["wp"]
        );
        $this->setPermission("worldprotection.command");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if (!$this->testPermission($sender)) {
            $sender->sendMessage(
                LanguageManager::getTranslation(
                    key: KnownTranslations::COMMAND_PERMISSION,
                )
            );
        }
        $subcmd = isset($args[0]) ? strtolower(array_shift($args)) : null;
        match ($subcmd) {
            'build' => new Build($sender, $args),
            'pvp' => new PvP($sender, $args),
            'antidecay' => new AntiDecay($sender, $args),
            'keepinventory', 'keepinv' => new KeepInventory($sender, $args),
            'keepexperience', 'keepexp' => new KeepExperience($sender, $args),
            'banitem' => new BanItem($sender, $args, 'ban'),
            'unbanitem' => new BanItem($sender, $args, 'unban'),
            'bancommand', 'bancmd' => new BanCommand($sender, $args, 'ban'),
            'unbancommand', 'unbancmd' => new BanCommand($sender, $args, 'unban'),
            default => $sender->sendMessage(
                LanguageManager::getTranslation(
                    key: KnownTranslations::COMMAND_USAGE
                )
            ),
        };
    }

    /**
     * @return Loader
     */
    public function getOwningPlugin(): Plugin 
    {
        return $this->plugin;
    }
}