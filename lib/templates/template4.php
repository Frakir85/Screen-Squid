<?php

//report для One login traffic
//id 8
$colhtext[1]="#";
$colhtext[2]=$_lang['stSITE'];
$colhtext[3]=$_lang['stMEGABYTES'];
$colhtext[4]=$_lang['stDENIEDMEGABYTES'];
$colhtext[5]=$_lang['stCATEGORY'];

$colftext[1]="&nbsp;";
$colftext[2]=$_lang['stTOTAL'];
$colftext[3]="total_column_byte";
$colftext[4]="total_column_byte";
$colftext[5]="&nbsp;";

//если есть модуль категорий то добавим столбец
if($category==", category")
$colh[0]=5;
else
$colh[0]=4;




$colh[1]="<th class=unsortable>".$colhtext[1]."</th>";
$colh[2]="<th>".$colhtext[2]."</th>";
$colh[3]="<th>".$colhtext[3]."</th>";
$colh[4]="<th>".$colhtext[4]."</th>";
$colh[5]="<th>".$colhtext[5]."</th>";


$colr[1]="<td>numrow</td>";
$colr[2]="<td>line0</td>";
$colr[3]="<td>line1</td>";
$colr[4]="<td>line2</td>";
$colr[5]="<td>getcategory</td>";



$colf[1]="<td>".$colftext[1]."</td>";
$colf[2]="<td><b>".$colftext[2]."</b></td>";
$colf[3]="<td><b>".$colftext[3]."</b></td>";
$colf[4]="<td><b>".$colftext[4]."</b></td>";
$colf[5]="<td><b>".$colftext[5]."</b></td>";


?>