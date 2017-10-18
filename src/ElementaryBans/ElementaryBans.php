<?php

namespace ElementaryBans;

use ElementaryBans\command\BanCommand;
use ElementaryBans\command\BanIPCommand;
use ElementaryBans\command\BanListCommand;
use ElementaryBans\command\BlockCommand;
use ElementaryBans\command\BlockIPCommand;
use ElementaryBans\command\BlockListCommand;
use ElementaryBans\command\KickCommand;
use ElementaryBans\command\MuteCommand;
use ElementaryBans\command\MuteIPCommand;
use ElementaryBans\command\MuteListCommand;
use ElementaryBans\command\PardonCommand;
use ElementaryBans\command\PardonIPCommand;
use ElementaryBans\command\TempBanCommand;
use ElementaryBans\command\TempBanIPCommand;
use ElementaryBans\command\TempBlockCommand;
use ElementaryBans\command\TempBlockIPCommand;
use ElementaryBans\command\TempMuteCommand;
use ElementaryBans\command\TempMuteIPCommand;
use ElementaryBans\command\UnbanCommand;
use ElementaryBans\command\UnbanIPCommand;
use ElementaryBans\command\UnblockCommand;
use ElementaryBans\command\UnblockIPCommand;
use ElementaryBans\command\UnmuteCommand;
use ElementaryBans\command\UnmuteIPCommand;
use ElementaryBans\listener\PlayerChatListener;
use ElementaryBans\listener\PlayerCommandPreproccessListener;
use ElementaryBans\listener\PlayerPreLoginListener;
use pocketmine\event\Listener;
use pocketmine\permission\Permission;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;

class ElementaryBans extends PluginBase {

    public $ebmessages;
    
    private function removeCommand(string $command) {
        $commandMap = $this->getServer()->getCommandMap();
        $cmd = $commandMap->getCommand($command);
        if ($cmd == null) {
            return;
        }
        $cmd->setLabel("");
        $cmd->unregister($commandMap);
    }
    
    private function initializeCommands() {
        $commands = array("ban", "banlist", "pardon", "pardon-ip", "ban-ip", "kick");
        for ($i = 0; $i < count($commands); $i++) {
            $this->removeCommand($commands[$i]);
        }
        $commandMap = $this->getServer()->getCommandMap();
        $commandMap->registerAll("elementarybans", array(
            new BanCommand(),
            new BanIPCommand(),
            new BanListCommand(),
            new BlockCommand(),
            new BlockIPCommand(),
            new BlockListCommand(),
            new KickCommand(),
            new MuteCommand(),
            new MuteIPCommand(),
            new MuteListCommand(),
            new PardonCommand(),
            new PardonIPCommand(),
            new TempBanCommand(),
            new TempBanIPCommand(),
            new TempBlockCommand(),
            new TempBlockIPCommand(),
            new TempMuteCommand(),
            new TempMuteIPCommand(),
            new UnbanCommand(),
            new UnbanIPCommand(),
            new UnblockCommand(),
            new UnblockIPCommand(),
            new UnmuteCommand(),
            new UnmuteIPCommand()
        ));
    }
    
    /**
     * @param Permission[] $permissions
     */
    protected function addPermissions(array $permissions) {
        foreach ($permissions as $permission) {
            $this->getServer()->getPluginManager()->addPermission($permission);
        }
    }
    
    /**
     * 
     * @param Plugin $plugin
     * @param Listener[] $listeners
     */
    protected function registerListeners(Plugin $plugin, array $listeners) {
        foreach ($listeners as $listener) {
            $this->getServer()->getPluginManager()->registerEvents($listener, $plugin);
        }
    }
    
    private function initializeListeners() {
        $this->registerListeners($this, array(
            new PlayerChatListener(),
            new PlayerCommandPreproccessListener(),
            new PlayerPreLoginListener()
        ));
    }
    
