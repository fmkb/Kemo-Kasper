<?php

$tdata=array();
	
	$tdata[".ShortName"]="MQ_Chart";
	$tdata[".OwnerID"]="";
	$tdata[".OriginalTable"]="player";

	$keys=array();
	$tdata[".Keys"]=$keys;

	
//	p_mk
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "p_mk";
		$fdata["FullName"]= "p_mk";
	
	
	
	
	$fdata["Index"]= 1;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=1";
				$tdata["p_mk"]=$fdata;
	
//	count(p_mk)
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "count_p_mk_";
		$fdata["FullName"]= "count(p_mk)";
	
	
	
	
	$fdata["Index"]= 2;
	
			$fdata["EditParams"]="";
					$tdata["count(p_mk)"]=$fdata;
$tables_data["MQ Chart"]=$tdata;
?>