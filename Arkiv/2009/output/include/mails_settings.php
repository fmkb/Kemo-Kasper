<?php

$tdata=array();
	 $tdata[".NumberOfChars"]=80; 
	$tdata[".ShortName"]="mails";
	$tdata[".OwnerID"]="";
	$tdata[".OriginalTable"]="mails";

	$keys=array();
	$keys[]="m_id";
	$tdata[".Keys"]=$keys;

	
//	m_id
	$fdata = array();
	 $fdata["Label"]="ID"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "m_id";
		$fdata["FullName"]= "m_id";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 1;
	
			$fdata["EditParams"]="";
						$fdata["FieldPermissions"]=true;
		$tdata["m_id"]=$fdata;
	
//	m_name
	$fdata = array();
	 $fdata["Label"]="Name"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "m_name";
		$fdata["FullName"]= "m_name";
	
	
	
	
	$fdata["Index"]= 2;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=30";
					$fdata["FieldPermissions"]=true;
		$tdata["m_name"]=$fdata;
	
//	m_body
	$fdata = array();
	 $fdata["Label"]="Text"; 
	
	
	$fdata["FieldType"]= 201;
	$fdata["EditFormat"]= "Text area";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "m_body";
		$fdata["FullName"]= "m_body";
	
	
	
	
	$fdata["Index"]= 3;
	
		$fdata["EditParams"]="";
			$fdata["EditParams"].= " rows=250";
		$fdata["nRows"] = 250;
			$fdata["EditParams"].= " cols=500";
		$fdata["nCols"] = 500;
					$fdata["FieldPermissions"]=true;
		$tdata["m_body"]=$fdata;
$tables_data["mails"]=$tdata;
?>