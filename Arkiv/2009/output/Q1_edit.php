<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/Q1_variables.php");
include("include/languages.php");


/////////////////////////////////////////////////////////////
//	check if logged in
/////////////////////////////////////////////////////////////
if(!@$_SESSION["UserID"] || !CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Edit"))
{ 
	$_SESSION["MyURL"]=$_SERVER["SCRIPT_NAME"]."?".$_SERVER["QUERY_STRING"];
	header("Location: login.php?message=expired"); 
	return;
}

/////////////////////////////////////////////////////////////
//init variables
/////////////////////////////////////////////////////////////


$filename="";
$status="";
$message="";
$usermessage="";
$error_happened=false;
$readevalues=false;
$bodyonload="";


$body=array();
$showKeys = array();
$showValues = array();
$showRawValues = array();
$showFields = array();
$showDetailKeys = array();
$IsSaved = false;
$HaveData = true;
$inlineedit = (@$_REQUEST["editType"]=="inline") ? true : false;
$templatefile = "Q1_edit.htm";

/////////////////////////////////////////////////////////////
//connect database
/////////////////////////////////////////////////////////////
$conn = db_connect();

//	Before Process event
if(function_exists("BeforeProcessEdit"))
	BeforeProcessEdit($conn);

$keys=array();
$keys["Q1_id"]=postvalue("editid1");

/////////////////////////////////////////////////////////////
//	process entered data, read and save
/////////////////////////////////////////////////////////////

if(@$_POST["a"]=="edited")
{
	$strWhereClause=KeyWhere($keys);
	$strSQL = "update ".AddTableWrappers($strOriginalTableName)." set ";
	$evalues=array();
	$efilename_values=array();
	$files_delete=array();
	$files_move=array();
	$files_save=array();
//	processing Q1_work - start
	if(!$inlineedit)
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


		$evalues["Q1_work"]=$value;
	}


//	processibng Q1_work - end
	}
//	processing Q1_where - start
	if(!$inlineedit)
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


		$evalues["Q1_where"]=$value;
	}


//	processibng Q1_where - end
	}
//	processing Q1_why - start
	if(!$inlineedit)
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


		$evalues["Q1_why"]=$value;
	}


//	processibng Q1_why - end
	}
//	processing Q1_rating - start
	if(!$inlineedit)
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


		$evalues["Q1_rating"]=$value;
	}


//	processibng Q1_rating - end
	}
//	processing Q1_speakothers - start
	if(!$inlineedit)
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


		$evalues["Q1_speakothers"]=$value;
	}


//	processibng Q1_speakothers - end
	}
//	processing Q1_changedme - start
	if(!$inlineedit)
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


		$evalues["Q1_changedme"]=$value;
	}


//	processibng Q1_changedme - end
	}
//	processing Q1_playoften - start
	if(!$inlineedit)
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


		$evalues["Q1_playoften"]=$value;
	}


//	processibng Q1_playoften - end
	}
//	processing Q1_infolevel - start
	if(!$inlineedit)
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


		$evalues["Q1_infolevel"]=$value;
	}


//	processibng Q1_infolevel - end
	}
//	processing Q1_SNotice - start
	if(!$inlineedit)
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


		$evalues["Q1_SNotice"]=$value;
	}


//	processibng Q1_SNotice - end
	}
//	processing Q1_SNoticewhere - start
	if(!$inlineedit)
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


		$evalues["Q1_SNoticewhere"]=$value;
	}


//	processibng Q1_SNoticewhere - end
	}
//	processing Q1_SNoticemainsponsor - start
	if(!$inlineedit)
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


		$evalues["Q1_SNoticemainsponsor"]=$value;
	}


//	processibng Q1_SNoticemainsponsor - end
	}
//	processing Q1_SNoticeteam - start
	if(!$inlineedit)
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


		$evalues["Q1_SNoticeteam"]=$value;
	}


//	processibng Q1_SNoticeteam - end
	}
//	processing Q1_SNoticeother - start
	if(!$inlineedit)
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


		$evalues["Q1_SNoticeother"]=$value;
	}


