<?php

$tdata=array();
	 $tdata[".NumberOfChars"]=80; 
	$tdata[".ShortName"]="xstatus";
	$tdata[".OwnerID"]="";
	$tdata[".OriginalTable"]="xstatus";

	$keys=array();
	$keys[]="xs_id";
	$tdata[".Keys"]=$keys;

	
//	xs_id
	$fdata = array();
	 $fdata["Label"]="ID"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "xs_id";
		$fdata["FullName"]= "xs_id";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 1;
	
			$fdata["EditParams"]="";
						$fdata["FieldPermissions"]=true;
		$tdata["xs_id"]=$fdata;
	
//	xs_text
	$fdata = array();
	 $fdata["Label"]="Description"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "xs_text";
		$fdata["FullName"]= "xs_text";
	
	
	
	
	$fdata["Index"]= 2;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=30";
					$fdata["FieldPermissions"]=true;
		$tdata["xs_text"]=$fdata;
$tables_data["xstatus"]=$tdata;
?>