<?php

$tdata=array();
	
	$tdata[".ShortName"]="Accept_news_Chart";
	$tdata[".OwnerID"]="";
	$tdata[".OriginalTable"]="player";

	$keys=array();
	$tdata[".Keys"]=$keys;

	
//	p_newsaccept
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "p_newsaccept";
		$fdata["FullName"]= "p_newsaccept";
	
	
	
	
	$fdata["Index"]= 1;
	
			$fdata["EditParams"]="";
					$tdata["p_newsaccept"]=$fdata;
	
//	COUNT(p_newsaccept)
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "COUNT_p_newsaccept_";
		$fdata["FullName"]= "COUNT(p_newsaccept)";
	
	
	
	
	$fdata["Index"]= 2;
	
			$fdata["EditParams"]="";
					$tdata["COUNT(p_newsaccept)"]=$fdata;
$tables_data["Accept news Chart"]=$tdata;
?>