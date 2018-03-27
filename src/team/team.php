<?php

namespace team;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\Plugin;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\Utils;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

//コマンド処理部分の読み込み
//use team\command\TeamCommand;

class team extends PluginBase implements Listener{

  //plugin読み込み時に実行
    public function onLoad(){
        //設定ファイル保存場所作成
        if(!file_exists($this->getDataFolder())){
            @mkdir($this->getDataFolder());
        }
        if(!file_exists($this->getDataFolder() . "players")){
            @mkdir($this->getDataFolder() . "players");
        }
        if(!file_exists($this->getDataFolder() . "teams")){
            @mkdir($this->getDataFolder() . "teams");
        }
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
        $new_player_config= new Config($this->getDataFolder() . "player/" . $player . ".yml", Config::YAML);
    }

    public function onCommand(CommandSender $sender, Command $command, $label, array $args) : bool {

        switch (strtolower($command->getName())){
    
            case "team" :
    
                //OPのみに実行を許可
                if($sender->isOp()){
                        
                //引数が空のとき
                if(!isset($args[0])){
                    $sender->sendMessage("《使用法》\n/team [操作][チーム名]\n操作は make(作成) delete(削除)");
                    break;
                }
        
                else{

                    if(isset($args[1])){
                        $teamname = $args[1];
                        $team_file = ($this->getDataFolder() . "teams/" . $teamname . ".yml");
                        $arg1_correct = true;
                        $team_exists = file_exists($team_file);
                    }
                    else{
                        $team_exists = false;
                        $arg1_correct = false;
                    }
                        
                    switch ($args[0]){
        
                    case "make" :
                    case "m" : //エイリアスとしてmも同様に処理
        
                        //引数・既存のチームのチェックをする
                        if(!$team_exists && $arg1_correct){ 
                            new Config($team_file, Config::YAML);
                            $sender->sendMessage("チーム：" . $teamname . "を作成しました");
                        }
                        //引数エラーの場合
                        elseif($arg1_correct){
                            $sender->sendMessage("チーム：" . $teamname . "はすでに存在します");
                        }
                        else{
                            $sender->sendMessage("チーム名を正しく指定してください");
                            break;
                        }
                                
                    break 2;
        
                    case "delete" :
                    case "d" : //エイリアスとしてdも同様に処理
        
                        //入力されたチームが存在することを確認
                        if($team_exists){
                        @rmdir($team_file);
                        $sender->sendMessage("チーム：" . $teamname . "を削除しました");
                        }
                        //存在しなければ
                        elseif($arg1_correct){
                        $sender->sendMessage("チーム：" . $teamname . "は存在しません");
                        }
                        else{
                            $sender->sendMessage("チーム名を正しく指定してください");
                            break;
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
            case "leave" :

                //join,leave用の引数判定
                if(isset($args[0])){
                    $teamname = $args[0];
                    $team_file = ($this->getDataFolder() . "teams/" . $teamname . ".yml");
                        //チームが存在しないとき
                        if(!file_exists($team_file)){
                            $sender->sendMessage("チーム：" . $teamname . "は存在しません");
                            break;
                        }
                }
                else{
                    $sender->sendMessage("チーム名を正しく指定してください");
                    break ;
                }

                switch (strtolower($command->getName())){
                
                    /*ここから下は入力されたチームが存在するときのみ実行されるので判定不要*/

                    case "join" :
                        $teamname = "join";
                        $sender->sendMessage("チーム" . $teamname . "に参加しました");            
                    break 2;
                
                    case "leave" : 
                        $teamname = "leave";
                        $sender->sendMessage("チーム" . $teamname . "から抜けました");
                    break 2;       
                
                }

            break;
        }

        return true;

    }
}