<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/Dialog_variables.php");
include("include/languages.php");

//	check if logged in
if(!@$_SESSION["UserID"] || !CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Search"))
{ 
	$_SESSION["MyURL"]=$_SERVER["SCRIPT_NAME"]."?".$_SERVER["QUERY_STRING"];
	header("Location: login.php?message=expired"); 
	return;
}

//connect database
$conn = db_connect();

include('libs/xtempl.php');
$xt = new Xtempl();

//	Before Process event
if(function_exists("BeforeProcessSearch"))
	BeforeProcessSearch($conn);


$includes=
"<script language=\"JavaScript\" src=\"include/calendar.js\"></script>
<script language=\"JavaScript\" src=\"include/jsfunctions.js\"></script>\r\n";
$includes.="<script language=\"JavaScript\" src=\"include/jquery.js\"></script>";
if ($useAJAX) {
$includes.="<script language=\"JavaScript\" src=\"include/ajaxsuggest.js\"></script>\r\n";
}
$includes.="<script language=\"JavaScript\" type=\"text/javascript\">\r\n".
"var locale_dateformat = ".$locale_info["LOCALE_IDATE"].";\r\n".
"var locale_datedelimiter = \"".$locale_info["LOCALE_SDATE"]."\";\r\n".
"var bLoading=false;\r\n".
"var TEXT_PLEASE_SELECT='".addslashes(mlang_message("PLEASE_SELECT"))."';\r\n";
if ($useAJAX) {
$includes.="var SUGGEST_TABLE = \"Dialog_searchsuggest.php\";\r\n";
}
$includes.="var detect = navigator.userAgent.toLowerCase();

function checkIt(string)
{
	place = detect.indexOf(string) + 1;
	thestring = string;
	return place;
}


function ShowHideControls()
{
	document.getElementById('second_d_datetime').style.display =  
		document.forms.editform.elements['asearchopt_d_datetime'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_d_msg_type').style.display =  
		document.forms.editform.elements['asearchopt_d_msg_type'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_d_from_p_id').style.display =  
		document.forms.editform.elements['asearchopt_d_from_p_id'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_d_to_p_id').style.display =  
		document.forms.editform.elements['asearchopt_d_to_p_id'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_d_message').style.display =  
		document.forms.editform.elements['asearchopt_d_message'].value==\"Between\" ? '' : 'none'; 
	return false;
}
function ResetControls()
{
	var i;
	e = document.forms[0].elements; 
	for (i=0;i<e.length;i++) 
	{
		if (e[i].name!='type' && e[i].className!='button' && e[i].type!='hidden')
		{
			if(e[i].type=='select-one')
				e[i].selectedIndex=0;
			else if(e[i].type=='select-multiple')
			{
				var j;
				for(j=0;j<e[i].options.length;j++)
					e[i].options[j].selected=false;
			}
			else if(e[i].type=='checkbox' || e[i].type=='radio')
				e[i].checked=false;
			else 
				e[i].value = ''; 
		}
		else if(e[i].name.substr(0,6)=='value_' && e[i].type=='hidden')
			e[i].value = ''; 
	}
	ShowHideControls();	
	return false;
}";

$includes.="
$(document).ready(function() {
	document.forms.editform.value_d_msg_type.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_d_msg_type,'advanced')};
	document.forms.editform.value1_d_msg_type.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_d_msg_type,'advanced1')};
	document.forms.editform.value_d_msg_type.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_d_msg_type,'advanced')};
	document.forms.editform.value1_d_msg_type.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_d_msg_type,'advanced1')};
});
</script>
<div id=\"search_suggest\"></div>
";



$all_checkbox="value=\"and\"";
$any_checkbox="value=\"or\"";

if(@$_SESSION[$strTableName."_asearchtype"]=="or")
	$any_checkbox.=" checked";
else
	$all_checkbox.=" checked";
$xt->assign("any_checkbox",$any_checkbox);
$xt->assign("all_checkbox",$all_checkbox);

$editformats=array();

