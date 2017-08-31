<?php
/**
 * Created by PhpStorm.
 * User: whale
 * Date: 2017/8/31
 * Time: 下午8:31
 */

namespace CC\WPhar\Commands;

use CC\WPhar\WPhar;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as T;

class UnpackCommand extends Command
{
    private $plugin;
    private $cmd;

    public function __construct(WPhar $plugin){
        $this->plugin = $plugin;
        $desc = $this->plugin->getCommands()->get("UnpackCommand");
        parent::__construct($desc["command"],$desc["description"]);
        $this->setPermission($desc["permission"]);
        $this->cmd = $desc["command"];
    }

    public function execute(CommandSender $sender, $label, array $args){
        if(!$this->plugin->isEnabled() || !$this->testPermission($sender)){
            return false;
        }
        if(isset($args[0]) && isset($args[1])){
            $fileName = $args[0];
            $target = $args[1];
            if(!file_exists($this->plugin->getDataFolder().$fileName.(strripos($fileName,".phar") === false ? ".phar" : ""))){
                $sender->sendMessage(T::ESCAPE."c目标文件不存在，无法解包！");
                return true;
            }
            $r = $this->plugin->unpackPhar($fileName,$target);
            if(!$r){
                $sender->sendMessage(T::ESCAPE."c解包失败！");
            }
            return true;
        }
        else{
            $sender->sendMessage(T::ESCAPE."e用法： /".$this->cmd." [phar文件名] [解压的路径] （服务器主目录输入 &&root ）");
            return true;
        }
    }
}