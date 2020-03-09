<?php

$tdata=array();
	 $tdata[".NumberOfChars"]=80; 
	$tdata[".ShortName"]="xcountries";
	$tdata[".OwnerID"]="";
	$tdata[".OriginalTable"]="xcountries";

	$keys=array();
	$keys[]="xc_id";
	$tdata[".Keys"]=$keys;

	
//	xc_id
	$fdata = array();
	 $fdata["Label"]="Country"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "xc_id";
		$fdata["FullName"]= "xc_id";
	
	
	
	
	$fdata["Index"]= 1;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=2";
					$fdata["FieldPermissions"]=true;
		$tdata["xc_id"]=$fdata;
	
//	xc_name
	$fdata = array();
	 $fdata["Label"]="Name"; 
	
	
	$fdata["FieldType"]= 201;
	$fdata["EditFormat"]= "Text area";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "xc_name";
		$fdata["FullName"]= "xc_name";
	
	
	
	
	$fdata["Index"]= 2;
	
		$fdata["EditParams"]="";
			$fdata["EditParams"].= " rows=250";
		$fdata["nRows"] = 250;
			$fdata["EditParams"].= " cols=500";
		$fdata["nCols"] = 500;
					$fdata["FieldPermissions"]=true;
		$tdata["xc_name"]=$fdata;
	
//	xc_currency
	$fdata = array();
	 $fdata["Label"]="Currency"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "xc_currency";
		$fdata["FullName"]= "xc_currency";
	
	
	
	
	$fdata["Index"]= 3;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=3";
					$fdata["FieldPermissions"]=true;
		$tdata["xc_currency"]=$fdata;
	
//	xc_code
	$fdata = array();
	 $fdata["Label"]="Country Code"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "xc_code";
		$fdata["FullName"]= "xc_code";
	
	
	
	
	$fdata["Index"]= 4;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=3";
					$fdata["FieldPermissions"]=true;
		$tdata["xc_code"]=$fdata;
	
//	xc_desc
	$fdata = array();
	 $fdata["Label"]="Description"; 
	
	
	$fdata["FieldType"]= 201;
	$fdata["EditFormat"]= "Text area";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "xc_desc";
		$fdata["FullName"]= "xc_desc";
	
	
	
	
	$fdata["Index"]= 5;
	
		$fdata["EditParams"]="";
			$fdata["EditParams"].= " rows=250";
		$fdata["nRows"] = 250;
			$fdata["EditParams"].= " cols=500";
		$fdata["nCols"] = 500;
					$fdata["FieldPermissions"]=true;
		$tdata["xc_desc"]=$fdata;
$tables_data["xcountries"]=$tdata;
?>