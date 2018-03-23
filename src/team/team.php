<?php

namespace team;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\Plugin;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

//コマンド処理部分の読み込み
use team\command\TeamCommand;

class team extends PluginBase implements Listener{

        private $teamlist;

        //plugin読み込み時に実行
        public function onLoad(){
                //チーム名のリストを作成 まだ途中
                @mkdir($this->getDataFolder());
                @mkdir($this->getDataFolder() . "/players");
                $this->saveResource("teamlist.yml"); 
                $teamlist = new Config($this->getDataFolder() . "teamlist.yml", Config::ENUM);
                $this->getLogger()->info("初期化完了");
        }
        //pluginが有効になった時に実行
        public function onEnable(){
                $this->getServer()->getPluginManager()->registerEvents($this,$this); //イベント登録
                $this->getLogger()->info("プラグインは有効になりました");
        }

        public function onDisable(){
                $this->getLogger()->info("プラグインは無効になりました");
        }

        //プレイヤーが入ったらconfigの生成
        public function onPlayerJoin(PlayerJoinEvent $event){
                $player = $event->getPlayer()->getName();
                $this->getLogger()->info($player);
                $player_config_path = ($this->getDataFolder() . "/player/" . $player . ".yml");
                ${$player . "_config"} = new Config($this->getDataFolder() . "/player/" . $player . ".yml", Config::YAML);
        }
}