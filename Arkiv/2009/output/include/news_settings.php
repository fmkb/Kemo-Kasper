<?php

$tdata=array();
	 $tdata[".NumberOfChars"]=80; 
	$tdata[".ShortName"]="news";
	$tdata[".OwnerID"]="";
	$tdata[".OriginalTable"]="news";

	$keys=array();
	$keys[]="n_id";
	$tdata[".Keys"]=$keys;

	
//	n_id
	$fdata = array();
	 $fdata["Label"]="ID"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "n_id";
		$fdata["FullName"]= "n_id";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 1;
	
			$fdata["EditParams"]="";
						$fdata["FieldPermissions"]=true;
		$tdata["n_id"]=$fdata;
	
//	n_active
	$fdata = array();
	 $fdata["Label"]="Active"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Lookup wizard";
	$fdata["ViewFormat"]= "";
	
	
	
		$fdata["LookupType"]=1;
		$fdata["LookupWhere"]="";
	$fdata["LinkField"]="`xs_id`";
	$fdata["LinkFieldType"]=16;
		$fdata["DisplayField"]="`xs_text`";
	$fdata["LookupTable"]="xstatus";
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "n_active";
		$fdata["FullName"]= "n_active";
	
	
	
	
	$fdata["Index"]= 2;
	
						$fdata["FieldPermissions"]=true;
		$tdata["n_active"]=$fdata;
	
//	n_start
	$fdata = array();
	 $fdata["Label"]="Start"; 
	
	
	$fdata["FieldType"]= 7;
	$fdata["EditFormat"]= "Date";
	$fdata["ViewFormat"]= "Short Date";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "n_start";
		$fdata["FullName"]= "n_start";
	
	
	
	
	$fdata["Index"]= 3;
	 $fdata["DateEditType"]=13; 
						$fdata["FieldPermissions"]=true;
		$tdata["n_start"]=$fdata;
	
//	n_end
	$fdata = array();
	 $fdata["Label"]="End"; 
	
	
	$fdata["FieldType"]= 7;
	$fdata["EditFormat"]= "Date";
	$fdata["ViewFormat"]= "Short Date";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "n_end";
		$fdata["FullName"]= "n_end";
	
	
	
	
	$fdata["Index"]= 4;
	 $fdata["DateEditType"]=13; 
						$fdata["FieldPermissions"]=true;
		$tdata["n_end"]=$fdata;
	
//	n_date
	$fdata = array();
	 $fdata["Label"]="Date"; 
	
	
	$fdata["FieldType"]= 7;
	$fdata["EditFormat"]= "Date";
	$fdata["ViewFormat"]= "Short Date";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "n_date";
		$fdata["FullName"]= "n_date";
	
	
	
	
	$fdata["Index"]= 5;
	 $fdata["DateEditType"]=13; 
						$fdata["FieldPermissions"]=true;
		$tdata["n_date"]=$fdata;
	
//	n_head
	$fdata = array();
	 $fdata["Label"]="Headline"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "n_head";
		$fdata["FullName"]= "n_head";
	
	
	
	
	$fdata["Index"]= 6;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=50";
					$fdata["FieldPermissions"]=true;
		$tdata["n_head"]=$fdata;
	
//	n_text
	$fdata = array();
	 $fdata["Label"]="Text"; 
	
	
	$fdata["FieldType"]= 201;
	$fdata["EditFormat"]= "Text area";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "n_text";
		$fdata["FullName"]= "n_text";
	
	
	
	
	$fdata["Index"]= 7;
	
		$fdata["EditParams"]="";
			$fdata["EditParams"].= " rows=250";
		$fdata["nRows"] = 250;
			$fdata["EditParams"].= " cols=500";
		$fdata["nCols"] = 500;
					$fdata["FieldPermissions"]=true;
		$tdata["n_text"]=$fdata;
	
//	n_link
	$fdata = array();
	 $fdata["Label"]="HTTP Link"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "n_link";
		$fdata["FullName"]= "n_link";
	
	
	
	
	$fdata["Index"]= 8;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=255";
						$tdata["n_link"]=$fdata;
	
//	n_file
	$fdata = array();
	 $fdata["Label"]="File upload"; 
	
	 $fdata["LinkPrefix"]="/admin/pictures/news/"; 
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Document upload";
	$fdata["ViewFormat"]= "File-based Image";
	
	
	
				
	$fdata["GoodName"]= "n_file";
		$fdata["FullName"]= "n_file";
	
	
	
	 $fdata["UploadFolder"]="/admin/pictures/news/"; 
	$fdata["Index"]= 9;
	
						$fdata["FieldPermissions"]=true;
		$fdata["CreateThumbnail"]=true;
	$fdata["ThumbnailPrefix"]="th_";
	$tdata["n_file"]=$fdata;
	
//	n_type
	$fdata = array();
	 $fdata["Label"]="News Type"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "n_type";
		$fdata["FullName"]= "n_type";
	
	
	
	
	$fdata["Index"]= 10;
	
			$fdata["EditParams"]="";
						$fdata["FieldPermissions"]=true;
		$tdata["n_type"]=$fdata;
	
//	n_teaser
	$fdata = array();
	 $fdata["Label"]="Teaser"; 
	
	
	$fdata["FieldType"]= 201;
	$fdata["EditFormat"]= "Text area";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "n_teaser";
		$fdata["FullName"]= "n_teaser";
	
	
	
	
	$fdata["Index"]= 11;
	
		$fdata["EditParams"]="";
			$fdata["EditParams"].= " rows=250";
		$fdata["nRows"] = 250;
			$fdata["EditParams"].= " cols=500";
		$fdata["nCols"] = 500;
					$fdata["FieldPermissions"]=true;
		$tdata["n_teaser"]=$fdata;
	
//	n_country
	$fdata = array();
	 $fdata["Label"]="Country"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Lookup wizard";
	$fdata["ViewFormat"]= "";
	
	
	
		$fdata["LookupType"]=1;
		$fdata["LookupWhere"]="";
	$fdata["LinkField"]="`xc_id`";
	$fdata["LinkFieldType"]=129;
		$fdata["DisplayField"]="`xc_name`";
	$fdata["LookupTable"]="xcountries";
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "n_country";
		$fdata["FullName"]= "n_country";
	
	
	
	
	$fdata["Index"]= 12;
	
						$fdata["FieldPermissions"]=true;
		$tdata["n_country"]=$fdata;
$tables_data["news"]=$tdata;
?>