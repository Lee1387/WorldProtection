<?php

declare(strict_types=1);

namespace Lee1387\WorldProtection;

use pocketmine\plugin\PluginBase;
use Lee1387\WorldProtection\language\LanguageManager;

class Loader extends PluginBase {

    protected function onEnable(): void 
    {
        $this->saveDefaultConfig();
        LanguageManager::init($this, $this->getConfig()->get("defaultLanguage", null));
    }
}