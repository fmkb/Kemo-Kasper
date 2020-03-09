<?php

$tdata=array();
	 $tdata[".NumberOfChars"]=80; 
	$tdata[".ShortName"]="sponsor";
	$tdata[".OwnerID"]="";
	$tdata[".OriginalTable"]="sponsor";

	$keys=array();
	$keys[]="s_id";
	$tdata[".Keys"]=$keys;

	
//	s_id
	$fdata = array();
	 $fdata["Label"]="ID"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "s_id";
		$fdata["FullName"]= "s_id";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 1;
	
			$fdata["EditParams"]="";
							$tdata["s_id"]=$fdata;
	
//	s_active
	$fdata = array();
	 $fdata["Label"]="Status"; 
	
	
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
	
	$fdata["GoodName"]= "s_active";
		$fdata["FullName"]= "s_active";
	
	
	
	
	$fdata["Index"]= 2;
	
						$fdata["FieldPermissions"]=true;
		$tdata["s_active"]=$fdata;
	
//	s_name
	$fdata = array();
	 $fdata["Label"]="Name"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "s_name";
		$fdata["FullName"]= "s_name";
	
	
	
	
	$fdata["Index"]= 3;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=30";
					$fdata["FieldPermissions"]=true;
		$tdata["s_name"]=$fdata;
	
//	s_contact
	$fdata = array();
	 $fdata["Label"]="Contact"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "s_contact";
		$fdata["FullName"]= "s_contact";
	
	
	
	
	$fdata["Index"]= 4;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=30";
					$fdata["FieldPermissions"]=true;
		$tdata["s_contact"]=$fdata;
	
//	s_adr
	$fdata = array();
	 $fdata["Label"]="Address"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "s_adr";
		$fdata["FullName"]= "s_adr";
	
	
	
	
	$fdata["Index"]= 5;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=30";
					$fdata["FieldPermissions"]=true;
		$tdata["s_adr"]=$fdata;
	
//	s_zip
	$fdata = array();
	 $fdata["Label"]="Zip"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "s_zip";
		$fdata["FullName"]= "s_zip";
	
	
	
	
	$fdata["Index"]= 6;
	
			$fdata["EditParams"]="";
						$fdata["FieldPermissions"]=true;
		$tdata["s_zip"]=$fdata;
	
//	s_phone1
	$fdata = array();
	 $fdata["Label"]="Phone 1"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "s_phone1";
		$fdata["FullName"]= "s_phone1";
	
	
	
	
	$fdata["Index"]= 7;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=30";
					$fdata["FieldPermissions"]=true;
		$tdata["s_phone1"]=$fdata;
	
//	s_phone2
	$fdata = array();
	 $fdata["Label"]="Phone 2"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "s_phone2";
		$fdata["FullName"]= "s_phone2";
	
	
	
	
	$fdata["Index"]= 8;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=30";
					$fdata["FieldPermissions"]=true;
		$tdata["s_phone2"]=$fdata;
	
//	s_total
	$fdata = array();
	 $fdata["Label"]="Amount Sponsored"; 
	
	
	$fdata["FieldType"]= 5;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "Number";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "s_total";
		$fdata["FullName"]= "s_total";
	
	
	
	
	$fdata["Index"]= 9;
	
			$fdata["EditParams"]="";
						$fdata["FieldPermissions"]=true;
		$tdata["s_total"]=$fdata;
	
//	s_paid
	$fdata = array();
	 $fdata["Label"]="Amount Paid"; 
	
	
	$fdata["FieldType"]= 5;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "Number";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "s_paid";
		$fdata["FullName"]= "s_paid";
	
	
	
	
	$fdata["Index"]= 10;
	
			$fdata["EditParams"]="";
						$fdata["FieldPermissions"]=true;
		$tdata["s_paid"]=$fdata;
	
//	s_logo
	$fdata = array();
	 $fdata["Label"]="Banner Ad 468x60"; 
	
	 $fdata["LinkPrefix"]="/admin/images/"; 
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Document upload";
	$fdata["ViewFormat"]= "File-based Image";
	
	
	
				
	$fdata["GoodName"]= "s_logo";
		$fdata["FullName"]= "s_logo";
	
	
	
	 $fdata["UploadFolder"]="/admin/images/"; 
	$fdata["Index"]= 11;
	
						$fdata["FieldPermissions"]=true;
		$tdata["s_logo"]=$fdata;
	
//	s_banner
	$fdata = array();
	 $fdata["Label"]="Logo"; 
	
	 $fdata["LinkPrefix"]="/admin/images/"; 
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Document upload";
	$fdata["ViewFormat"]= "File-based Image";
	
	
	
				
	$fdata["GoodName"]= "s_banner";
		$fdata["FullName"]= "s_banner";
	
	
	
	 $fdata["UploadFolder"]="/admin/images/"; 
	$fdata["Index"]= 12;
	
						$fdata["FieldPermissions"]=true;
		$tdata["s_banner"]=$fdata;
	
//	s_www
	$fdata = array();
	 $fdata["Label"]="WWW"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "Hyperlink";
	
	
	
				
	$fdata["GoodName"]= "s_www";
		$fdata["FullName"]= "s_www";
	
	
	
	
	$fdata["Index"]= 13;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=255";
					$fdata["FieldPermissions"]=true;
		$tdata["s_www"]=$fdata;
	
//	s_mail
	$fdata = array();
	 $fdata["Label"]="Email"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "Email Hyperlink";
	
	
	
				
	$fdata["GoodName"]= "s_mail";
		$fdata["FullName"]= "s_mail";
	
	
	
	
	$fdata["Index"]= 14;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=30";
					$fdata["FieldPermissions"]=true;
		$tdata["s_mail"]=$fdata;
	
//	s_cmt
	$fdata = array();
	 $fdata["Label"]="Comment"; 
	
	
	$fdata["FieldType"]= 201;
	$fdata["EditFormat"]= "Text area";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "s_cmt";
		$fdata["FullName"]= "s_cmt";
	
	
	
	
	$fdata["Index"]= 15;
	
		$fdata["EditParams"]="";
			$fdata["EditParams"].= " rows=250";
		$fdata["nRows"] = 250;
			$fdata["EditParams"].= " cols=500";
		$fdata["nCols"] = 500;
					$fdata["FieldPermissions"]=true;
		$tdata["s_cmt"]=$fdata;
	
//	s_country
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
	
	$fdata["GoodName"]= "s_country";
		$fdata["FullName"]= "s_country";
	
	
	
	
	$fdata["Index"]= 16;
	
						$fdata["FieldPermissions"]=true;
		$tdata["s_country"]=$fdata;
$tables_data["sponsor"]=$tdata;
?>