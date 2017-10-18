<?php

namespace ElementaryBans\translation;

use ElementaryBans\exception\TranslationFailedException;
use InvalidArgumentException;
use pocketmine\command\Command;
use pocketmine\utils\TextFormat;

use ElementaryBans\ElementaryBans;

class Translation {
    
    public static function translate(string $translation) : string {
        switch ($translation) {
            case "noPermission":
                return TextFormat::RED . ElementaryBans::getMessage("noPermission");
            case "playerNotFound":
                return TextFormat::GOLD . ElementaryBans::getMessage("playerNotFound");
            case "playerAlreadyBanned":
                return TextFormat::GOLD . ElementaryBans::getMessage("playerAlreadyBanned");
            case "ipAlreadyBanned":
                return TextFormat::GOLD . ElementaryBans::getMessage("ipAlreadyBanned");
            case "ipNotBanned":
                return TextFormat::GOLD . ElementaryBans::getMessage("ipNotBanned");
            case "ipAlreadyMuted":
                return TextFormat::GOLD . ElementaryBans::getMessage("ipAlreadyMuted");
            case "playerNotBanned":
                return TextFormat::GOLD . ElementaryBans::getMessage("playerNotBanned");
            case "playerAlreadyMuted":
                return TextFormat::GOLD . ElementaryBans::getMessage("playerAlreadyMuted");
            case "playerNotMuted":
                return TextFormat::GOLD . ElementaryBans::getMessage("playerNotMuted");
            case "ipNotMuted":
                return TextFormat::GOLD . ElementaryBans::getMessage("ipNotMuted");
            case "playerAlreadyBlocked":
                return TextFormat::GOLD . ElementaryBans::getMessage("playerAlreadyBlocked");
            case "playerNotBlocked":
                return TextFormat::GOLD . ElementaryBans::getMessage("playerNotBlocked");
            case "ipAlreadyBlocked":
                return TextFormat::GOLD . ElementaryBans::getMessage("ipAlreadyBlocked");
            case "ipNotBlocked":
                return TextFormat::GOLD . ElementaryBans::getMessage("ipNotBlocked");
            default:
                throw new TranslationFailedException(ElementaryBans::getMessage("failedTranslate"));
        }
    }
    
    public static function translateParams(string $translation, array $parameters) : string {
        if (empty($parameters)) {
            throw new InvalidArgumentException("Parameter is empty.");
        }
        switch ($translation) {
            case "usage":
                $command = $parameters[0];
                if ($command instanceof Command) {
                    return TextFormat::DARK_GREEN . ElementaryBans::getMessage("usage") . TextFormat::GREEN . $command->getUsage();
                } else {
                    throw new InvalidArgumentException("Parameter index 0 must be the type of Command.");
                }
        }
    }
}