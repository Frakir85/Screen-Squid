<?php
#build 20191113

class IP2NAME
{

function __construct($variables){ // 

	
    $this->vars = $variables;

	
  	$this->ssq = new ScreenSquid($variables); #получим экземпляр класса и будем уже туда закиыдвать запросы на исполнение
	
	include("langs/".$this->vars['language']); #подтянем файл языка
  	
	$this->lang = $_lang;
}

  function GetDesc()
  {
	  
      return $this->lang['stMODULEDESC']; 
  }

 

  function GetIdByIp($ipaddress) #по ip получаем  ID
  {

	$sqlGetId = "
		SELECT id FROM scsq_ipaddress t where t.name = '$ipaddress';";


		$result=$this->ssq->query($sqlGetId) or die ("Can`t get ID from scsq_ipaddress table");

		$row=$this->ssq->fetch_array($result);

		$this->ssq->free_result($result);

return $row[0];


}


  function Install()
  {



		$UpdateModules = "
		INSERT INTO scsq_modules (name,link) VALUES ('IP2NAME','modules/IP2NAME/index.php');";


		$result=$this->ssq->query($UpdateModules) or die ("Can`t update module table");

		$this->ssq->free_result($result);
		


		echo "".$this->lang['stINSTALLED']."<br /><br />";
 }
  
 function Uninstall() #добавить LANG
  {

		$UpdateModules = "
		DELETE FROM scsq_modules where name = 'IP2NAME';";

		$result=$this->ssq->query($UpdateModules) or die ("Can`t update module table");

		$this->ssq->free_result($result);

		echo "".$this->lang['stUNINSTALLED']."<br /><br />";
	
  }


}
?>