<?php

$tdata=array();
	 $tdata[".NumberOfChars"]=80; 
	$tdata[".ShortName"]="Dialog";
	$tdata[".OwnerID"]="";
	$tdata[".OriginalTable"]="Dialog";

	$keys=array();
	$keys[]="d_id";
	$tdata[".Keys"]=$keys;

	
//	d_id
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "d_id";
		$fdata["FullName"]= "d_id";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 1;
	
			$fdata["EditParams"]="";
							$tdata["d_id"]=$fdata;
	
//	d_from_p_id
	$fdata = array();
	 $fdata["Label"]="From"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Lookup wizard";
	$fdata["ViewFormat"]= "";
	
	
	
		$fdata["LookupType"]=1;
		$fdata["LookupWhere"]="";
	$fdata["LinkField"]="`p_id`";
	$fdata["LinkFieldType"]=3;
		$fdata["DisplayField"]="concat(P_First,' ',P_Name)";
	$fdata["LookupTable"]="player";
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "d_from_p_id";
		$fdata["FullName"]= "d_from_p_id";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 2;
	
						$fdata["FieldPermissions"]=true;
		$tdata["d_from_p_id"]=$fdata;
	
//	d_to_p_id
	$fdata = array();
	 $fdata["Label"]="To"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Lookup wizard";
	$fdata["ViewFormat"]= "";
	
	
	
		$fdata["LookupType"]=1;
		$fdata["LookupWhere"]="";
	$fdata["LinkField"]="`p_id`";
	$fdata["LinkFieldType"]=3;
		$fdata["DisplayField"]="concat(P_First,' ',P_Name)";
	$fdata["LookupTable"]="player";
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "d_to_p_id";
		$fdata["FullName"]= "d_to_p_id";
	
	
	
	
	$fdata["Index"]= 3;
	
						$fdata["FieldPermissions"]=true;
		$tdata["d_to_p_id"]=$fdata;
	
//	d_message
	$fdata = array();
	 $fdata["Label"]="Message"; 
	
	
	$fdata["FieldType"]= 201;
	$fdata["EditFormat"]= "Text area";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "d_message";
		$fdata["FullName"]= "d_message";
	
	
	
	
	$fdata["Index"]= 4;
	
		$fdata["EditParams"]="";
			$fdata["EditParams"].= " rows=250";
		$fdata["nRows"] = 250;
			$fdata["EditParams"].= " cols=500";
		$fdata["nCols"] = 500;
					$fdata["FieldPermissions"]=true;
		$tdata["d_message"]=$fdata;
	
//	d_datetime
	$fdata = array();
	 $fdata["Label"]="Date and Time"; 
	
	
	$fdata["FieldType"]= 135;
	$fdata["EditFormat"]= "Date";
	$fdata["ViewFormat"]= "Datetime";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "d_datetime";
		$fdata["FullName"]= "d_datetime";
	
	
	
	
	$fdata["Index"]= 5;
	 $fdata["DateEditType"]=13; 
						$fdata["FieldPermissions"]=true;
		$tdata["d_datetime"]=$fdata;
	
//	d_msg_type
	$fdata = array();
	 $fdata["Label"]="Type"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "d_msg_type";
		$fdata["FullName"]= "d_msg_type";
	
	
	
	
	$fdata["Index"]= 6;
	
			$fdata["EditParams"]="";
						$fdata["FieldPermissions"]=true;
		$tdata["d_msg_type"]=$fdata;
$tables_data["Dialog"]=$tdata;
?>