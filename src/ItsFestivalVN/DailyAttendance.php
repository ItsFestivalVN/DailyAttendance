<?php
    
namespace ItsFestivalVN;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\{PlayerJoinEvent};
use pocketmine\utils\Config;
use onebone\economyapi\EconomyAPI;

class DailyAttendance extends PluginBase implements Listener {
    
    public function onEnable():void{
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->status = new Config($this->getDataFolder() . "Status.yml", Config::YAML);
        $this->getScheduler()->scheduleRepeatingTask(new StatusTask($this), 20);
    }

    public function reset(){
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $time = date("h:i:s");
        if($time == "24:00:00" or $time == "23:59:59"){
            foreach($this->status->getAll() as $all => $data){
                $this->status->set($all, "Not Receive");
                $this->status->save();
            }
        }
    }

    public function onDisable(): void{
        $this->status->save();
    }

    public function onJoin(PlayerJoinEvent $ev){
        $player = $ev->getPlayer();
        if(!$this->status->exists($player->getName())){
            $this->status->set($player->getName(), "Not Receive");
            $this->status->save();
        }
        $status = $this->status->get($player->getName());
        if($status == "Not Receive"){
            $money = mt_rand(1000, 5000); #Random From 1000 To 5000 Money
            EconomyAPI::getInstance()->addMoney($player, $money);
            $player->sendMessage("§6You Got ". $money ." Money From Attendance Gift");
        }
    }
}