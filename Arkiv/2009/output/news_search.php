<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/news_variables.php");
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
$includes.="var SUGGEST_TABLE = \"news_searchsuggest.php\";\r\n";
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
	document.getElementById('second_n_id').style.display =  
		document.forms.editform.elements['asearchopt_n_id'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_n_active').style.display =  
		document.forms.editform.elements['asearchopt_n_active'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_n_start').style.display =  
		document.forms.editform.elements['asearchopt_n_start'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_n_end').style.display =  
		document.forms.editform.elements['asearchopt_n_end'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_n_date').style.display =  
		document.forms.editform.elements['asearchopt_n_date'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_n_head').style.display =  
		document.forms.editform.elements['asearchopt_n_head'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_n_teaser').style.display =  
		document.forms.editform.elements['asearchopt_n_teaser'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_n_text').style.display =  
		document.forms.editform.elements['asearchopt_n_text'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_n_file').style.display =  
		document.forms.editform.elements['asearchopt_n_file'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_n_type').style.display =  
		document.forms.editform.elements['asearchopt_n_type'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_n_country').style.display =  
		document.forms.editform.elements['asearchopt_n_country'].value==\"Between\" ? '' : 'none'; 
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
	document.forms.editform.value_n_id.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_n_id,'advanced')};
	document.forms.editform.value1_n_id.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_n_id,'advanced1')};
	document.forms.editform.value_n_id.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_n_id,'advanced')};
	document.forms.editform.value1_n_id.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_n_id,'advanced1')};
	document.forms.editform.value_n_head.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_n_head,'advanced')};
	document.forms.editform.value1_n_head.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_n_head,'advanced1')};
	document.forms.editform.value_n_head.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_n_head,'advanced')};
	document.forms.editform.value1_n_head.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_n_head,'advanced1')};
	document.forms.editform.value_n_type.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_n_type,'advanced')};
	document.forms.editform.value1_n_type.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_n_type,'advanced1')};
	document.forms.editform.value_n_type.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_n_type,'advanced')};
	document.forms.editform.value1_n_type.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_n_type,'advanced1')};
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