//	processibng Q1_SNoticeother - end
	}
//	processing Q1_Shavechangedview - start
	if(!$inlineedit)
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


		$evalues["Q1_Shavechangedview"]=$value;
	}


//	processibng Q1_Shavechangedview - end
	}
//	processing Q1_Sbuyproducts - start
	if(!$inlineedit)
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


		$evalues["Q1_Sbuyproducts"]=$value;
	}


//	processibng Q1_Sbuyproducts - end
	}
//	processing Q1_Snameothers - start
	if(!$inlineedit)
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


		$evalues["Q1_Snameothers"]=$value;
	}


//	processibng Q1_Snameothers - end
	}
//	processing Q1_Comments - start
	if(!$inlineedit)
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


		$evalues["Q1_Comments"]=$value;
	}


//	processibng Q1_Comments - end
	}
//	processing Q1_p_id - start
	if(!$inlineedit)
	{
	$value = postvalue("value_Q1_p_id");
	$type=postvalue("type_Q1_p_id");
	if (in_assoc_array("type_Q1_p_id",$_POST) || in_assoc_array("value_Q1_p_id",$_POST) || in_assoc_array("value_Q1_p_id",$_FILES))	
	{
		$value=prepare_for_db("Q1_p_id",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["Q1_p_id"]=$value;
	}


//	processibng Q1_p_id - end
	}

	foreach($efilename_values as $ekey=>$value)
		$evalues[$ekey]=$value;
//	do event
	$retval=true;
	if(function_exists("BeforeEdit"))
		$retval=BeforeEdit($evalues,$strWhereClause,$dataold,$keys,$usermessage,$inlineedit);
	if($retval)
	{		
//	construct SQL string
		foreach($evalues as $ekey=>$value)
		{
			$strSQL.=AddFieldWrappers($ekey)."=".add_db_quotes($ekey,$value).", ";
		}
		if(substr($strSQL,-2)==", ")
			$strSQL=substr($strSQL,0,strlen($strSQL)-2);
		$strSQL.=" where ".$strWhereClause;
		set_error_handler("edit_error_handler");
		db_exec($strSQL,$conn);
		set_error_handler("error_handler");
		if(!$error_happened)
		{
//	delete & move files
			foreach ($files_delete as $file)
			{
				if(file_exists($file))
					@unlink($file);
			}
			foreach($files_move as $file)
			{
				move_uploaded_file($file[0],$file[1]);
				if(strtoupper(substr(PHP_OS,0,3))!="WIN")
					@chmod($file[1],0777);
			}
			foreach($files_save as $file)
			{
				if(file_exists($file["filename"]))
						@unlink($file["filename"]);
				$th = fopen($file["filename"],"w");
				fwrite($th,$file["file"]);
				fclose($th);
			}
			
			if ( $inlineedit ) 
			{
				$status="UPDATED";
				$message="".mlang_message("RECORD_UPDATED")."";
				$IsSaved = true;
			} 
			else 
				$message="<div class=message><<< ".mlang_message("RECORD_UPDATED")." >>></div>";
			if($usermessage!="")
				$message = $usermessage;
//	after edit event
			if(function_exists("AfterEdit"))
			{
				foreach($dataold as $idx=>$val)
				{
					if(!array_key_exists($idx,$evalues))
						$evalues[$idx]=$val;
				}
				AfterEdit($evalues,KeyWhere($keys),$dataold,$keys,$inlineedit);
			}
		}
	}
	else
	{
		$readevalues=true;
		$message = $usermessage;
		$status="DECLINED";
	}
}

/////////////////////////////////////////////////////////////
//	read current values from the database
/////////////////////////////////////////////////////////////

$strWhereClause=KeyWhere($keys);

$strSQL=gSQLWhere($strWhereClause);

$strSQLbak = $strSQL;
//	Before Query event
if(function_exists("BeforeQueryEdit"))
	BeforeQueryEdit($strSQL,$strWhereClause);

if($strSQLbak == $strSQL)
	$strSQL=gSQLWhere($strWhereClause);
LogInfo($strSQL);
$rs=db_query($strSQL,$conn);
$data=db_fetch_array($rs);

