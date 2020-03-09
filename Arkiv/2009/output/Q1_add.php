<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0); 

include("include/dbcommon.php");
include("include/Q1_variables.php");
include("include/languages.php");


//	check if logged in

if(!@$_SESSION["UserID"] || !CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Add"))
{ 
	$_SESSION["MyURL"]=$_SERVER["SCRIPT_NAME"]."?".$_SERVER["QUERY_STRING"];
	header("Location: login.php?message=expired"); 
	return;
}

$filename="";
$status="";
$message="";
$usermessage="";
$error_happened=false;
$readavalues=false;


$showKeys = array();
$showValues = array();
$showRawValues = array();
$showFields = array();
$showDetailKeys = array();
$IsSaved = false;
$HaveData = true;
if(@$_REQUEST["editType"]=="inline")
	$inlineedit=ADD_INLINE;
elseif(@$_REQUEST["editType"]=="onthefly")
	$inlineedit=ADD_ONTHEFLY;
else
	$inlineedit=ADD_SIMPLE;
$keys=array();
if($inlineedit==ADD_INLINE)
	$templatefile = "Q1_inline_add.htm";
else
	$templatefile = "Q1_add.htm";

//connect database
$conn = db_connect();

//	Before Process event
if(function_exists("BeforeProcessAdd"))
	BeforeProcessAdd($conn);

include('libs/xtempl.php');
$xt = new Xtempl();

// insert new record if we have to

if(@$_POST["a"]=="added")
{
	$afilename_values=array();
	$avalues=array();
	$files_move=array();
//	processing Q1_work - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_Q1_work");
	$type=postvalue("type_Q1_work");
	if (in_assoc_array("type_Q1_work",$_POST) || in_assoc_array("value_Q1_work",$_POST) || in_assoc_array("value_Q1_work",$_FILES))
	{
		$value=prepare_for_db("Q1_work",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["Q1_work"]=$value;
	}
	}
//	processibng Q1_work - end
//	processing Q1_where - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_Q1_where");
	$type=postvalue("type_Q1_where");
	if (in_assoc_array("type_Q1_where",$_POST) || in_assoc_array("value_Q1_where",$_POST) || in_assoc_array("value_Q1_where",$_FILES))
	{
		$value=prepare_for_db("Q1_where",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["Q1_where"]=$value;
	}
	}
//	processibng Q1_where - end
//	processing Q1_why - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_Q1_why");
	$type=postvalue("type_Q1_why");
	if (in_assoc_array("type_Q1_why",$_POST) || in_assoc_array("value_Q1_why",$_POST) || in_assoc_array("value_Q1_why",$_FILES))
	{
		$value=prepare_for_db("Q1_why",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["Q1_why"]=$value;
	}
	}
//	processibng Q1_why - end
//	processing Q1_rating - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_Q1_rating");
	$type=postvalue("type_Q1_rating");
	if (in_assoc_array("type_Q1_rating",$_POST) || in_assoc_array("value_Q1_rating",$_POST) || in_assoc_array("value_Q1_rating",$_FILES))
	{
		$value=prepare_for_db("Q1_rating",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["Q1_rating"]=$value;
	}
	}
//	processibng Q1_rating - end
//	processing Q1_speakothers - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_Q1_speakothers");
	$type=postvalue("type_Q1_speakothers");
	if (in_assoc_array("type_Q1_speakothers",$_POST) || in_assoc_array("value_Q1_speakothers",$_POST) || in_assoc_array("value_Q1_speakothers",$_FILES))
	{
		$value=prepare_for_db("Q1_speakothers",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["Q1_speakothers"]=$value;
	}
	}
//	processibng Q1_speakothers - end
//	processing Q1_changedme - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_Q1_changedme");
	$type=postvalue("type_Q1_changedme");
	if (in_assoc_array("type_Q1_changedme",$_POST) || in_assoc_array("value_Q1_changedme",$_POST) || in_assoc_array("value_Q1_changedme",$_FILES))
	{
		$value=prepare_for_db("Q1_changedme",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["Q1_changedme"]=$value;
	}
	}
//	processibng Q1_changedme - end
//	processing Q1_playoften - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_Q1_playoften");
	$type=postvalue("type_Q1_playoften");
	if (in_assoc_array("type_Q1_playoften",$_POST) || in_assoc_array("value_Q1_playoften",$_POST) || in_assoc_array("value_Q1_playoften",$_FILES))
	{
		$value=prepare_for_db("Q1_playoften",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["Q1_playoften"]=$value;
	}
	}
//	processibng Q1_playoften - end
//	processing Q1_infolevel - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_Q1_infolevel");
	$type=postvalue("type_Q1_infolevel");
	if (in_assoc_array("type_Q1_infolevel",$_POST) || in_assoc_array("value_Q1_infolevel",$_POST) || in_assoc_array("value_Q1_infolevel",$_FILES))
	{
		$value=prepare_for_db("Q1_infolevel",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["Q1_infolevel"]=$value;
	}
	}
//	processibng Q1_infolevel - end
//	processing Q1_SNotice - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_Q1_SNotice");
	$type=postvalue("type_Q1_SNotice");
	if (in_assoc_array("type_Q1_SNotice",$_POST) || in_assoc_array("value_Q1_SNotice",$_POST) || in_assoc_array("value_Q1_SNotice",$_FILES))
	{
		$value=prepare_for_db("Q1_SNotice",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["Q1_SNotice"]=$value;
	}
	}
//	processibng Q1_SNotice - end
//	processing Q1_SNoticewhere - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_Q1_SNoticewhere");
	$type=postvalue("type_Q1_SNoticewhere");
	if (in_assoc_array("type_Q1_SNoticewhere",$_POST) || in_assoc_array("value_Q1_SNoticewhere",$_POST) || in_assoc_array("value_Q1_SNoticewhere",$_FILES))
	{
		$value=prepare_for_db("Q1_SNoticewhere",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["Q1_SNoticewhere"]=$value;
	}
	}
//	processibng Q1_SNoticewhere - end
//	processing Q1_SNoticemainsponsor - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_Q1_SNoticemainsponsor");
	$type=postvalue("type_Q1_SNoticemainsponsor");
	if (in_assoc_array("type_Q1_SNoticemainsponsor",$_POST) || in_assoc_array("value_Q1_SNoticemainsponsor",$_POST) || in_assoc_array("value_Q1_SNoticemainsponsor",$_FILES))
	{
		$value=prepare_for_db("Q1_SNoticemainsponsor",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["Q1_SNoticemainsponsor"]=$value;
	}
	}
//	processibng Q1_SNoticemainsponsor - end
//	processing Q1_SNoticeteam - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_Q1_SNoticeteam");
	$type=postvalue("type_Q1_SNoticeteam");
	if (in_assoc_array("type_Q1_SNoticeteam",$_POST) || in_assoc_array("value_Q1_SNoticeteam",$_POST) || in_assoc_array("value_Q1_SNoticeteam",$_FILES))
	{
		$value=prepare_for_db("Q1_SNoticeteam",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["Q1_SNoticeteam"]=$value;
	}
	}
//	processibng Q1_SNoticeteam - end
//	processing Q1_SNoticeother - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_Q1_SNoticeother");
	$type=postvalue("type_Q1_SNoticeother");
	if (in_assoc_array("type_Q1_SNoticeother",$_POST) || in_assoc_array("value_Q1_SNoticeother",$_POST) || in_assoc_array("value_Q1_SNoticeother",$_FILES))
	{
		$value=prepare_for_db("Q1_SNoticeother",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["Q1_SNoticeother"]=$value;
	}
	}
//	processibng Q1_SNoticeother - end
//	processing Q1_Shavechangedview - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_Q1_Shavechangedview");
	$type=postvalue("type_Q1_Shavechangedview");
	if (in_assoc_array("type_Q1_Shavechangedview",$_POST) || in_assoc_array("value_Q1_Shavechangedview",$_POST) || in_assoc_array("value_Q1_Shavechangedview",$_FILES))
	{
		$value=prepare_for_db("Q1_Shavechangedview",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["Q1_Shavechangedview"]=$value;
	}
	}
//	processibng Q1_Shavechangedview - end
//	processing Q1_Sbuyproducts - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_Q1_Sbuyproducts");
	$type=postvalue("type_Q1_Sbuyproducts");
	if (in_assoc_array("type_Q1_Sbuyproducts",$_POST) || in_assoc_array("value_Q1_Sbuyproducts",$_POST) || in_assoc_array("value_Q1_Sbuyproducts",$_FILES))
	{
		$value=prepare_for_db("Q1_Sbuyproducts",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["Q1_Sbuyproducts"]=$value;
	}
	}
//	processibng Q1_Sbuyproducts - end
//	processing Q1_Snameothers - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_Q1_Snameothers");
	$type=postvalue("type_Q1_Snameothers");
	if (in_assoc_array("type_Q1_Snameothers",$_POST) || in_assoc_array("value_Q1_Snameothers",$_POST) || in_assoc_array("value_Q1_Snameothers",$_FILES))
	{
		$value=prepare_for_db("Q1_Snameothers",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["Q1_Snameothers"]=$value;
	}
	}
//	processibng Q1_Snameothers - end
//	processing Q1_Comments - start
	if($inlineedit!=ADD_INLINE)
	{
	$value = postvalue("value_Q1_Comments");
	$type=postvalue("type_Q1_Comments");
	if (in_assoc_array("type_Q1_Comments",$_POST) || in_assoc_array("value_Q1_Comments",$_POST) || in_assoc_array("value_Q1_Comments",$_FILES))
	{
		$value=prepare_for_db("Q1_Comments",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["Q1_Comments"]=$value;
	}
	}
//	processibng Q1_Comments - end





if($inlineedit==ADD_ONTHEFLY)
{
}


//	add filenames to values
	foreach($afilename_values as $akey=>$value)
		$avalues[$akey]=$value;
//	make SQL string
	$strSQL = "insert into ".AddTableWrappers($strOriginalTableName)." ";
	$strFields="(";
	$strValues="(";
	
//	before Add event
	$retval = true;
	if(function_exists("BeforeAdd"))
		$retval=BeforeAdd($avalues,$usermessage,$inlineedit);
	if($retval)
	{
		foreach($avalues as $akey=>$value)
		{
			$strFields.=AddFieldWrappers($akey).", ";
			$strValues.=add_db_quotes($akey,$value).", ";
		}
		if(substr($strFields,-2)==", ")
			$strFields=substr($strFields,0,strlen($strFields)-2);
		if(substr($strValues,-2)==", ")
			$strValues=substr($strValues,0,strlen($strValues)-2);
		$strSQL.=$strFields.") values ".$strValues.")";
		LogInfo($strSQL);
		set_error_handler("add_error_handler");
		db_exec($strSQL,$conn);
		set_error_handler("error_handler");
//	move files
		if(!$error_happened)
		{
			foreach ($files_move as $file)
			{
				move_uploaded_file($file[0],$file[1]);
				if(strtoupper(substr(PHP_OS,0,3))!="WIN")
					@chmod($file[1],0777);
			}
			if ( $inlineedit==ADD_INLINE ) 
			{
				$status="ADDED";
				$message="".mlang_message("RECORD_ADDED")."";
				$IsSaved = true;
			} 
			else
				$message="<div class=message><<< ".mlang_message("RECORD_ADDED")." >>></div>";
			if($usermessage!="")
				$message = $usermessage;
if($inlineedit==ADD_INLINE || $inlineedit==ADD_ONTHEFLY || function_exists("AfterAdd"))
{

	$failed_inline_add = false;
						$keys["Q1_id"]=mysql_insert_id($conn);
}	

//	after edit event
			if(function_exists("AfterAdd"))
			{
				foreach($keys as $idx=>$val)
					$avalues[$idx]=$val;
				AfterAdd($avalues,$keys,$inlineedit);
			}
		}
	}
	else
	{
		$message = $usermessage;
		$status="DECLINED";
		$readavalues=true;
	}
}

$defvalues=array();


//	copy record
if(array_key_exists("copyid1",$_REQUEST) || array_key_exists("editid1",$_REQUEST))
{
	$copykeys=array();
	if(array_key_exists("copyid1",$_REQUEST))
	{
		$copykeys["Q1_id"]=postvalue("copyid1");
	}
	else
	{
		$copykeys["Q1_id"]=postvalue("editid1");
	}
	$strWhere=KeyWhere($copykeys);
	$strSQL = gSQLWhere($strWhere);

	LogInfo($strSQL);
	$rs=db_query($strSQL,$conn);
	$defvalues=db_fetch_array($rs);
//	clear key fields
	$defvalues["Q1_id"]="";
//call CopyOnLoad event
	if(function_exists("CopyOnLoad"))
		CopyOnLoad($defvalues,$strWhere);
}
else if(!count($defvalues))
{
}
if($inlineedit==ADD_ONTHEFLY)
{
}
if($readavalues)
{
	$defvalues["Q1_work"]=@$avalues["Q1_work"];
	$defvalues["Q1_where"]=@$avalues["Q1_where"];
	$defvalues["Q1_why"]=@$avalues["Q1_why"];
	$defvalues["Q1_rating"]=@$avalues["Q1_rating"];
	$defvalues["Q1_speakothers"]=@$avalues["Q1_speakothers"];
	$defvalues["Q1_changedme"]=@$avalues["Q1_changedme"];
	$defvalues["Q1_playoften"]=@$avalues["Q1_playoften"];
	$defvalues["Q1_infolevel"]=@$avalues["Q1_infolevel"];
	$defvalues["Q1_SNotice"]=@$avalues["Q1_SNotice"];
	$defvalues["Q1_SNoticewhere"]=@$avalues["Q1_SNoticewhere"];
	$defvalues["Q1_SNoticemainsponsor"]=@$avalues["Q1_SNoticemainsponsor"];
	$defvalues["Q1_SNoticeteam"]=@$avalues["Q1_SNoticeteam"];
	$defvalues["Q1_SNoticeother"]=@$avalues["Q1_SNoticeother"];
	$defvalues["Q1_Shavechangedview"]=@$avalues["Q1_Shavechangedview"];
	$defvalues["Q1_Sbuyproducts"]=@$avalues["Q1_Sbuyproducts"];
	$defvalues["Q1_Snameothers"]=@$avalues["Q1_Snameothers"];
	$defvalues["Q1_Comments"]=@$avalues["Q1_Comments"];
}

/*
foreach($defvalues as $key=>$value)
	$smarty->assign("value_".GoodFieldName($key),$value);
*/

$linkdata="";
$includes="";
$arr_includes=array();
$bodyonload="";
	
if ( $inlineedit!=ADD_INLINE ) 
{
	//	include files

	//	validation stuff
	$onsubmit="";
	$needvalidate=false;
	if($inlineedit!=ADD_ONTHEFLY)
		$includes.="<script language=\"JavaScript\" src=\"include/validate.js\"></script>\r\n";
	
	if($inlineedit!=ADD_ONTHEFLY)
	{
		$includes.="<script language=\"JavaScript\">\r\n";
		$includes.="var TEXT_FIELDS_REQUIRED='".addslashes(mlang_message("FIELDS_REQUIRED"))."';\r\n";
		$includes.="var TEXT_FIELDS_ZIPCODES='".addslashes(mlang_message("FIELDS_ZIPCODES"))."';\r\n";
		$includes.="var TEXT_FIELDS_EMAILS='".addslashes(mlang_message("FIELDS_EMAILS"))."';\r\n";
		$includes.="var TEXT_FIELDS_NUMBERS='".addslashes(mlang_message("FIELDS_NUMBERS"))."';\r\n";
		$includes.="var TEXT_FIELDS_CURRENCY='".addslashes(mlang_message("FIELDS_CURRENCY"))."';\r\n";
		$includes.="var TEXT_FIELDS_PHONE='".addslashes(mlang_message("FIELDS_PHONE"))."';\r\n";
		$includes.="var TEXT_FIELDS_PASSWORD1='".addslashes(mlang_message("FIELDS_PASSWORD1"))."';\r\n";
		$includes.="var TEXT_FIELDS_PASSWORD2='".addslashes(mlang_message("FIELDS_PASSWORD2"))."';\r\n";
		$includes.="var TEXT_FIELDS_PASSWORD3='".addslashes(mlang_message("FIELDS_PASSWORD3"))."';\r\n";
		$includes.="var TEXT_FIELDS_STATE='".addslashes(mlang_message("FIELDS_STATE"))."';\r\n";
		$includes.="var TEXT_FIELDS_SSN='".addslashes(mlang_message("FIELDS_SSN"))."';\r\n";
		$includes.="var TEXT_FIELDS_DATE='".addslashes(mlang_message("FIELDS_DATE"))."';\r\n";
		$includes.="var TEXT_FIELDS_TIME='".addslashes(mlang_message("FIELDS_TIME"))."';\r\n";
		$includes.="var TEXT_FIELDS_CC='".addslashes(mlang_message("FIELDS_CC"))."';\r\n";
		$includes.="var TEXT_FIELDS_SSN='".addslashes(mlang_message("FIELDS_SSN"))."';\r\n";
		$includes.="</script>\r\n";
	}
	else
	{
		$includes.="var TEXT_INLINE_FIELD_REQUIRED='".jsreplace(mlang_message("INLINE_FIELD_REQUIRED"))."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_ZIPCODE='".jsreplace(mlang_message("INLINE_FIELD_ZIPCODE"))."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_EMAIL='".jsreplace(mlang_message("INLINE_FIELD_EMAIL"))."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_NUMBER='".jsreplace(mlang_message("INLINE_FIELD_NUMBER"))."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_CURRENCY='".jsreplace(mlang_message("INLINE_FIELD_CURRENCY"))."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_PHONE='".jsreplace(mlang_message("INLINE_FIELD_PHONE"))."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_PASSWORD1='".jsreplace(mlang_message("INLINE_FIELD_PASSWORD1"))."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_PASSWORD2='".jsreplace(mlang_message("INLINE_FIELD_PASSWORD2"))."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_STATE='".jsreplace(mlang_message("INLINE_FIELD_STATE"))."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_SSN='".jsreplace(mlang_message("INLINE_FIELD_SSN"))."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_DATE='".jsreplace(mlang_message("INLINE_FIELD_DATE"))."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_TIME='".jsreplace(mlang_message("INLINE_FIELD_TIME"))."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_CC='".jsreplace(mlang_message("INLINE_FIELD_CC"))."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_SSN='".jsreplace(mlang_message("INLINE_FIELD_SSN"))."';\r\n";
	}
		  		$validatetype="IsNumeric";
			if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_Q1_work_".postvalue("id")."','".$validatetype."');";
			else
				$bodyonload.="define('value_Q1_work','".$validatetype."','Q1_work');";
			
		}
		  		$validatetype="IsNumeric";
			if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_Q1_where_".postvalue("id")."','".$validatetype."');";
			else
				$bodyonload.="define('value_Q1_where','".$validatetype."','Q1_where');";
			
		}
		  		$validatetype="IsNumeric";
			if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_Q1_why_".postvalue("id")."','".$validatetype."');";
			else
				$bodyonload.="define('value_Q1_why','".$validatetype."','Q1_why');";
			
		}
		  		$validatetype="IsNumeric";
			if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_Q1_rating_".postvalue("id")."','".$validatetype."');";
			else
				$bodyonload.="define('value_Q1_rating','".$validatetype."','Q1_rating');";
			
		}
		  		$validatetype="IsNumeric";
			if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_Q1_speakothers_".postvalue("id")."','".$validatetype."');";
			else
				$bodyonload.="define('value_Q1_speakothers','".$validatetype."','Q1_speakothers');";
			
		}
		  		$validatetype="IsNumeric";
			if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_Q1_changedme_".postvalue("id")."','".$validatetype."');";
			else
				$bodyonload.="define('value_Q1_changedme','".$validatetype."','Q1_changedme');";
			
		}
		  		$validatetype="IsNumeric";
			if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_Q1_playoften_".postvalue("id")."','".$validatetype."');";
			else
				$bodyonload.="define('value_Q1_playoften','".$validatetype."','Q1_playoften');";
			
		}
		  		$validatetype="IsNumeric";
			if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_Q1_infolevel_".postvalue("id")."','".$validatetype."');";
			else
				$bodyonload.="define('value_Q1_infolevel','".$validatetype."','Q1_infolevel');";
			
		}
		  		$validatetype="IsNumeric";
			if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_Q1_SNotice_".postvalue("id")."','".$validatetype."');";
			else
				$bodyonload.="define('value_Q1_SNotice','".$validatetype."','Q1_SNotice');";
			
		}
		  		$validatetype="IsNumeric";
			if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_Q1_SNoticewhere_".postvalue("id")."','".$validatetype."');";
			else
				$bodyonload.="define('value_Q1_SNoticewhere','".$validatetype."','Q1_SNoticewhere');";
			
		}
		  		$validatetype="IsNumeric";
			if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_Q1_SNoticemainsponsor_".postvalue("id")."','".$validatetype."');";
			else
				$bodyonload.="define('value_Q1_SNoticemainsponsor','".$validatetype."','Q1_SNoticemainsponsor');";
			
		}
		  		$validatetype="IsNumeric";
			if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_Q1_SNoticeteam_".postvalue("id")."','".$validatetype."');";
			else
				$bodyonload.="define('value_Q1_SNoticeteam','".$validatetype."','Q1_SNoticeteam');";
			
		}
		  		$validatetype="IsNumeric";
			if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_Q1_SNoticeother_".postvalue("id")."','".$validatetype."');";
			else
				$bodyonload.="define('value_Q1_SNoticeother','".$validatetype."','Q1_SNoticeother');";
			
		}
		  		$validatetype="IsNumeric";
			if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_Q1_Shavechangedview_".postvalue("id")."','".$validatetype."');";
			else
				$bodyonload.="define('value_Q1_Shavechangedview','".$validatetype."','Q1_Shavechangedview');";
			
		}
		  		$validatetype="IsNumeric";
			if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_Q1_Sbuyproducts_".postvalue("id")."','".$validatetype."');";
			else
				$bodyonload.="define('value_Q1_Sbuyproducts','".$validatetype."','Q1_Sbuyproducts');";
			
		}

	if($needvalidate)
	{
		if($inlineedit==ADD_ONTHEFLY)
			$onsubmit="return validate_fly(this);";
		else
			$onsubmit="return validate();";
//		$bodyonload="onload=\"".$bodyonload."\"";
	}

	if($inlineedit!=ADD_ONTHEFLY)
	{
		$includes.="<script language=\"JavaScript\" src=\"include/jquery.js\"></script>\r\n";
		$includes.="<script language=\"JavaScript\" src=\"include/onthefly.js\"></script>\r\n";
		if ($useAJAX) 
			$includes.="<script language=\"JavaScript\" src=\"include/ajaxsuggest.js\"></script>\r\n";
		$includes.="<script language=\"JavaScript\" src=\"include/jsfunctions.js\"></script>\r\n";
	}
	if($inlineedit!=ADD_ONTHEFLY)
	{
		$includes.="<script language=\"JavaScript\">\r\n";
	}
	$includes.="var locale_dateformat = ".$locale_info["LOCALE_IDATE"].";\r\n".
	"var locale_datedelimiter = \"".$locale_info["LOCALE_SDATE"]."\";\r\n".
	"var bLoading=false;\r\n".
	"var TEXT_PLEASE_SELECT='".addslashes(mlang_message("PLEASE_SELECT"))."';\r\n";
	if ($useAJAX) {
	$includes.="var SUGGEST_TABLE='Q1_searchsuggest.php';\r\n";
	}
	if($inlineedit!=ADD_ONTHEFLY)
	{
		$includes.="</script>\r\n";
		if ($useAJAX)
			$includes.="<div id=\"search_suggest\"></div>\r\n";
	}




	$xt->assign("Q1_work_fieldblock",true);
	$xt->assign("Q1_where_fieldblock",true);
	$xt->assign("Q1_why_fieldblock",true);
	$xt->assign("Q1_rating_fieldblock",true);
	$xt->assign("Q1_speakothers_fieldblock",true);
	$xt->assign("Q1_changedme_fieldblock",true);
	$xt->assign("Q1_playoften_fieldblock",true);
	$xt->assign("Q1_infolevel_fieldblock",true);
	$xt->assign("Q1_SNotice_fieldblock",true);
	$xt->assign("Q1_SNoticewhere_fieldblock",true);
	$xt->assign("Q1_SNoticemainsponsor_fieldblock",true);
	$xt->assign("Q1_SNoticeteam_fieldblock",true);
	$xt->assign("Q1_SNoticeother_fieldblock",true);
	$xt->assign("Q1_Shavechangedview_fieldblock",true);
	$xt->assign("Q1_Sbuyproducts_fieldblock",true);
	$xt->assign("Q1_Snameothers_fieldblock",true);
	$xt->assign("Q1_Comments_fieldblock",true);
	
	$body=array();
	$formname="editform";
	if($inlineedit!=ADD_ONTHEFLY)
	{
		if($onsubmit)
			$onsubmit="onsubmit=\"".$onsubmit."\"";
		$body["begin"]=$includes.
		"<form name=\"editform\" encType=\"multipart/form-data\" method=\"post\" action=\"Q1_add.php\" ".$onsubmit.">".
		"<input type=hidden name=\"a\" value=\"added\">";
		$xt->assign("backbutton_attrs","onclick=\"window.location.href='Q1_list.php?a=return'\"");
		$xt->assign("back_button",true);
	}
	else
	{
		$formname="editform".postvalue("id");
		$body["begin"]="<form name=\"editform".postvalue("id")."\" encType=\"multipart/form-data\" method=\"post\" action=\"Q1_add.php\" ".$onsubmit.">".
		"<input type=hidden name=\"a\" value=\"added\">".
		"<input type=hidden name=\"editType\" value=\"onthefly\">".
		"<input type=hidden name=\"table\" value=\"".postvalue("table")."\">".
		"<input type=hidden name=\"field\" value=\"".postvalue("field")."\">".
		"<input type=hidden name=\"category\" value=\"".postvalue("category")."\">".
		"<input type=hidden name=\"id\" value=\"".postvalue("id")."\">";
		$xt->assign("cancelbutton_attrs","onclick=\"RemoveFlyDiv('".substr(postvalue("id"),3)."');\"");
		$xt->assign("cancel_button",true);
	}
	$xt->assign("save_button",true);
	$xt->assign("reset_button",true);
}

