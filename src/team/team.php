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
                $this->getLogger()->info("初期化完了");
        }
        //pluginが有効になった時に実行
        public function onEnable(){
                $this->getLogger()->info("プラグインは有効になりました");
        }

        public function onDisable(){
                $this->getLogger()->info("プラグインは無効になりました");
        }

        //コマンドの処理
        public function onCommand(CommandSender $sender, Command $command, $label, array $args) : bool {

                switch ($command->getName()){

                        case "team" :

                                //OPのみに実行を許可
                                if($sender->isOp()){
                                        
                                        //引数が空のとき
                                        if(count($args) === 0){
                                                
                                                $sender->sendMessage
                                                ("《使用法》\n/team [操作][チーム名]\n操作は make(作成) delete(削除)");
                                                return false;

                                        }

                                        switch ($args[0]){

                                                case "make" :
                                                        $sender->sendMessage("チーム" . $args[1] . "を作成しました");
                                                break;

                                                case "delete" :
                                                        $sender->sendMessage("チーム" . $args[1] . "を削除しました");
                                                break;

                                        }

                                }

                                else{
                                        //エラーメッセージの送信
                                        $sender->sendMessage("このコマンドはOP権限が必要です");
                                }

                        break;

                        case "join" :
                                
                                //joinの処理
                        break;

                        case "leave" :

                                //leaveの処理
                        break;                     
                
                }

                //一旦コメント化
                /*
                //引数が空のとき
                if(count($args) === 0)
                {
                $sender->sendMessage("Usage:  /tmeke [TeamName]");
                return false;
                }
                
                //OPが実行したとき
                if($sender->isOp()){
                
                $username = strtolower($args[0]);
                $player = $sender->getServer()->getPlayer($username);
                
                        if($player instanceOf Player)
                        {
                        //$playerip = $player->getAddress();
                        //$sender->sendMessage("".$player->getPlayer()->getName()." is IP:".$playerip."");
                        //return true;
                        }
                        
                        else
                        {
                        //$sender->sendMessage("".$username." doesn't exist");
                        //return true;
                        }
                
                //OP以外が実行したとき
                }
                
                else
                {
                $sender->sendMessage("You don't have permission.");
                return true;
                }
                */
        }
}
