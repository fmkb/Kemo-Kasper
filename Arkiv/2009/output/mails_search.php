<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/mails_variables.php");
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
$includes.="var SUGGEST_TABLE = \"mails_searchsuggest.php\";\r\n";
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
	document.getElementById('second_m_id').style.display =  
		document.forms.editform.elements['asearchopt_m_id'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_m_name').style.display =  
		document.forms.editform.elements['asearchopt_m_name'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_m_body').style.display =  
		document.forms.editform.elements['asearchopt_m_body'].value==\"Between\" ? '' : 'none'; 
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
	document.forms.editform.value_m_id.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_m_id,'advanced')};
	document.forms.editform.value1_m_id.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_m_id,'advanced1')};
	document.forms.editform.value_m_id.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_m_id,'advanced')};
	document.forms.editform.value1_m_id.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_m_id,'advanced1')};
	document.forms.editform.value_m_name.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_m_name,'advanced')};
	document.forms.editform.value1_m_name.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_m_name,'advanced1')};
	document.forms.editform.value_m_name.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_m_name,'advanced')};
	document.forms.editform.value1_m_name.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_m_name,'advanced1')};
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

// m_id 
$opt="";
$not=false;
$control_m_id=array();
$control_m_id["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["m_id"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["m_id"];
	$control_m_id["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["m_id"];
}
$control_m_id["func"]="xt_buildeditcontrol";
$control_m_id["params"]["field"]="m_id";
$control_m_id["params"]["mode"]="search";
$xt->assignbyref("m_id_editcontrol",$control_m_id);
$control1_m_id=$control_m_id;
$control1_m_id["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_m_id["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["m_id"];
$xt->assignbyref("m_id_editcontrol1",$control1_m_id);
	
$xt->assign_section("m_id_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"m_id\">","");
$m_id_notbox="name=\"not_m_id\"";
if($not)
	$m_id_notbox=" checked";
$xt->assign("m_id_notbox",$m_id_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_m_id\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_m_id",$searchtype);
//	edit format
$editformats["m_id"]="Text field";
// m_name 
$opt="";
$not=false;
$control_m_name=array();
$control_m_name["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["m_name"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["m_name"];
	$control_m_name["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["m_name"];
}
$control_m_name["func"]="xt_buildeditcontrol";
$control_m_name["params"]["field"]="m_name";
$control_m_name["params"]["mode"]="search";
$xt->assignbyref("m_name_editcontrol",$control_m_name);
$control1_m_name=$control_m_name;
$control1_m_name["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_m_name["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["m_name"];
$xt->assignbyref("m_name_editcontrol1",$control1_m_name);
	
$xt->assign_section("m_name_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"m_name\">","");
$m_name_notbox="name=\"not_m_name\"";
if($not)
	$m_name_notbox=" checked";
$xt->assign("m_name_notbox",$m_name_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_m_name\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_m_name",$searchtype);
//	edit format
$editformats["m_name"]="Text field";
// m_body 
$opt="";
$not=false;
$control_m_body=array();
$control_m_body["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["m_body"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["m_body"];
	$control_m_body["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["m_body"];
}
$control_m_body["func"]="xt_buildeditcontrol";
$control_m_body["params"]["field"]="m_body";
$control_m_body["params"]["mode"]="search";
$xt->assignbyref("m_body_editcontrol",$control_m_body);
$control1_m_body=$control_m_body;
$control1_m_body["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_m_body["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["m_body"];
$xt->assignbyref("m_body_editcontrol1",$control1_m_body);
	
$xt->assign_section("m_body_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"m_body\">","");
$m_body_notbox="name=\"not_m_body\"";
if($not)
	$m_body_notbox=" checked";
$xt->assign("m_body_notbox",$m_body_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_m_body\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_m_body",$searchtype);
//	edit format
$editformats["m_body"]=EDIT_FORMAT_TEXT_FIELD;

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
$contents_block["begin"].="action=\"mails_list.php\" ";
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
	
$templatefile = "mails_search.htm";
if(function_exists("BeforeShowSearch"))
	BeforeShowSearch($xt,$templatefile);

$xt->display($templatefile);

?>