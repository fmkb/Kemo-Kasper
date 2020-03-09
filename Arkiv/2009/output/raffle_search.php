<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/raffle_variables.php");
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
$includes.="var SUGGEST_TABLE = \"raffle_searchsuggest.php\";\r\n";
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
	document.getElementById('second_r_id').style.display =  
		document.forms.editform.elements['asearchopt_r_id'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_r_name').style.display =  
		document.forms.editform.elements['asearchopt_r_name'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_r_img').style.display =  
		document.forms.editform.elements['asearchopt_r_img'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_r_text').style.display =  
		document.forms.editform.elements['asearchopt_r_text'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_r_date').style.display =  
		document.forms.editform.elements['asearchopt_r_date'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_r_p_id').style.display =  
		document.forms.editform.elements['asearchopt_r_p_id'].value==\"Between\" ? '' : 'none'; 
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
	document.forms.editform.value_r_id.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_r_id,'advanced')};
	document.forms.editform.value1_r_id.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_r_id,'advanced1')};
	document.forms.editform.value_r_id.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_r_id,'advanced')};
	document.forms.editform.value1_r_id.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_r_id,'advanced1')};
	document.forms.editform.value_r_name.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_r_name,'advanced')};
	document.forms.editform.value1_r_name.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_r_name,'advanced1')};
	document.forms.editform.value_r_name.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_r_name,'advanced')};
	document.forms.editform.value1_r_name.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_r_name,'advanced1')};
	document.forms.editform.value_r_img.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_r_img,'advanced')};
	document.forms.editform.value1_r_img.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_r_img,'advanced1')};
	document.forms.editform.value_r_img.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_r_img,'advanced')};
	document.forms.editform.value1_r_img.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_r_img,'advanced1')};
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

