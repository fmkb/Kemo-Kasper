<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/news_variables.php");
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
$templatefile = "news_edit.htm";

/////////////////////////////////////////////////////////////
//connect database
/////////////////////////////////////////////////////////////
$conn = db_connect();

//	Before Process event
if(function_exists("BeforeProcessEdit"))
	BeforeProcessEdit($conn);

$keys=array();
$keys["n_id"]=postvalue("editid1");

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
//	processing n_active - start
	if(!$inlineedit)
	{
	$value = postvalue("value_n_active");
	$type=postvalue("type_n_active");
	if (in_assoc_array("type_n_active",$_POST) || in_assoc_array("value_n_active",$_POST) || in_assoc_array("value_n_active",$_FILES))	
	{
		$value=prepare_for_db("n_active",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["n_active"]=$value;
	}


//	processibng n_active - end
	}
//	processing n_start - start
	if(!$inlineedit)
	{
	$value = postvalue("value_n_start");
	$type=postvalue("type_n_start");
	if (in_assoc_array("type_n_start",$_POST) || in_assoc_array("value_n_start",$_POST) || in_assoc_array("value_n_start",$_FILES))	
	{
		$value=prepare_for_db("n_start",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["n_start"]=$value;
	}


//	processibng n_start - end
	}
//	processing n_end - start
	if(!$inlineedit)
	{
	$value = postvalue("value_n_end");
	$type=postvalue("type_n_end");
	if (in_assoc_array("type_n_end",$_POST) || in_assoc_array("value_n_end",$_POST) || in_assoc_array("value_n_end",$_FILES))	
	{
		$value=prepare_for_db("n_end",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["n_end"]=$value;
	}


//	processibng n_end - end
	}
//	processing n_date - start
	if(!$inlineedit)
	{
	$value = postvalue("value_n_date");
	$type=postvalue("type_n_date");
	if (in_assoc_array("type_n_date",$_POST) || in_assoc_array("value_n_date",$_POST) || in_assoc_array("value_n_date",$_FILES))	
	{
		$value=prepare_for_db("n_date",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["n_date"]=$value;
	}


//	processibng n_date - end
	}
//	processing n_head - start
	if(!$inlineedit)
	{
	$value = postvalue("value_n_head");
	$type=postvalue("type_n_head");
	if (in_assoc_array("type_n_head",$_POST) || in_assoc_array("value_n_head",$_POST) || in_assoc_array("value_n_head",$_FILES))	
	{
		$value=prepare_for_db("n_head",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["n_head"]=$value;
	}


//	processibng n_head - end
	}
//	processing n_text - start
	if(!$inlineedit)
	{
	$value = postvalue("value_n_text");
	$type=postvalue("type_n_text");
	if (in_assoc_array("type_n_text",$_POST) || in_assoc_array("value_n_text",$_POST) || in_assoc_array("value_n_text",$_FILES))	
	{
		$value=prepare_for_db("n_text",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["n_text"]=$value;
	}


//	processibng n_text - end
	}
//	processing n_file - start
	if(!$inlineedit)
	{
	$value = postvalue("value_n_file");
	$type=postvalue("type_n_file");
	if (in_assoc_array("type_n_file",$_POST) || in_assoc_array("value_n_file",$_POST) || in_assoc_array("value_n_file",$_FILES))	
	{
		$value=prepare_for_db("n_file",$value,$type,postvalue("filename_n_file"));
	}
	else
		$value=false;
	if(!($value===false))
	{	
			if($value)
		{
			$ext = CheckImageExtension($_FILES["file_n_file"]["name"]);
			$contents = myfile_get_contents($_FILES["file_n_file"]['tmp_name']);
			$thumb = CreateThumbnail($contents,150,$ext);
			$file = GetUploadFolder("n_file")."th_".$value;
			$files_save[] = array("file"=>$thumb,"filename"=>$file);
		}


		$evalues["n_file"]=$value;
	}


//	processibng n_file - end
	}
//	processing n_type - start
	if(!$inlineedit)
	{
	$value = postvalue("value_n_type");
	$type=postvalue("type_n_type");
	if (in_assoc_array("type_n_type",$_POST) || in_assoc_array("value_n_type",$_POST) || in_assoc_array("value_n_type",$_FILES))	
	{
		$value=prepare_for_db("n_type",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["n_type"]=$value;
	}


//	processibng n_type - end
	}
//	processing n_teaser - start
	if(!$inlineedit)
	{
	$value = postvalue("value_n_teaser");
	$type=postvalue("type_n_teaser");
	if (in_assoc_array("type_n_teaser",$_POST) || in_assoc_array("value_n_teaser",$_POST) || in_assoc_array("value_n_teaser",$_FILES))	
	{
		$value=prepare_for_db("n_teaser",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["n_teaser"]=$value;
	}


//	processibng n_teaser - end
	}
//	processing n_country - start
	if(!$inlineedit)
	{
	$value = postvalue("value_n_country");
	$type=postvalue("type_n_country");
	if (in_assoc_array("type_n_country",$_POST) || in_assoc_array("value_n_country",$_POST) || in_assoc_array("value_n_country",$_FILES))	
	{
		$value=prepare_for_db("n_country",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["n_country"]=$value;
	}


//	processibng n_country - end
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
	$data["n_active"]=$evalues["n_active"];
	$data["n_start"]=$evalues["n_start"];
	$data["n_end"]=$evalues["n_end"];
	$data["n_date"]=$evalues["n_date"];
	$data["n_head"]=$evalues["n_head"];
	$data["n_text"]=$evalues["n_text"];
	$data["n_type"]=$evalues["n_type"];
	$data["n_teaser"]=$evalues["n_teaser"];
	$data["n_country"]=$evalues["n_country"];
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
			$validatetype="";
			if($validatetype)
			$bodyonload.="define('value_n_active','".$validatetype."','Active');";
			  		$validatetype="IsNumeric";
			if($validatetype)
			$bodyonload.="define('value_n_type','".$validatetype."','News Type');";

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
	$includes.="var SUGGEST_TABLE='news_searchsuggest.php';\r\n";
	}
	$includes.="</script>\r\n";

	if ($useAJAX)
		$includes.="<div id=\"search_suggest\"></div>\r\n";

		//	include datepicker files
	$includes.="<script language=\"JavaScript\" src=\"include/calendar.js\"></script>\r\n";
		



	$xt->assign("n_active_fieldblock",true);
	$xt->assign("n_start_fieldblock",true);
	$xt->assign("n_end_fieldblock",true);
	$xt->assign("n_date_fieldblock",true);
	$xt->assign("n_head_fieldblock",true);
	$xt->assign("n_text_fieldblock",true);
	$xt->assign("n_file_fieldblock",true);
	$xt->assign("n_type_fieldblock",true);
	$xt->assign("n_teaser_fieldblock",true);
	$xt->assign("n_country_fieldblock",true);

	if(strlen($onsubmit))
		$onsubmit="onSubmit=\"".$onsubmit."\"";
	$body["begin"]=$includes."
	<form name=\"editform\" encType=\"multipart/form-data\" method=\"post\" action=\"news_edit.php\" ".$onsubmit.">".
	"<input type=hidden name=\"a\" value=\"edited\">";
	$body["begin"].="<input type=\"hidden\" name=\"editid1\" value=\"".htmlspecialchars($keys["n_id"])."\">";
		$xt->assign("show_key1", htmlspecialchars(GetData($data,"n_id", "")));

	$xt->assign("backbutton_attrs","onclick=\"window.location.href='news_list.php?a=return'\"");
	$xt->assign("save_button",true);
	$xt->assign("reset_button",true);
	$xt->assign("back_button",true);
}

$showKeys[] = rawurlencode($keys["n_id"]);

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
$control_n_active=array();
$control_n_active["func"]="xt_buildeditcontrol";
$control_n_active["params"] = array();
$control_n_active["params"]["field"]="n_active";
$control_n_active["params"]["value"]=@$data["n_active"];
$control_n_active["params"]["id"]=$record_id;
if($inlineedit)
	$control_n_active["params"]["mode"]="inline_edit";
else
	$control_n_active["params"]["mode"]="edit";
$xt->assignbyref("n_active_editcontrol",$control_n_active);
$control_n_start=array();
$control_n_start["func"]="xt_buildeditcontrol";
$control_n_start["params"] = array();
$control_n_start["params"]["field"]="n_start";
$control_n_start["params"]["value"]=@$data["n_start"];
$control_n_start["params"]["id"]=$record_id;
if($inlineedit)
	$control_n_start["params"]["mode"]="inline_edit";
else
	$control_n_start["params"]["mode"]="edit";
$xt->assignbyref("n_start_editcontrol",$control_n_start);
$control_n_end=array();
$control_n_end["func"]="xt_buildeditcontrol";
$control_n_end["params"] = array();
$control_n_end["params"]["field"]="n_end";
$control_n_end["params"]["value"]=@$data["n_end"];
$control_n_end["params"]["id"]=$record_id;
if($inlineedit)
	$control_n_end["params"]["mode"]="inline_edit";
else
	$control_n_end["params"]["mode"]="edit";
$xt->assignbyref("n_end_editcontrol",$control_n_end);
$control_n_date=array();
$control_n_date["func"]="xt_buildeditcontrol";
$control_n_date["params"] = array();
$control_n_date["params"]["field"]="n_date";
$control_n_date["params"]["value"]=@$data["n_date"];
$control_n_date["params"]["id"]=$record_id;
if($inlineedit)
	$control_n_date["params"]["mode"]="inline_edit";
else
	$control_n_date["params"]["mode"]="edit";
$xt->assignbyref("n_date_editcontrol",$control_n_date);
$control_n_head=array();
$control_n_head["func"]="xt_buildeditcontrol";
$control_n_head["params"] = array();
$control_n_head["params"]["field"]="n_head";
$control_n_head["params"]["value"]=@$data["n_head"];
$control_n_head["params"]["id"]=$record_id;
if($inlineedit)
	$control_n_head["params"]["mode"]="inline_edit";
else
	$control_n_head["params"]["mode"]="edit";
$xt->assignbyref("n_head_editcontrol",$control_n_head);
$control_n_text=array();
$control_n_text["func"]="xt_buildeditcontrol";
$control_n_text["params"] = array();
$control_n_text["params"]["field"]="n_text";
$control_n_text["params"]["value"]=@$data["n_text"];
$control_n_text["params"]["id"]=$record_id;
if($inlineedit)
	$control_n_text["params"]["mode"]="inline_edit";
else
	$control_n_text["params"]["mode"]="edit";
$xt->assignbyref("n_text_editcontrol",$control_n_text);
$control_n_file=array();
$control_n_file["func"]="xt_buildeditcontrol";
$control_n_file["params"] = array();
$control_n_file["params"]["field"]="n_file";
$control_n_file["params"]["value"]=@$data["n_file"];
$control_n_file["params"]["id"]=$record_id;
if($inlineedit)
	$control_n_file["params"]["mode"]="inline_edit";
else
	$control_n_file["params"]["mode"]="edit";
$xt->assignbyref("n_file_editcontrol",$control_n_file);
$control_n_type=array();
$control_n_type["func"]="xt_buildeditcontrol";
$control_n_type["params"] = array();
$control_n_type["params"]["field"]="n_type";
$control_n_type["params"]["value"]=@$data["n_type"];
$control_n_type["params"]["id"]=$record_id;
if($inlineedit)
	$control_n_type["params"]["mode"]="inline_edit";
else
	$control_n_type["params"]["mode"]="edit";
$xt->assignbyref("n_type_editcontrol",$control_n_type);
$control_n_teaser=array();
$control_n_teaser["func"]="xt_buildeditcontrol";
$control_n_teaser["params"] = array();
$control_n_teaser["params"]["field"]="n_teaser";
$control_n_teaser["params"]["value"]=@$data["n_teaser"];
$control_n_teaser["params"]["id"]=$record_id;
if($inlineedit)
	$control_n_teaser["params"]["mode"]="inline_edit";
else
	$control_n_teaser["params"]["mode"]="edit";
$xt->assignbyref("n_teaser_editcontrol",$control_n_teaser);
$control_n_country=array();
$control_n_country["func"]="xt_buildeditcontrol";
$control_n_country["params"] = array();
$control_n_country["params"]["field"]="n_country";
$control_n_country["params"]["value"]=@$data["n_country"];
$control_n_country["params"]["id"]=$record_id;
if($inlineedit)
	$control_n_country["params"]["mode"]="inline_edit";
else
	$control_n_country["params"]["mode"]="edit";
$xt->assignbyref("n_country_editcontrol",$control_n_country);

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