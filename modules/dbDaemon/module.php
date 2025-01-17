<?php

#Build date Friday 15th of May 2020 13:18:35 PM
#Build revision 1.3

class dbDaemon
{

function __construct($variables){ // 
    $this->vars = $variables;
    	
	include_once(''.$this->vars['root_dir'].'/lib/functions/function.database.php');

	if (file_exists("langs/".$this->vars['language']))
		include("langs/".$this->vars['language']);  #подтянем файл языка если это возможно
	else	
		include("langs/en"); #если перевода на язык нет, то по умолчанию тянем английский.  	
	$this->lang = $_lang;
}

  function GetDesc()
  {
	  
	  return $this->lang['stMODULEDESC']; 
   
  }

  function Install()
  {

# Table structure for table `scsq_mod_dbDaemon`

		if($this->vars['connectionParams']['dbtype']==0) #mysql version
		$CreateTable = "
		CREATE TABLE IF NOT EXISTS scsq_mod_dbDaemon (
			  id bigint NOT NULL AUTO_INCREMENT,
			  date int(11) NOT NULL,
			  lineitem varchar(4000) DEFAULT NULL,
			  numproxy tinyint(4) DEFAULT '0',
			  PRIMARY KEY (id)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		";
		
				if($this->vars['connectionParams']['dbtype']==1) #postgre version
		$CreateTable = "
		CREATE TABLE IF NOT EXISTS scsq_mod_dbDaemon (
			  id serial NOT NULL,
			  date integer NOT NULL,
			  lineitem text,
			  numproxy integer DEFAULT 0,
			  CONSTRAINT scsq_mod_dbDaemon_pkey PRIMARY KEY (id)
			);

		";
		
		
		$UpdateModules = "
		INSERT INTO scsq_modules (name,link) VALUES ('dbDaemon','modules/dbDaemon/index.php');";


		

		doQuery($this->vars, $CreateTable) or die ("Can`t install module!");
		doQuery($this->vars, $UpdateModules) or die ("Can`t update module table");

		echo "".$this->lang['stINSTALLED']."<br /><br />";
	 }
  
 function Uninstall() #добавить LANG
  {

		$query = "
		DROP TABLE IF EXISTS scsq_mod_dbDaemon;
		";

		$UpdateModules = "
		DELETE FROM scsq_modules where name = 'dbDaemon';";

		doQuery($this->vars, $query) or die ("Can`t uninstall module!");

		doQuery($this->vars, $UpdateModules) or die ("Can`t update module table");


		echo "".$this->lang['stUNINSTALLED']."<br /><br />";

  }


}
?>