if($readevalues)
{
	$data["Q1_work"]=$evalues["Q1_work"];
	$data["Q1_where"]=$evalues["Q1_where"];
	$data["Q1_why"]=$evalues["Q1_why"];
	$data["Q1_rating"]=$evalues["Q1_rating"];
	$data["Q1_speakothers"]=$evalues["Q1_speakothers"];
	$data["Q1_changedme"]=$evalues["Q1_changedme"];
	$data["Q1_playoften"]=$evalues["Q1_playoften"];
	$data["Q1_infolevel"]=$evalues["Q1_infolevel"];
	$data["Q1_SNotice"]=$evalues["Q1_SNotice"];
	$data["Q1_SNoticewhere"]=$evalues["Q1_SNoticewhere"];
	$data["Q1_SNoticemainsponsor"]=$evalues["Q1_SNoticemainsponsor"];
	$data["Q1_SNoticeteam"]=$evalues["Q1_SNoticeteam"];
	$data["Q1_SNoticeother"]=$evalues["Q1_SNoticeother"];
	$data["Q1_Shavechangedview"]=$evalues["Q1_Shavechangedview"];
	$data["Q1_Sbuyproducts"]=$evalues["Q1_Sbuyproducts"];
	$data["Q1_Snameothers"]=$evalues["Q1_Snameothers"];
	$data["Q1_Comments"]=$evalues["Q1_Comments"];
	$data["Q1_p_id"]=$evalues["Q1_p_id"];
}

/////////////////////////////////////////////////////////////
//	assign values to $xt class, prepare page for displaying
/////////////////////////////////////////////////////////////

include('libs/xtempl.php');
$xt = new Xtempl();

