<?php

namespace team;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;

class team extends PluginBase{


        //plugin読み込み時に実行
        public function onLoad(){
                $this->getLogger()->info("onLoad() has been called!");
        }
        //pluginが有効になった時に実行
        public function onEnable(){
                $this->getLogger()->info("onEnable() has been called!");
        }

        public function onDisable(){
                $this->getLogger()->info("onDisable() has been called!");
        }
}
