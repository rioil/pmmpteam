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
                                        if(count($args) === 1){
                                                
                                                $sender->sendMessage("《使用法》\n/team [操作][チーム名]\n操作は make(作成) delete(削除)");
                                                break;

                                        }
                                        else{
                                                /* 
                                                ここを実行すると処理はされるが
                                                An unknown error occured while attempting to perform this command
                                                というエラーが出る（2018/3/13）
                                                原因は　$teamname =  strtolower($args[1]);　だと思われる
                                                →f(count($args) === 0)となっていたのが原因　解決済み
                                                */
                                                $teamname = $args[0];                                              
                                                switch ($args[0]){

                                                        case "make" :
                                                                $config = new Config($this->plugin->getDataFolder() . $teamname . ".yml", Config::YAML, array("thing" => "hello"));
                                                                $config->get("thing"); //returns hello
                                                                $sender->sendMessage("チーム" . $teamname . "を作成しました");
                                                        break 2;

                                                        case "delete" :
                                                                $sender->sendMessage("チーム" . $teamname . "を削除しました");
                                                        break 2;

                                                        default : 
                                                                $sender->sendMessage("存在しない操作です");
                                                        break 2;

                                                }
                                        }

                                }
                                else{
                                        //エラーメッセージの送信
                                        $sender->sendMessage("このコマンドはOP権限が必要です");
                                        break;
                                }
                        
                        break;

                        case "join" : 
                                //joinの処理
                                $sender->sendMessage("チーム" . $args[1] . "に参加しました");
                                return true;
                        break;

                        case "leave" :
                                //leaveの処理
                                $sender->sendMessage("チーム" . $args[1] . "から抜けました");
                                return true;
                        break;                     
                
                }
                return true;

        }
}