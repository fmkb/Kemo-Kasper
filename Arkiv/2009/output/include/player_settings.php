<?php

$tdata=array();
	 $tdata[".NumberOfChars"]=80; 
	$tdata[".ShortName"]="player";
	$tdata[".OwnerID"]="";
	$tdata[".OriginalTable"]="player";

	$keys=array();
	$keys[]="p_id";
	$tdata[".Keys"]=$keys;

	
//	p_id
	$fdata = array();
	 $fdata["Label"]="ID"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "p_id";
		$fdata["FullName"]= "p_id";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 1;
	
			$fdata["EditParams"]="";
							$tdata["p_id"]=$fdata;
	
//	p_active
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
	
	$fdata["GoodName"]= "p_active";
		$fdata["FullName"]= "p_active";
	
	
	
	
	$fdata["Index"]= 2;
	
						$fdata["FieldPermissions"]=true;
		$tdata["p_active"]=$fdata;
	
//	p_location
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "p_location";
		$fdata["FullName"]= "p_location";
	
	
	
	
	$fdata["Index"]= 3;
	
			$fdata["EditParams"]="";
							$tdata["p_location"]=$fdata;
	
//	p_s_id
	$fdata = array();
	 $fdata["Label"]="Team"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Lookup wizard";
	$fdata["ViewFormat"]= "";
	
	
	
		$fdata["LookupType"]=1;
		$fdata["LookupWhere"]="";
	$fdata["LinkField"]="`ts_id`";
	$fdata["LinkFieldType"]=3;
		$fdata["DisplayField"]="concat('(',ts_id,') ',ts_name)";
	$fdata["LookupTable"]="teams";
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "p_s_id";
		$fdata["FullName"]= "p_s_id";
	
	
	
	
	$fdata["Index"]= 4;
	
						$fdata["FieldPermissions"]=true;
		$tdata["p_s_id"]=$fdata;
	
//	p_first
	$fdata = array();
	 $fdata["Label"]="First name"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "p_first";
		$fdata["FullName"]= "p_first";
	
	
	
	
	$fdata["Index"]= 5;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=30";
					$fdata["FieldPermissions"]=true;
		$tdata["p_first"]=$fdata;
	
//	p_name
	$fdata = array();
	 $fdata["Label"]="Last Name"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "p_name";
		$fdata["FullName"]= "p_name";
	
	
	
	
	$fdata["Index"]= 6;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=30";
					$fdata["FieldPermissions"]=true;
		$tdata["p_name"]=$fdata;
	
//	p_adr
	$fdata = array();
	 $fdata["Label"]="Adress"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "p_adr";
		$fdata["FullName"]= "p_adr";
	
	
	
	
	$fdata["Index"]= 7;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=30";
					$fdata["FieldPermissions"]=true;
		$tdata["p_adr"]=$fdata;
	
//	p_zip
	$fdata = array();
	 $fdata["Label"]="Zip"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "p_zip";
		$fdata["FullName"]= "p_zip";
	
	
	
	
	$fdata["Index"]= 8;
	
			$fdata["EditParams"]="";
						$fdata["FieldPermissions"]=true;
		$tdata["p_zip"]=$fdata;
	
//	p_mail
	$fdata = array();
	 $fdata["Label"]="Email"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "Email Hyperlink";
	
	
	
				
	$fdata["GoodName"]= "p_mail";
		$fdata["FullName"]= "p_mail";
	
	
	
	
	$fdata["Index"]= 9;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=50";
					$fdata["FieldPermissions"]=true;
		$tdata["p_mail"]=$fdata;
	
//	p_newsaccept
	$fdata = array();
	 $fdata["Label"]="Newsaccept"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Checkbox";
	$fdata["ViewFormat"]= "Checkbox";
	
	
	
				
	$fdata["GoodName"]= "p_newsaccept";
		$fdata["FullName"]= "p_newsaccept";
	
	
	
	
	$fdata["Index"]= 10;
	
						$fdata["FieldPermissions"]=true;
		$tdata["p_newsaccept"]=$fdata;
	
//	p_score
	$fdata = array();
	 $fdata["Label"]="Score"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "p_score";
		$fdata["FullName"]= "p_score";
	
	
	
	
	$fdata["Index"]= 11;
	
			$fdata["EditParams"]="";
						$fdata["FieldPermissions"]=true;
		$tdata["p_score"]=$fdata;
	
//	p_scorehigh
	$fdata = array();
	 $fdata["Label"]="Scorehigh"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "p_scorehigh";
		$fdata["FullName"]= "p_scorehigh";
	
	
	
	
	$fdata["Index"]= 12;
	
			$fdata["EditParams"]="";
						$fdata["FieldPermissions"]=true;
		$tdata["p_scorehigh"]=$fdata;
	
