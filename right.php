<?php

#Build date Sunday 17th of May 2020 08:13:18 AM
#Build revision 1.13

#чтобы убрать возможные ошибки с датой, установим на время исполнения скрипта ту зону, которую отдает система.
date_default_timezone_set(date_default_timezone_get());

#вводим новую переменную - количество байт в мегабайте. Но не все обновят конфиг, поэтому для таких случаев сделаем переход безударным.
if(!isset($oneMegabyte))
$oneMegabyte=1000000;

if(isset($_GET['srv']))
  $srv=$_GET['srv'];
else
  $srv=0;
  
  include("config.php");

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<!-- The themes file -->
<link rel="stylesheet" type="text/css" href="themes/<?php echo $globaltheme; ?>/global.css"/>

</head>
<body>

<script type="text/javascript" src="javascript/sortable.js"></script>
<script language=javascript>

function switchTables()
{
   if (document.getElementById("loginsTable").style.display == "table" ) {
          document.getElementById("loginsTable").style.display="none";

} else {
document.getElementById("loginsTable").style.display="table";
}
   if (document.getElementById("ipaddressTable").style.display == "table" ) {
          document.getElementById("ipaddressTable").style.display="none";

} else {
document.getElementById("ipaddressTable").style.display="table";
}

}

function PartlyReportsLogin(idReport, dom, login,loginname,site)
{
parent.right.location.href='reports/reports.php?srv=<?php echo $srv ?>&id='+idReport+'&date='+window.document.fastdateswitch_form.date_field_hidden.value+'&dom='+dom+'&login='+login+'&loginname='+loginname+'&site='+site;
}

function PartlyReportsIpaddress(idReport, dom, ip,ipname,site)
{
parent.right.location.href='reports/reports.php?srv=<?php echo $srv ?>&id='+idReport+'&date='+window.document.fastdateswitch_form.date_field_hidden.value+'&dom='+dom+'&ip='+ip+'&ipname='+ipname+'&site='+site;
}


</script>


<?php



$addr=$address[$srv];
$usr=$user[$srv];
$psw=$pass[$srv];
$dbase=$db[$srv];
$dbtype=$srvdbtype[$srv];

$variableSet = array();
$variableSet['addr']=$addr;
$variableSet['usr']=$usr;
$variableSet['psw']=$psw;
$variableSet['dbase']=$dbase;
$variableSet['dbtype']=$dbtype;
$variableSet['language']=$language;

#в зависимости от типа БД, подключаем разные модули
if($dbtype==0)
{
include("lib/dbDriver/mysqlmodule.php");
$ssq = new m_ScreenSquid($variableSet); #получим экземпляр класса и будем уже туда закидывать запросы на исполнение
}

if($dbtype==1)
{
include("lib/dbDriver/pgmodule.php");
$ssq = new p_ScreenSquid($variableSet); #получим экземпляр класса и будем уже туда закидывать запросы на исполнение
}


if(!isset($_GET['id']))
echo "<h2>".$_lang['stWELCOME']."".$vers."</h2>";

$start=microtime(true);