if($message)
{
	$xt->assign("message_block",true);
	$xt->assign("message",$message);
}
//$xt->assign("status",$status);

$readonlyfields=array();

//	show readonly fields


$record_id= postvalue("recordID");

if ($useAJAX) 
{
	if($inlineedit==ADD_ONTHEFLY)
		$record_id= postvalue("id");

	if ( $inlineedit==ADD_INLINE ) 
	{
		$linkdata=str_replace(array("&","<",">"),array("&amp;","&lt;","&gt;"),$linkdata);

	} 
	else 
	{
		$linkdata.="SetToFirstControl('".$formname."');";
		if($inlineedit==ADD_SIMPLE)
		{
			$linkdata = "<script type=\"text/javascript\">\r\n".
			"$(document).ready(function(){ \r\n".
			$linkdata.
			"});</script>";
		}
		else
		{
			$linkdata=$includes."\r\n".$linkdata;
			$includes="var s;";
			foreach($arr_includes as $file)
			{
				$includes.="s = document.createElement('script');s.src = '".$file."';\r\n".
				"document.getElementsByTagName('HEAD')[0].appendChild(s);\r\n";
			}			
			$linkdata=$includes."\r\n".$linkdata;

			if(!@$_POST["a"]=="added")
			{
				$linkdata = str_replace(array("\\","\r","\n"),array("\\\\","\\r","\\n"),$linkdata);
				echo $linkdata;
				echo "\n";
			}
			else if(@$_POST["a"]=="added" && ($error_happened || $status=="DECLINED"))
			{
				echo "<textarea id=\"data\">decli";
				echo htmlspecialchars($linkdata);
				echo "</textarea>";
			}

		}
	}
} 
else 
{
}