    private function initializeFiles() {
        @mkdir($this->getDataFolder());
        if (!(file_exists("muted-players.txt") && is_file("muted-players.txt"))) {
            @fopen("muted-players.txt", "w+");
        }
        if (!(file_exists("muted-ips.txt") && is_file("muted-ips.txt"))) {
            @fopen("muted-ips.txt", "w+");
        }
        if (!(file_exists("blocked-players.txt") && is_file("blocked-players.txt"))) {
            @fopen("blocked-players.txt", "w+");
        }
        if (!(file_exists("blocked-ips.txt") && is_file("blocked-ips.txt"))) {
            @fopen("blocked-ips.txt", "w+");
        }
    }
    
    private function initializePermissions() {
        $this->addPermissions(array(
            new Permission("elementarybans.command.ban", "Allows the player to prevent the given player to use this server.", Permission::DEFAULT_OP),
            new Permission("elementarybans.command.banip", "Allows the player to prevent the given IP address to use this server.", Permission::DEFAULT_OP),
            new Permission("elementarybans.command.banlist", "Allows the player to view the players/IP addresses banned on this server.", Permission::DEFAULT_OP),
            new Permission("elementarybans.command.blocklist", "Allows the player to view all the players/IP addresses banned from this server."),
            new Permission("elementarybans.command.kick", "Allows the player to remove the given player.", Permission::DEFAULT_OP),
            new Permission("elementarybans.command.mute", "Allows the player to prevent the given player from sending public chat message.", Permission::DEFAULT_OP),
            new Permission("elementarybans.command.muteip", "Allows the player to prevent the given IP address from sending public chat message.", Permission::DEFAULT_OP),
            new Permission("elementarybans.command.mutelist", "Allows the player to view all the players muted from this server.", Permission::DEFAULT_OP),
            new Permission("elementarybans.command.pardon", "Allows the player to allow the given player to use this server.", Permission::DEFAULT_OP),
            new Permission("elementarybans.command.pardonip", "Allows the player to allow the given IP address to use this server.", Permission::DEFAULT_OP),
            new Permission("elementarybans.command.tempban", "Allows the player to temporarily prevent the given player to use this server.", Permission::DEFAULT_OP),
            new Permission("elementarybans.command.tempbanip", "Allows the player to temporarily prevent the given IP address to use this server.", Permission::DEFAULT_OP),
            new Permission("elementarybans.command.tempmute", "Allows the player to temporarily prevents the given player to send public chat message.", Permission::DEFAULT_OP),
            new Permission("elementarybans.command.tempmuteip", "Allows the player to prevents the given IP address to send public chat message.", Permission::DEFAULT_OP),
            new Permission("elementarybans.command.unban", "Allows the player to allow the given player to use this server.", Permission::DEFAULT_OP),
            new Permission("elementarybans.command.unbanip", "Allows the player to allow the given IP address to use this server.", Permission::DEFAULT_OP),
            new Permission("elementarybans.command.unmute", "Allows the player to allow the given player to send public chat message.", Permission::DEFAULT_OP),
            new Permission("elementarybans.command.unmuteip", "Allows the player to allow the given IP address to send public chat message.")
        ));
    }
    
    private function removeBanExpired() {
        $this->getServer()->getNameBans()->removeExpired();
        $this->getServer()->getIPBans()->removeExpired();
        Manager::getNameMutes()->removeExpired();
        Manager::getIPMutes()->removeExpired();
        Manager::getNameBlocks()->removeExpired();
        Manager::getIPBlocks()->removeExpired();
    }
    
    public function onLoad() {
        $this->getLogger()->info("ElementaryBans is now loading...");
    }
    
    public function onEnable() {
        $this->getLogger()->info("ElementaryBans is now enabled.");
        $this->initializeCommands();
        $this->initializeListeners();
        $this->initializePermissions();
        $this->initializeFiles();
        $this->removeBanExpired();
        $this->ebmessages = new EBMessages($this);
    }
    
    public function onDisable() {
        $this->getLogger()->info("ElementaryBans is now disabled.");
    }

    public function getMessage($node, ...$vars) {
        return $this->ebmessages->getMessage($node, ...$vars);
    }

}