if ( !$inlineedit ) {
	//	include files
	$includes="";

	//	validation stuff
	$onsubmit="";
		$includes.="<script language=\"JavaScript\" src=\"include/validate.js\"></script>\r\n";
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
		  		$validatetype="IsNumeric";
			if($validatetype)
			$bodyonload.="define('value_Q1_work','".$validatetype."','Q1_work');";
			  		$validatetype="IsNumeric";
			if($validatetype)
			$bodyonload.="define('value_Q1_where','".$validatetype."','Q1_where');";
			  		$validatetype="IsNumeric";
			if($validatetype)
			$bodyonload.="define('value_Q1_why','".$validatetype."','Q1_why');";
			  		$validatetype="IsNumeric";
			if($validatetype)
			$bodyonload.="define('value_Q1_rating','".$validatetype."','Q1_rating');";
			  		$validatetype="IsNumeric";
			if($validatetype)
			$bodyonload.="define('value_Q1_speakothers','".$validatetype."','Q1_speakothers');";
			  		$validatetype="IsNumeric";
			if($validatetype)
			$bodyonload.="define('value_Q1_changedme','".$validatetype."','Q1_changedme');";
			  		$validatetype="IsNumeric";
			if($validatetype)
			$bodyonload.="define('value_Q1_playoften','".$validatetype."','Q1_playoften');";
			  		$validatetype="IsNumeric";
			if($validatetype)
			$bodyonload.="define('value_Q1_infolevel','".$validatetype."','Q1_infolevel');";
			  		$validatetype="IsNumeric";
			if($validatetype)
			$bodyonload.="define('value_Q1_SNotice','".$validatetype."','Q1_SNotice');";
			  		$validatetype="IsNumeric";
			if($validatetype)
			$bodyonload.="define('value_Q1_SNoticewhere','".$validatetype."','Q1_SNoticewhere');";
			  		$validatetype="IsNumeric";
			if($validatetype)
			$bodyonload.="define('value_Q1_SNoticemainsponsor','".$validatetype."','Q1_SNoticemainsponsor');";
			  		$validatetype="IsNumeric";
			if($validatetype)
			$bodyonload.="define('value_Q1_SNoticeteam','".$validatetype."','Q1_SNoticeteam');";
			  		$validatetype="IsNumeric";
			if($validatetype)
			$bodyonload.="define('value_Q1_SNoticeother','".$validatetype."','Q1_SNoticeother');";
			  		$validatetype="IsNumeric";
			if($validatetype)
			$bodyonload.="define('value_Q1_Shavechangedview','".$validatetype."','Q1_Shavechangedview');";
			  		$validatetype="IsNumeric";
			if($validatetype)
			$bodyonload.="define('value_Q1_Sbuyproducts','".$validatetype."','Q1_Sbuyproducts');";
			  		$validatetype="IsNumeric";
			if($validatetype)
			$bodyonload.="define('value_Q1_p_id','".$validatetype."','Q1_p_id');";

	if($bodyonload)
		$onsubmit="return validate();";

	$includes.="<script language=\"JavaScript\" src=\"include/jquery.js\"></script>\r\n";
	$includes.="<script language=\"JavaScript\" src=\"include/onthefly.js\"></script>\r\n";
	if ($useAJAX) {
	$includes.="<script language=\"JavaScript\" src=\"include/ajaxsuggest.js\"></script>\r\n";
	}
	$includes.="<script language=\"JavaScript\" src=\"include/jsfunctions.js\"></script>\r\n";
	$includes.="<script language=\"JavaScript\">\r\n";
	$includes .= "var locale_dateformat = ".$locale_info["LOCALE_IDATE"].";\r\n".
	"var locale_datedelimiter = \"".$locale_info["LOCALE_SDATE"]."\";\r\n".
	"var bLoading=false;\r\n".
	"var TEXT_PLEASE_SELECT='".addslashes(mlang_message("PLEASE_SELECT"))."';\r\n";
	if ($useAJAX) {
	$includes.="var SUGGEST_TABLE='Q1_searchsuggest.php';\r\n";
	}
	$includes.="</script>\r\n";

	if ($useAJAX)
		$includes.="<div id=\"search_suggest\"></div>\r\n";





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
	$xt->assign("Q1_p_id_fieldblock",true);

	if(strlen($onsubmit))
		$onsubmit="onSubmit=\"".$onsubmit."\"";
	$body["begin"]=$includes."
	<form name=\"editform\" encType=\"multipart/form-data\" method=\"post\" action=\"Q1_edit.php\" ".$onsubmit.">".
	"<input type=hidden name=\"a\" value=\"edited\">";
	$body["begin"].="<input type=\"hidden\" name=\"editid1\" value=\"".htmlspecialchars($keys["Q1_id"])."\">";
		$xt->assign("show_key1", htmlspecialchars(GetData($data,"Q1_id", "")));

	$xt->assign("backbutton_attrs","onclick=\"window.location.href='Q1_list.php?a=return'\"");
	$xt->assign("save_button",true);
	$xt->assign("reset_button",true);
	$xt->assign("back_button",true);
}

$showKeys[] = rawurlencode($keys["Q1_id"]);

if($message)
{
	$xt->assign("message_block",true);
	$xt->assign("message",$message);
}

/////////////////////////////////////////////////////////////
//process readonly and auto-update fields
/////////////////////////////////////////////////////////////

$readonlyfields=array();



$linkdata="";

if ($useAJAX) 
{
	$record_id= postvalue("recordID");

	if ( $inlineedit ) 
	{

		$linkdata=str_replace(array("&","<",">"),array("&amp;","&lt;","&gt;"),$linkdata);

		$xt->assignbyref("linkdata",$linkdata);
	} 
	else
	{
		$linkdata = "<script type=\"text/javascript\">\r\n".
		"$(document).ready(function(){ \r\n".
		$linkdata.
		"});</script>";
	}
} 
else 
{
}

$body["end"]="</form>".$linkdata.
"<script>".$bodyonload."</script>".
"<script>SetToFirstControl('editform');</script>";
$xt->assignbyref("body",$body);


/////////////////////////////////////////////////////////////
//	prepare Edit Controls
/////////////////////////////////////////////////////////////
$control_Q1_work=array();
$control_Q1_work["func"]="xt_buildeditcontrol";
$control_Q1_work["params"] = array();
$control_Q1_work["params"]["field"]="Q1_work";
$control_Q1_work["params"]["value"]=@$data["Q1_work"];
$control_Q1_work["params"]["id"]=$record_id;
if($inlineedit)
	$control_Q1_work["params"]["mode"]="inline_edit";
