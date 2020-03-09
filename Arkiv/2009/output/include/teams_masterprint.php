<?php
include("teams_settings.php");

function DisplayMasterTableInfo_teams($params)
{
	$detailtable=$params["detailtable"];
	$keys=$params["keys"];
	global $conn,$strTableName;
	$xt = new Xtempl();
	
	$oldTableName=$strTableName;
	$strTableName="teams";

//$strSQL = "SELECT  ts_id,  ts_name,  ts_p0,  ts_p1,  ts_p2,  ts_p9,  ts_s_id,  ts_Score  FROM teams  ORDER BY ts_s_id  ";

$sqlHead="SELECT ts_id,  ts_name,  ts_p0,  ts_p1,  ts_p2,  ts_p9,  ts_s_id,  ts_Score ";
$sqlFrom="FROM teams ";
$sqlWhere="";
$sqlTail="";

$where="";

if($detailtable=="player")
{
		$where.= GetFullFieldName("ts_id")."=".make_db_value("ts_id",$keys[1-1]);
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
	$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["ts_id"]));
	

//	ts_id - 
			$value="";
				$value = ProcessLargeText(GetData($data,"ts_id", ""),"field=ts%5Fid".$keylink,"",MODE_PRINT);
			$xt->assign("ts_id_mastervalue",$value);

//	ts_p0 - 
			$value="";
				$value = ProcessLargeText(GetData($data,"ts_p0", ""),"field=ts%5Fp0".$keylink,"",MODE_PRINT);
			$xt->assign("ts_p0_mastervalue",$value);

//	ts_s_id - 
			$value="";
				$value=DisplayLookupWizard("ts_s_id",$data["ts_s_id"],$data,$keylink,MODE_PRINT);
			$xt->assign("ts_s_id_mastervalue",$value);

//	ts_name - 
			$value="";
				$value = ProcessLargeText(GetData($data,"ts_name", ""),"field=ts%5Fname".$keylink,"",MODE_PRINT);
			$xt->assign("ts_name_mastervalue",$value);
	$strTableName=$oldTableName;
	$xt->display("teams_masterprint.htm");

}

// events

?>