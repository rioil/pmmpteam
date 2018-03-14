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

class team extends PluginBase implements Listener{


        //plugin読み込み時に実行
        public function onLoad(){
                //チーム名のリストを作成 まだ途中
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
                $player = $event->getPlayer();
                //保存フォルダがあるかどうかの確認が必要　なければ作成処理　mkdirが使える
                $player_config = new Config($this->getDataFolder() . "/player/" . $player . ".yml", Config::YAML);
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
                                                $teamname = $args[1];
                                                
                                                switch ($args[0]){

                                                        case "make" :
                                                                if(!$this->teamlist->exists($teamname)){ //入力されたチームが存在しないことを確認 エラーが出る
                                                                        $sender->sendMessage("チーム：" . $teamname . "を作成しました");
                                                                }
                                                                else{
                                                                        $sender->sendMessage("チーム：" . $teamname . "はすでに存在しています");
                                                                }
                                                        break 2;

                                                        case "delete" :
                                                                if($this->teamlist->config->exists($teamname)){ //入力されたチームが存在することを確認
                                                                        $sender->sendMessage("チーム：" . $teamname . "を削除しました");
                                                                }
                                                                else{
                                                                        $sender->sendMessage("チーム：" . $teamname . "は存在しません");
                                                                }
                                                        break 2;

                                                        default : 
                                                                $sender->sendMessage("存在しない操作です\n/teamで使い方を確認できます");
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
                                //joinの処理　例外処理が必要
                                $sender->sendMessage("チーム" . $args[0] . "に参加しました");
                                return true;
                        break;

                        case "leave" :
                                //leaveの処理　例外処理が必要
                                $sender->sendMessage("チーム" . $args[0] . "から抜けました");
                                return true;
                        break;                     
                
                }
                return true;

        }
}