if($inlineedit!=ADD_ONTHEFLY)
{
	$body["end"]="</form>".$linkdata.
	"<script>".$bodyonload."</script>";
	
	$xt->assign("body",$body);
	$xt->assign("flybody",true);
}
else
{
	$xt->assign("flybody",$body);
	$xt->assign("body",true);
}




if(@$_POST["a"]=="added" && $inlineedit==ADD_ONTHEFLY && !$error_happened && $status!="DECLINED")
{
	$LookupSQL="";
	if($LookupSQL)
		$LookupSQL.=" from ".AddTableWrappers($strOriginalTableName);

	$data=0;
	if(count($keys) && $LookupSQL)
	{
		$where=KeyWhere($keys);
		$LookupSQL.=" where ".$where;
		$rs=db_query($LookupSQL,$conn);
		$data=db_fetch_numarray($rs);
	}
	if(!$data)
	{
		$data=array(@$avalues[$linkfield],@$avalues[$dispfield]);
	}
	echo "<textarea id=\"data\">";
	echo "added";
	print_inline_array($data);
	echo "</textarea>";
	exit();
}


/////////////////////////////////////////////////////////////
//	prepare Edit Controls
/////////////////////////////////////////////////////////////
$control_Q1_work=array();
$control_Q1_work["func"]="xt_buildeditcontrol";
$control_Q1_work["params"] = array();
$control_Q1_work["params"]["field"]="Q1_work";
$control_Q1_work["params"]["value"]=@$defvalues["Q1_work"];
$control_Q1_work["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_Q1_work["params"]["mode"]="inline_add";
else
	$control_Q1_work["params"]["mode"]="add";
$xt->assignbyref("Q1_work_editcontrol",$control_Q1_work);
$control_Q1_where=array();
$control_Q1_where["func"]="xt_buildeditcontrol";
$control_Q1_where["params"] = array();
$control_Q1_where["params"]["field"]="Q1_where";
$control_Q1_where["params"]["value"]=@$defvalues["Q1_where"];
$control_Q1_where["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_Q1_where["params"]["mode"]="inline_add";
else
	$control_Q1_where["params"]["mode"]="add";
$xt->assignbyref("Q1_where_editcontrol",$control_Q1_where);
$control_Q1_why=array();
$control_Q1_why["func"]="xt_buildeditcontrol";
$control_Q1_why["params"] = array();
$control_Q1_why["params"]["field"]="Q1_why";
$control_Q1_why["params"]["value"]=@$defvalues["Q1_why"];
$control_Q1_why["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_Q1_why["params"]["mode"]="inline_add";
else
	$control_Q1_why["params"]["mode"]="add";
$xt->assignbyref("Q1_why_editcontrol",$control_Q1_why);
$control_Q1_rating=array();
$control_Q1_rating["func"]="xt_buildeditcontrol";
$control_Q1_rating["params"] = array();
$control_Q1_rating["params"]["field"]="Q1_rating";
$control_Q1_rating["params"]["value"]=@$defvalues["Q1_rating"];
$control_Q1_rating["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_Q1_rating["params"]["mode"]="inline_add";
else
	$control_Q1_rating["params"]["mode"]="add";
$xt->assignbyref("Q1_rating_editcontrol",$control_Q1_rating);
$control_Q1_speakothers=array();
$control_Q1_speakothers["func"]="xt_buildeditcontrol";
$control_Q1_speakothers["params"] = array();
$control_Q1_speakothers["params"]["field"]="Q1_speakothers";
$control_Q1_speakothers["params"]["value"]=@$defvalues["Q1_speakothers"];
$control_Q1_speakothers["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_Q1_speakothers["params"]["mode"]="inline_add";
else
	$control_Q1_speakothers["params"]["mode"]="add";
$xt->assignbyref("Q1_speakothers_editcontrol",$control_Q1_speakothers);
$control_Q1_changedme=array();
$control_Q1_changedme["func"]="xt_buildeditcontrol";
$control_Q1_changedme["params"] = array();
$control_Q1_changedme["params"]["field"]="Q1_changedme";
$control_Q1_changedme["params"]["value"]=@$defvalues["Q1_changedme"];
$control_Q1_changedme["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_Q1_changedme["params"]["mode"]="inline_add";
else
	$control_Q1_changedme["params"]["mode"]="add";
$xt->assignbyref("Q1_changedme_editcontrol",$control_Q1_changedme);
$control_Q1_playoften=array();
$control_Q1_playoften["func"]="xt_buildeditcontrol";
$control_Q1_playoften["params"] = array();
$control_Q1_playoften["params"]["field"]="Q1_playoften";
$control_Q1_playoften["params"]["value"]=@$defvalues["Q1_playoften"];
$control_Q1_playoften["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_Q1_playoften["params"]["mode"]="inline_add";
else
	$control_Q1_playoften["params"]["mode"]="add";
$xt->assignbyref("Q1_playoften_editcontrol",$control_Q1_playoften);
$control_Q1_infolevel=array();
$control_Q1_infolevel["func"]="xt_buildeditcontrol";
$control_Q1_infolevel["params"] = array();
$control_Q1_infolevel["params"]["field"]="Q1_infolevel";
$control_Q1_infolevel["params"]["value"]=@$defvalues["Q1_infolevel"];
$control_Q1_infolevel["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_Q1_infolevel["params"]["mode"]="inline_add";
else
	$control_Q1_infolevel["params"]["mode"]="add";
$xt->assignbyref("Q1_infolevel_editcontrol",$control_Q1_infolevel);
$control_Q1_SNotice=array();
$control_Q1_SNotice["func"]="xt_buildeditcontrol";
$control_Q1_SNotice["params"] = array();
$control_Q1_SNotice["params"]["field"]="Q1_SNotice";
$control_Q1_SNotice["params"]["value"]=@$defvalues["Q1_SNotice"];
$control_Q1_SNotice["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_Q1_SNotice["params"]["mode"]="inline_add";
else
	$control_Q1_SNotice["params"]["mode"]="add";
$xt->assignbyref("Q1_SNotice_editcontrol",$control_Q1_SNotice);
$control_Q1_SNoticewhere=array();
$control_Q1_SNoticewhere["func"]="xt_buildeditcontrol";
$control_Q1_SNoticewhere["params"] = array();
$control_Q1_SNoticewhere["params"]["field"]="Q1_SNoticewhere";
$control_Q1_SNoticewhere["params"]["value"]=@$defvalues["Q1_SNoticewhere"];
$control_Q1_SNoticewhere["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_Q1_SNoticewhere["params"]["mode"]="inline_add";
else
	$control_Q1_SNoticewhere["params"]["mode"]="add";
$xt->assignbyref("Q1_SNoticewhere_editcontrol",$control_Q1_SNoticewhere);
$control_Q1_SNoticemainsponsor=array();
$control_Q1_SNoticemainsponsor["func"]="xt_buildeditcontrol";
$control_Q1_SNoticemainsponsor["params"] = array();
$control_Q1_SNoticemainsponsor["params"]["field"]="Q1_SNoticemainsponsor";
$control_Q1_SNoticemainsponsor["params"]["value"]=@$defvalues["Q1_SNoticemainsponsor"];
$control_Q1_SNoticemainsponsor["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_Q1_SNoticemainsponsor["params"]["mode"]="inline_add";
else
	$control_Q1_SNoticemainsponsor["params"]["mode"]="add";
$xt->assignbyref("Q1_SNoticemainsponsor_editcontrol",$control_Q1_SNoticemainsponsor);
$control_Q1_SNoticeteam=array();
$control_Q1_SNoticeteam["func"]="xt_buildeditcontrol";
$control_Q1_SNoticeteam["params"] = array();
$control_Q1_SNoticeteam["params"]["field"]="Q1_SNoticeteam";
$control_Q1_SNoticeteam["params"]["value"]=@$defvalues["Q1_SNoticeteam"];
$control_Q1_SNoticeteam["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_Q1_SNoticeteam["params"]["mode"]="inline_add";
else
	$control_Q1_SNoticeteam["params"]["mode"]="add";
$xt->assignbyref("Q1_SNoticeteam_editcontrol",$control_Q1_SNoticeteam);
$control_Q1_SNoticeother=array();
$control_Q1_SNoticeother["func"]="xt_buildeditcontrol";
$control_Q1_SNoticeother["params"] = array();
$control_Q1_SNoticeother["params"]["field"]="Q1_SNoticeother";
$control_Q1_SNoticeother["params"]["value"]=@$defvalues["Q1_SNoticeother"];
$control_Q1_SNoticeother["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_Q1_SNoticeother["params"]["mode"]="inline_add";
else
	$control_Q1_SNoticeother["params"]["mode"]="add";
$xt->assignbyref("Q1_SNoticeother_editcontrol",$control_Q1_SNoticeother);
$control_Q1_Shavechangedview=array();
$control_Q1_Shavechangedview["func"]="xt_buildeditcontrol";
$control_Q1_Shavechangedview["params"] = array();
$control_Q1_Shavechangedview["params"]["field"]="Q1_Shavechangedview";
$control_Q1_Shavechangedview["params"]["value"]=@$defvalues["Q1_Shavechangedview"];
$control_Q1_Shavechangedview["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_Q1_Shavechangedview["params"]["mode"]="inline_add";
else
	$control_Q1_Shavechangedview["params"]["mode"]="add";
$xt->assignbyref("Q1_Shavechangedview_editcontrol",$control_Q1_Shavechangedview);
$control_Q1_Sbuyproducts=array();
$control_Q1_Sbuyproducts["func"]="xt_buildeditcontrol";
$control_Q1_Sbuyproducts["params"] = array();
$control_Q1_Sbuyproducts["params"]["field"]="Q1_Sbuyproducts";
$control_Q1_Sbuyproducts["params"]["value"]=@$defvalues["Q1_Sbuyproducts"];
$control_Q1_Sbuyproducts["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_Q1_Sbuyproducts["params"]["mode"]="inline_add";
else
	$control_Q1_Sbuyproducts["params"]["mode"]="add";
$xt->assignbyref("Q1_Sbuyproducts_editcontrol",$control_Q1_Sbuyproducts);
$control_Q1_Snameothers=array();
$control_Q1_Snameothers["func"]="xt_buildeditcontrol";
$control_Q1_Snameothers["params"] = array();
$control_Q1_Snameothers["params"]["field"]="Q1_Snameothers";
$control_Q1_Snameothers["params"]["value"]=@$defvalues["Q1_Snameothers"];
$control_Q1_Snameothers["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_Q1_Snameothers["params"]["mode"]="inline_add";
else
	$control_Q1_Snameothers["params"]["mode"]="add";
$xt->assignbyref("Q1_Snameothers_editcontrol",$control_Q1_Snameothers);
$control_Q1_Comments=array();
$control_Q1_Comments["func"]="xt_buildeditcontrol";
$control_Q1_Comments["params"] = array();
$control_Q1_Comments["params"]["field"]="Q1_Comments";
$control_Q1_Comments["params"]["value"]=@$defvalues["Q1_Comments"];
$control_Q1_Comments["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_Q1_Comments["params"]["mode"]="inline_add";
else
	$control_Q1_Comments["params"]["mode"]="add";
$xt->assignbyref("Q1_Comments_editcontrol",$control_Q1_Comments);

$xt->assign("style_block",true);

if(function_exists("BeforeShowAdd"))
	BeforeShowAdd($xt,$templatefile);


if($inlineedit==ADD_ONTHEFLY)
{
	$xt->load_template($templatefile);
	$xt->display_loaded("style_block");
	$xt->display_loaded("flybody");
}
else
	$xt->display($templatefile);

function add_error_handler($errno, $errstr, $errfile, $errline)
{
	global $readavalues, $message, $status, $inlineedit, $error_happened;
	if ( $inlineedit!=ADD_SIMPLE ) 
		$message="".mlang_message("RECORD_NOT_ADDED").". ".$errstr;
	else  
		$message="<div class=message><<< ".mlang_message("RECORD_NOT_ADDED")." >>><br><br>".$errstr."</div>";
	$readavalues=true;
	$error_happened=true;
}
?>
