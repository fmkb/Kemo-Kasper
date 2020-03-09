<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/top_variables.php");
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
$templatefile = "top_edit.htm";

/////////////////////////////////////////////////////////////
//connect database
/////////////////////////////////////////////////////////////
$conn = db_connect();

//	Before Process event
if(function_exists("BeforeProcessEdit"))
	BeforeProcessEdit($conn);

$keys=array();
$keys["t_id"]=postvalue("editid1");

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
//	processing t_user - start
	if(!$inlineedit)
	{
	$value = postvalue("value_t_user");
	$type=postvalue("type_t_user");
	if (in_assoc_array("type_t_user",$_POST) || in_assoc_array("value_t_user",$_POST) || in_assoc_array("value_t_user",$_FILES))	
	{
		$value=prepare_for_db("t_user",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["t_user"]=$value;
	}


//	processibng t_user - end
	}
//	processing t_score - start
	if(!$inlineedit)
	{
	$value = postvalue("value_t_score");
	$type=postvalue("type_t_score");
	if (in_assoc_array("type_t_score",$_POST) || in_assoc_array("value_t_score",$_POST) || in_assoc_array("value_t_score",$_FILES))	
	{
		$value=prepare_for_db("t_score",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["t_score"]=$value;
	}


//	processibng t_score - end
	}
//	processing t_datetime - start
	if(!$inlineedit)
	{
	$value = postvalue("value_t_datetime");
	$type=postvalue("type_t_datetime");
	if (in_assoc_array("type_t_datetime",$_POST) || in_assoc_array("value_t_datetime",$_POST) || in_assoc_array("value_t_datetime",$_FILES))	
	{
		$value=prepare_for_db("t_datetime",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["t_datetime"]=$value;
	}


//	processibng t_datetime - end
	}
//	processing t_ip - start
	if(!$inlineedit)
	{
	$value = postvalue("value_t_ip");
	$type=postvalue("type_t_ip");
	if (in_assoc_array("type_t_ip",$_POST) || in_assoc_array("value_t_ip",$_POST) || in_assoc_array("value_t_ip",$_FILES))	
	{
		$value=prepare_for_db("t_ip",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["t_ip"]=$value;
	}


//	processibng t_ip - end
	}
//	processing t_p_id - start
	if(!$inlineedit)
	{
	$value = postvalue("value_t_p_id");
	$type=postvalue("type_t_p_id");
	if (in_assoc_array("type_t_p_id",$_POST) || in_assoc_array("value_t_p_id",$_POST) || in_assoc_array("value_t_p_id",$_FILES))	
	{
		$value=prepare_for_db("t_p_id",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["t_p_id"]=$value;
	}


//	processibng t_p_id - end
	}
//	processing t_ts_id - start
	if(!$inlineedit)
	{
	$value = postvalue("value_t_ts_id");
	$type=postvalue("type_t_ts_id");
	if (in_assoc_array("type_t_ts_id",$_POST) || in_assoc_array("value_t_ts_id",$_POST) || in_assoc_array("value_t_ts_id",$_FILES))	
	{
		$value=prepare_for_db("t_ts_id",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["t_ts_id"]=$value;
	}


//	processibng t_ts_id - end
	}
//	processing t_kills - start
	if(!$inlineedit)
	{
	$value = postvalue("value_t_kills");
	$type=postvalue("type_t_kills");
	if (in_assoc_array("type_t_kills",$_POST) || in_assoc_array("value_t_kills",$_POST) || in_assoc_array("value_t_kills",$_FILES))	
	{
		$value=prepare_for_db("t_kills",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["t_kills"]=$value;
	}


//	processibng t_kills - end
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
	$data["t_user"]=$evalues["t_user"];
	$data["t_score"]=$evalues["t_score"];
	$data["t_datetime"]=$evalues["t_datetime"];
	$data["t_ip"]=$evalues["t_ip"];
	$data["t_p_id"]=$evalues["t_p_id"];
	$data["t_ts_id"]=$evalues["t_ts_id"];
	$data["t_kills"]=$evalues["t_kills"];
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
			$bodyonload.="define('value_t_score','".$validatetype."','Score');";
				$validatetype="";
			$validatetype.="IsRequired";
		if($validatetype)
			$bodyonload.="define('value_t_datetime','".$validatetype."','Datetime');";
				$validatetype="";
			if($validatetype)
			$bodyonload.="define('value_t_p_id','".$validatetype."','Player');";
			  		$validatetype="IsNumeric";
			if($validatetype)
			$bodyonload.="define('value_t_ts_id','".$validatetype."','Team');";
			  		$validatetype="IsNumeric";
			if($validatetype)
			$bodyonload.="define('value_t_kills','".$validatetype."','Kills');";

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
	$includes.="var SUGGEST_TABLE='top_searchsuggest.php';\r\n";
	}
	$includes.="</script>\r\n";

	if ($useAJAX)
		$includes.="<div id=\"search_suggest\"></div>\r\n";

		//	include datepicker files
	$includes.="<script language=\"JavaScript\" src=\"include/calendar.js\"></script>\r\n";




	$xt->assign("t_user_fieldblock",true);
	$xt->assign("t_score_fieldblock",true);
	$xt->assign("t_datetime_fieldblock",true);
	$xt->assign("t_ip_fieldblock",true);
	$xt->assign("t_p_id_fieldblock",true);
	$xt->assign("t_ts_id_fieldblock",true);
	$xt->assign("t_kills_fieldblock",true);

	if(strlen($onsubmit))
		$onsubmit="onSubmit=\"".$onsubmit."\"";
	$body["begin"]=$includes."
	<form name=\"editform\" encType=\"multipart/form-data\" method=\"post\" action=\"top_edit.php\" ".$onsubmit.">".
	"<input type=hidden name=\"a\" value=\"edited\">";
	$body["begin"].="<input type=\"hidden\" name=\"editid1\" value=\"".htmlspecialchars($keys["t_id"])."\">";
		$xt->assign("show_key1", htmlspecialchars(GetData($data,"t_id", "")));

	$xt->assign("backbutton_attrs","onclick=\"window.location.href='top_list.php?a=return'\"");
	$xt->assign("save_button",true);
	$xt->assign("reset_button",true);
	$xt->assign("back_button",true);
}

$showKeys[] = rawurlencode($keys["t_id"]);

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
$control_t_user=array();
$control_t_user["func"]="xt_buildeditcontrol";
$control_t_user["params"] = array();
$control_t_user["params"]["field"]="t_user";
$control_t_user["params"]["value"]=@$data["t_user"];
$control_t_user["params"]["id"]=$record_id;
if($inlineedit)
	$control_t_user["params"]["mode"]="inline_edit";
else
	$control_t_user["params"]["mode"]="edit";
$xt->assignbyref("t_user_editcontrol",$control_t_user);
$control_t_score=array();
$control_t_score["func"]="xt_buildeditcontrol";
$control_t_score["params"] = array();
$control_t_score["params"]["field"]="t_score";
$control_t_score["params"]["value"]=@$data["t_score"];
$control_t_score["params"]["id"]=$record_id;
if($inlineedit)
	$control_t_score["params"]["mode"]="inline_edit";
else
	$control_t_score["params"]["mode"]="edit";
$xt->assignbyref("t_score_editcontrol",$control_t_score);
$control_t_datetime=array();
$control_t_datetime["func"]="xt_buildeditcontrol";
$control_t_datetime["params"] = array();
$control_t_datetime["params"]["field"]="t_datetime";
$control_t_datetime["params"]["value"]=@$data["t_datetime"];
$control_t_datetime["params"]["id"]=$record_id;
if($inlineedit)
	$control_t_datetime["params"]["mode"]="inline_edit";
else
	$control_t_datetime["params"]["mode"]="edit";
$xt->assignbyref("t_datetime_editcontrol",$control_t_datetime);
$control_t_ip=array();
$control_t_ip["func"]="xt_buildeditcontrol";
$control_t_ip["params"] = array();
$control_t_ip["params"]["field"]="t_ip";
$control_t_ip["params"]["value"]=@$data["t_ip"];
$control_t_ip["params"]["id"]=$record_id;
if($inlineedit)
	$control_t_ip["params"]["mode"]="inline_edit";
else
	$control_t_ip["params"]["mode"]="edit";
$xt->assignbyref("t_ip_editcontrol",$control_t_ip);
$control_t_p_id=array();
$control_t_p_id["func"]="xt_buildeditcontrol";
$control_t_p_id["params"] = array();
$control_t_p_id["params"]["field"]="t_p_id";
$control_t_p_id["params"]["value"]=@$data["t_p_id"];
$control_t_p_id["params"]["id"]=$record_id;
if($inlineedit)
	$control_t_p_id["params"]["mode"]="inline_edit";
else
	$control_t_p_id["params"]["mode"]="edit";
$xt->assignbyref("t_p_id_editcontrol",$control_t_p_id);
$control_t_ts_id=array();
$control_t_ts_id["func"]="xt_buildeditcontrol";
$control_t_ts_id["params"] = array();
$control_t_ts_id["params"]["field"]="t_ts_id";
$control_t_ts_id["params"]["value"]=@$data["t_ts_id"];
$control_t_ts_id["params"]["id"]=$record_id;
if($inlineedit)
	$control_t_ts_id["params"]["mode"]="inline_edit";
else
	$control_t_ts_id["params"]["mode"]="edit";
$xt->assignbyref("t_ts_id_editcontrol",$control_t_ts_id);
$control_t_kills=array();
$control_t_kills["func"]="xt_buildeditcontrol";
$control_t_kills["params"] = array();
$control_t_kills["params"]["field"]="t_kills";
$control_t_kills["params"]["value"]=@$data["t_kills"];
$control_t_kills["params"]["id"]=$record_id;
if($inlineedit)
	$control_t_kills["params"]["mode"]="inline_edit";
else
	$control_t_kills["params"]["mode"]="edit";
$xt->assignbyref("t_kills_editcontrol",$control_t_kills);

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