// d_datetime 
$opt="";
$not=false;
$control_d_datetime=array();
$control_d_datetime["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["d_datetime"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["d_datetime"];
	$control_d_datetime["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["d_datetime"];
}
$control_d_datetime["func"]="xt_buildeditcontrol";
$control_d_datetime["params"]["field"]="d_datetime";
$control_d_datetime["params"]["mode"]="search";
$xt->assignbyref("d_datetime_editcontrol",$control_d_datetime);
$control1_d_datetime=$control_d_datetime;
$control1_d_datetime["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_d_datetime["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["d_datetime"];
$xt->assignbyref("d_datetime_editcontrol1",$control1_d_datetime);
	
$xt->assign_section("d_datetime_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"d_datetime\">","");
$d_datetime_notbox="name=\"not_d_datetime\"";
if($not)
	$d_datetime_notbox=" checked";
$xt->assign("d_datetime_notbox",$d_datetime_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">".mlang_message("EQUALS")."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">".mlang_message("MORE_THAN")."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">".mlang_message("LESS_THAN")."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">".mlang_message("EQUAL_OR_MORE")."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">".mlang_message("EQUAL_OR_LESS")."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">".mlang_message("BETWEEN")."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">".mlang_message("EMPTY")."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_d_datetime\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_d_datetime",$searchtype);
//	edit format
$editformats["d_datetime"]="Date";
// d_msg_type 
$opt="";
$not=false;
$control_d_msg_type=array();
$control_d_msg_type["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["d_msg_type"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["d_msg_type"];
	$control_d_msg_type["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["d_msg_type"];
}
$control_d_msg_type["func"]="xt_buildeditcontrol";
$control_d_msg_type["params"]["field"]="d_msg_type";
$control_d_msg_type["params"]["mode"]="search";
$xt->assignbyref("d_msg_type_editcontrol",$control_d_msg_type);
$control1_d_msg_type=$control_d_msg_type;
$control1_d_msg_type["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_d_msg_type["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["d_msg_type"];
$xt->assignbyref("d_msg_type_editcontrol1",$control1_d_msg_type);
	
$xt->assign_section("d_msg_type_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"d_msg_type\">","");
$d_msg_type_notbox="name=\"not_d_msg_type\"";
if($not)
	$d_msg_type_notbox=" checked";
$xt->assign("d_msg_type_notbox",$d_msg_type_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Contains\" ".(($opt=="Contains")?"selected":"").">".mlang_message("CONTAINS")."</option>";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">".mlang_message("EQUALS")."</option>";
$options.="<OPTION VALUE=\"Starts with ...\" ".(($opt=="Starts with ...")?"selected":"").">".mlang_message("STARTS_WITH")."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">".mlang_message("MORE_THAN")."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">".mlang_message("LESS_THAN")."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">".mlang_message("EQUAL_OR_MORE")."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">".mlang_message("EQUAL_OR_LESS")."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">".mlang_message("BETWEEN")."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">".mlang_message("EMPTY")."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_d_msg_type\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_d_msg_type",$searchtype);
//	edit format
$editformats["d_msg_type"]="Text field";
// d_from_p_id 
$opt="";
$not=false;
$control_d_from_p_id=array();
$control_d_from_p_id["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["d_from_p_id"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["d_from_p_id"];
	$control_d_from_p_id["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["d_from_p_id"];
}
$control_d_from_p_id["func"]="xt_buildeditcontrol";
$control_d_from_p_id["params"]["field"]="d_from_p_id";
$control_d_from_p_id["params"]["mode"]="search";
$xt->assignbyref("d_from_p_id_editcontrol",$control_d_from_p_id);
$control1_d_from_p_id=$control_d_from_p_id;
$control1_d_from_p_id["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_d_from_p_id["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["d_from_p_id"];
$xt->assignbyref("d_from_p_id_editcontrol1",$control1_d_from_p_id);
	
$xt->assign_section("d_from_p_id_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"d_from_p_id\">","");
$d_from_p_id_notbox="name=\"not_d_from_p_id\"";
if($not)
	$d_from_p_id_notbox=" checked";
$xt->assign("d_from_p_id_notbox",$d_from_p_id_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">".mlang_message("EQUALS")."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_d_from_p_id\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_d_from_p_id",$searchtype);
//	edit format
$editformats["d_from_p_id"]="Lookup wizard";
// d_to_p_id 
$opt="";
$not=false;
$control_d_to_p_id=array();
$control_d_to_p_id["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["d_to_p_id"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["d_to_p_id"];
	$control_d_to_p_id["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["d_to_p_id"];
}
$control_d_to_p_id["func"]="xt_buildeditcontrol";
$control_d_to_p_id["params"]["field"]="d_to_p_id";
$control_d_to_p_id["params"]["mode"]="search";
$xt->assignbyref("d_to_p_id_editcontrol",$control_d_to_p_id);
$control1_d_to_p_id=$control_d_to_p_id;
$control1_d_to_p_id["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_d_to_p_id["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["d_to_p_id"];
$xt->assignbyref("d_to_p_id_editcontrol1",$control1_d_to_p_id);
	
$xt->assign_section("d_to_p_id_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"d_to_p_id\">","");
$d_to_p_id_notbox="name=\"not_d_to_p_id\"";
if($not)
	$d_to_p_id_notbox=" checked";
$xt->assign("d_to_p_id_notbox",$d_to_p_id_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">".mlang_message("EQUALS")."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_d_to_p_id\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_d_to_p_id",$searchtype);
//	edit format
$editformats["d_to_p_id"]="Lookup wizard";
// d_message 
$opt="";
$not=false;
$control_d_message=array();
$control_d_message["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["d_message"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["d_message"];
	$control_d_message["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["d_message"];
}
$control_d_message["func"]="xt_buildeditcontrol";
$control_d_message["params"]["field"]="d_message";
$control_d_message["params"]["mode"]="search";
$xt->assignbyref("d_message_editcontrol",$control_d_message);
$control1_d_message=$control_d_message;
$control1_d_message["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_d_message["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["d_message"];
$xt->assignbyref("d_message_editcontrol1",$control1_d_message);
	
$xt->assign_section("d_message_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"d_message\">","");
$d_message_notbox="name=\"not_d_message\"";
if($not)
	$d_message_notbox=" checked";
$xt->assign("d_message_notbox",$d_message_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Contains\" ".(($opt=="Contains")?"selected":"").">".mlang_message("CONTAINS")."</option>";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">".mlang_message("EQUALS")."</option>";
$options.="<OPTION VALUE=\"Starts with ...\" ".(($opt=="Starts with ...")?"selected":"").">".mlang_message("STARTS_WITH")."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">".mlang_message("MORE_THAN")."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">".mlang_message("LESS_THAN")."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">".mlang_message("EQUAL_OR_MORE")."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">".mlang_message("EQUAL_OR_LESS")."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">".mlang_message("BETWEEN")."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">".mlang_message("EMPTY")."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_d_message\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_d_message",$searchtype);
//	edit format
$editformats["d_message"]=EDIT_FORMAT_TEXT_FIELD;

$linkdata="";

$linkdata .= "<script type=\"text/javascript\">\r\n";

if ($useAJAX) {
}
else
{
}
$linkdata.="</script>\r\n";


$body=array();
$body["begin"]=$includes;
$body["end"]=$linkdata."<script>ShowHideControls()</script>";
$xt->assignbyref("body",$body);

$contents_block=array();
$contents_block["begin"]="<form method=\"POST\" ";
if(isset( $_GET["rname"]))
{
	$contents_block["begin"].="action=\"dreport.php?rname=".$_GET["rname"]."\" ";
}	
else if(isset( $_GET["cname"]))
{
	$contents_block["begin"].="action=\"dchart.php?cname=".$_GET["cname"]."\" ";
}	
else
{
$contents_block["begin"].="action=\"Dialog_list.php\" ";
}
$contents_block["begin"].="name=\"editform\"><input type=\"hidden\" id=\"a\" name=\"a\" value=\"advsearch\">";
$contents_block["end"]="</form>";
$xt->assignbyref("contents_block",$contents_block);

$xt->assign("searchbutton_attrs","name=\"SearchButton\" onclick=\"javascript:document.forms.editform.submit();\"");
$xt->assign("resetbutton_attrs","onclick=\"return ResetControls();\"");

$xt->assign("backbutton_attrs","onclick=\"javascript: document.forms.editform.a.value='return'; document.forms.editform.submit();\"");

$xt->assign("conditions_block",true);
$xt->assign("search_button",true);
$xt->assign("reset_button",true);
$xt->assign("back_button",true);

if ( isset( $_GET["rname"] ) ) {
	$xt->assign("dynamic", "true");
	$xt->assign("rname", $_GET["rname"]);
}
if ( isset( $_GET["cname"] ) ) {
	$xt->assign("dynamic", "true");
	$xt->assign("cname", $_GET["cname"]);
}
	
$templatefile = "Dialog_search.htm";
if(function_exists("BeforeShowSearch"))
	BeforeShowSearch($xt,$templatefile);

$xt->display($templatefile);

?>