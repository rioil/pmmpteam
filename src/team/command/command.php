<?php

namespace team\command;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\Plugin;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\Server;
use pocketmine\utils\Config;

class TeamCommand implements CommandExecutor{

    public function __construct(Loader $plugin) {
        $this->plugin = $plugin;
    }

    public function onCommand(CommandSender $sender, Command $command, $label, array $args) : bool {

        switch (strlower($command->getName())){

            case "team" :

                //OPのみに実行を許可
                if($sender->isOp()){
                                
                    //引数が空のとき
                    if(!isset($args[0])){
                        $sender->sendMessage("《使用法》\n/team [操作][チーム名]\n操作は make(作成) delete(削除)");
                        break;
                    }

                    else{
                       
                        $teamname = $args[1];
                        
                        switch ($args[0]){

                            case "make" :

                                if(!$this->teamlist->file_exists($teamname)){ //入力されたチームが存在しないことを確認 エラーが出る
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