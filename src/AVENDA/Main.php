<?php

namespace AVENDA;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\command\Command as C;
use pocketmine\command\CommandSender as Cs;
use pocketmine\event\player\PlayerJoinEvent as Pj;
use pocketmine\event\player\PlayerQuitEvent as Pq;

class Main extends PluginBase implements Listener { 
	public $tag = "§b§l[Police]§f "
	public function onEnable (){ 
		$data = $this->getDataFolder();
		@mkdir ($data);
		$this->config = new Config($data . "polices.yml", Config::YAML);
		$this->cdb = $this->config->getAll();
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		}
	public function onCommand (Cs $sender, C $cmd, string $label, array $args):bool{
		if ( $cmd == "police"){
			if ( ! isset ($args[0])){
				if ( $sender->isOp()){
				$sender->sendMessage ( $this->tag . "/police add [playername] - Add Player as Police");
				$sender->sendMessage ( $this->tag . "/police list [page] - Show Police Members");
				$sender->sendMessage ( $this->tag . "/police remove [listnumber] - Remove Player as Police");
				} else {
					$sender->sendMessage ( $this->tag . "You are not Op");
					}
				return true;
				}
				switch ($args[1]){
					case "add" :
					$this->addPolice($sender, strtolower($name));
					break;
					case "list" :
					$this->listPolice($sender);
					break;
					case "remove" :
					$this->isPolice(strtolower($args[1]));
					$this->removePolice(strtolower($args[1]));
					break;
					}
			}
		}
		public function onJoin (Pj $event){
			$player = $event->getPlayer();
			$name = strtolower($player->getName()); 
            if ( in_array($this->cdb ["police"], $name)){
            	$player = $player->addAttachment($this);
            $player->setPermission("pocketmine.command.kick",true);
            $player->setPermission("pocketmine.command.list",true);
            $player->setPermission("pocketmine.command.ban",true);
            $player->setPermission("pocketmine.command.teleport",true);
            	}
			}
		public function onQuit (Pq $event) {
			$player = $event->getPlayer();
			$name = strtolower($player->getName()); 
            if ( in_array($this->cdb ["police"], $name)){
            	$player = $player->addAttachment($this);
            $player->setPermission("pocketmine.command.kick",false);
            $player->setPermission("pocketmine.command.list",false);
            $player->setPermission("pocketmine.command.ban",false);
            $player->setPermission("pocketmine.command.teleport",false);
            	}
			}
		public function listPolice($player){
			$player->sendMessage ("====[Police List]====");
			foreach ($this->cdb ["police"] as $pol => $k){
				$player->sendMessage ("{[number $k]} {$pol}");
				}
			}
		public function removePolice($player, $name, $num){
			if( ! isset ($this->cdb ["police"] [$num])){
				$player->sendMessage ( $this->tag . "There is no player in that number");
				} else {
					unset($this->cdb ["police"] [$num]);
					$player->sendMessage ($this->tag . "Sucessfully remove him to the police");
					$this->save();
					}
			}
		public function addPolice($player, $name){
			array_push($this->cdb ["police"], $name);
			$this->save();
			$player->sendMessage ("Sucessfully add him to the police");
			}
		public function isPolice ($name){
			if ( in_array($this->cdb ["police"], $name)){
						$sender->sendMessage ( $this->tag . " player is already a police");
						return true;
						}
			}
		public function save (){
			$this->config->setAll($this->cdb);
			$this->config->save();
			}
	}