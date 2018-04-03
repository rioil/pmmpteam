<?php

namespace team;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\Plugin;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use pocketmine\utils\Utils;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

//コマンド処理部分の読み込み
//use team\command\TeamCommand;

class team extends PluginBase implements Listener{

    public $teamlist;

  //plugin読み込み時に実行
    public function onLoad(){
        //設定ファイル保存場所作成
        if(!file_exists($this->getDataFolder())){
            @mkdir($this->getDataFolder());
        }
        if(!file_exists($this->getDataFolder() . "players")){
            @mkdir($this->getDataFolder() . "players");
        }
        //チームのリストを作成
        $this->teamlist = new Config($this->getDataFolder() . "teamlist.yml", Config::YAML);
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
        $new_player_config = new Config($this->getDataFolder() . "players/" . $player . ".yml", Config::YAML);
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
                        $this->teamname = $args[1];
                        $arg1_correct = true;
                        //teamlistファイルにチーム名があるか確認
                        if ($this->teamlist->exists($this->teamname)){
                            $team_exists = true;
                        }
                        else{
                            $team_exists = false;
                        }
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
                            $this->teamlist->set($this->teamname,"0");
                            $this->teamlist->save();
                            $sender->sendMessage("チーム：" . $this->teamname . "を作成しました");
                        }
                        //引数エラーの場合
                        elseif($arg1_correct){
                            $sender->sendMessage("チーム：" . $this->teamname . "はすでに存在します");
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
                            $this->teamlist->remove($this->teamname);
                            $this->teamlist->save();
                            $sender->sendMessage("チーム：" . $this->teamname . "を削除しました");
                        }
                        //存在しなければ
                        elseif($arg1_correct){
                            $sender->sendMessage("チーム：" . $this->teamname . "は存在しません");
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

                if(isset($args[0])){
                    $this->teamname = $args[0];
                        //チームが存在しないとき
                        if(!$this->teamlist->exists($this->teamname)){
                            $sender->sendMessage("チーム：" . $this->teamname . "は存在しません");
                            break;
                        }
                        $this->send_player = $sender->getName();
                        //プレイヤーのコンフィグ準備
                        $this->current_player = new Config($this->getDataFolder() . "players/" . $this->send_player . ".yml", Config::YAML); 
                }
                else{
                    $sender->sendMessage("チーム名を正しく指定してください");
                    break ;
                }

                //コンフィグにチーム名をセット・完了メッセージ
                $this->current_player->set("team",$this->teamname);
                $this->current_player->save();
                $sender->sendMessage("チーム" . $this->teamname . "に参加しました");
                $this->getLogger()->info($this->send_player . "がチーム" . $this->teamname . "に参加しました");

            break;

            case "leave" :

                $this->send_player = $sender->getName();
                //プレイヤーのコンフィグ準備
                $this->current_player = new Config($this->getDataFolder() . "players/" . $this->send_player . ".yml", Config::YAML); 
                if($this->current_player->exists("team")){
                    $this->current_team = $this->current_player->get("team");
                    $this->current_player->remove("team",$this->teamname);
                    $this->current_player->save();
                    //完了メッセージ
                    $sender->sendMessage("チーム" . $this->current_team . "から抜けました");
                    $this->getLogger()->info($this->send_player . "がチーム" . $this->current_team . "から抜けました");
                }
                else{
                    $sender->sendMessage("現在どのチームにも属していません");
                }

            break;

        }

        return true;

    }
}