<?php

	//EXCEL DOWNLOAD FILE
	
	include("./mysql_excel/mysql_excel.inc.php");
	
	$month = date('n');
	$year = date('Y');
	$month_full = str_replace('','_', date('F Y')); 
		
	$sql = 
	
	$date = date("d_M_Y");
	
	$import=new HarImport();
	$import->openDatabase("localhost","root","horizon","hhcms");
	
	$import->ImportData($sql,"HH_".$filename."_".$date.".xls",true); //To force to download
	
?>