else
	$control_Q1_work["params"]["mode"]="edit";
$xt->assignbyref("Q1_work_editcontrol",$control_Q1_work);
$control_Q1_where=array();
$control_Q1_where["func"]="xt_buildeditcontrol";
$control_Q1_where["params"] = array();
$control_Q1_where["params"]["field"]="Q1_where";
$control_Q1_where["params"]["value"]=@$data["Q1_where"];
$control_Q1_where["params"]["id"]=$record_id;
if($inlineedit)
	$control_Q1_where["params"]["mode"]="inline_edit";
else
	$control_Q1_where["params"]["mode"]="edit";
$xt->assignbyref("Q1_where_editcontrol",$control_Q1_where);
$control_Q1_why=array();
$control_Q1_why["func"]="xt_buildeditcontrol";
$control_Q1_why["params"] = array();
$control_Q1_why["params"]["field"]="Q1_why";
$control_Q1_why["params"]["value"]=@$data["Q1_why"];
$control_Q1_why["params"]["id"]=$record_id;
if($inlineedit)
	$control_Q1_why["params"]["mode"]="inline_edit";
else
	$control_Q1_why["params"]["mode"]="edit";
$xt->assignbyref("Q1_why_editcontrol",$control_Q1_why);
$control_Q1_rating=array();
$control_Q1_rating["func"]="xt_buildeditcontrol";
$control_Q1_rating["params"] = array();
$control_Q1_rating["params"]["field"]="Q1_rating";
$control_Q1_rating["params"]["value"]=@$data["Q1_rating"];
$control_Q1_rating["params"]["id"]=$record_id;
if($inlineedit)
	$control_Q1_rating["params"]["mode"]="inline_edit";
else
	$control_Q1_rating["params"]["mode"]="edit";
$xt->assignbyref("Q1_rating_editcontrol",$control_Q1_rating);
$control_Q1_speakothers=array();
$control_Q1_speakothers["func"]="xt_buildeditcontrol";
$control_Q1_speakothers["params"] = array();
$control_Q1_speakothers["params"]["field"]="Q1_speakothers";
$control_Q1_speakothers["params"]["value"]=@$data["Q1_speakothers"];
$control_Q1_speakothers["params"]["id"]=$record_id;
if($inlineedit)
	$control_Q1_speakothers["params"]["mode"]="inline_edit";
else
	$control_Q1_speakothers["params"]["mode"]="edit";
$xt->assignbyref("Q1_speakothers_editcontrol",$control_Q1_speakothers);
$control_Q1_changedme=array();
$control_Q1_changedme["func"]="xt_buildeditcontrol";
$control_Q1_changedme["params"] = array();
$control_Q1_changedme["params"]["field"]="Q1_changedme";
$control_Q1_changedme["params"]["value"]=@$data["Q1_changedme"];
$control_Q1_changedme["params"]["id"]=$record_id;
if($inlineedit)
	$control_Q1_changedme["params"]["mode"]="inline_edit";
else
	$control_Q1_changedme["params"]["mode"]="edit";
$xt->assignbyref("Q1_changedme_editcontrol",$control_Q1_changedme);
$control_Q1_playoften=array();
$control_Q1_playoften["func"]="xt_buildeditcontrol";
$control_Q1_playoften["params"] = array();
$control_Q1_playoften["params"]["field"]="Q1_playoften";
$control_Q1_playoften["params"]["value"]=@$data["Q1_playoften"];
$control_Q1_playoften["params"]["id"]=$record_id;
if($inlineedit)
	$control_Q1_playoften["params"]["mode"]="inline_edit";
else
	$control_Q1_playoften["params"]["mode"]="edit";