// n_id 
$opt="";
$not=false;
$control_n_id=array();
$control_n_id["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["n_id"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["n_id"];
	$control_n_id["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["n_id"];
}
$control_n_id["func"]="xt_buildeditcontrol";
$control_n_id["params"]["field"]="n_id";
$control_n_id["params"]["mode"]="search";
$xt->assignbyref("n_id_editcontrol",$control_n_id);
$control1_n_id=$control_n_id;
$control1_n_id["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_n_id["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["n_id"];
$xt->assignbyref("n_id_editcontrol1",$control1_n_id);
	
$xt->assign_section("n_id_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"n_id\">","");
$n_id_notbox="name=\"not_n_id\"";
if($not)
	$n_id_notbox=" checked";
$xt->assign("n_id_notbox",$n_id_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_n_id\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_n_id",$searchtype);
//	edit format
$editformats["n_id"]="Text field";
// n_active 
$opt="";
$not=false;
$control_n_active=array();
$control_n_active["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["n_active"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["n_active"];
	$control_n_active["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["n_active"];
}
$control_n_active["func"]="xt_buildeditcontrol";
$control_n_active["params"]["field"]="n_active";
$control_n_active["params"]["mode"]="search";
$xt->assignbyref("n_active_editcontrol",$control_n_active);
$control1_n_active=$control_n_active;
$control1_n_active["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_n_active["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["n_active"];
$xt->assignbyref("n_active_editcontrol1",$control1_n_active);
	
$xt->assign_section("n_active_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"n_active\">","");
$n_active_notbox="name=\"not_n_active\"";
if($not)
	$n_active_notbox=" checked";
$xt->assign("n_active_notbox",$n_active_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">".mlang_message("EQUALS")."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_n_active\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_n_active",$searchtype);
//	edit format
$editformats["n_active"]="Lookup wizard";
// n_start 
$opt="";
$not=false;
$control_n_start=array();
$control_n_start["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["n_start"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["n_start"];
	$control_n_start["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["n_start"];
}
$control_n_start["func"]="xt_buildeditcontrol";
$control_n_start["params"]["field"]="n_start";
$control_n_start["params"]["mode"]="search";
$xt->assignbyref("n_start_editcontrol",$control_n_start);
$control1_n_start=$control_n_start;
$control1_n_start["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_n_start["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["n_start"];
$xt->assignbyref("n_start_editcontrol1",$control1_n_start);
	
$xt->assign_section("n_start_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"n_start\">","");
$n_start_notbox="name=\"not_n_start\"";
if($not)
	$n_start_notbox=" checked";
$xt->assign("n_start_notbox",$n_start_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">".mlang_message("EQUALS")."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">".mlang_message("MORE_THAN")."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">".mlang_message("LESS_THAN")."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">".mlang_message("EQUAL_OR_MORE")."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">".mlang_message("EQUAL_OR_LESS")."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">".mlang_message("BETWEEN")."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">".mlang_message("EMPTY")."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_n_start\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_n_start",$searchtype);
//	edit format
$editformats["n_start"]="Date";
// n_end 
$opt="";
$not=false;
$control_n_end=array();
$control_n_end["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["n_end"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["n_end"];
	$control_n_end["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["n_end"];
}
$control_n_end["func"]="xt_buildeditcontrol";
$control_n_end["params"]["field"]="n_end";
$control_n_end["params"]["mode"]="search";
$xt->assignbyref("n_end_editcontrol",$control_n_end);
$control1_n_end=$control_n_end;
$control1_n_end["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_n_end["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["n_end"];
$xt->assignbyref("n_end_editcontrol1",$control1_n_end);
	
$xt->assign_section("n_end_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"n_end\">","");
$n_end_notbox="name=\"not_n_end\"";
if($not)
	$n_end_notbox=" checked";
$xt->assign("n_end_notbox",$n_end_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">".mlang_message("EQUALS")."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">".mlang_message("MORE_THAN")."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">".mlang_message("LESS_THAN")."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">".mlang_message("EQUAL_OR_MORE")."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">".mlang_message("EQUAL_OR_LESS")."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">".mlang_message("BETWEEN")."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">".mlang_message("EMPTY")."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_n_end\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_n_end",$searchtype);
//	edit format
$editformats["n_end"]="Date";
// n_date 
$opt="";
$not=false;
$control_n_date=array();
$control_n_date["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["n_date"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["n_date"];
	$control_n_date["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["n_date"];
}
$control_n_date["func"]="xt_buildeditcontrol";
$control_n_date["params"]["field"]="n_date";
$control_n_date["params"]["mode"]="search";
$xt->assignbyref("n_date_editcontrol",$control_n_date);
$control1_n_date=$control_n_date;
$control1_n_date["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_n_date["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["n_date"];
$xt->assignbyref("n_date_editcontrol1",$control1_n_date);
	
$xt->assign_section("n_date_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"n_date\">","");
$n_date_notbox="name=\"not_n_date\"";
if($not)
	$n_date_notbox=" checked";
$xt->assign("n_date_notbox",$n_date_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">".mlang_message("EQUALS")."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">".mlang_message("MORE_THAN")."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">".mlang_message("LESS_THAN")."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">".mlang_message("EQUAL_OR_MORE")."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">".mlang_message("EQUAL_OR_LESS")."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">".mlang_message("BETWEEN")."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">".mlang_message("EMPTY")."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_n_date\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_n_date",$searchtype);
//	edit format
$editformats["n_date"]="Date";
// n_head 
$opt="";
$not=false;
$control_n_head=array();
$control_n_head["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["n_head"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["n_head"];
	$control_n_head["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["n_head"];
}
$control_n_head["func"]="xt_buildeditcontrol";
$control_n_head["params"]["field"]="n_head";
$control_n_head["params"]["mode"]="search";
$xt->assignbyref("n_head_editcontrol",$control_n_head);
$control1_n_head=$control_n_head;
$control1_n_head["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_n_head["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["n_head"];
$xt->assignbyref("n_head_editcontrol1",$control1_n_head);
	
$xt->assign_section("n_head_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"n_head\">","");
$n_head_notbox="name=\"not_n_head\"";
if($not)
	$n_head_notbox=" checked";
$xt->assign("n_head_notbox",$n_head_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_n_head\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_n_head",$searchtype);
//	edit format
$editformats["n_head"]="Text field";
// n_teaser 
$opt="";
$not=false;
$control_n_teaser=array();
$control_n_teaser["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["n_teaser"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["n_teaser"];
	$control_n_teaser["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["n_teaser"];
}
$control_n_teaser["func"]="xt_buildeditcontrol";
$control_n_teaser["params"]["field"]="n_teaser";
$control_n_teaser["params"]["mode"]="search";
$xt->assignbyref("n_teaser_editcontrol",$control_n_teaser);
$control1_n_teaser=$control_n_teaser;
$control1_n_teaser["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_n_teaser["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["n_teaser"];
$xt->assignbyref("n_teaser_editcontrol1",$control1_n_teaser);
	
$xt->assign_section("n_teaser_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"n_teaser\">","");
$n_teaser_notbox="name=\"not_n_teaser\"";
if($not)
	$n_teaser_notbox=" checked";
$xt->assign("n_teaser_notbox",$n_teaser_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_n_teaser\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_n_teaser",$searchtype);
//	edit format
$editformats["n_teaser"]=EDIT_FORMAT_TEXT_FIELD;
// n_text 
$opt="";
$not=false;
$control_n_text=array();
$control_n_text["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["n_text"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["n_text"];
	$control_n_text["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["n_text"];
}
$control_n_text["func"]="xt_buildeditcontrol";
$control_n_text["params"]["field"]="n_text";
$control_n_text["params"]["mode"]="search";
$xt->assignbyref("n_text_editcontrol",$control_n_text);
$control1_n_text=$control_n_text;
$control1_n_text["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_n_text["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["n_text"];
$xt->assignbyref("n_text_editcontrol1",$control1_n_text);
	
$xt->assign_section("n_text_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"n_text\">","");
$n_text_notbox="name=\"not_n_text\"";
if($not)
	$n_text_notbox=" checked";
$xt->assign("n_text_notbox",$n_text_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_n_text\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_n_text",$searchtype);
//	edit format
$editformats["n_text"]=EDIT_FORMAT_TEXT_FIELD;
// n_file 
$opt="";
$not=false;
$control_n_file=array();
$control_n_file["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["n_file"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["n_file"];
	$control_n_file["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["n_file"];
}
$control_n_file["func"]="xt_buildeditcontrol";
$control_n_file["params"]["field"]="n_file";
$control_n_file["params"]["mode"]="search";
$xt->assignbyref("n_file_editcontrol",$control_n_file);
$control1_n_file=$control_n_file;
$control1_n_file["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_n_file["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["n_file"];
$xt->assignbyref("n_file_editcontrol1",$control1_n_file);
	
$xt->assign_section("n_file_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"n_file\">","");
$n_file_notbox="name=\"not_n_file\"";
if($not)
	$n_file_notbox=" checked";
$xt->assign("n_file_notbox",$n_file_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">".mlang_message("EQUALS")."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_n_file\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_n_file",$searchtype);
//	edit format
$editformats["n_file"]=EDIT_FORMAT_TEXT_FIELD;
// n_type 
$opt="";
$not=false;
$control_n_type=array();
$control_n_type["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["n_type"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["n_type"];
	$control_n_type["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["n_type"];
}
$control_n_type["func"]="xt_buildeditcontrol";
$control_n_type["params"]["field"]="n_type";
$control_n_type["params"]["mode"]="search";
$xt->assignbyref("n_type_editcontrol",$control_n_type);
$control1_n_type=$control_n_type;
$control1_n_type["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_n_type["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["n_type"];
$xt->assignbyref("n_type_editcontrol1",$control1_n_type);
	
$xt->assign_section("n_type_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"n_type\">","");
$n_type_notbox="name=\"not_n_type\"";
if($not)
	$n_type_notbox=" checked";
$xt->assign("n_type_notbox",$n_type_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_n_type\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_n_type",$searchtype);
//	edit format
$editformats["n_type"]="Text field";
// n_country 
$opt="";
$not=false;
$control_n_country=array();
$control_n_country["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["n_country"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["n_country"];
	$control_n_country["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["n_country"];
}
$control_n_country["func"]="xt_buildeditcontrol";
$control_n_country["params"]["field"]="n_country";
$control_n_country["params"]["mode"]="search";
$xt->assignbyref("n_country_editcontrol",$control_n_country);
$control1_n_country=$control_n_country;
$control1_n_country["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_n_country["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["n_country"];
$xt->assignbyref("n_country_editcontrol1",$control1_n_country);
	
$xt->assign_section("n_country_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"n_country\">","");
$n_country_notbox="name=\"not_n_country\"";
if($not)
	$n_country_notbox=" checked";
$xt->assign("n_country_notbox",$n_country_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">".mlang_message("EQUALS")."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_n_country\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_n_country",$searchtype);
//	edit format
$editformats["n_country"]="Lookup wizard";

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
$contents_block["begin"].="action=\"news_list.php\" ";
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
	
$templatefile = "news_search.htm";
if(function_exists("BeforeShowSearch"))
	BeforeShowSearch($xt,$templatefile);

$xt->display($templatefile);

?>