//	p_games
	$fdata = array();
	 $fdata["Label"]="Games Played"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "p_games";
		$fdata["FullName"]= "p_games";
	
	
	
	
	$fdata["Index"]= 13;
	
			$fdata["EditParams"]="";
						$fdata["FieldPermissions"]=true;
		$tdata["p_games"]=$fdata;
	
//	p_time
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 134;
	$fdata["EditFormat"]= "Date";
	$fdata["ViewFormat"]= "Time";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "p_time";
		$fdata["FullName"]= "p_time";
	
	
	
	
	$fdata["Index"]= 14;
	 $fdata["DateEditType"]=13; 
							$tdata["p_time"]=$fdata;
	
//	p_win
	$fdata = array();
	 $fdata["Label"]="Winner"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "p_win";
		$fdata["FullName"]= "p_win";
	
	
	
	
	$fdata["Index"]= 15;
	
			$fdata["EditParams"]="";
						$fdata["FieldPermissions"]=true;
		$tdata["p_win"]=$fdata;
	
//	p_mk
	$fdata = array();
	 $fdata["Label"]="MQ"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "p_mk";
		$fdata["FullName"]= "p_mk";
	
	
	
	
	$fdata["Index"]= 16;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=1";
					$fdata["FieldPermissions"]=true;
		$tdata["p_mk"]=$fdata;
	
//	p_born
	$fdata = array();
	 $fdata["Label"]="Born"; 
	
	
	$fdata["FieldType"]= 13;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "p_born";
		$fdata["FullName"]= "p_born";
	
	
	
	
	$fdata["Index"]= 17;
	
			$fdata["EditParams"]="";
						$fdata["FieldPermissions"]=true;
		$tdata["p_born"]=$fdata;
	
//	p_user
	$fdata = array();
	 $fdata["Label"]="Username"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "p_user";
		$fdata["FullName"]= "p_user";
	
	
	
	
	$fdata["Index"]= 18;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=12";
					$fdata["FieldPermissions"]=true;
		$tdata["p_user"]=$fdata;
	
//	p_pwd
	$fdata = array();
	 $fdata["Label"]="Password"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "p_pwd";
		$fdata["FullName"]= "p_pwd";
	
	
	
	
	$fdata["Index"]= 19;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=12";
					$fdata["FieldPermissions"]=true;
		$tdata["p_pwd"]=$fdata;
	
//	p_ip
	$fdata = array();
	 $fdata["Label"]="IP"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "p_ip";
		$fdata["FullName"]= "p_ip";
	
	
	
	
	$fdata["Index"]= 20;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=40";
					$fdata["FieldPermissions"]=true;
		$tdata["p_ip"]=$fdata;
	
//	p_datetime
	$fdata = array();
	 $fdata["Label"]="Datetime"; 
	
	
	$fdata["FieldType"]= 135;
	$fdata["EditFormat"]= "Date";
	$fdata["ViewFormat"]= "Short Date";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "p_datetime";
		$fdata["FullName"]= "p_datetime";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 21;
	 $fdata["DateEditType"]=13; 
						$fdata["FieldPermissions"]=true;
		$tdata["p_datetime"]=$fdata;
	
//	p_tscore
	$fdata = array();
	 $fdata["Label"]="Competetion Score"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "p_tscore";
		$fdata["FullName"]= "p_tscore";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 22;
	
			$fdata["EditParams"]="";
						$fdata["FieldPermissions"]=true;
		$tdata["p_tscore"]=$fdata;
	
//	p_tkills
	$fdata = array();
	 $fdata["Label"]="Competetion Kills"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "p_tkills";
		$fdata["FullName"]= "p_tkills";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 23;
	
			$fdata["EditParams"]="";
						$fdata["FieldPermissions"]=true;
		$tdata["p_tkills"]=$fdata;
	
//	p_country
	$fdata = array();
	 $fdata["Label"]="Country"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "p_country";
		$fdata["FullName"]= "p_country";
	
	
	
	
	$fdata["Index"]= 24;
	
			$fdata["EditParams"]="";
				$fdata["EditParams"].= " size=2";
				$fdata["FieldPermissions"]=true;
		$tdata["p_country"]=$fdata;
	
//	p_mobile
	$fdata = array();
	 $fdata["Label"]="Mobile"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "p_mobile";
		$fdata["FullName"]= "p_mobile";
	
	
	
	
	$fdata["Index"]= 25;
	
			$fdata["EditParams"]="";
						$fdata["FieldPermissions"]=true;
		$tdata["p_mobile"]=$fdata;
	
//	p_avatar
	$fdata = array();
	 $fdata["Label"]="Avatar No"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
	
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "p_avatar";
		$fdata["FullName"]= "p_avatar";
	
	
	
	
	$fdata["Index"]= 26;
	
			$fdata["EditParams"]="";
							$tdata["p_avatar"]=$fdata;
$tables_data["player"]=$tdata;
?>