$xt->assignbyref("Q1_playoften_editcontrol",$control_Q1_playoften);
$control_Q1_infolevel=array();
$control_Q1_infolevel["func"]="xt_buildeditcontrol";
$control_Q1_infolevel["params"] = array();
$control_Q1_infolevel["params"]["field"]="Q1_infolevel";
$control_Q1_infolevel["params"]["value"]=@$data["Q1_infolevel"];
$control_Q1_infolevel["params"]["id"]=$record_id;
if($inlineedit)
	$control_Q1_infolevel["params"]["mode"]="inline_edit";
else
	$control_Q1_infolevel["params"]["mode"]="edit";
$xt->assignbyref("Q1_infolevel_editcontrol",$control_Q1_infolevel);
$control_Q1_SNotice=array();
$control_Q1_SNotice["func"]="xt_buildeditcontrol";
$control_Q1_SNotice["params"] = array();
$control_Q1_SNotice["params"]["field"]="Q1_SNotice";
$control_Q1_SNotice["params"]["value"]=@$data["Q1_SNotice"];
$control_Q1_SNotice["params"]["id"]=$record_id;
if($inlineedit)
	$control_Q1_SNotice["params"]["mode"]="inline_edit";
else
	$control_Q1_SNotice["params"]["mode"]="edit";
$xt->assignbyref("Q1_SNotice_editcontrol",$control_Q1_SNotice);
$control_Q1_SNoticewhere=array();
$control_Q1_SNoticewhere["func"]="xt_buildeditcontrol";
$control_Q1_SNoticewhere["params"] = array();
$control_Q1_SNoticewhere["params"]["field"]="Q1_SNoticewhere";
$control_Q1_SNoticewhere["params"]["value"]=@$data["Q1_SNoticewhere"];
$control_Q1_SNoticewhere["params"]["id"]=$record_id;
if($inlineedit)
	$control_Q1_SNoticewhere["params"]["mode"]="inline_edit";
else
	$control_Q1_SNoticewhere["params"]["mode"]="edit";
$xt->assignbyref("Q1_SNoticewhere_editcontrol",$control_Q1_SNoticewhere);
$control_Q1_SNoticemainsponsor=array();
$control_Q1_SNoticemainsponsor["func"]="xt_buildeditcontrol";
$control_Q1_SNoticemainsponsor["params"] = array();
$control_Q1_SNoticemainsponsor["params"]["field"]="Q1_SNoticemainsponsor";
$control_Q1_SNoticemainsponsor["params"]["value"]=@$data["Q1_SNoticemainsponsor"];
$control_Q1_SNoticemainsponsor["params"]["id"]=$record_id;
if($inlineedit)
	$control_Q1_SNoticemainsponsor["params"]["mode"]="inline_edit";
else
	$control_Q1_SNoticemainsponsor["params"]["mode"]="edit";
$xt->assignbyref("Q1_SNoticemainsponsor_editcontrol",$control_Q1_SNoticemainsponsor);
$control_Q1_SNoticeteam=array();
$control_Q1_SNoticeteam["func"]="xt_buildeditcontrol";
$control_Q1_SNoticeteam["params"] = array();
$control_Q1_SNoticeteam["params"]["field"]="Q1_SNoticeteam";
$control_Q1_SNoticeteam["params"]["value"]=@$data["Q1_SNoticeteam"];
$control_Q1_SNoticeteam["params"]["id"]=$record_id;
if($inlineedit)
	$control_Q1_SNoticeteam["params"]["mode"]="inline_edit";
else
	$control_Q1_SNoticeteam["params"]["mode"]="edit";
$xt->assignbyref("Q1_SNoticeteam_editcontrol",$control_Q1_SNoticeteam);
$control_Q1_SNoticeother=array();
$control_Q1_SNoticeother["func"]="xt_buildeditcontrol";
$control_Q1_SNoticeother["params"] = array();
$control_Q1_SNoticeother["params"]["field"]="Q1_SNoticeother";
$control_Q1_SNoticeother["params"]["value"]=@$data["Q1_SNoticeother"];
$control_Q1_SNoticeother["params"]["id"]=$record_id;
if($inlineedit)
	$control_Q1_SNoticeother["params"]["mode"]="inline_edit";
else
	$control_Q1_SNoticeother["params"]["mode"]="edit";
