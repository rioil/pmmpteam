<?php

namespace team\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

class tmaeke extends Command{

    //コマンドの処理
    public function onCommand(CommandSender $sender, Command $command, $label, array $args){
          
        //引数が空のとき
        if(count($args) === 0){

          $sender->sendMessage("Usage:  /tmeke [TeamName]");
          return false;

        }

        //OPが実行したとき
        if($sender->isOp()){

          $username = strtolower($args[0]);
          $player = $sender->getServer()->getPlayer($username);

          if($player instanceOf Player){

            //$playerip = $player->getAddress();
            //$sender->sendMessage("".$player->getPlayer()->getName()." is IP:".$playerip."");
            //return true;

          }else{

            //$sender->sendMessage("".$username." doesn't exist");
            //return true;

          }

        //OP以外が実行したとき
        }else{

          $sender->sendMessage("You don't have permission.");
          return true;

        }
    }
}