<?php
//----------------------------------------------------------------------------------------
//string functions

function generatePwd($length)
{	$vowel=array('a','e','i','o','u');
	$other=array_diff(range('a','z'),$vowel);
	$letters=array($vowel,$other);
	$ret='';
    for($i=0;$i<$length;$i++)
    	$ret.=$letters[$i%2][array_rand($letters[$i%2])];
	return $ret;}

function arrayToCSV($ar)
{

$r='';
foreach($ar as $k=>$item)
{
	$str=str_replace("\n"," ",$ar[$k]);
	$str=str_replace("\r","",$str);
	$ar[$k]='"'.str_replace('"','""',$str).'"';
}
return implode(';',$ar)."\n";
}

function cp1251toUTF8($str)
{
	return iconv('cp1251','UTF-8',$str);
}

function UTF8tocp1251($str)
{
	return mb_convert_encoding($str,'cp1251','UTF-8');//iconv('cp1251','cp1251',$str);
}


function xlsBOF() {
echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
return;
}

function xlsEOF() {
echo pack("ss", 0x0A, 0x00);
return;
}

function xlsWriteNumber($Row, $Col, $Value) {
echo pack("sssss", 0x203, 14, $Row, $Col, 0x0);
echo pack("d", $Value);
return;
}

function xlsWriteLabel($Row, $Col, $Value ) {
$L = strlen($Value);
echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
echo $Value;
return;
}

/*
C помощью этих функций можно легко и просто создавать xls-файлы, например так:


    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");;
    header("Content-Disposition: attachment;filename="list.xls");
    header("Content-Transfer-Encoding: binary ");

    xlsBOF(); //начинаем собирать файл
    //первая строка
    xlsWriteLabel(1,0,"Название");
    //вторая строка
    xlsWriteLabel(2,0,"№п/п");
    xlsWriteLabel(2,1,"Имя");
    xlsWriteLabel(2,2,"Фамилия");
    //третья строка
    xlsWriteNumber(3,0,"1");
    xlsWriteLabel(3,1,"Петр");
    xlsWriteLabel(3,2,"Иванов");


    xlsEOF(); //заканчиваем собирать
*/
?>
