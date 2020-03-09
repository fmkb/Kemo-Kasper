<?php

$tdata=array();
	 $tdata[".NumberOfChars"]=80; 
	$tdata[".ShortName"]="raffle";
	$tdata[".OwnerID"]="";
	$tdata[".OriginalTable"]="raffle";

	$keys=array();
	$keys[]="r_id";
	$tdata[".Keys"]=$keys;

	
//	r_id
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "r_id";
		$fdata["FullName"]= "r_id";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 1;
	
			$fdata["EditParams"]="";
						$fdata["FieldPermissions"]=true;
		$tdata["r_id"]=$fdata;
	
//	r_name
	$fdata = array();
	 $fdata["Label"]="Name"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "r_name";
		$fdata["FullName"]= "r_name";
	
	
	
	
	$fdata["Index"]= 2;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=255";
					$fdata["FieldPermissions"]=true;
		$tdata["r_name"]=$fdata;
	
//	r_img
	$fdata = array();
	 $fdata["Label"]="Image"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "r_img";
		$fdata["FullName"]= "r_img";
	
	
	
	
	$fdata["Index"]= 3;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=255";
					$fdata["FieldPermissions"]=true;
		$tdata["r_img"]=$fdata;
	
//	r_text
	$fdata = array();
	 $fdata["Label"]="Description"; 
	
	
	$fdata["FieldType"]= 201;
	$fdata["EditFormat"]= "Text area";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "r_text";
		$fdata["FullName"]= "r_text";
	
	
	
	
	$fdata["Index"]= 4;
	
		$fdata["EditParams"]="";
			$fdata["EditParams"].= " rows=250";
		$fdata["nRows"] = 250;
			$fdata["EditParams"].= " cols=500";
		$fdata["nCols"] = 500;
					$fdata["FieldPermissions"]=true;
		$tdata["r_text"]=$fdata;
	
//	r_date
	$fdata = array();
	 $fdata["Label"]="Date"; 
	
	
	$fdata["FieldType"]= 7;
	$fdata["EditFormat"]= "Date";
	$fdata["ViewFormat"]= "Short Date";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "r_date";
		$fdata["FullName"]= "r_date";
	
	
	
	
	$fdata["Index"]= 5;
	 $fdata["DateEditType"]=13; 
						$fdata["FieldPermissions"]=true;
		$tdata["r_date"]=$fdata;
	
//	r_p_id
	$fdata = array();
	 $fdata["Label"]="Player"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Lookup wizard";
	$fdata["ViewFormat"]= "";
	
	
	
		$fdata["LookupType"]=1;
		$fdata["LookupWhere"]="";
	$fdata["LinkField"]="`p_id`";
	$fdata["LinkFieldType"]=3;
		$fdata["DisplayField"]="`p_mail`";
	$fdata["LookupTable"]="player";
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "r_p_id";
		$fdata["FullName"]= "r_p_id";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 6;
	
						$fdata["FieldPermissions"]=true;
		$tdata["r_p_id"]=$fdata;
$tables_data["raffle"]=$tdata;
?>