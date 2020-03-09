<?php

$tdata=array();
	 $tdata[".NumberOfChars"]=80; 
	$tdata[".ShortName"]="top";
	$tdata[".OwnerID"]="";
	$tdata[".OriginalTable"]="top";

	$keys=array();
	$keys[]="t_id";
	$tdata[".Keys"]=$keys;

	
//	t_id
	$fdata = array();
	 $fdata["Label"]="ID"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "t_id";
		$fdata["FullName"]= "t_id";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 1;
	
			$fdata["EditParams"]="";
						$fdata["FieldPermissions"]=true;
		$tdata["t_id"]=$fdata;
	
//	t_user
	$fdata = array();
	 $fdata["Label"]="User"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "t_user";
		$fdata["FullName"]= "t_user";
	
	
	
	
	$fdata["Index"]= 2;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=255";
					$fdata["FieldPermissions"]=true;
		$tdata["t_user"]=$fdata;
	
//	t_score
	$fdata = array();
	 $fdata["Label"]="Score"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "t_score";
		$fdata["FullName"]= "t_score";
	
	
	
	
	$fdata["Index"]= 3;
	
			$fdata["EditParams"]="";
						$fdata["FieldPermissions"]=true;
		$tdata["t_score"]=$fdata;
	
//	t_datetime
	$fdata = array();
	 $fdata["Label"]="Datetime"; 
	
	
	$fdata["FieldType"]= 135;
	$fdata["EditFormat"]= "Date";
	$fdata["ViewFormat"]= "Short Date";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "t_datetime";
		$fdata["FullName"]= "t_datetime";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 4;
	 $fdata["DateEditType"]=13; 
						$fdata["FieldPermissions"]=true;
		$tdata["t_datetime"]=$fdata;
	
//	t_ip
	$fdata = array();
	 $fdata["Label"]="IP"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "t_ip";
		$fdata["FullName"]= "t_ip";
	
	
	
	
	$fdata["Index"]= 5;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=15";
					$fdata["FieldPermissions"]=true;
		$tdata["t_ip"]=$fdata;
	
//	t_p_id
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
	
	$fdata["GoodName"]= "t_p_id";
		$fdata["FullName"]= "t_p_id";
	
	
	
	
	$fdata["Index"]= 6;
	
						$fdata["FieldPermissions"]=true;
		$tdata["t_p_id"]=$fdata;
	
//	t_ts_id
	$fdata = array();
	 $fdata["Label"]="Team"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "t_ts_id";
		$fdata["FullName"]= "t_ts_id";
	
	
	
	
	$fdata["Index"]= 7;
	
			$fdata["EditParams"]="";
						$fdata["FieldPermissions"]=true;
		$tdata["t_ts_id"]=$fdata;
	
//	t_kills
	$fdata = array();
	 $fdata["Label"]="Kills"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "t_kills";
		$fdata["FullName"]= "t_kills";
	
	
	
	
	$fdata["Index"]= 8;
	
			$fdata["EditParams"]="";
						$fdata["FieldPermissions"]=true;
		$tdata["t_kills"]=$fdata;
$tables_data["top"]=$tdata;
?>