$xt->assignbyref("Q1_SNoticeother_editcontrol",$control_Q1_SNoticeother);
$control_Q1_Shavechangedview=array();
$control_Q1_Shavechangedview["func"]="xt_buildeditcontrol";
$control_Q1_Shavechangedview["params"] = array();
$control_Q1_Shavechangedview["params"]["field"]="Q1_Shavechangedview";
$control_Q1_Shavechangedview["params"]["value"]=@$data["Q1_Shavechangedview"];
$control_Q1_Shavechangedview["params"]["id"]=$record_id;
if($inlineedit)
	$control_Q1_Shavechangedview["params"]["mode"]="inline_edit";
else
	$control_Q1_Shavechangedview["params"]["mode"]="edit";
$xt->assignbyref("Q1_Shavechangedview_editcontrol",$control_Q1_Shavechangedview);
$control_Q1_Sbuyproducts=array();
$control_Q1_Sbuyproducts["func"]="xt_buildeditcontrol";
$control_Q1_Sbuyproducts["params"] = array();
$control_Q1_Sbuyproducts["params"]["field"]="Q1_Sbuyproducts";
$control_Q1_Sbuyproducts["params"]["value"]=@$data["Q1_Sbuyproducts"];
$control_Q1_Sbuyproducts["params"]["id"]=$record_id;
if($inlineedit)
	$control_Q1_Sbuyproducts["params"]["mode"]="inline_edit";
else
	$control_Q1_Sbuyproducts["params"]["mode"]="edit";
$xt->assignbyref("Q1_Sbuyproducts_editcontrol",$control_Q1_Sbuyproducts);
$control_Q1_Snameothers=array();
$control_Q1_Snameothers["func"]="xt_buildeditcontrol";
$control_Q1_Snameothers["params"] = array();
$control_Q1_Snameothers["params"]["field"]="Q1_Snameothers";
$control_Q1_Snameothers["params"]["value"]=@$data["Q1_Snameothers"];
$control_Q1_Snameothers["params"]["id"]=$record_id;
if($inlineedit)
	$control_Q1_Snameothers["params"]["mode"]="inline_edit";
else
	$control_Q1_Snameothers["params"]["mode"]="edit";
$xt->assignbyref("Q1_Snameothers_editcontrol",$control_Q1_Snameothers);
$control_Q1_Comments=array();
$control_Q1_Comments["func"]="xt_buildeditcontrol";
$control_Q1_Comments["params"] = array();
$control_Q1_Comments["params"]["field"]="Q1_Comments";
$control_Q1_Comments["params"]["value"]=@$data["Q1_Comments"];
$control_Q1_Comments["params"]["id"]=$record_id;
if($inlineedit)
	$control_Q1_Comments["params"]["mode"]="inline_edit";
else
	$control_Q1_Comments["params"]["mode"]="edit";
$xt->assignbyref("Q1_Comments_editcontrol",$control_Q1_Comments);
$control_Q1_p_id=array();
$control_Q1_p_id["func"]="xt_buildeditcontrol";
$control_Q1_p_id["params"] = array();
$control_Q1_p_id["params"]["field"]="Q1_p_id";
$control_Q1_p_id["params"]["value"]=@$data["Q1_p_id"];
$control_Q1_p_id["params"]["id"]=$record_id;
if($inlineedit)
	$control_Q1_p_id["params"]["mode"]="inline_edit";
else
	$control_Q1_p_id["params"]["mode"]="edit";
$xt->assignbyref("Q1_p_id_editcontrol",$control_Q1_p_id);

/////////////////////////////////////////////////////////////
//display the page
/////////////////////////////////////////////////////////////

if(function_exists("BeforeShowEdit"))
	BeforeShowEdit($xt,$templatefile);
$xt->display($templatefile);

function edit_error_handler($errno, $errstr, $errfile, $errline)
{
	global $readevalues, $message, $status, $inlineedit, $error_happened;
		$message="<div class=message><<< ".mlang_message("RECORD_NOT_UPDATED")." >>><br><br>".$errstr."</div>";
	$readevalues=true;
	$error_happened=true;
}

?>