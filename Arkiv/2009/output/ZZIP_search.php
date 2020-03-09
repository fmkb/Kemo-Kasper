<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/ZZIP_variables.php");
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
$includes.="var SUGGEST_TABLE = \"ZZIP_searchsuggest.php\";\r\n";
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
	document.getElementById('second_ZZIP').style.display =  
		document.forms.editform.elements['asearchopt_ZZIP'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_ZZCity').style.display =  
		document.forms.editform.elements['asearchopt_ZZCity'].value==\"Between\" ? '' : 'none'; 
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
	document.forms.editform.value_ZZIP.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_ZZIP,'advanced')};
	document.forms.editform.value1_ZZIP.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_ZZIP,'advanced1')};
	document.forms.editform.value_ZZIP.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_ZZIP,'advanced')};
	document.forms.editform.value1_ZZIP.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_ZZIP,'advanced1')};
	document.forms.editform.value_ZZCity.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_ZZCity,'advanced')};
	document.forms.editform.value1_ZZCity.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_ZZCity,'advanced1')};
	document.forms.editform.value_ZZCity.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_ZZCity,'advanced')};
	document.forms.editform.value1_ZZCity.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_ZZCity,'advanced1')};
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

// ZZIP 
$opt="";
$not=false;
$control_ZZIP=array();
$control_ZZIP["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["ZZIP"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["ZZIP"];
	$control_ZZIP["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["ZZIP"];
}
$control_ZZIP["func"]="xt_buildeditcontrol";
$control_ZZIP["params"]["field"]="ZZIP";
$control_ZZIP["params"]["mode"]="search";
$xt->assignbyref("ZZIP_editcontrol",$control_ZZIP);
$control1_ZZIP=$control_ZZIP;
$control1_ZZIP["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_ZZIP["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["ZZIP"];
$xt->assignbyref("ZZIP_editcontrol1",$control1_ZZIP);
	
$xt->assign_section("ZZIP_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"ZZIP\">","");
$ZZIP_notbox="name=\"not_ZZIP\"";
if($not)
	$ZZIP_notbox=" checked";
$xt->assign("ZZIP_notbox",$ZZIP_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_ZZIP\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_ZZIP",$searchtype);
//	edit format
$editformats["ZZIP"]="Text field";
// ZZCity 
$opt="";
$not=false;
$control_ZZCity=array();
$control_ZZCity["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["ZZCity"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["ZZCity"];
	$control_ZZCity["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["ZZCity"];
}
$control_ZZCity["func"]="xt_buildeditcontrol";
$control_ZZCity["params"]["field"]="ZZCity";
$control_ZZCity["params"]["mode"]="search";
$xt->assignbyref("ZZCity_editcontrol",$control_ZZCity);
$control1_ZZCity=$control_ZZCity;
$control1_ZZCity["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_ZZCity["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["ZZCity"];
$xt->assignbyref("ZZCity_editcontrol1",$control1_ZZCity);
	
$xt->assign_section("ZZCity_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"ZZCity\">","");
$ZZCity_notbox="name=\"not_ZZCity\"";
if($not)
	$ZZCity_notbox=" checked";
$xt->assign("ZZCity_notbox",$ZZCity_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_ZZCity\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_ZZCity",$searchtype);
//	edit format
$editformats["ZZCity"]="Text field";

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
$contents_block["begin"].="action=\"ZZIP_list.php\" ";
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
	
$templatefile = "ZZIP_search.htm";
if(function_exists("BeforeShowSearch"))
	BeforeShowSearch($xt,$templatefile);

$xt->display($templatefile);

?>