if($enableNofriends==1) {
  $friendsTmp=implode("','",explode(" ", $goodLogins));
  $friendsTmp=",'".$friendsTmp."'";
  $goodLoginsList=$friendsTmp;
  $friendsTmp=implode("','",explode(" ", $goodIpaddress));
  $friendsTmp=",'".$friendsTmp."'";
  $goodIpaddressList=$friendsTmp;
  }
    else {
      $goodLoginsList="";
      $goodIpaddressList="";
      }


       if(isset($_POST['userlogin'])) // логин для авторизации в кабинете
          $userlogin=$_POST['userlogin'];
        else
          $userlogin="";

        if(isset($_POST['userpassword'])) // пароль для авторизации в кабинете
          $userpassword=md5(md5(trim($_POST['userpassword'])));
        else
          $userpassword="";

        if(isset($_POST['activeauth'])) // включить авторизацию
          $activeauth=1;
        else
          $activeauth=0;

        if(isset($_POST['changepassword'])) // признак. Если установлено - изменить пароль
          $changepassword=1;
        else
          $changepassword=0;



  if($ssq->db_object==null) {
    echo $_lang['stDONTFORGET'];
  }
  else
  {
	if(!isset($_GET['id'])) 
			echo $_lang['stALLISOK'];
	  
	if(isset($_GET['id'])) {

    if($_GET['id']==1) { //краткая статистика
		
		
			  $queryCountRowsTraffic="select count(*) from scsq_traffic";
			  $queryCountRowsLogin="select count(*) from scsq_logins";
			  $queryCountRowsIpaddress="select count(*) from scsq_ipaddress";
			  $queryMinMaxDateTraffic="select from_unixtime(t.mindate,'%d-%m-%Y %H:%i:%s'),from_unixtime(t.maxdate,'%d-%m-%Y %H:%i:%s') from ( select min(date) as mindate,max(date) as maxdate from scsq_traffic) t";
			  
			  if($dbtype==1) #postgres 
			  $queryMinMaxDateTraffic="select to_char(to_timestamp(t.mindate),'DD-MM-YYYY HH24:MI:SS'),to_char(to_timestamp(t.maxdate),'DD-MM-YYYY HH24:MI:SS') from ( select min(date) as mindate,max(date) as maxdate from scsq_traffic) t";
			  
			  
			  $querySumSizeTraffic="select sum(sizeinbytes) from scsq_quicktraffic where par=1";
			  $queryCountObjectsTraffic1="select count(id) from scsq_traffic where sizeinbytes<=1000";
			  $queryCountObjectsTraffic2="select count(id) from scsq_traffic where sizeinbytes>1000 and sizeinbytes<=5000";
			  $queryCountObjectsTraffic3="select count(id) from scsq_traffic where sizeinbytes>5000 and sizeinbytes<=10000";
			  $queryCountObjectsTraffic4="select count(id) from scsq_traffic where sizeinbytes>10000";

			#  $result=mysqli_query($connection,$queryCountRowsTraffic, MYSQLI_USE_RESULT);

			$result = $ssq->query($queryCountRowsTraffic) ;
			$CountRowsTraffic=$ssq->fetch_array($result);
			$ssq->free_result($result);

			$result = $ssq->query($queryCountRowsLogin) ;
			$CountRowsLogin=$ssq->fetch_array($result);
			$ssq->free_result($result);

			$result = $ssq->query($queryCountRowsIpaddress) ;
			$CountRowsIpaddress=$ssq->fetch_array($result);
			$ssq->free_result($result);

			$result = $ssq->query($queryMinMaxDateTraffic) ;
			$MinMaxDateTraffic=$ssq->fetch_array($result);
			$ssq->free_result($result);

			$result = $ssq->query($querySumSizeTraffic) ;
			$SumSizeTraffic=$ssq->fetch_array($result);
			$ssq->free_result($result);

				if($enableTrafficObjectsInStat==1)
				{
				  $result=$ssq->query($queryCountObjectsTraffic1);
				  $CountObjects1=$ssq->fetch_array($result);
				  $result=$ssq->query($queryCountObjectsTraffic2);
				  $CountObjects2=$ssq->fetch_array($result);
				  $result=$ssq->query($queryCountObjectsTraffic3);
				  $CountObjects3=$ssq->fetch_array($result);
				  $result=$ssq->query($queryCountObjectsTraffic4);
				  $CountObjects4=$ssq->fetch_array($result);
				}

  
		
		
		
		
		
      echo "
      <h2>".$_lang['stSTATS'].":</h2>
      <table class=\"datatable\">
      <tr>
         <td>".$_lang['stQUANTITYRECORDS']."</td>
         <td>".$CountRowsTraffic[0]."</td>
      </tr>
      <tr>
         <td>".$_lang['stQUANTITYLOGINS']."</td>
         <td>".$CountRowsLogin[0]."</td>
      </tr>
      <tr>
         <td>".$_lang['stQUANTITYIPADDRESSES']."</td>
         <td>".$CountRowsIpaddress[0]."</td>
      </tr>
      <tr>
         <td>".$_lang['stMINDATE']."</td>
         <td>".$MinMaxDateTraffic[0]."</td>
      </tr>
      <tr>
         <td>".$_lang['stMAXDATE']."</td>
         <td>".$MinMaxDateTraffic[1]."</td>
      </tr>
      <tr>
         <td>".$_lang['stTRAFFICSUM']."</td>
         <td>".($SumSizeTraffic[0]/$oneMegabyte)."</td>
      </tr>
";

	if($enableTrafficObjectsInStat==1)
	{
	echo "
	      <tr>
	         <td colspan=2 align=center><b>".$_lang['stTRAFFICOBJECTS']."</b></td>
	      </tr>
	      <tr>
	         <td> < 1 kB</td>
	         <td>".$CountObjects1[0]."</td>
	      </tr>
	      <tr>
	         <td> > 1 kB & < 5 kB </td>
	         <td>".$CountObjects2[0]."</td>
	      </tr>
	      <tr>
	         <td> > 5 kB & < 10 kB </td>
	         <td>".$CountObjects3[0]."</td>
	      </tr>
	      <tr>
	         <td> > 10 kB </td>
	         <td>".$CountObjects4[0]."</td>
	      </tr>
      ";
	}

echo "</table>";

}  //end GET[id]=1
//    else

      if($_GET['id']==2) {  //aliases

        if(isset($_GET['actid'])) //action ID.
          $actid=$_GET['actid'];
        else
          $actid=0;


        if(isset($_GET['aliasid'])) //alias ID из таблицы scsq_alias для редактирования/удаления alias.
          $aliasid=$_GET['aliasid'];
        else
          $aliasid=0;

        if(isset($_POST['name'])) // имя
          $name=$_POST['name'];
        else
          $name=0;

        if(isset($_POST['typeid'])) // login или ipaddress 0 - логин, 1 - ipadddress 
          $typeid=1;
        else
          $typeid=0;

        if(isset($_POST['tableid'])) // id логина или ipaddress
          $tableid=$_POST['tableid'];
        else
          $tableid=0;

 

///SQL querys

        $queryAllLoginsToAdd="select id,name from scsq_logins  where name NOT IN (''".$goodLoginsList.") and id NOT IN (select scsq_alias.tableid from scsq_alias where typeid=0) order by name asc;";

        $queryAllIpaddressToAdd="select id,name from scsq_ipaddress where name NOT IN (''".$goodIpaddressList.") and id NOT IN (select scsq_alias.tableid from scsq_alias where typeid=1) order by name asc;";

if($dbtype==0)
$str = "group_concat(scsq_groups.name order by scsq_groups.name asc) as gconcat,
	group_concat(scsq_groups.id order by scsq_groups.name asc)";

if($dbtype==1)
$str = "string_agg(scsq_groups.name, ',' order by scsq_groups.name asc) as gconcat,
	string_agg(CAST(scsq_groups.id as text), ',' order by scsq_groups.name asc)";



        $queryAllLogins="select id,name from scsq_logins  where name NOT IN (''".$goodLoginsList.") order by name asc;";
        $queryAllIpaddress="select id,name from scsq_ipaddress where name NOT IN (''".$goodIpaddressList.") order by name asc;";
        $queryAllAliases="
	   SELECT 
	     tmp.alname,
	     tmp.altypeid,
	     tmp.altableid,
	     tmp.alid,
	     tmp.tablename,
	     ".$str."
	  FROM ((SELECT 
		scsq_alias.name as alname,
		scsq_alias.typeid as altypeid,
		scsq_alias.tableid as altableid,
		scsq_alias.id as alid,
		scsq_logins.name as tablename 
	       FROM scsq_alias 
	       LEFT JOIN scsq_logins ON scsq_alias.tableid=scsq_logins.id 
	       WHERE scsq_alias.typeid=0) 

	       UNION 
			
	        (SELECT 
		   scsq_alias.name as alname,
		   scsq_alias.typeid as altypeid,
		   scsq_alias.tableid as altableid,
		   scsq_alias.id as alid,
		   scsq_ipaddress.name as tablename 
		 FROM scsq_alias 
		 LEFT JOIN scsq_ipaddress ON scsq_alias.tableid=scsq_ipaddress.id 
		 WHERE scsq_alias.typeid=1)) as tmp
	  
	  LEFT JOIN scsq_aliasingroups ON scsq_aliasingroups.aliasid=tmp.alid
	  LEFT JOIN scsq_groups ON scsq_aliasingroups.groupid=scsq_groups.id
	  GROUP BY tmp.altableid,tmp.alname,tmp.altypeid,tmp.alid,tmp.tablename
	  ORDER BY alname asc";

        $queryOneAlias="select name,typeid,tableid,id,userlogin,password,active from scsq_alias where id='".$aliasid."';";
if($changepassword==1)        
$queryUpdateOneAlias="update scsq_alias set name='".$name."',typeid='".$typeid."',tableid='".$tableid."',userlogin='".$userlogin."',password='".$userpassword."',active='".$activeauth."' where id='".$aliasid."'";
else
$queryUpdateOneAlias="update scsq_alias set name='".$name."',typeid='".$typeid."',tableid='".$tableid."',userlogin='".$userlogin."',active='".$activeauth."' where id='".$aliasid."'";


        $queryDeleteOneAlias="delete from scsq_alias where id='".$aliasid."'";
        $queryDeleteOneAliasFromGroup="delete from scsq_aliasingroups where aliasid='".$aliasid."'";



///SQL querys end

//mysqli_connect($address,$user,$pass);
//mysqli_select_db($db);

///надо добавить обработку ошибки подключения к БД

        if(!isset($_GET['actid'])) {
          $result=$ssq->query($queryAllAliases);
          $numrow=1;
		echo 	"<h2>".$_lang['stALIASES'].":</h2>";
          echo "<a href=right.php?srv=".$srv."&id=2&actid=1>".$_lang['stADDALIAS']."</a>";
          echo "<br /><br />";

          echo "<table class=\"datatable\">
       		   <tr>
       		     <th ><b>#</b></th>
      		      <th><b>".$_lang['stALIASNAME']."&nbsp;</b></th>
        		    <th ><b>".$_lang['stTYPE']."</b></th>
         	      <th><b>".$_lang['stVALUE']."</b></th>
         	      <th><b>".$_lang['stGROUP']."</b></th>
         	  </tr>
          ";

         while($line = $ssq->fetch_array($result)) {
           if($line[1]=="1")
             $line[1]="<b><font color=green>".$_lang['stIPADDRESS']."</font></b>";
           else
             $line[1]="<b><font color=red>".$_lang['stLOGIN']."</font></b>";
           echo "
           <tr>
             <td>".$numrow."</td>

             <td align=center><a href=right.php?srv=".$srv."&id=2&actid=3&aliasid=".$line[3].">".$line[0]."&nbsp;</a></td>
             <td align=center>".$line[1]."</td>
             <td>".$line[4]."&nbsp;</td>
             <td>";
	   $i=0;
	   $splitgroupsname=explode(',',$line[5]);
	   $splitgroupsid=explode(',',$line[6]);
	   foreach($splitgroupsname as $val) {
	     echo "<a href=right.php?srv=".$srv."&id=3&actid=3&groupid=".$splitgroupsid[$i].">".$val."</a>&nbsp";
	     $i++;
	  
	 }

	   echo "</td>
           </tr>
           ";
           $numrow++;
          }  //end while
	$ssq->free_result($result);         
	echo "</table>";
         echo "<br />";
         echo "<a href=right.php?srv=".$srv."&id=2&actid=1>".$_lang['stADDALIAS']."</a>";
         echo "<br />";
        } // end if(!isset...
    

        if($actid==1) {

          echo "<h2>".$_lang['stFORMADDALIAS']."</h2>";
          echo '
            <form action="right.php?srv='.$srv.'&id=2&actid=2" method="post">
            <table class=datatable >
           <tr><td>'.$_lang['stALIASNAME'].':</td> <td><input type="text" name="name"></td></tr>
           <tr><td>'.$_lang['stTYPECHECK'].':</td> <td> <input type="checkbox" onClick="switchTables();" name="typeid"></td></tr>
           <tr><td>'.$_lang['stACTIVEAUTH'].':</td> <td> <input type="checkbox" name="activeauth"></td></tr>
		   <tr><td>'.$_lang['stUSERLOGIN'].':</td> <td> <input type="text" name="userlogin"></td></tr>
		   <tr><td>'.$_lang['stUSERPASSWORD'].':</td> <td> <input type="text" name="userpassword"></td></tr>
		   </table>
		   <br />
           <p>'.$_lang['stVALUE'].':</p>
           <br />
           ';

          $result=$ssq->query($queryAllLoginsToAdd);
          $numrow=1;

          echo "<table id='loginsTable' class=\"datatable\" style='display:table;'>";
	  echo "<tr>";
	  echo "    <th >#</th>
		    <th >".$_lang['stLOGIN']."</th>
		    <th >".$_lang['stINCLUDE']."</th>
		</tr>";
          while($line = $ssq->fetch_array($result)) {
            echo "
            <tr>
              <td >".$numrow."</td>
              <td >".$line[1]."</td>
              <td><input type='radio' name='tableid' value='".$line[0]."';</td>
            </tr>
            ";
            $numrow++;
          }
          echo "</table>";
	  $ssq->free_result($result);

          $result=$ssq->query($queryAllIpaddressToAdd);
          $numrow=1;

          echo "<table id='ipaddressTable' class=\"datatable\" style='display:none;'>";
	  echo "<tr>";
	  echo "    <th class=unsortable>#</th>
		    <th>".$_lang['stIPADDRESS']."</th>
		    <th class=unsortable>".$_lang['stINCLUDE']."</th>
		</tr>";

          while($line = $ssq->fetch_array($result)) {
            echo "
              <tr>
                <td >".$numrow."</td>
                <td >".$line[1]."</td>
                <td><input type='radio' name='tableid' value='".$line[0]."';</td>
              </tr>
            ";
            $numrow++;
          }

	  $ssq->free_result($result);
                
	  echo "</table>";
          echo '
            <input type="submit" value="'.$_lang['stADD'].'"><br />
            </form>
            <br />
          ';
        }  //end if($actid==1..

        if($actid==2) {  //добавление алиаса
          $name=$_POST['name'];

          if(!isset($_POST['typeid']))
            $typeid=0;
          else
            $typeid=1;

	  if(!isset($_POST['activeauth']))
            $activeauth=0;
          else
            $activeauth=1;



          $tableid=$_POST['tableid'];

	

          $sql="INSERT INTO scsq_alias (name, typeid,tableid,userlogin,password,active) VALUES ('$name', '$typeid','$tableid','$userlogin','$userpassword','$activeauth')";

          if (!$ssq->query($sql)) {
            die('Error: Can`t insert alias into table!');
          }
          echo "".$_lang['stALIASADDED']."<br /><br />";
          echo "<a href=right.php?srv=".$srv."&id=2 target=right>".$_lang['stBACK']."</a>";
        }

        if($actid==3) { ///Редактирование алиаса
	  
	  $result=$ssq->query($queryOneAlias);
          $line=$ssq->fetch_array($result);
	  $ssq->free_result($result);
          
	  $typeid=$line[1]; #сохраняем тип алиаса.
	  if($line[1]==1)
            $isChecked="checked";
          else
            $isChecked="";

          $tableid=$line[2];
          $aliasid=$line[3];

          if($line[6]==1)
            $activeIsChecked="checked";
          else
            $activeIsChecked="";
		

		  echo "<h2>".$_lang['stFORMEDITALIAS']."</h2>";
          echo '
            <form action="right.php?srv='.$srv.'&id=2&actid=4&aliasid='.$aliasid.'" method="post">
            <table class=datatable>
           <tr><td>'.$_lang['stALIASNAME'].':</td> <td><input type="text" name="name" value="'.$line[0].'"></td></tr>
           <tr><td>'.$_lang['stACTIVEAUTH'].':</td> <td> <input type="checkbox" '.$activeIsChecked.' name="activeauth"></td></tr>
		   <tr><td>'.$_lang['stUSERLOGIN'].':</td> <td> <input type="text" name="userlogin" value="'.$line[4].'"></td></tr>
		   <tr><td>'.$_lang['stCHANGEPASSWORD'].':</td> <td> <input type="checkbox" name="changepassword"></td></tr>
		   <tr><td>'.$_lang['stUSERPASSWORD'].':</td> <td> <input type="text" name="userpassword"></td></tr>
		   </table>
		   <br />
           <p>'.$_lang['stVALUE'].':</p>
           <br />
                ';

          $result=$ssq->query($queryAllLogins);
          $numrow=1;

          if($isChecked=="checked")
            echo "<table id='loginsTable' class=datatable style='display:none;'>";
          else
            echo "<table id='loginsTable' class=datatable style='display:table;'>";
	  echo "<tr>";
	  echo "    <th class=unsortable>#</th>
		    <th>".$_lang['stLOGIN']."</th>
		    <th class=unsortable>".$_lang['stINCLUDE']."</th>
	        </tr>";

          while($line = $ssq->fetch_array($result)) {
            echo "<tr>";
            echo "<td >".$numrow."</td>";
            echo "<td >".$line[1]."</td>";
            if(($tableid==$line[0])&&($ischecked==""))
              echo "<td><input type='radio' name='tableid' checked value='".$line[0]."'></td>";
            else
              echo "<td><input type='radio' name='tableid' value='".$line[0]."'></td>";
	    echo   "</tr>";
            $numrow++;
          }
          echo "</table>";
          $ssq->free_result($result);
          $result=$ssq->query($queryAllIpaddress);
          $numrow=1;

          if($isChecked=="checked")
            echo "<table id='ipaddressTable' class=datatable style='display:table;'>";
          else
            echo "<table id='ipaddressTable' class=datatable style='display:none;'>";
	  echo "<tr>";
	  echo "    <th class=unsortable>#</th>
		    <th>".$_lang['stIPADDRESS']."</th>
		    <th class=unsortable>".$_lang['stINCLUDE']."</th>
	        </tr>";
          while($line = $ssq->fetch_array($result)) {
            echo "<tr>";
	    echo "<td >".$numrow."</td>";
	    echo "<td >".$line[1]."</td>";
            if(($tableid==$line[0])&&($isChecked=="checked"))
              echo "<td><input type='radio' name='tableid' checked value='".$line[0]."'></td>";
            else
              echo "<td><input type='radio' name='tableid' value='".$line[0]."'></td>";
	    echo "</tr>";
            $numrow++;
          }
	  $ssq->free_result($result);
          echo "</table>";
	  if($typeid == 1)
	  echo '<input type="hidden" name=typeid value="'.$typeid.'">';
          
	  echo '
	    <input type="submit" value="'.$_lang['stSAVE'].'"><br />
            </form>
            <form action="right.php?srv='.$srv.'&id=2&actid=5&aliasid='.$aliasid.'" method="post">
            <input type="submit" value="'.$_lang['stDELETE'].'"><br />
            </form>
            <br />';
        }
        if($actid==4) { //сохранение изменений UPDATE
 
          if (!$ssq->query($queryUpdateOneAlias)) {
            die('Error: Can`t update 1 alias');
          }
          echo "".$_lang['stALIASUPDATED']."<br /><br />";
          echo "<a href=right.php?srv=".$srv."&id=2 target=right>".$_lang['stBACK']."</a>";
        }

        if($actid==5) { //удаление DELETE
        
          if (!$ssq->query($queryDeleteOneAlias)) {
            die('Error: Can`t delete 1 alias');
          }
          if (!$ssq->query($queryDeleteOneAliasFromGroup)) {
            die('Error1: Cant delete 1 alias from group');
          }
          
	  echo "".$_lang['stALIASDELETED']."<br /><br />";
          echo "<a href=right.php?srv=".$srv."&id=2 target=right>".$_lang['stBACK']."</a>";

        } //удаление
      } ///end if($_GET['id']==2

//else

         if($_GET['id']==3) { //группы

            if(isset($_GET['actid'])) //action ID.
              $actid=$_GET['actid'];
            else
              $actid=0;


              if(isset($_GET['groupid'])) //group ID из таблицы scsq_groups для редактирования/удаления group.
                $groupid=$_GET['groupid'];
              else
                $groupid=0;

              if(isset($_POST['name'])) // имя
                $name=$_POST['name'];
              else
                $name=0;

              if(isset($_POST['typeid'])) // login или ipaddress 0 - логин, 1 - ipadddress 
                $typeid=1;
              else
                $typeid=0;

              if(isset($_POST['comment'])) // комментарий к группе 
                $comment=$_POST['comment'];
              else
                $comment="";

///SQL querys

            $queryAllLogins="select id,name from scsq_alias where typeid=0 order by name asc;";
            $queryAllIpaddress="select id,name from scsq_alias where typeid=1 order by name asc;";

            $queryAllGroups="SELECT 
				scsq_groups.name,
				scsq_groups.typeid,
				scsq_groups.id,
				scsq_groups.comment,
				count(scsq_aliasingroups.aliasid)
			     FROM scsq_aliasingroups 
			     RIGHT OUTER JOIN scsq_groups ON scsq_groups.id=scsq_aliasingroups.groupid
			     GROUP BY scsq_aliasingroups.groupid
			     ORDER BY name asc;";

           $queryAllGroups="SELECT 
				scsq_groups.name,
				scsq_groups.typeid,
				scsq_groups.id,
				scsq_groups.comment,
				count(scsq_aliasingroups.aliasid)
			     FROM scsq_aliasingroups 
			     RIGHT OUTER JOIN scsq_groups ON scsq_groups.id=scsq_aliasingroups.groupid
			     GROUP BY scsq_aliasingroups.groupid, scsq_groups.name, scsq_groups.id, scsq_groups.typeid, scsq_groups.comment
			     ORDER BY name asc;";

            $queryGroupMembers="select aliasid from scsq_aliasingroups where groupid='".$groupid."'";
            $queryOneGroup="select name,typeid,id,comment,userlogin,active from scsq_groups where id='".$groupid."';";
            $queryOneGroupList="SELECT
				   scsq_groups.name, 
				   scsq_alias.name
				FROM scsq_aliasingroups 
				RIGHT JOIN scsq_alias ON scsq_alias.id=scsq_aliasingroups.aliasid
				RIGHT JOIN scsq_groups ON scsq_groups.id=scsq_aliasingroups.groupid
				WHERE groupid='".$groupid."'
				ORDER BY scsq_alias.name asc;";
	if($changepassword==1) 
            $queryUpdateOneGroup="update scsq_groups set name='".$name."',typeid='".$typeid."',comment='".$comment."',userlogin='".$userlogin."',password='".$userpassword."',active='".$activeauth."' where id='".$groupid."'";
	else
            $queryUpdateOneGroup="update scsq_groups set name='".$name."',typeid='".$typeid."',comment='".$comment."',userlogin='".$userlogin."',active='".$activeauth."' where id='".$groupid."'";


            $queryDeleteOneGroup="delete from scsq_groups where id='".$groupid."'";

            if(!isset($_GET['actid'])) {
              $result=$ssq->query($queryAllGroups);
              $numrow=1;
              echo 	"<h2>".$_lang['stGROUPS'].":</h2>";
              echo "<a href=right.php?srv=".$srv."&id=3&actid=1>".$_lang['stADDGROUP']."</a>";
              echo "<br /><br />";
              echo "<table id=report_table_id_group class=datatable>
              <tr>
                <th class=unsortable><b>#</b></th>
                <th><b>".$_lang['stGROUPNAME']."</b></th>
                <th class=unsortable><b>".$_lang['stTYPE']."</b></th>
                <th class=unsortable><b>".$_lang['stCOMMENT']."</b></th>
		<th><b>".$_lang['stQUANTITYALIASES']."</b></th>

              </tr>";

              while($line = $ssq->fetch_array($result)) {
                if($line[1]=="1")
                  $line[1]="<b><font color=green>".$_lang['stIPADDRESS']."</font></b>";
                else
                  $line[1]="<b><font color=red>".$_lang['stLOGIN']."</font></b>";

                echo "
                  <tr>
                    <td>".$numrow."</td>
                    <td align=center><a href=right.php?srv=".$srv."&id=3&actid=3&groupid=".$line[2].">".$line[0]."&nbsp;</a></td>
                    <td align=center>".$line[1]."</td>
                    <td align=center>".$line[3]."&nbsp;</td>
                    <td align=center>".$line[4]."&nbsp;<a href=right.php?srv=".$srv."&id=3&actid=6&groupid=".$line[2].">".$_lang['stWHO']."</a></td>
                 </tr>";
                $numrow++;
              }
              echo "</table>";
              echo "<br />";
              echo "<a href=right.php?srv=".$srv."&id=3&actid=1>".$_lang['stADDGROUP']."</a>";
              echo "<br />";
		$ssq->free_result($result);
            }

            if($actid==1) {
              echo "<h2>".$_lang['stFORMADDGROUP']."</h2>";
              echo '
					<form action="right.php?srv='.$srv.'&id=3&actid=2" method="post">
					<table class=datatable>
					   <tr><td>'.$_lang['stGROUPNAME'].':</td> <td><input type="text" name="name"></td></tr>
					   <tr><td>'.$_lang['stTYPECHECK'].':</td> <td> <input type="checkbox" onClick="switchTables();" name="typeid"></td></tr>
					   <tr><td>'.$_lang['stCOMMENT'].':</td> <td> <input type="text" name="comment"></td></tr>
					   <tr><td>'.$_lang['stACTIVEAUTH'].':</td> <td> <input type="checkbox" name="activeauth"></td></tr>
					   <tr><td>'.$_lang['stUSERLOGIN'].':</td> <td> <input type="text" name="userlogin"></td></tr>
					   <tr><td>'.$_lang['stUSERPASSWORD'].':</td> <td> <input type="text" name="userpassword"></td></tr>
					</table>
				   <br />
				   <br />
                '.$_lang['stVALUE'].':<br />';

                $result=$ssq->query($queryAllLogins);
                $numrow=1;

              echo "<table id='loginsTable' class=datatable style='display:table;'>";
              echo "<tr>
		    <th >#</th>
		    <th>".$_lang['stALIAS']."</th>
		    <th >".$_lang['stINCLUDE']."</th>
		    </tr>";

              while($line = $ssq->fetch_array($result)) {
                echo "
                  <tr>
                    <td >".$numrow."</td>
                    <td >".$line[1]."</td>
                    <td><input type='checkbox' name='tableidl[]' value='".$line[0]."';</td>
                  </tr>";
                $numrow++;
              }
	      $ssq->free_result($result);
              echo "</table>";

              $result=$ssq->query($queryAllIpaddress);
              $numrow=1;

              echo "<table id='ipaddressTable' class=datatable style='display:none;'>";
              echo "<tr>
		    <th class=unsortable>#</th>
		    <th>".$_lang['stALIAS']."</th>
		    <th class=unsortable>".$_lang['stINCLUDE']."</th>
		    </tr>";
              while($line = $ssq->fetch_array($result)) {
                echo "
                  <tr>
                    <td >".$numrow."</td>
                    <td >".$line[1]."</td>
                    <td><input type='checkbox' name='tableidip[]' value='".$line[0]."';</td>
                  </tr>";
                $numrow++;
              }
              $ssq->free_result($result);
	      echo "</table>";

              echo '
                <input type="submit" value="'.$_lang['stADD'].'"><br />
                </form>
                <br />';
            }

            if($actid==2) { ///добавление группы

              $name=$_POST['name'];

              if(!isset($_POST['typeid']))
                $typeid=0;
              else
                $typeid=1;

              if(!isset($_POST['comment']))
                $comment="";
              else
                $comment=$_POST['comment'];


              $sql="INSERT INTO scsq_groups (name, typeid,comment,userlogin, password,active) VALUES ('$name', '$typeid','$comment','$userlogin','$userpassword','$activeauth')";

              if (!$ssq->query($sql)) {
                die('Error: Can`t insert new group');
              }

              $sql="select id from scsq_groups where name='".$name."';";
              $result=$ssq->query($sql);
              $newid=$ssq->fetch_array($result);
	      $ssq->free_result($result);
              $sql="INSERT INTO scsq_aliasingroups (groupid, aliasid) VALUES  ";

              if($typeid==0) {
                foreach($_POST['tableidl'] as $key=>$value)
                  $sql = $sql." ('".$newid[0]."','". $value."'),";
            
                $sql=substr($sql,0,strlen($sql)-1);
                $sql=$sql.";";
              }
              if($typeid==1) {
                foreach($_POST['tableidip'] as $key=>$value)
                  $sql = $sql." ('".$newid[0]."','". $value."'),";
                $sql=substr($sql,0,strlen($sql)-1);
                $sql=$sql.";";
              }

              $ssq->query($sql);

              echo "".$_lang['stGROUPADDED']."<br /><br />";
              echo "<a href=right.php?srv=".$srv."&id=3 target=right>".$_lang['stBACK']."</a>";
            }

            if($actid==3) { ///Редактирование группы
              $result=$ssq->query($queryOneGroup);
              $line=$ssq->fetch_array($result);
	      $ssq->free_result($result);
              if($line[1]==1)
                $isChecked="checked";
              else
                $isChecked="";

		 if($line[5]==1)
		    $activeIsChecked="checked";
		  else
		    $activeIsChecked="";

 
			  echo "<h2>".$_lang['stFORMEDITGROUP']."</h2>";
			  echo '
			<form action="right.php?srv='.$srv.'&id=3&actid=4&groupid='.$groupid.'" method="post">
					<table class=datatable>
					   <tr><td>'.$_lang['stGROUPNAME'].':</td> <td><input type="text" name="name" value="'.$line[0].'"></td></tr>
					   <tr><td>'.$_lang['stTYPECHECK'].':</td> <td> <input type="checkbox" onClick="switchTables();" name="typeid" '.$isChecked.' ></td></tr>
					   <tr><td>'.$_lang['stCOMMENT'].':</td> <td> <input type="text" name="comment" value="'.$line[3].'"></td></tr>
					   <tr><td>'.$_lang['stACTIVEAUTH'].':</td> <td> <input type="checkbox" '.$activeIsChecked.' name="activeauth"></td></tr>
					   <tr><td>'.$_lang['stUSERLOGIN'].':</td> <td> <input type="text" name="userlogin" value="'.$line[4].'"></td></tr>
					   <tr><td>'.$_lang['stCHANGEPASSWORD'].':</td> <td> <input type="checkbox" name="changepassword"></td></tr>
					   <tr><td>'.$_lang['stUSERPASSWORD'].':</td> <td> <input type="text" name="userpassword"></td></tr>
					</table>
				   <br />			  
				   <br />
               '.$_lang['stVALUE'].':<br />';

               $result=$ssq->query($queryAllLogins);
               $result1=$ssq->query($queryGroupMembers);

               $numrow=1;

               if($isChecked=="checked")
                 echo "<table id='loginsTable' class=datatable style='display:none;'>";
               else
                 echo "<table id='loginsTable' class=datatable style='display:table;'>";
               echo "<tr>
		    <th >#</th>
		    <th>".$_lang['stALIAS']."</th>
		    <th >".$_lang['stINCLUDE']."</th>
		    </tr>";
               $groupmembers=array();
               while($line = $ssq->fetch_array($result1))
                 $groupmembers[]= $line[0];
	       $ssq->free_result($result1);
               while($line = $ssq->fetch_array($result)) {
                 echo "<tr>";
		 echo "<td >".$numrow."</td>";
		 echo "<td >".$line[1]."</td>";
                 if((in_array($line[0],$groupmembers))&&($ischecked==""))
                   echo "<td><input type='checkbox' name='tableidl[]' checked value='".$line[0]."'></td>";
                 else
                   echo "<td><input type='checkbox' name='tableidl[]' value='".$line[0]."'></td>";
		 echo "</tr>";
                   ;
                 $numrow++;
               }
	       $ssq->free_result($result);
               echo "</table>";
	       $ssq->free_result($result);
               $result=$ssq->query($queryAllIpaddress);
               $numrow=1;

               if($isChecked=="checked")
                 echo "<table id='ipaddressTable' class=datatable style='display:table;'>";
               else
                 echo "<table id='ipaddressTable' class=datatable style='display:none;'>";
               echo "<tr>
		    <th >#</th>
		    <th>".$_lang['stALIAS']."</th>
		    <th >".$_lang['stINCLUDE']."</th>
		    </tr>";
               while($line = $ssq->fetch_array($result)) {

                 echo "<tr>";
		 echo "<td >".$numrow."</td>";
		 echo "<td >".$line[1]."</td>";
                 if((in_array($line[0],$groupmembers))&&($isChecked=="checked"))
                   echo "<td><input type='checkbox' name='tableidip[]' checked value='".$line[0]."'></td>";
                 else
                   echo "<td><input type='checkbox' name='tableidip[]' value='".$line[0]."'></td>";
                 echo "</tr>";

                 $numrow++;
               }
               $ssq->free_result($result);
	       echo "</table>";

               echo '
                 <input type="submit" value="'.$_lang['stSAVE'].'"><br />
                 </form>
                 <form action="right.php?srv='.$srv.'&id=3&actid=5&groupid='.$groupid.'" method="post">
                 <input type="submit" value="'.$_lang['stDELETE'].'"><br />
                 </form>
                 <br />';
            } // end actid=3

            if($actid==4) { //сохранение изменений UPDATE

              if (!$ssq->query($queryUpdateOneGroup)) {
                die('Error: Cant update one group');
              }

              $sql="delete from scsq_aliasingroups where groupid='".$groupid."';";

              $ssq->query($sql) or die();

              $sql="INSERT INTO scsq_aliasingroups (groupid, aliasid) VALUES  ";

              if($typeid==0) {
                foreach($_POST['tableidl'] as $key=>$value)
                  $sql = $sql." ('".$groupid."','". $value."'),";

                $sql=substr($sql,0,strlen($sql)-1);
                $sql=$sql.";";
              }

              if($typeid==1) {
                foreach($_POST['tableidip'] as $key=>$value)
                  $sql = $sql." ('".$groupid."','". $value."'),";

                $sql=substr($sql,0,strlen($sql)-1);
                $sql=$sql.";";
              }

              $ssq->query($sql);

              echo "".$_lang['stGROUPUPDATED']."<br /><br />";
              echo "<a href=right.php?srv=".$srv."&id=3 target=right>".$_lang['stBACK']."</a>";
            } //end actid=4

            if($actid==5) {//удаление DELETE

              if (!$ssq->query($queryDeleteOneGroup)) {
                die('Error: Cant delete one group');
              }
              $sql="delete from scsq_aliasingroups where groupid='".$groupid."';";

              $ssq->query($sql) or die();

              echo "".$_lang['stGROUPDELETED']."<br /><br />";
              echo "<a href=right.php?srv=".$srv."&id=3 target=right>".$_lang['stBACK']."</a><br />";

            } //end actid=5

            if($actid==6) { ///Просмотр группы

              $result=$ssq->query($queryOneGroupList);

	      $numrow=1;

               while($line = $ssq->fetch_array($result)) {
		 if($numrow==1) {
		   echo "".$_lang['stGROUPNAME']." : <b>".$line[0]."</b><br /><br />";
                   echo "<table id='OneGroupList' class=datatable >";
                   echo "<tr>
		      <th >#</th>
		      <th>".$_lang['stALIAS']."</th>
		      </tr>";
		 }
                 echo "<tr>";
		 echo "<td >".$numrow."</td>";
		 echo "<td >".$line[1]."</td>";
		 echo "</tr>";
                 $numrow++;
               }
	       $ssq->free_result($result);
               echo "</table>";
	       echo "<br />";
               echo "<a href=right.php?srv=".$srv."&id=3>".$_lang['stBACKTOGROUPLIST']."</a>";
                 
            } // end actid=6

          } ///end GET[id]=3

//else

            if($_GET['id']==4) { ///быстрый поиск
           
              if(isset($_GET['actid'])) //action ID.
                $actid=$_GET['actid'];
              else
              $actid=0;


              if(isset($_POST['findstr'])) //alias ID из таблицы scsq_alias для редактирования/удаления alias.
                $findstr=$_POST['findstr'];
              else
                $findstr="";

              if(isset($_POST['typeid'])) // login или ipaddress 0 - логин, 1 - ipadddress 
                $typeid=1;
              else
                $typeid=0;

///SQL querys

              if($typeid==0)
                $queryForFindstr="select name,id from (select name,id from scsq_logins where name like '%".$findstr."%') as tmp where tmp.name NOT IN (''".$goodLoginsList.") order by name asc;";
              
              if($typeid==1)
                $queryForFindstr="select name,id from (select name,id from scsq_ipaddress where name like '%".$findstr."%') as tmp where tmp.name NOT IN (''".$goodIpaddressList.") order by name asc;";


              if(!isset($_GET['actid'])) {

                echo "<h2>".$_lang['stSEARCHFORM']."</h2>";
                echo '
                  <form action="right.php?srv='.$srv.'&id=4&actid=1" method="post">
                 		<table class=datatable>
					   <tr><td>'.$_lang['stSEARCHSTRING'].':</td> <td><input type="text" name="findstr"></td></tr>
					   <tr><td>'.$_lang['stTYPECHECK'].':</td> <td> <input type="checkbox" name="typeid"></td></tr>
						</table>
                 <br />
                  <input type="submit" value="'.$_lang['stSEARCH'].'"><br />
                  </form>
                  <br />';
              }

              if($actid==1) { ///результаты поиска

                echo $_lang['stSEARCHFORM'];
                echo '
                  <form action="right.php?srv='.$srv.'&id=4&actid=1" method="post">
                 		<table class=datatable>
					   <tr><td>'.$_lang['stSEARCHSTRING'].':</td> <td><input type="text" name="findstr"></td></tr>
					   <tr><td>'.$_lang['stTYPECHECK'].':</td> <td> <input type="checkbox" name="typeid"></td></tr>
						</table>
                 <br />
                  <input type="submit" value="'.$_lang['stSEARCH'].'"><br />
                  </form>
                  <br />';

/////////// LOGINS

                if($typeid==0) {
                  echo "
                    <table id=report_table_id_1 class=datatable>
                    <tr>
                      <th >
                        #
                      </th>
                      <th>
                        ".$_lang['stLOGIN']."
                      </th>
                    </tr>";

                  $result=$ssq->query($queryForFindstr) or die ('Error find str');
                  $numrow=1;

                  while ($line = $ssq->fetch_array($result)) {
                    echo "<tr>";
                    echo "<td>".$numrow."</td>";
                    echo "<td><a href=javascript:PartlyReportsLogin(8,'day','".$line[1]."','".$line[0]."','')>".$line[0]."</td>";
                    echo "</tr>";
                    $numrow++;
                  }
		  $ssq->free_result($result);
                  echo "</table>";
                }

/////////// LOGINS  END

//////////// IPADDRESS

                if($typeid==1) {
                  echo "
                    <table id=report_table_id_1 class=datatable>
                    <tr>
                      <th >
                      #
                      </th>
                      <th>
                      ".$_lang['stIPADDRESS']."
                      </th>
                    </tr>";

                  $result=$ssq->query($queryForFindstr) or die ('Cant find str');
                  $numrow=1;

                  while ($line = $ssq->fetch_array($result)) {
                    echo "<tr>";
                    echo "<td>".$numrow."</td>";
                    echo "<td><a href=javascript:PartlyReportsIpaddress(11,'day','".$line[1]."','".$line[0]."','')>".$line[0]."</td>";
                    echo "</tr>";
                    $numrow++;
                  }
		  $ssq->free_result($result);
                  echo "</table>";

                }
              }
/////////////// IPADDRESS END

            } ///end GET[id]=4
 ///	    else
	

   if($_GET['id']==5) {
      echo "
      <h2>".$_lang['stLOGTABLE'].":</h2>
      <table class=datatable>
      <tr>
         <th>#</th>
         <th>".$_lang['stLOGDATESTART']."</th>
         <th>".$_lang['stLOGDATEEND']."</th>
         <th>".$_lang['stLOGMESSAGE']."</th>
      </tr>
   ";
  		
  		if($dbtype==0)
  		#mysql
		  $queryLogTable="SELECT
			FROM_UNIXTIME(datestart,'%Y-%m-%d %H:%i:%s') as d1,
			FROM_UNIXTIME(dateend,'%Y-%m-%d %H:%i:%s'),
			message
		  FROM scsq_logtable order by d1 desc";

		if($dbtype==1)
		#postgre 
		  $queryLogTable="SELECT
			to_char(to_timestamp(datestart),'YYYY-MM-DD-HH-MM-SS') as d1,
			to_char(to_timestamp(dateend),'YYYY-MM-DD-HH-MM-SS'),
 			message
		  FROM scsq_logtable order by d1 desc";



                  $result=$ssq->query($queryLogTable) or die ('Cant get log table');
                  $numrow=1;

                  while ($line = $ssq->fetch_array($result)) {
                    echo "<tr>";
                    echo "<td>".$numrow."</td>";
                    echo "<td>".$line[0]."</td>";
                    echo "<td>".$line[1]."</td>";
                    echo "<td>".$line[2]."</td>";
                    echo "</tr>";
                    $numrow++;
                  }
		  $ssq->free_result($result);
                  echo "</table>";

    }  //end GET[id]=5

 if($_GET['id']==6) {
   	
	//определим файл который будем изменять. Это сделано для того, чтобы работая в админском интерфейсе, можно было конфигурить версию для директора:)
	$configfile = "config.php";

if(isset($_GET['actid']))

	if($_GET['actid'] == 3) ///сохранить настройки
	{
		$file=file($configfile); 

		foreach($_POST as $key => $val) {
		
		$stkey = str_replace("<","[",$key);
		$stkey = str_replace(">","]",$stkey);
		
		
		for($i=0;$i<sizeof($file);$i++)
		  {
			$st = $file[$i];
			#исключаем лишние строки. Чтоб не изменять лишнее.
			if($st[0]<>"#" && $st[0]<>"\n" && $st[0]<>"/" && $st[0]<>"?" && $st[0]<>"<" && $st[0]<>"i")
			
				if(strpos($file[$i],$stkey)){
					$st = '$'.$stkey.'="'.$val.'";'.PHP_EOL;
					$file[$i] = $st; 
				}
		  }
	
		}
	
		$fp=fopen($configfile,"w"); 
		fputs($fp,implode("",$file)); 
		fclose($fp);
		

	} //if($_GET['actid'] == 3) 
	

  

    echo "
      <h2>".$_lang['stCONFIG']." Screen Squid:</h2>
      <table class=datatable>
      <tr>
         <th>#</th>
         <th>".$_lang['stPARAMNAME']."</th>
         <th>".$_lang['stPARAMVALUE']."</th>
         <th>".$_lang['stCOMMENT']."</th>
      </tr>
   ";
	$file=file($configfile); 
	
	$num = 1; //
	
	echo '<form name=configphp_form action="right.php?srv='.$srv.'&id=6&actid=3" method="post">';
	
	for($i=0;$i<sizeof($file);$i++){
	
	$st = $file[$i];
		#исключаем лишние строки. Также можно будет регулировать количество отображаемых параметров
		if($st[0]<>"#" && $st[0]<>"\n" && $st[0]<>"/" && $st[0]<>"?" && $st[0]<>"<" && $st[0]<>"i" && strpos($st,"vers")==0 && strpos($st,"debug")==0)	{

	  	$expString = explode("$",$file[$i]);
	  	if(isset($expString[1]))
	  	$expParamname = explode("=",$expString[1]);
		$expParamname = str_replace("[","<",$expParamname);
		$expParamname = str_replace("]",">",$expParamname);
		if(isset($expParamname[1]))
	  	$expParamvalue = explode(";",$expParamname[1]);		
		#стираем лишние символы для пользователя		
		$expParamvalue = str_replace("\"","",$expParamvalue);
		

		echo '
			<tr>
				<td>'.$num.'</td>
				<td>'.trim($expParamname[0]).'</td>
				<td><input type="text" name="'.trim($expParamname[0]).'" value="'.trim($expParamvalue[0]).'"></td>
				<td></td>
			</tr>
		';
		$num++;
		}
	
	} //end for

	
	echo "</table>";
	echo "<br />";
	echo '<input type="submit" value="'.$_lang['stSAVE'].'"><br />';
   	echo " </form>";


                  
                  

    }  //end GET[id]=6


 if($_GET['id']==7) {
   	
	$configfile = "config.php";
	
	
	

$path    = 'modules/';
$files = array_diff(scandir($path), array('.', '..'));



    echo "
      <h2>".$_lang['stMODULEMANAGER']." Screen Squid:</h2>
      <table class=datatable>
      <tr>
         <th>#</th>
         <th>".$_lang['stNAME']."</th>
	 <th>".$_lang['stDESCRIPTION']."</th>
         <th>".$_lang['stINSTALL']."</th>
         <th>".$_lang['stUNINSTALL']."</th>
      </tr>
	";
$num=1;
foreach($files as $file)
{
	include("modules/".$file."/module.php");
	$module = new $file($variableSet);

echo "
      <tr>

         <td>".$num."</td>

         <td><a href=\"modules/".$file."/index.php?srv=".$srv."\">".$file."</a></td>
         <td>".$module->GetDesc()."</td>
         <td><a href=\"right.php?srv=".$srv."&id=7&actid=10&mod=".$file."\">".$_lang['stINSTALL']."</a></td>
         <td><a href=\"right.php?srv=".$srv."&id=7&actid=11&mod=".$file."\">".$_lang['stUNINSTALL']."</a></td>

      </tr>
	";
$num++;
}
echo "	</table>
   ";

		if(isset($_GET['actid']))
		{
			if($_GET['actid'] == 10) ///установить
			{
			
			$test = new $_GET['mod']($variableSet);
			echo $test->Install();
				

			} //if($_GET['actid'] == 10) 
			
			if($_GET['actid'] == 11) ///удалить
			{
			
			$test = new $_GET['mod']($variableSet);	
			$test->Uninstall();
				

			} //if($_GET['actid'] == 11) 

		} //if(isset($_GET['actid']))
                  
                  

    }  //end GET[id]=7



///            else


 } //if(isset($_GET['id'])) {   

            

 } /// else { 

//if($connectionStatus!="error")




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
    <input type="hidden" name=group_field_hidden value="<?php if(isset($currentgroupid)) echo $currentgroupid; else echo "0"; ?>">
    <input type="hidden" name=groupname_field_hidden value="<?php if(isset($currentgroup)) echo $currentgroup; else echo "0"; ?>">
    <input type="hidden" name=typeid_field_hidden value="<?php if(isset($typeid)) echo $typeid; else echo "0";?>">
    </form>
</body>
</html>
