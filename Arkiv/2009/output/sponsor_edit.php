<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/sponsor_variables.php");
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
$templatefile = "sponsor_edit.htm";

/////////////////////////////////////////////////////////////
//connect database
/////////////////////////////////////////////////////////////
$conn = db_connect();

//	Before Process event
if(function_exists("BeforeProcessEdit"))
	BeforeProcessEdit($conn);

$keys=array();
$keys["s_id"]=postvalue("editid1");

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
//	processing s_active - start
	if(!$inlineedit)
	{
	$value = postvalue("value_s_active");
	$type=postvalue("type_s_active");
	if (in_assoc_array("type_s_active",$_POST) || in_assoc_array("value_s_active",$_POST) || in_assoc_array("value_s_active",$_FILES))	
	{
		$value=prepare_for_db("s_active",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["s_active"]=$value;
	}


//	processibng s_active - end
	}
//	processing s_name - start
	if(!$inlineedit)
	{
	$value = postvalue("value_s_name");
	$type=postvalue("type_s_name");
	if (in_assoc_array("type_s_name",$_POST) || in_assoc_array("value_s_name",$_POST) || in_assoc_array("value_s_name",$_FILES))	
	{
		$value=prepare_for_db("s_name",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["s_name"]=$value;
	}


//	processibng s_name - end
	}
//	processing s_contact - start
	if(!$inlineedit)
	{
	$value = postvalue("value_s_contact");
	$type=postvalue("type_s_contact");
	if (in_assoc_array("type_s_contact",$_POST) || in_assoc_array("value_s_contact",$_POST) || in_assoc_array("value_s_contact",$_FILES))	
	{
		$value=prepare_for_db("s_contact",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["s_contact"]=$value;
	}


//	processibng s_contact - end
	}
//	processing s_adr - start
	if(!$inlineedit)
	{
	$value = postvalue("value_s_adr");
	$type=postvalue("type_s_adr");
	if (in_assoc_array("type_s_adr",$_POST) || in_assoc_array("value_s_adr",$_POST) || in_assoc_array("value_s_adr",$_FILES))	
	{
		$value=prepare_for_db("s_adr",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["s_adr"]=$value;
	}


//	processibng s_adr - end
	}
//	processing s_zip - start
	if(!$inlineedit)
	{
	$value = postvalue("value_s_zip");
	$type=postvalue("type_s_zip");
	if (in_assoc_array("type_s_zip",$_POST) || in_assoc_array("value_s_zip",$_POST) || in_assoc_array("value_s_zip",$_FILES))	
	{
		$value=prepare_for_db("s_zip",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["s_zip"]=$value;
	}


//	processibng s_zip - end
	}
//	processing s_phone1 - start
	if(!$inlineedit)
	{
	$value = postvalue("value_s_phone1");
	$type=postvalue("type_s_phone1");
	if (in_assoc_array("type_s_phone1",$_POST) || in_assoc_array("value_s_phone1",$_POST) || in_assoc_array("value_s_phone1",$_FILES))	
	{
		$value=prepare_for_db("s_phone1",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["s_phone1"]=$value;
	}


//	processibng s_phone1 - end
	}
//	processing s_phone2 - start
	if(!$inlineedit)
	{
	$value = postvalue("value_s_phone2");
	$type=postvalue("type_s_phone2");
	if (in_assoc_array("type_s_phone2",$_POST) || in_assoc_array("value_s_phone2",$_POST) || in_assoc_array("value_s_phone2",$_FILES))	
	{
		$value=prepare_for_db("s_phone2",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["s_phone2"]=$value;
	}


//	processibng s_phone2 - end
	}
//	processing s_total - start
	if(!$inlineedit)
	{
	$value = postvalue("value_s_total");
	$type=postvalue("type_s_total");
	if (in_assoc_array("type_s_total",$_POST) || in_assoc_array("value_s_total",$_POST) || in_assoc_array("value_s_total",$_FILES))	
	{
		$value=prepare_for_db("s_total",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["s_total"]=$value;
	}


//	processibng s_total - end
	}
//	processing s_paid - start
	if(!$inlineedit)
	{
	$value = postvalue("value_s_paid");
	$type=postvalue("type_s_paid");
	if (in_assoc_array("type_s_paid",$_POST) || in_assoc_array("value_s_paid",$_POST) || in_assoc_array("value_s_paid",$_FILES))	
	{
		$value=prepare_for_db("s_paid",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["s_paid"]=$value;
	}


//	processibng s_paid - end
	}
//	processing s_logo - start
	if(!$inlineedit)
	{
	$value = postvalue("value_s_logo");
	$type=postvalue("type_s_logo");
	if (in_assoc_array("type_s_logo",$_POST) || in_assoc_array("value_s_logo",$_POST) || in_assoc_array("value_s_logo",$_FILES))	
	{
		$value=prepare_for_db("s_logo",$value,$type,postvalue("filename_s_logo"));
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["s_logo"]=$value;
	}


//	processibng s_logo - end
	}
//	processing s_banner - start
	if(!$inlineedit)
	{
	$value = postvalue("value_s_banner");
	$type=postvalue("type_s_banner");
	if (in_assoc_array("type_s_banner",$_POST) || in_assoc_array("value_s_banner",$_POST) || in_assoc_array("value_s_banner",$_FILES))	
	{
		$value=prepare_for_db("s_banner",$value,$type,postvalue("filename_s_banner"));
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["s_banner"]=$value;
	}


//	processibng s_banner - end
	}
//	processing s_www - start
	if(!$inlineedit)
	{
	$value = postvalue("value_s_www");
	$type=postvalue("type_s_www");
	if (in_assoc_array("type_s_www",$_POST) || in_assoc_array("value_s_www",$_POST) || in_assoc_array("value_s_www",$_FILES))	
	{
		$value=prepare_for_db("s_www",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["s_www"]=$value;
	}


//	processibng s_www - end
	}
//	processing s_mail - start
	if(!$inlineedit)
	{
	$value = postvalue("value_s_mail");
	$type=postvalue("type_s_mail");
	if (in_assoc_array("type_s_mail",$_POST) || in_assoc_array("value_s_mail",$_POST) || in_assoc_array("value_s_mail",$_FILES))	
	{
		$value=prepare_for_db("s_mail",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["s_mail"]=$value;
	}


//	processibng s_mail - end
	}
//	processing s_cmt - start
	if(!$inlineedit)
	{
	$value = postvalue("value_s_cmt");
	$type=postvalue("type_s_cmt");
	if (in_assoc_array("type_s_cmt",$_POST) || in_assoc_array("value_s_cmt",$_POST) || in_assoc_array("value_s_cmt",$_FILES))	
	{
		$value=prepare_for_db("s_cmt",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["s_cmt"]=$value;
	}


//	processibng s_cmt - end
	}
//	processing s_country - start
	if(!$inlineedit)
	{
	$value = postvalue("value_s_country");
	$type=postvalue("type_s_country");
	if (in_assoc_array("type_s_country",$_POST) || in_assoc_array("value_s_country",$_POST) || in_assoc_array("value_s_country",$_FILES))	
	{
		$value=prepare_for_db("s_country",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	


		$evalues["s_country"]=$value;
	}


//	processibng s_country - end
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
	$data["s_active"]=$evalues["s_active"];
	$data["s_name"]=$evalues["s_name"];
	$data["s_contact"]=$evalues["s_contact"];
	$data["s_adr"]=$evalues["s_adr"];
	$data["s_zip"]=$evalues["s_zip"];
	$data["s_phone1"]=$evalues["s_phone1"];
	$data["s_phone2"]=$evalues["s_phone2"];
	$data["s_total"]=$evalues["s_total"];
	$data["s_paid"]=$evalues["s_paid"];
	$data["s_www"]=$evalues["s_www"];
	$data["s_mail"]=$evalues["s_mail"];
	$data["s_cmt"]=$evalues["s_cmt"];
	$data["s_country"]=$evalues["s_country"];
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
			$bodyonload.="define('value_s_active','".$validatetype."','Status');";
			  		$validatetype="IsNumeric";
			if($validatetype)
			$bodyonload.="define('value_s_zip','".$validatetype."','Zip');";
			  		$validatetype="IsNumeric";
			if($validatetype)
			$bodyonload.="define('value_s_total','".$validatetype."','Amount Sponsored');";
			  		$validatetype="IsNumeric";
			if($validatetype)
			$bodyonload.="define('value_s_paid','".$validatetype."','Amount Paid');";

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
	$includes.="var SUGGEST_TABLE='sponsor_searchsuggest.php';\r\n";
	}
	$includes.="</script>\r\n";

	if ($useAJAX)
		$includes.="<div id=\"search_suggest\"></div>\r\n";





	$xt->assign("s_active_fieldblock",true);
	$xt->assign("s_name_fieldblock",true);
	$xt->assign("s_contact_fieldblock",true);
	$xt->assign("s_adr_fieldblock",true);
	$xt->assign("s_zip_fieldblock",true);
	$xt->assign("s_phone1_fieldblock",true);
	$xt->assign("s_phone2_fieldblock",true);
	$xt->assign("s_total_fieldblock",true);
	$xt->assign("s_paid_fieldblock",true);
	$xt->assign("s_logo_fieldblock",true);
	$xt->assign("s_banner_fieldblock",true);
	$xt->assign("s_www_fieldblock",true);
	$xt->assign("s_mail_fieldblock",true);
	$xt->assign("s_cmt_fieldblock",true);
	$xt->assign("s_country_fieldblock",true);

	if(strlen($onsubmit))
		$onsubmit="onSubmit=\"".$onsubmit."\"";
	$body["begin"]=$includes."
	<form name=\"editform\" encType=\"multipart/form-data\" method=\"post\" action=\"sponsor_edit.php\" ".$onsubmit.">".
	"<input type=hidden name=\"a\" value=\"edited\">";
	$body["begin"].="<input type=\"hidden\" name=\"editid1\" value=\"".htmlspecialchars($keys["s_id"])."\">";
		$xt->assign("show_key1", htmlspecialchars(GetData($data,"s_id", "")));

	$xt->assign("backbutton_attrs","onclick=\"window.location.href='sponsor_list.php?a=return'\"");
	$xt->assign("save_button",true);
	$xt->assign("reset_button",true);
	$xt->assign("back_button",true);
}

$showKeys[] = rawurlencode($keys["s_id"]);

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
$control_s_active=array();
$control_s_active["func"]="xt_buildeditcontrol";
$control_s_active["params"] = array();
$control_s_active["params"]["field"]="s_active";
$control_s_active["params"]["value"]=@$data["s_active"];
$control_s_active["params"]["id"]=$record_id;
if($inlineedit)
	$control_s_active["params"]["mode"]="inline_edit";
else
	$control_s_active["params"]["mode"]="edit";
$xt->assignbyref("s_active_editcontrol",$control_s_active);
$control_s_name=array();
$control_s_name["func"]="xt_buildeditcontrol";
$control_s_name["params"] = array();
$control_s_name["params"]["field"]="s_name";
$control_s_name["params"]["value"]=@$data["s_name"];
$control_s_name["params"]["id"]=$record_id;
if($inlineedit)
	$control_s_name["params"]["mode"]="inline_edit";
else
	$control_s_name["params"]["mode"]="edit";
$xt->assignbyref("s_name_editcontrol",$control_s_name);
$control_s_contact=array();
$control_s_contact["func"]="xt_buildeditcontrol";
$control_s_contact["params"] = array();
$control_s_contact["params"]["field"]="s_contact";
$control_s_contact["params"]["value"]=@$data["s_contact"];
$control_s_contact["params"]["id"]=$record_id;
if($inlineedit)
	$control_s_contact["params"]["mode"]="inline_edit";
else
	$control_s_contact["params"]["mode"]="edit";
$xt->assignbyref("s_contact_editcontrol",$control_s_contact);
$control_s_adr=array();
$control_s_adr["func"]="xt_buildeditcontrol";
$control_s_adr["params"] = array();
$control_s_adr["params"]["field"]="s_adr";
$control_s_adr["params"]["value"]=@$data["s_adr"];
$control_s_adr["params"]["id"]=$record_id;
if($inlineedit)
	$control_s_adr["params"]["mode"]="inline_edit";
else
	$control_s_adr["params"]["mode"]="edit";
$xt->assignbyref("s_adr_editcontrol",$control_s_adr);
$control_s_zip=array();
$control_s_zip["func"]="xt_buildeditcontrol";
$control_s_zip["params"] = array();
$control_s_zip["params"]["field"]="s_zip";
$control_s_zip["params"]["value"]=@$data["s_zip"];
$control_s_zip["params"]["id"]=$record_id;
if($inlineedit)
	$control_s_zip["params"]["mode"]="inline_edit";
else
	$control_s_zip["params"]["mode"]="edit";
$xt->assignbyref("s_zip_editcontrol",$control_s_zip);
$control_s_phone1=array();
$control_s_phone1["func"]="xt_buildeditcontrol";
$control_s_phone1["params"] = array();
$control_s_phone1["params"]["field"]="s_phone1";
$control_s_phone1["params"]["value"]=@$data["s_phone1"];
$control_s_phone1["params"]["id"]=$record_id;
if($inlineedit)
	$control_s_phone1["params"]["mode"]="inline_edit";
else
	$control_s_phone1["params"]["mode"]="edit";
$xt->assignbyref("s_phone1_editcontrol",$control_s_phone1);
$control_s_phone2=array();
$control_s_phone2["func"]="xt_buildeditcontrol";
$control_s_phone2["params"] = array();
$control_s_phone2["params"]["field"]="s_phone2";
$control_s_phone2["params"]["value"]=@$data["s_phone2"];
$control_s_phone2["params"]["id"]=$record_id;
if($inlineedit)
	$control_s_phone2["params"]["mode"]="inline_edit";
else
	$control_s_phone2["params"]["mode"]="edit";
$xt->assignbyref("s_phone2_editcontrol",$control_s_phone2);
$control_s_total=array();
$control_s_total["func"]="xt_buildeditcontrol";
$control_s_total["params"] = array();
$control_s_total["params"]["field"]="s_total";
$control_s_total["params"]["value"]=@$data["s_total"];
$control_s_total["params"]["id"]=$record_id;
if($inlineedit)
	$control_s_total["params"]["mode"]="inline_edit";
else
	$control_s_total["params"]["mode"]="edit";
$xt->assignbyref("s_total_editcontrol",$control_s_total);
$control_s_paid=array();
$control_s_paid["func"]="xt_buildeditcontrol";
$control_s_paid["params"] = array();
$control_s_paid["params"]["field"]="s_paid";
$control_s_paid["params"]["value"]=@$data["s_paid"];
$control_s_paid["params"]["id"]=$record_id;
if($inlineedit)
	$control_s_paid["params"]["mode"]="inline_edit";
else
	$control_s_paid["params"]["mode"]="edit";
$xt->assignbyref("s_paid_editcontrol",$control_s_paid);
$control_s_logo=array();
$control_s_logo["func"]="xt_buildeditcontrol";
$control_s_logo["params"] = array();
$control_s_logo["params"]["field"]="s_logo";
$control_s_logo["params"]["value"]=@$data["s_logo"];
$control_s_logo["params"]["id"]=$record_id;
if($inlineedit)
	$control_s_logo["params"]["mode"]="inline_edit";
else
	$control_s_logo["params"]["mode"]="edit";
$xt->assignbyref("s_logo_editcontrol",$control_s_logo);
$control_s_banner=array();
$control_s_banner["func"]="xt_buildeditcontrol";
$control_s_banner["params"] = array();
$control_s_banner["params"]["field"]="s_banner";
$control_s_banner["params"]["value"]=@$data["s_banner"];
$control_s_banner["params"]["id"]=$record_id;
if($inlineedit)
	$control_s_banner["params"]["mode"]="inline_edit";
else
	$control_s_banner["params"]["mode"]="edit";
$xt->assignbyref("s_banner_editcontrol",$control_s_banner);
$control_s_www=array();
$control_s_www["func"]="xt_buildeditcontrol";
$control_s_www["params"] = array();
$control_s_www["params"]["field"]="s_www";
$control_s_www["params"]["value"]=@$data["s_www"];
$control_s_www["params"]["id"]=$record_id;
if($inlineedit)
	$control_s_www["params"]["mode"]="inline_edit";
else
	$control_s_www["params"]["mode"]="edit";
$xt->assignbyref("s_www_editcontrol",$control_s_www);
$control_s_mail=array();
$control_s_mail["func"]="xt_buildeditcontrol";
$control_s_mail["params"] = array();
$control_s_mail["params"]["field"]="s_mail";
$control_s_mail["params"]["value"]=@$data["s_mail"];
$control_s_mail["params"]["id"]=$record_id;
if($inlineedit)
	$control_s_mail["params"]["mode"]="inline_edit";
else
	$control_s_mail["params"]["mode"]="edit";
$xt->assignbyref("s_mail_editcontrol",$control_s_mail);
$control_s_cmt=array();
$control_s_cmt["func"]="xt_buildeditcontrol";
$control_s_cmt["params"] = array();
$control_s_cmt["params"]["field"]="s_cmt";
$control_s_cmt["params"]["value"]=@$data["s_cmt"];
$control_s_cmt["params"]["id"]=$record_id;
if($inlineedit)
	$control_s_cmt["params"]["mode"]="inline_edit";
else
	$control_s_cmt["params"]["mode"]="edit";
$xt->assignbyref("s_cmt_editcontrol",$control_s_cmt);
$control_s_country=array();
$control_s_country["func"]="xt_buildeditcontrol";
$control_s_country["params"] = array();
$control_s_country["params"]["field"]="s_country";
$control_s_country["params"]["value"]=@$data["s_country"];
$control_s_country["params"]["id"]=$record_id;
if($inlineedit)
	$control_s_country["params"]["mode"]="inline_edit";
else
	$control_s_country["params"]["mode"]="edit";
$xt->assignbyref("s_country_editcontrol",$control_s_country);

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