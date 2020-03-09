<?php

$tdata=array();
	 $tdata[".NumberOfChars"]=80; 
	$tdata[".ShortName"]="ZZIP";
	$tdata[".OwnerID"]="";
	$tdata[".OriginalTable"]="ZZIP";

	$keys=array();
	$keys[]="ZZIP";
	$tdata[".Keys"]=$keys;

	
//	ZZIP
	$fdata = array();
	 $fdata["Label"]="ZIP"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "ZZIP";
		$fdata["FullName"]= "ZZIP";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 1;
	
			$fdata["EditParams"]="";
						$fdata["FieldPermissions"]=true;
		$tdata["ZZIP"]=$fdata;
	
//	ZZCity
	$fdata = array();
	 $fdata["Label"]="City"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "ZZCity";
		$fdata["FullName"]= "ZZCity";
	
	
	
	
	$fdata["Index"]= 2;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=30";
					$fdata["FieldPermissions"]=true;
		$tdata["ZZCity"]=$fdata;
$tables_data["ZZIP"]=$tdata;
?>