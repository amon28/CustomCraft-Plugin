<?php
namespace CustomCraft;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\item\Item;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\inventory\ShapedRecipe;
use pocketmine\inventory\CraftingRecipe;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as C;
use pocketmine\event\inventory\CraftItemEvent;
use jojoe77777\FormAPI\SimpleForm;
use jojoe77777\FormAPI\ModalForm;
use jojoe77777\FormAPI\CustomForm;

class Main extends PluginBase implements Listener{
    
    public $cfg;
    
    public $player;
    public $listkey;
    
    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this,$this);
        
    if(!is_dir($this->getDataFolder())){
			@mkdir($this->getDataFolder());
		}
		if(!file_exists($this->getDataFolder() . "config.yml")){
			$this->saveDefaultConfig();
		}
		$this->cfg = new Config($this->getDataFolder() . "config.yml");
		if(count($this->cfg->getall()) == 0){
		$this->getLogger()->info("No Recipes In CustomCraft"); 
		}else{
	$this->customCraft();
	$this->getLogger()->info("Loading Recipe Complete!");
		}
    }
    
	public function onCommand(CommandSender $sender, Command $cmd, string $label,array $args) : bool {
	if(($cmd->getName()) == "cc"){
	if(!$sender->isOp()) return true;
	if(!isset($args[0])){
	$sender->sendMessage(C::RED.C::UNDERLINE."/cc add|del|list|reload");
	return true;
	}
$this->player = $sender->getPlayer();
	switch($args[0]){
	    
	    case "add":
	     $sender->sendMessage(C::RED.C::UNDERLINE."This is not yet implemented");
	        break;
	        
	    case "list":
	     $this->listCraft();   
	        break;
	        
	    case "reload":
	     $this->reloadCraft();
	        break;
	}
	}
return true;	
	}
	
	public function addCraft(){
	    
	}
	
	public function listCraft(){
	$cfg = $this->cfg->getall();
	$keys = (array_keys($cfg));
	$form = new SimpleForm(function (Player $sender, $data){
        if($data === null){
            return true;
        }
    $cfg = $this->cfg->getall();
	$keys = (array_keys($cfg));
    $this->listModal($keys[$data]);
    });
    foreach($keys as $k){
    $form->addButton($k);   
    }
    $this->player->sendForm($form);    
	}
	
	public function reloadCraft(){
	$this->customCraft();
	$this->player->sendMessage("Reloading Custom Recipes");
	}
	
	public function listModal(String $key){
	$this->listkey = $key;
	$form = new ModalForm(function (Player $sender, $data){
        if($data === null){
            return true;
        }
   switch($data){
    case 0:
      $this->listCraft();
      return true;
        break;
        
    default:
        $cfg = $this->cfg->getall();
        unset($cfg[$this->listkey]);
        $this->cfg->setAll($cfg);
        $this->cfg->save();
        $this->listCraft();
        return true;
   }
    });
    $res= explode(":",$this->cfg->getNested($key.".result"));
    $item = Item::get($res[0],$res[1])->getName();
    //Grid
	$top= $this->cfg->getNested($key.".top");
	$mid= $this->cfg->getNested($key.".mid");
	$bot= $this->cfg->getNested($key.".bot");
	//Items In The Grid
	$id1 = explode(":",$this->cfg->getNested($key.".A"));
	$it1 = Item::get($id1[0],$id1[1])->getName();
	$id2 = explode(":",$this->cfg->getNested($key.".B"));
	$it2 = Item::get($id2[0],$id2[1])->getName();
	$id3 = explode(":",$this->cfg->getNested($key.".C"));
	$it3 = Item::get($id3[0],$id3[1])->getName();
	$id4 = explode(":",$this->cfg->getNested($key.".D"));
	$it4 = Item::get($id4[0],$id4[1])->getName();
	$id5 = explode(":",$this->cfg->getNested($key.".E"));
	$it5 = Item::get($id5[0],$id5[1])->getName();
	$id6 = explode(":",$this->cfg->getNested($key.".F"));
	$it6 = Item::get($id6[0],$id6[1])->getName();
	$id7 = explode(":",$this->cfg->getNested($key.".G"));
	$it7 = Item::get($id7[0],$id7[1])->getName();
	$id8 = explode(":",$this->cfg->getNested($key.".H"));
	$it8 = Item::get($id8[0],$id8[1])->getName();
	$id9 = explode(":",$this->cfg->getNested($key.".I"));
	$it9 = Item::get($id9[0],$id9[1])->getName();
	  
    $form->setTitle($key." Recipe");
    $form->setContent("Result: ".$item."\n"."top: ".$top."\n"."mid: ".$mid."\n"."bot: ".$bot."\n"."A: ".$it1."\n"."B: ".$it2."\n"."C: ".$it3."\n"."D: ".$it4."\n"."E: ".$it5."\n"."F: ".$it6."\n"."G: ".$it7."\n"."H: ".$it8."\n"."I: ".$it9);
    $form->setButton1("Delete");
    $form->setButton2("Back");
    $this->player->sendForm($form);   
	}
	
	public function customCraft(){
	$cfg = $this->cfg->getall();
	$key = (array_keys($cfg));
	$result = $key[0];
	$ckeys = 0;
	foreach($cfg as $keys){
	$rkey = (array_keys($keys));
//Item to craft	
$res= explode(":",$this->cfg->getNested($key[$ckeys].".".$rkey[0]));	
    //Grid
	$top= $this->cfg->getNested($key[$ckeys].".".$rkey[1]);
	$mid= $this->cfg->getNested($key[$ckeys].".".$rkey[2]);
	$bot= $this->cfg->getNested($key[$ckeys].".".$rkey[3]);
	//Items In The Grid
	$id1 = explode(":",$this->cfg->getNested($key[$ckeys].".".$rkey[4]));
	$id2 = explode(":",$this->cfg->getNested($key[$ckeys].".".$rkey[5]));
	$id3 = explode(":",$this->cfg->getNested($key[$ckeys].".".$rkey[6]));
	$id4 = explode(":",$this->cfg->getNested($key[$ckeys].".".$rkey[7]));
	$id5 = explode(":",$this->cfg->getNested($key[$ckeys].".".$rkey[8]));
	$id6 = explode(":",$this->cfg->getNested($key[$ckeys].".".$rkey[9]));
	$id7 = explode(":",$this->cfg->getNested($key[$ckeys].".".$rkey[10]));
	$id8 = explode(":",$this->cfg->getNested($key[$ckeys].".".$rkey[11]));
	$id9 = explode(":",$this->cfg->getNested($key[$ckeys].".".$rkey[12]));
	
$i = Item::get($res[0],$res[1]);

$recipe = new ShapedRecipe([$top,$mid,$bot],
[$rkey[4]=>Item::get($id1[0],$id1[1]),
$rkey[5]=>Item::get($id2[0],$id2[1]),
$rkey[6]=>Item::get($id3[0],$id3[1]),
$rkey[7]=>Item::get($id4[0],$id4[1]),
$rkey[8]=>Item::get($id5[0],$id5[1]),
$rkey[9]=>Item::get($id6[0],$id6[1]),
$rkey[10]=>Item::get($id7[0],$id7[1]),
$rkey[11]=>Item::get($id8[0],$id8[1]),
$rkey[12]=>Item::get($id9[0],$id9[1])],[$i]);
$recipe->registerToCraftingManager($this->getServer()->getCraftingManager());      $ckeys++;
	}
	}
	
    public function onDisable(){
     $this->getLogger()->info("§cOffline");
    }
}
