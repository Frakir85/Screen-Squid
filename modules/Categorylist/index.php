<?php

#Build date Friday 24th of April 2020 13:00:10 PM
#Build revision 1.3

#чтобы убрать возможные ошибки с датой, установим на время исполнения скрипта ту зону, которую отдает система.
date_default_timezone_set(date_default_timezone_get());


if(isset($_GET['srv']))
  $srv=$_GET['srv'];
else
  $srv=0;



include("../../config.php");

$language=$globalSS['language'];

include("module.php");
include_once($globalSS['root_dir']."/lang/$language");

if (file_exists("langs/".$language))
		include("langs/".$language);  #подтянем файл языка если это возможно
	else	
		include("langs/en"); #если перевода на язык нет, то по умолчанию тянем английский. 

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<!-- The themes file -->
<link rel="stylesheet" type="text/css" href="<?php echo $globalSS['root_http']; ?>/themes/<?php echo $globalSS['globaltheme']; ?>/global.css"/>


</head>
<body>

<script type="text/javascript" src="../../javascript/sortable.js"></script>


<?php



$start=microtime(true);
      
    
echo "<h2>".$_lang['stCATMODULE']."</h2>";
      
echo "<br><p>".$_lang['stCATWAIT']."</p>";
         



$end=microtime(true);

$runtime=$end - $start;

echo "<br /><br /><font size=2>".$_lang['stEXECUTIONTIME']." ".round($runtime,3)." ".$_lang['stSECONDS']."</font><br />";

echo $_lang['stCREATORS'];

$newdate=strtotime(date("d-m-Y"))-86400;
$newdate=date("d-m-Y",$newdate);



?>
<form name=fastdateswitch_form>
    <input type="hidden" name=date_field_hidden value="<?php echo $newdate; ?>">
    <input type="hidden" name=dom_field_hidden value="<?php echo 'day'; ?>">
    <input type="hidden" name=group_field_hidden value=0>
    <input type="hidden" name=groupname_field_hidden value=0>
    <input type="hidden" name=typeid_field_hidden value=0>
    </form>
</body>
</html>
