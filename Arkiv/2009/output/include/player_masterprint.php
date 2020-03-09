<?php
include("player_settings.php");

function DisplayMasterTableInfo_player($params)
{
	$detailtable=$params["detailtable"];
	$keys=$params["keys"];
	global $conn,$strTableName;
	$xt = new Xtempl();
	
	$oldTableName=$strTableName;
	$strTableName="player";

//$strSQL = "SELECT  p_id,  p_active,  p_location,  p_s_id,  p_first,  p_name,  p_adr,  p_zip,  p_mail,  p_newsaccept,  p_score,  p_scorehigh,  p_games,  p_time,  p_win,  p_mk,  p_born,  p_user,  p_pwd,  p_ip,  p_datetime,  p_tscore,  p_tkills,  p_country,  p_mobile,  p_avatar  FROM player  ORDER BY p_active, p_id DESC  ";

$sqlHead="SELECT p_id,  p_active,  p_location,  p_s_id,  p_first,  p_name,  p_adr,  p_zip,  p_mail,  p_newsaccept,  p_score,  p_scorehigh,  p_games,  p_time,  p_win,  p_mk,  p_born,  p_user,  p_pwd,  p_ip,  p_datetime,  p_tscore,  p_tkills,  p_country,  p_mobile,  p_avatar ";
$sqlFrom="FROM player ";
$sqlWhere="";
$sqlTail="";

$where="";

if($detailtable=="Dialog")
{
		$where.= GetFullFieldName("p_id")."=".make_db_value("p_id",$keys[1-1]);
}
elseif($detailtable=="raffle")
{
		$where.= GetFullFieldName("p_id")."=".make_db_value("p_id",$keys[1-1]);
}
elseif($detailtable=="top")
{
		$where.= GetFullFieldName("p_id")."=".make_db_value("p_id",$keys[1-1]);
}
if(!$where)
{
	$strTableName=$oldTableName;
	return;
}
	$str = SecuritySQL("Export");
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
	$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["p_id"]));
	

//	p_active - 
			$value="";
				$value=DisplayLookupWizard("p_active",$data["p_active"],$data,$keylink,MODE_PRINT);
			$xt->assign("p_active_mastervalue",$value);

//	p_s_id - 
			$value="";
				$value=DisplayLookupWizard("p_s_id",$data["p_s_id"],$data,$keylink,MODE_PRINT);
			$xt->assign("p_s_id_mastervalue",$value);

//	p_first - 
			$value="";
				$value = ProcessLargeText(GetData($data,"p_first", ""),"field=p%5Ffirst".$keylink,"",MODE_PRINT);
			$xt->assign("p_first_mastervalue",$value);

//	p_name - 
			$value="";
				$value = ProcessLargeText(GetData($data,"p_name", ""),"field=p%5Fname".$keylink,"",MODE_PRINT);
			$xt->assign("p_name_mastervalue",$value);

//	p_zip - 
			$value="";
				$value = ProcessLargeText(GetData($data,"p_zip", ""),"field=p%5Fzip".$keylink,"",MODE_PRINT);
			$xt->assign("p_zip_mastervalue",$value);

//	p_country - 
			$value="";
				$value = ProcessLargeText(GetData($data,"p_country", ""),"field=p%5Fcountry".$keylink,"",MODE_PRINT);
			$xt->assign("p_country_mastervalue",$value);

//	p_mail - Email Hyperlink
			$value="";
				$value = GetData($data,"p_mail", "Email Hyperlink");
			$xt->assign("p_mail_mastervalue",$value);

//	p_mobile - 
			$value="";
				$value = ProcessLargeText(GetData($data,"p_mobile", ""),"field=p%5Fmobile".$keylink,"",MODE_PRINT);
			$xt->assign("p_mobile_mastervalue",$value);

//	p_newsaccept - Checkbox
			$value="";
				$value = GetData($data,"p_newsaccept", "Checkbox");
			$xt->assign("p_newsaccept_mastervalue",$value);

//	p_win - 
			$value="";
				$value = ProcessLargeText(GetData($data,"p_win", ""),"field=p%5Fwin".$keylink,"",MODE_PRINT);
			$xt->assign("p_win_mastervalue",$value);

//	p_mk - 
			$value="";
				$value = ProcessLargeText(GetData($data,"p_mk", ""),"field=p%5Fmk".$keylink,"",MODE_PRINT);
			$xt->assign("p_mk_mastervalue",$value);

//	p_born - 
			$value="";
				$value = ProcessLargeText(GetData($data,"p_born", ""),"field=p%5Fborn".$keylink,"",MODE_PRINT);
			$xt->assign("p_born_mastervalue",$value);

//	p_ip - 
			$value="";
				$value = ProcessLargeText(GetData($data,"p_ip", ""),"field=p%5Fip".$keylink,"",MODE_PRINT);
			$xt->assign("p_ip_mastervalue",$value);

//	p_datetime - Short Date
			$value="";
				$value = ProcessLargeText(GetData($data,"p_datetime", "Short Date"),"field=p%5Fdatetime".$keylink,"",MODE_PRINT);
			$xt->assign("p_datetime_mastervalue",$value);

//	p_tscore - 
			$value="";
				$value = ProcessLargeText(GetData($data,"p_tscore", ""),"field=p%5Ftscore".$keylink,"",MODE_PRINT);
			$xt->assign("p_tscore_mastervalue",$value);
	$strTableName=$oldTableName;
	$xt->display("player_masterprint.htm");

}

// events

?>