<?php
include("sponsor_settings.php");

function DisplayMasterTableInfo_sponsor($params)
{
	$detailtable=$params["detailtable"];
	$keys=$params["keys"];
	global $conn,$strTableName;
	$xt = new Xtempl();
	
	$oldTableName=$strTableName;
	$strTableName="sponsor";

//$strSQL = "SELECT  s_id,  s_active,  s_name,  s_contact,  s_adr,  s_zip,  s_phone1,  s_phone2,  s_total,  s_paid,  s_logo,  s_banner,  s_www,  s_mail,  s_cmt,  s_country  FROM sponsor  ORDER BY s_name  ";

$sqlHead="SELECT s_id,  s_active,  s_name,  s_contact,  s_adr,  s_zip,  s_phone1,  s_phone2,  s_total,  s_paid,  s_logo,  s_banner,  s_www,  s_mail,  s_cmt,  s_country ";
$sqlFrom="FROM sponsor ";
$sqlWhere="";
$sqlTail="";

$where="";

if($detailtable=="teams")
{
		$where.= GetFullFieldName("s_id")."=".make_db_value("s_id",$keys[1-1]);
}
if(!$where)
{
	$strTableName=$oldTableName;
	return;
}
	$str = SecuritySQL("Search");
	if(strlen($str))
		$where.=" and ".$str;

	$strWhere=whereAdd($sqlWhere,$where);
	if(strlen($strWhere))
		$strWhere=" where ".$strWhere." ";
	$strSQL= $sqlHead.$sqlFrom.$strWhere.$sqlTail;

//	$strSQL=AddWhere($strSQL,$where);
	LogInfo($strSQL);
	$rs=db_query($strSQL,$conn);
	$data=db_fetch_array($rs);
	$keylink="";
	$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["s_id"]));
	

//	s_active - 
			$value="";
				$value=DisplayLookupWizard("s_active",$data["s_active"],$data,$keylink,MODE_LIST);
			$xt->assign("s_active_mastervalue",$value);

//	s_name - 
			$value="";
				$value = ProcessLargeText(GetData($data,"s_name", ""),"field=s%5Fname".$keylink);
			$xt->assign("s_name_mastervalue",$value);

//	s_contact - 
			$value="";
				$value = ProcessLargeText(GetData($data,"s_contact", ""),"field=s%5Fcontact".$keylink);
			$xt->assign("s_contact_mastervalue",$value);

//	s_country - 
			$value="";
				$value=DisplayLookupWizard("s_country",$data["s_country"],$data,$keylink,MODE_LIST);
			$xt->assign("s_country_mastervalue",$value);

//	s_phone1 - 
			$value="";
				$value = ProcessLargeText(GetData($data,"s_phone1", ""),"field=s%5Fphone1".$keylink);
			$xt->assign("s_phone1_mastervalue",$value);

//	s_banner - File-based Image
			$value="";
				if(CheckImageExtension($data["s_banner"])) 
			{
						$value="<img";
										$value.=" border=0";
				$value.=" src=\"".htmlspecialchars(AddLinkPrefix("s_banner",$data["s_banner"]))."\">";
			}
			$xt->assign("s_banner_mastervalue",$value);

//	s_www - Hyperlink
			$value="";
				$value = GetData($data,"s_www", "Hyperlink");
			$xt->assign("s_www_mastervalue",$value);

//	s_mail - Email Hyperlink
			$value="";
				$value = GetData($data,"s_mail", "Email Hyperlink");
			$xt->assign("s_mail_mastervalue",$value);
	$strTableName=$oldTableName;
	$xt->display("sponsor_masterlist.htm");
}

// events

?>