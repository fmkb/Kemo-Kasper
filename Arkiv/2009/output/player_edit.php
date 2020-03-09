<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/player_variables.php");
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
$templatefile = "player_edit.htm";

/////////////////////////////////////////////////////////////
//connect database
/////////////////////////////////////////////////////////////
$conn = db_connect();

//	Before Process event
if(function_exists("BeforeProcessEdit"))
	BeforeProcessEdit($conn);

$keys=array();
$keys["p_id"]=postvalue("editid1");

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
//	processing p_active - start
	if(!$inlineedit)
	{
	$value = postvalue("value_p_active");
	$type=postvalue("type_p_active");
	if (in_assoc_array("type_p_active",$_POST) || in_assoc_array("value_p_active",$_POST) || in_assoc_array("value_p_active",$_FILES))	
	{
		$value=prepare_for_db("p_active",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["p_active"]=$value;
	}


//	processibng p_active - end
	}
//	processing p_s_id - start
	if(!$inlineedit)
	{
	$value = postvalue("value_p_s_id");
	$type=postvalue("type_p_s_id");
	if (in_assoc_array("type_p_s_id",$_POST) || in_assoc_array("value_p_s_id",$_POST) || in_assoc_array("value_p_s_id",$_FILES))	
	{
		$value=prepare_for_db("p_s_id",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["p_s_id"]=$value;
	}


//	processibng p_s_id - end
	}
//	processing p_first - start
	if(!$inlineedit)
	{
	$value = postvalue("value_p_first");
	$type=postvalue("type_p_first");
	if (in_assoc_array("type_p_first",$_POST) || in_assoc_array("value_p_first",$_POST) || in_assoc_array("value_p_first",$_FILES))	
	{
		$value=prepare_for_db("p_first",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["p_first"]=$value;
	}


//	processibng p_first - end
	}
//	processing p_name - start
	if(!$inlineedit)
	{
	$value = postvalue("value_p_name");
	$type=postvalue("type_p_name");
	if (in_assoc_array("type_p_name",$_POST) || in_assoc_array("value_p_name",$_POST) || in_assoc_array("value_p_name",$_FILES))	
	{
		$value=prepare_for_db("p_name",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["p_name"]=$value;
	}


//	processibng p_name - end
	}
//	processing p_adr - start
	if(!$inlineedit)
	{
	$value = postvalue("value_p_adr");
	$type=postvalue("type_p_adr");
	if (in_assoc_array("type_p_adr",$_POST) || in_assoc_array("value_p_adr",$_POST) || in_assoc_array("value_p_adr",$_FILES))	
	{
		$value=prepare_for_db("p_adr",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["p_adr"]=$value;
	}


//	processibng p_adr - end
	}
//	processing p_zip - start
	if(!$inlineedit)
	{
	$value = postvalue("value_p_zip");
	$type=postvalue("type_p_zip");
	if (in_assoc_array("type_p_zip",$_POST) || in_assoc_array("value_p_zip",$_POST) || in_assoc_array("value_p_zip",$_FILES))	
	{
		$value=prepare_for_db("p_zip",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["p_zip"]=$value;
	}


//	processibng p_zip - end
	}
//	processing p_mail - start
	if(!$inlineedit)
	{
	$value = postvalue("value_p_mail");
	$type=postvalue("type_p_mail");
	if (in_assoc_array("type_p_mail",$_POST) || in_assoc_array("value_p_mail",$_POST) || in_assoc_array("value_p_mail",$_FILES))	
	{
		$value=prepare_for_db("p_mail",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["p_mail"]=$value;
	}


//	processibng p_mail - end
	}
//	processing p_newsaccept - start
	if(!$inlineedit)
	{
	$value = postvalue("value_p_newsaccept");
	$type=postvalue("type_p_newsaccept");
	if (in_assoc_array("type_p_newsaccept",$_POST) || in_assoc_array("value_p_newsaccept",$_POST) || in_assoc_array("value_p_newsaccept",$_FILES))	
	{
		$value=prepare_for_db("p_newsaccept",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["p_newsaccept"]=$value;
	}


//	processibng p_newsaccept - end
	}
//	processing p_win - start
	if(!$inlineedit)
	{
	$value = postvalue("value_p_win");
	$type=postvalue("type_p_win");
	if (in_assoc_array("type_p_win",$_POST) || in_assoc_array("value_p_win",$_POST) || in_assoc_array("value_p_win",$_FILES))	
	{
		$value=prepare_for_db("p_win",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["p_win"]=$value;
	}


//	processibng p_win - end
	}
//	processing p_mk - start
	if(!$inlineedit)
	{
	$value = postvalue("value_p_mk");
	$type=postvalue("type_p_mk");
	if (in_assoc_array("type_p_mk",$_POST) || in_assoc_array("value_p_mk",$_POST) || in_assoc_array("value_p_mk",$_FILES))	
	{
		$value=prepare_for_db("p_mk",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["p_mk"]=$value;
	}


//	processibng p_mk - end
	}
//	processing p_born - start
	if(!$inlineedit)
	{
	$value = postvalue("value_p_born");
	$type=postvalue("type_p_born");
	if (in_assoc_array("type_p_born",$_POST) || in_assoc_array("value_p_born",$_POST) || in_assoc_array("value_p_born",$_FILES))	
	{
		$value=prepare_for_db("p_born",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["p_born"]=$value;
	}


//	processibng p_born - end
	}
//	processing p_user - start
	if(!$inlineedit)
	{
	$value = postvalue("value_p_user");
	$type=postvalue("type_p_user");
	if (in_assoc_array("type_p_user",$_POST) || in_assoc_array("value_p_user",$_POST) || in_assoc_array("value_p_user",$_FILES))	
	{
		$value=prepare_for_db("p_user",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["p_user"]=$value;
	}


//	processibng p_user - end
	}
//	processing p_pwd - start
	if(!$inlineedit)
	{
	$value = postvalue("value_p_pwd");
	$type=postvalue("type_p_pwd");
	if (in_assoc_array("type_p_pwd",$_POST) || in_assoc_array("value_p_pwd",$_POST) || in_assoc_array("value_p_pwd",$_FILES))	
	{
		$value=prepare_for_db("p_pwd",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["p_pwd"]=$value;
	}


//	processibng p_pwd - end
	}
//	processing p_country - start
	if(!$inlineedit)
	{
	$value = postvalue("value_p_country");
	$type=postvalue("type_p_country");
	if (in_assoc_array("type_p_country",$_POST) || in_assoc_array("value_p_country",$_POST) || in_assoc_array("value_p_country",$_FILES))	
	{
		$value=prepare_for_db("p_country",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["p_country"]=$value;
	}


//	processibng p_country - end
	}
//	processing p_mobile - start
	if(!$inlineedit)
	{
	$value = postvalue("value_p_mobile");
	$type=postvalue("type_p_mobile");
	if (in_assoc_array("type_p_mobile",$_POST) || in_assoc_array("value_p_mobile",$_POST) || in_assoc_array("value_p_mobile",$_FILES))	
	{
		$value=prepare_for_db("p_mobile",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["p_mobile"]=$value;
	}


//	processibng p_mobile - end
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
	$data["p_active"]=$evalues["p_active"];
	$data["p_s_id"]=$evalues["p_s_id"];
	$data["p_first"]=$evalues["p_first"];
	$data["p_name"]=$evalues["p_name"];
	$data["p_adr"]=$evalues["p_adr"];
	$data["p_zip"]=$evalues["p_zip"];
	$data["p_mail"]=$evalues["p_mail"];
	$data["p_newsaccept"]=$evalues["p_newsaccept"];
	$data["p_win"]=$evalues["p_win"];
	$data["p_mk"]=$evalues["p_mk"];
	$data["p_born"]=$evalues["p_born"];
	$data["p_user"]=$evalues["p_user"];
	$data["p_pwd"]=$evalues["p_pwd"];
	$data["p_country"]=$evalues["p_country"];
	$data["p_mobile"]=$evalues["p_mobile"];
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
			$bodyonload.="define('value_p_active','".$validatetype."','Status');";
				$validatetype="";
			if($validatetype)
			$bodyonload.="define('value_p_s_id','".$validatetype."','Team');";
			  		$validatetype="IsNumeric";
			if($validatetype)
			$bodyonload.="define('value_p_zip','".$validatetype."','Zip');";
				$validatetype="";
			if($validatetype)
			$bodyonload.="define('value_p_newsaccept','".$validatetype."','Newsaccept');";
			  		$validatetype="IsNumeric";
			if($validatetype)
			$bodyonload.="define('value_p_win','".$validatetype."','Winner');";
			  		$validatetype="IsNumeric";
			if($validatetype)
			$bodyonload.="define('value_p_mobile','".$validatetype."','Mobile');";

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
	$includes.="var SUGGEST_TABLE='player_searchsuggest.php';\r\n";
	}
	$includes.="</script>\r\n";

	if ($useAJAX)
		$includes.="<div id=\"search_suggest\"></div>\r\n";





	$xt->assign("p_active_fieldblock",true);
	$xt->assign("p_s_id_fieldblock",true);
	$xt->assign("p_first_fieldblock",true);
	$xt->assign("p_name_fieldblock",true);
	$xt->assign("p_adr_fieldblock",true);
	$xt->assign("p_zip_fieldblock",true);
	$xt->assign("p_mail_fieldblock",true);
	$xt->assign("p_newsaccept_fieldblock",true);
	$xt->assign("p_win_fieldblock",true);
	$xt->assign("p_mk_fieldblock",true);
	$xt->assign("p_born_fieldblock",true);
	$xt->assign("p_user_fieldblock",true);
	$xt->assign("p_pwd_fieldblock",true);
	$xt->assign("p_country_fieldblock",true);
	$xt->assign("p_mobile_fieldblock",true);

	if(strlen($onsubmit))
		$onsubmit="onSubmit=\"".$onsubmit."\"";
	$body["begin"]=$includes."
	<form name=\"editform\" encType=\"multipart/form-data\" method=\"post\" action=\"player_edit.php\" ".$onsubmit.">".
	"<input type=hidden name=\"a\" value=\"edited\">";
	$body["begin"].="<input type=\"hidden\" name=\"editid1\" value=\"".htmlspecialchars($keys["p_id"])."\">";
		$xt->assign("show_key1", htmlspecialchars(GetData($data,"p_id", "")));

	$xt->assign("backbutton_attrs","onclick=\"window.location.href='player_list.php?a=return'\"");
	$xt->assign("save_button",true);
	$xt->assign("reset_button",true);
	$xt->assign("back_button",true);
}

$showKeys[] = rawurlencode($keys["p_id"]);

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
$control_p_active=array();
$control_p_active["func"]="xt_buildeditcontrol";
$control_p_active["params"] = array();
$control_p_active["params"]["field"]="p_active";
$control_p_active["params"]["value"]=@$data["p_active"];
$control_p_active["params"]["id"]=$record_id;
if($inlineedit)
	$control_p_active["params"]["mode"]="inline_edit";
else
	$control_p_active["params"]["mode"]="edit";
$xt->assignbyref("p_active_editcontrol",$control_p_active);
$control_p_s_id=array();
$control_p_s_id["func"]="xt_buildeditcontrol";
$control_p_s_id["params"] = array();
$control_p_s_id["params"]["field"]="p_s_id";
$control_p_s_id["params"]["value"]=@$data["p_s_id"];
$control_p_s_id["params"]["id"]=$record_id;
if($inlineedit)
	$control_p_s_id["params"]["mode"]="inline_edit";
else
	$control_p_s_id["params"]["mode"]="edit";
$xt->assignbyref("p_s_id_editcontrol",$control_p_s_id);
$control_p_first=array();
$control_p_first["func"]="xt_buildeditcontrol";
$control_p_first["params"] = array();
$control_p_first["params"]["field"]="p_first";
$control_p_first["params"]["value"]=@$data["p_first"];
$control_p_first["params"]["id"]=$record_id;
if($inlineedit)
	$control_p_first["params"]["mode"]="inline_edit";
else
	$control_p_first["params"]["mode"]="edit";
$xt->assignbyref("p_first_editcontrol",$control_p_first);
$control_p_name=array();
$control_p_name["func"]="xt_buildeditcontrol";
$control_p_name["params"] = array();
$control_p_name["params"]["field"]="p_name";
$control_p_name["params"]["value"]=@$data["p_name"];
$control_p_name["params"]["id"]=$record_id;
if($inlineedit)
	$control_p_name["params"]["mode"]="inline_edit";
else
	$control_p_name["params"]["mode"]="edit";
$xt->assignbyref("p_name_editcontrol",$control_p_name);
$control_p_adr=array();
$control_p_adr["func"]="xt_buildeditcontrol";
$control_p_adr["params"] = array();
$control_p_adr["params"]["field"]="p_adr";
$control_p_adr["params"]["value"]=@$data["p_adr"];
$control_p_adr["params"]["id"]=$record_id;
if($inlineedit)
	$control_p_adr["params"]["mode"]="inline_edit";
else
	$control_p_adr["params"]["mode"]="edit";
$xt->assignbyref("p_adr_editcontrol",$control_p_adr);
$control_p_zip=array();
$control_p_zip["func"]="xt_buildeditcontrol";
$control_p_zip["params"] = array();
$control_p_zip["params"]["field"]="p_zip";
$control_p_zip["params"]["value"]=@$data["p_zip"];
$control_p_zip["params"]["id"]=$record_id;
if($inlineedit)
	$control_p_zip["params"]["mode"]="inline_edit";
else
	$control_p_zip["params"]["mode"]="edit";
$xt->assignbyref("p_zip_editcontrol",$control_p_zip);
$control_p_mail=array();
$control_p_mail["func"]="xt_buildeditcontrol";
$control_p_mail["params"] = array();
$control_p_mail["params"]["field"]="p_mail";
$control_p_mail["params"]["value"]=@$data["p_mail"];
$control_p_mail["params"]["id"]=$record_id;
if($inlineedit)
	$control_p_mail["params"]["mode"]="inline_edit";
else
	$control_p_mail["params"]["mode"]="edit";
$xt->assignbyref("p_mail_editcontrol",$control_p_mail);
$control_p_newsaccept=array();
$control_p_newsaccept["func"]="xt_buildeditcontrol";
$control_p_newsaccept["params"] = array();
$control_p_newsaccept["params"]["field"]="p_newsaccept";
$control_p_newsaccept["params"]["value"]=@$data["p_newsaccept"];
$control_p_newsaccept["params"]["id"]=$record_id;
if($inlineedit)
	$control_p_newsaccept["params"]["mode"]="inline_edit";
else
	$control_p_newsaccept["params"]["mode"]="edit";
$xt->assignbyref("p_newsaccept_editcontrol",$control_p_newsaccept);
$control_p_win=array();
$control_p_win["func"]="xt_buildeditcontrol";
$control_p_win["params"] = array();
$control_p_win["params"]["field"]="p_win";
$control_p_win["params"]["value"]=@$data["p_win"];
$control_p_win["params"]["id"]=$record_id;
if($inlineedit)
	$control_p_win["params"]["mode"]="inline_edit";
else
	$control_p_win["params"]["mode"]="edit";
$xt->assignbyref("p_win_editcontrol",$control_p_win);
$control_p_mk=array();
$control_p_mk["func"]="xt_buildeditcontrol";
$control_p_mk["params"] = array();
$control_p_mk["params"]["field"]="p_mk";
$control_p_mk["params"]["value"]=@$data["p_mk"];
$control_p_mk["params"]["id"]=$record_id;
if($inlineedit)
	$control_p_mk["params"]["mode"]="inline_edit";
else
	$control_p_mk["params"]["mode"]="edit";
$xt->assignbyref("p_mk_editcontrol",$control_p_mk);
$control_p_born=array();
$control_p_born["func"]="xt_buildeditcontrol";
$control_p_born["params"] = array();
$control_p_born["params"]["field"]="p_born";
$control_p_born["params"]["value"]=@$data["p_born"];
$control_p_born["params"]["id"]=$record_id;
if($inlineedit)
	$control_p_born["params"]["mode"]="inline_edit";
else
	$control_p_born["params"]["mode"]="edit";
$xt->assignbyref("p_born_editcontrol",$control_p_born);
$control_p_user=array();
$control_p_user["func"]="xt_buildeditcontrol";
$control_p_user["params"] = array();
$control_p_user["params"]["field"]="p_user";
$control_p_user["params"]["value"]=@$data["p_user"];
$control_p_user["params"]["id"]=$record_id;
if($inlineedit)
	$control_p_user["params"]["mode"]="inline_edit";
else
	$control_p_user["params"]["mode"]="edit";
$xt->assignbyref("p_user_editcontrol",$control_p_user);
$control_p_pwd=array();
$control_p_pwd["func"]="xt_buildeditcontrol";
$control_p_pwd["params"] = array();
$control_p_pwd["params"]["field"]="p_pwd";
$control_p_pwd["params"]["value"]=@$data["p_pwd"];
$control_p_pwd["params"]["id"]=$record_id;
if($inlineedit)
	$control_p_pwd["params"]["mode"]="inline_edit";
else
	$control_p_pwd["params"]["mode"]="edit";
$xt->assignbyref("p_pwd_editcontrol",$control_p_pwd);
$control_p_country=array();
$control_p_country["func"]="xt_buildeditcontrol";
$control_p_country["params"] = array();
$control_p_country["params"]["field"]="p_country";
$control_p_country["params"]["value"]=@$data["p_country"];
$control_p_country["params"]["id"]=$record_id;
if($inlineedit)
	$control_p_country["params"]["mode"]="inline_edit";
else
	$control_p_country["params"]["mode"]="edit";
$xt->assignbyref("p_country_editcontrol",$control_p_country);
$control_p_mobile=array();
$control_p_mobile["func"]="xt_buildeditcontrol";
$control_p_mobile["params"] = array();
$control_p_mobile["params"]["field"]="p_mobile";
$control_p_mobile["params"]["value"]=@$data["p_mobile"];
$control_p_mobile["params"]["id"]=$record_id;
if($inlineedit)
	$control_p_mobile["params"]["mode"]="inline_edit";
else
	$control_p_mobile["params"]["mode"]="edit";
$xt->assignbyref("p_mobile_editcontrol",$control_p_mobile);

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