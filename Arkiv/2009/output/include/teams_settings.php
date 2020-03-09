<?php

$tdata=array();
	 $tdata[".NumberOfChars"]=80; 
	$tdata[".ShortName"]="teams";
	$tdata[".OwnerID"]="";
	$tdata[".OriginalTable"]="teams";

	$keys=array();
	$keys[]="ts_id";
	$tdata[".Keys"]=$keys;

	
//	ts_id
	$fdata = array();
	 $fdata["Label"]="Team ID"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "ts_id";
		$fdata["FullName"]= "ts_id";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 1;
	
			$fdata["EditParams"]="";
						$fdata["FieldPermissions"]=true;
		$tdata["ts_id"]=$fdata;
	
//	ts_name
	$fdata = array();
	 $fdata["Label"]="Name"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "ts_name";
		$fdata["FullName"]= "ts_name";
	
	
	
	
	$fdata["Index"]= 2;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=30";
					$fdata["FieldPermissions"]=true;
		$tdata["ts_name"]=$fdata;
	
//	ts_p0
	$fdata = array();
	 $fdata["Label"]="Status"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "ts_p0";
		$fdata["FullName"]= "ts_p0";
	
	
	
	
	$fdata["Index"]= 3;
	
			$fdata["EditParams"]="";
						$fdata["FieldPermissions"]=true;
		$tdata["ts_p0"]=$fdata;
	
//	ts_p1
	$fdata = array();
	 $fdata["Label"]="N/A"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "ts_p1";
		$fdata["FullName"]= "ts_p1";
	
	
	
	
	$fdata["Index"]= 4;
	
			$fdata["EditParams"]="";
							$tdata["ts_p1"]=$fdata;
	
//	ts_p2
	$fdata = array();
	 $fdata["Label"]="N/A"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "ts_p2";
		$fdata["FullName"]= "ts_p2";
	
	
	
	
	$fdata["Index"]= 5;
	
			$fdata["EditParams"]="";
							$tdata["ts_p2"]=$fdata;
	
//	ts_p9
	$fdata = array();
	 $fdata["Label"]="N/A"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "ts_p9";
		$fdata["FullName"]= "ts_p9";
	
	
	
	
	$fdata["Index"]= 6;
	
			$fdata["EditParams"]="";
							$tdata["ts_p9"]=$fdata;
	
//	ts_s_id
	$fdata = array();
	 $fdata["Label"]="Sponsor"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Lookup wizard";
	$fdata["ViewFormat"]= "";
	
	
	
		$fdata["LookupType"]=1;
		$fdata["LookupWhere"]="";
	$fdata["LinkField"]="`s_id`";
	$fdata["LinkFieldType"]=3;
		$fdata["DisplayField"]="`s_name`";
	$fdata["LookupTable"]="sponsor";
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "ts_s_id";
		$fdata["FullName"]= "ts_s_id";
	
	
	
	
	$fdata["Index"]= 7;
	
						$fdata["FieldPermissions"]=true;
		$tdata["ts_s_id"]=$fdata;
	
//	ts_Score
	$fdata = array();
	 $fdata["Label"]="Score"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "ts_Score";
		$fdata["FullName"]= "ts_Score";
	
	
	
	
	$fdata["Index"]= 8;
	
			$fdata["EditParams"]="";
							$tdata["ts_Score"]=$fdata;
$tables_data["teams"]=$tdata;
?>