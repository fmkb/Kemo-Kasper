<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/xcountries_variables.php");
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
$includes.="var SUGGEST_TABLE = \"xcountries_searchsuggest.php\";\r\n";
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
	document.getElementById('second_xc_id').style.display =  
		document.forms.editform.elements['asearchopt_xc_id'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_xc_name').style.display =  
		document.forms.editform.elements['asearchopt_xc_name'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_xc_currency').style.display =  
		document.forms.editform.elements['asearchopt_xc_currency'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_xc_code').style.display =  
		document.forms.editform.elements['asearchopt_xc_code'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_xc_desc').style.display =  
		document.forms.editform.elements['asearchopt_xc_desc'].value==\"Between\" ? '' : 'none'; 
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
	document.forms.editform.value_xc_id.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_xc_id,'advanced')};
	document.forms.editform.value1_xc_id.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_xc_id,'advanced1')};
	document.forms.editform.value_xc_id.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_xc_id,'advanced')};
	document.forms.editform.value1_xc_id.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_xc_id,'advanced1')};
	document.forms.editform.value_xc_currency.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_xc_currency,'advanced')};
	document.forms.editform.value1_xc_currency.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_xc_currency,'advanced1')};
	document.forms.editform.value_xc_currency.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_xc_currency,'advanced')};
	document.forms.editform.value1_xc_currency.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_xc_currency,'advanced1')};
	document.forms.editform.value_xc_code.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_xc_code,'advanced')};
	document.forms.editform.value1_xc_code.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_xc_code,'advanced1')};
	document.forms.editform.value_xc_code.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_xc_code,'advanced')};
	document.forms.editform.value1_xc_code.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_xc_code,'advanced1')};
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

// xc_id 
$opt="";
$not=false;
$control_xc_id=array();
$control_xc_id["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["xc_id"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["xc_id"];
	$control_xc_id["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["xc_id"];
}
$control_xc_id["func"]="xt_buildeditcontrol";
$control_xc_id["params"]["field"]="xc_id";
$control_xc_id["params"]["mode"]="search";
$xt->assignbyref("xc_id_editcontrol",$control_xc_id);
$control1_xc_id=$control_xc_id;
$control1_xc_id["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_xc_id["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["xc_id"];
$xt->assignbyref("xc_id_editcontrol1",$control1_xc_id);
	
$xt->assign_section("xc_id_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"xc_id\">","");
$xc_id_notbox="name=\"not_xc_id\"";
if($not)
	$xc_id_notbox=" checked";
$xt->assign("xc_id_notbox",$xc_id_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_xc_id\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_xc_id",$searchtype);
//	edit format
$editformats["xc_id"]="Text field";
// xc_name 
$opt="";
$not=false;
$control_xc_name=array();
$control_xc_name["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["xc_name"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["xc_name"];
	$control_xc_name["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["xc_name"];
}
$control_xc_name["func"]="xt_buildeditcontrol";
$control_xc_name["params"]["field"]="xc_name";
$control_xc_name["params"]["mode"]="search";
$xt->assignbyref("xc_name_editcontrol",$control_xc_name);
$control1_xc_name=$control_xc_name;
$control1_xc_name["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_xc_name["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["xc_name"];
$xt->assignbyref("xc_name_editcontrol1",$control1_xc_name);
	
$xt->assign_section("xc_name_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"xc_name\">","");
$xc_name_notbox="name=\"not_xc_name\"";
if($not)
	$xc_name_notbox=" checked";
$xt->assign("xc_name_notbox",$xc_name_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_xc_name\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_xc_name",$searchtype);
//	edit format
$editformats["xc_name"]=EDIT_FORMAT_TEXT_FIELD;
// xc_currency 
$opt="";
$not=false;
$control_xc_currency=array();
$control_xc_currency["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["xc_currency"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["xc_currency"];
	$control_xc_currency["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["xc_currency"];
}
$control_xc_currency["func"]="xt_buildeditcontrol";
$control_xc_currency["params"]["field"]="xc_currency";
$control_xc_currency["params"]["mode"]="search";
$xt->assignbyref("xc_currency_editcontrol",$control_xc_currency);
$control1_xc_currency=$control_xc_currency;
$control1_xc_currency["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_xc_currency["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["xc_currency"];
$xt->assignbyref("xc_currency_editcontrol1",$control1_xc_currency);
	
$xt->assign_section("xc_currency_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"xc_currency\">","");
$xc_currency_notbox="name=\"not_xc_currency\"";
if($not)
	$xc_currency_notbox=" checked";
$xt->assign("xc_currency_notbox",$xc_currency_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_xc_currency\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_xc_currency",$searchtype);
//	edit format
$editformats["xc_currency"]="Text field";
// xc_code 
$opt="";
$not=false;
$control_xc_code=array();
$control_xc_code["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["xc_code"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["xc_code"];
	$control_xc_code["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["xc_code"];
}
$control_xc_code["func"]="xt_buildeditcontrol";
$control_xc_code["params"]["field"]="xc_code";
$control_xc_code["params"]["mode"]="search";
$xt->assignbyref("xc_code_editcontrol",$control_xc_code);
$control1_xc_code=$control_xc_code;
$control1_xc_code["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_xc_code["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["xc_code"];
$xt->assignbyref("xc_code_editcontrol1",$control1_xc_code);
	
$xt->assign_section("xc_code_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"xc_code\">","");
$xc_code_notbox="name=\"not_xc_code\"";
if($not)
	$xc_code_notbox=" checked";
$xt->assign("xc_code_notbox",$xc_code_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_xc_code\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_xc_code",$searchtype);
//	edit format
$editformats["xc_code"]="Text field";
// xc_desc 
$opt="";
$not=false;
$control_xc_desc=array();
$control_xc_desc["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["xc_desc"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["xc_desc"];
	$control_xc_desc["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["xc_desc"];
}
$control_xc_desc["func"]="xt_buildeditcontrol";
$control_xc_desc["params"]["field"]="xc_desc";
$control_xc_desc["params"]["mode"]="search";
$xt->assignbyref("xc_desc_editcontrol",$control_xc_desc);
$control1_xc_desc=$control_xc_desc;
$control1_xc_desc["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_xc_desc["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["xc_desc"];
$xt->assignbyref("xc_desc_editcontrol1",$control1_xc_desc);
	
$xt->assign_section("xc_desc_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"xc_desc\">","");
$xc_desc_notbox="name=\"not_xc_desc\"";
if($not)
	$xc_desc_notbox=" checked";
$xt->assign("xc_desc_notbox",$xc_desc_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_xc_desc\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_xc_desc",$searchtype);
//	edit format
$editformats["xc_desc"]=EDIT_FORMAT_TEXT_FIELD;

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
$contents_block["begin"].="action=\"xcountries_list.php\" ";
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
	
$templatefile = "xcountries_search.htm";
if(function_exists("BeforeShowSearch"))
	BeforeShowSearch($xt,$templatefile);

$xt->display($templatefile);

?>