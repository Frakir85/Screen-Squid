<?php

#build 20161031
#server #0
$srvname[0]="Proxy0"; #nickname of server
$db[0] = "test";
$user[0] = "mysql-user";
$pass[0] ="pass";
$address[0] ="127.0.0.1"; //mysql db
$cfgsquidhost[0]="localhost";
$cfgsquidport[0] = 3128;
$cfgcachemgr_passwd[0]="";


#server #1
#$srvname[1]="Proxy1"; #nickname of server
#$db[1] = "test1";
#$user[1] = "mysql-user";
#$pass[1] ="pass";
#$address[1] ="127.0.0.1"; //mysql
#$cfgsquidhost[1]="localhost";
#$cfgsquidport[1] = 3128;
#$cfgcachemgr_passwd[1]="";


#language ru = russian, en = english
$language="ru";

include_once("lang/$language");

#Pokazivat aliasi - logini i/ili IP adresa. Vkl=1, Vikl=0
#Показывать алиасы логины и/или IP адреса. Вкл=1, выкл=0.
$useLoginalias=0;
$useIpaddressalias=1;

#Vkluchit regim ne pokazivat druzei. Vkl =1, Vikl=0
#Включить режим НЕ показывать друзей. Вкл.= 1, выкл.=0
$enableNofriends=0;

#Spisok druzei. Cherez odin probel. Naprimer, $goodLogins="Vasya Sergey Petr"; S IP adresami takzhe, cherez odin probel
#Список друзей. Через ОДИН ПРОБЕЛ. Например, $goodLogins="Vasya Sergey Petr"; С IP адресами также, через ОДИН ПРОБЕЛ.

$goodLogins="";
$goodIpaddress="";


#Ispolzovat iconv dliz perekodirovki CP1251 v UTF-8. Esli rugaetsa, to vidno iconv netu. Mozhno vikluchit etu opciu.
#ili ustanovit iconv. Po umolchaniu, viklucheno.
#использовать iconv для перекодировки CP1251 в UTF-8. Если ругается, то видно iconv нету. Можно выключить эту опцию.
#или установить iconv. По умолчанию, выключено.
$enableUseiconv=0;

#Show login/ipaddress if it have no traffic in partly reports. 1 - enable, 0 - disable
$showZeroTrafficInReports=0;

#Pokazivat dni nedeli v otchetah.
#Показывать дни недели в отчетах
$enableShowDayNameInReports=1;

#Vkluchit regim ne pokazivat saiti opredelennie v $goodSites. Vkl=1, Vikl=0
#Включить режим НЕ показывать сайты определенные в $goodSites. Вкл.= 1, выкл.=0
$enableNoSites=0;

#Spisok saitov iskluchennih iz statistiki. Cherez odin probel. Naprimer $goodSites="vk.me facebook.com ipp".
#Список сайтов исключенных из статистики. Через ОДИН ПРОБЕЛ. Например, $goodSites="vk.me facebook.com ipp".
$goodSites="";

#Pokazivat v kratkoy statistike, statistiku po objectam. Na bolshih viborkah, tormozit otobrazhenie
$enableTrafficObjectsInStat=0;

#Через сколько секунд обновлять страницу онлайна
$refreshPeriod=5;
#==============================


//========= queries config
$countTopSitesLimit=10;
$countTopLoginLimit=10;
$countTopIpLimit=10;
$countPopularSitesLimit=10;
$countWhoDownloadBigFilesLimit=10;

//======== queries config end


$vers="1.10";
?>