// r_id 
$opt="";
$not=false;
$control_r_id=array();
$control_r_id["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["r_id"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["r_id"];
	$control_r_id["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["r_id"];
}
$control_r_id["func"]="xt_buildeditcontrol";
$control_r_id["params"]["field"]="r_id";
$control_r_id["params"]["mode"]="search";
$xt->assignbyref("r_id_editcontrol",$control_r_id);
$control1_r_id=$control_r_id;
$control1_r_id["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_r_id["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["r_id"];
$xt->assignbyref("r_id_editcontrol1",$control1_r_id);
	
$xt->assign_section("r_id_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"r_id\">","");
$r_id_notbox="name=\"not_r_id\"";
if($not)
	$r_id_notbox=" checked";
$xt->assign("r_id_notbox",$r_id_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_r_id\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_r_id",$searchtype);
//	edit format
$editformats["r_id"]="Text field";
// r_name 
$opt="";
$not=false;
$control_r_name=array();
$control_r_name["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["r_name"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["r_name"];
	$control_r_name["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["r_name"];
}
$control_r_name["func"]="xt_buildeditcontrol";
$control_r_name["params"]["field"]="r_name";
$control_r_name["params"]["mode"]="search";
$xt->assignbyref("r_name_editcontrol",$control_r_name);
$control1_r_name=$control_r_name;
$control1_r_name["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_r_name["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["r_name"];
$xt->assignbyref("r_name_editcontrol1",$control1_r_name);
	
$xt->assign_section("r_name_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"r_name\">","");
$r_name_notbox="name=\"not_r_name\"";
if($not)
	$r_name_notbox=" checked";
$xt->assign("r_name_notbox",$r_name_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_r_name\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_r_name",$searchtype);
//	edit format
$editformats["r_name"]="Text field";
// r_img 
$opt="";
$not=false;
$control_r_img=array();
$control_r_img["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["r_img"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["r_img"];
	$control_r_img["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["r_img"];
}
$control_r_img["func"]="xt_buildeditcontrol";
$control_r_img["params"]["field"]="r_img";
$control_r_img["params"]["mode"]="search";
$xt->assignbyref("r_img_editcontrol",$control_r_img);
$control1_r_img=$control_r_img;
$control1_r_img["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_r_img["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["r_img"];
$xt->assignbyref("r_img_editcontrol1",$control1_r_img);
	
$xt->assign_section("r_img_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"r_img\">","");
$r_img_notbox="name=\"not_r_img\"";
if($not)
	$r_img_notbox=" checked";
$xt->assign("r_img_notbox",$r_img_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_r_img\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_r_img",$searchtype);
//	edit format
$editformats["r_img"]="Text field";
// r_text 
$opt="";
$not=false;
$control_r_text=array();
$control_r_text["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["r_text"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["r_text"];
	$control_r_text["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["r_text"];
}
$control_r_text["func"]="xt_buildeditcontrol";
$control_r_text["params"]["field"]="r_text";
$control_r_text["params"]["mode"]="search";
$xt->assignbyref("r_text_editcontrol",$control_r_text);
$control1_r_text=$control_r_text;
$control1_r_text["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_r_text["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["r_text"];
$xt->assignbyref("r_text_editcontrol1",$control1_r_text);
	
$xt->assign_section("r_text_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"r_text\">","");
$r_text_notbox="name=\"not_r_text\"";
if($not)
	$r_text_notbox=" checked";
$xt->assign("r_text_notbox",$r_text_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_r_text\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_r_text",$searchtype);
//	edit format
$editformats["r_text"]=EDIT_FORMAT_TEXT_FIELD;
// r_date 
$opt="";
$not=false;
$control_r_date=array();
$control_r_date["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["r_date"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["r_date"];
	$control_r_date["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["r_date"];
}
$control_r_date["func"]="xt_buildeditcontrol";
$control_r_date["params"]["field"]="r_date";
$control_r_date["params"]["mode"]="search";
$xt->assignbyref("r_date_editcontrol",$control_r_date);
$control1_r_date=$control_r_date;
$control1_r_date["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_r_date["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["r_date"];
$xt->assignbyref("r_date_editcontrol1",$control1_r_date);
	
$xt->assign_section("r_date_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"r_date\">","");
$r_date_notbox="name=\"not_r_date\"";
if($not)
	$r_date_notbox=" checked";
$xt->assign("r_date_notbox",$r_date_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">".mlang_message("EQUALS")."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">".mlang_message("MORE_THAN")."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">".mlang_message("LESS_THAN")."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">".mlang_message("EQUAL_OR_MORE")."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">".mlang_message("EQUAL_OR_LESS")."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">".mlang_message("BETWEEN")."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">".mlang_message("EMPTY")."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_r_date\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_r_date",$searchtype);
//	edit format
$editformats["r_date"]="Date";
// r_p_id 
$opt="";
$not=false;
$control_r_p_id=array();
$control_r_p_id["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["r_p_id"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["r_p_id"];
	$control_r_p_id["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["r_p_id"];
}
$control_r_p_id["func"]="xt_buildeditcontrol";
$control_r_p_id["params"]["field"]="r_p_id";
$control_r_p_id["params"]["mode"]="search";
$xt->assignbyref("r_p_id_editcontrol",$control_r_p_id);
$control1_r_p_id=$control_r_p_id;
$control1_r_p_id["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_r_p_id["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["r_p_id"];
$xt->assignbyref("r_p_id_editcontrol1",$control1_r_p_id);
	
$xt->assign_section("r_p_id_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"r_p_id\">","");
$r_p_id_notbox="name=\"not_r_p_id\"";
if($not)
	$r_p_id_notbox=" checked";
$xt->assign("r_p_id_notbox",$r_p_id_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">".mlang_message("EQUALS")."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_r_p_id\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_r_p_id",$searchtype);
//	edit format
$editformats["r_p_id"]="Lookup wizard";

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
$contents_block["begin"].="action=\"raffle_list.php\" ";
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
	
$templatefile = "raffle_search.htm";
if(function_exists("BeforeShowSearch"))
	BeforeShowSearch($xt,$templatefile);

$xt->display($templatefile);

?>