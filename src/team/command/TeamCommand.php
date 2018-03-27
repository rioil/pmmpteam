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

//いずれ分離するとき用

class TeamCommand extends Command implements PluginIdentifiableCommand{

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

                        //２つ目の引数を取得し場合分け
                        $teamname = $args[1];
                        $team_file = $this->getDataFolder() . "/teams/" . $teamaname . ".yml";
                        
                        switch ($args[0]){

                            case "make" :

                                //入力されたチームが存在しないことを確認
                                if(!$this->teamlist->file_exists($team_file)){ 
                                    new Config($team_file, Config::YAML);
                                    $sender->sendMessage("チーム：" . $teamname . "を作成しました");
                                }
                                //存在すれば
                                else{
                                    $sender->sendMessage("チーム：" . $teamname . "はすでに存在しています");
                                }

                            break 2;

                            case "delete" :

                                //入力されたチームが存在することを確認
                                if($this->teamlist->config->exists($team_file)){
                                    @rm($team_file);
                                    $sender->sendMessage("チーム：" . $teamname . "を削除しました");
                                }
                                //存在しなければ
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