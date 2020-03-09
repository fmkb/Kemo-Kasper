<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/top_variables.php");
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
$includes.="var SUGGEST_TABLE = \"top_searchsuggest.php\";\r\n";
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
	document.getElementById('second_t_id').style.display =  
		document.forms.editform.elements['asearchopt_t_id'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_t_datetime').style.display =  
		document.forms.editform.elements['asearchopt_t_datetime'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_t_p_id').style.display =  
		document.forms.editform.elements['asearchopt_t_p_id'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_t_ts_id').style.display =  
		document.forms.editform.elements['asearchopt_t_ts_id'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_t_user').style.display =  
		document.forms.editform.elements['asearchopt_t_user'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_t_score').style.display =  
		document.forms.editform.elements['asearchopt_t_score'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_t_kills').style.display =  
		document.forms.editform.elements['asearchopt_t_kills'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_t_ip').style.display =  
		document.forms.editform.elements['asearchopt_t_ip'].value==\"Between\" ? '' : 'none'; 
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
	document.forms.editform.value_t_id.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_t_id,'advanced')};
	document.forms.editform.value1_t_id.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_t_id,'advanced1')};
	document.forms.editform.value_t_id.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_t_id,'advanced')};
	document.forms.editform.value1_t_id.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_t_id,'advanced1')};
	document.forms.editform.value_t_user.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_t_user,'advanced')};
	document.forms.editform.value1_t_user.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_t_user,'advanced1')};
	document.forms.editform.value_t_user.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_t_user,'advanced')};
	document.forms.editform.value1_t_user.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_t_user,'advanced1')};
	document.forms.editform.value_t_score.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_t_score,'advanced')};
	document.forms.editform.value1_t_score.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_t_score,'advanced1')};
	document.forms.editform.value_t_score.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_t_score,'advanced')};
	document.forms.editform.value1_t_score.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_t_score,'advanced1')};
	document.forms.editform.value_t_ip.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_t_ip,'advanced')};
	document.forms.editform.value1_t_ip.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_t_ip,'advanced1')};
	document.forms.editform.value_t_ip.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_t_ip,'advanced')};
	document.forms.editform.value1_t_ip.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_t_ip,'advanced1')};
	document.forms.editform.value_t_ts_id.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_t_ts_id,'advanced')};
	document.forms.editform.value1_t_ts_id.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_t_ts_id,'advanced1')};
	document.forms.editform.value_t_ts_id.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_t_ts_id,'advanced')};
	document.forms.editform.value1_t_ts_id.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_t_ts_id,'advanced1')};
	document.forms.editform.value_t_kills.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_t_kills,'advanced')};
	document.forms.editform.value1_t_kills.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_t_kills,'advanced1')};
	document.forms.editform.value_t_kills.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_t_kills,'advanced')};
	document.forms.editform.value1_t_kills.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_t_kills,'advanced1')};
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

// t_id 
$opt="";
$not=false;
$control_t_id=array();
$control_t_id["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["t_id"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["t_id"];
	$control_t_id["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["t_id"];
}
$control_t_id["func"]="xt_buildeditcontrol";
$control_t_id["params"]["field"]="t_id";
$control_t_id["params"]["mode"]="search";
$xt->assignbyref("t_id_editcontrol",$control_t_id);
$control1_t_id=$control_t_id;
$control1_t_id["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_t_id["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["t_id"];
$xt->assignbyref("t_id_editcontrol1",$control1_t_id);
	
$xt->assign_section("t_id_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"t_id\">","");
$t_id_notbox="name=\"not_t_id\"";
if($not)
	$t_id_notbox=" checked";
$xt->assign("t_id_notbox",$t_id_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_t_id\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_t_id",$searchtype);
//	edit format
$editformats["t_id"]="Text field";
// t_datetime 
$opt="";
$not=false;
$control_t_datetime=array();
$control_t_datetime["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["t_datetime"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["t_datetime"];
	$control_t_datetime["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["t_datetime"];
}
$control_t_datetime["func"]="xt_buildeditcontrol";
$control_t_datetime["params"]["field"]="t_datetime";
$control_t_datetime["params"]["mode"]="search";
$xt->assignbyref("t_datetime_editcontrol",$control_t_datetime);
$control1_t_datetime=$control_t_datetime;
$control1_t_datetime["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_t_datetime["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["t_datetime"];
$xt->assignbyref("t_datetime_editcontrol1",$control1_t_datetime);
	
$xt->assign_section("t_datetime_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"t_datetime\">","");
$t_datetime_notbox="name=\"not_t_datetime\"";
if($not)
	$t_datetime_notbox=" checked";
$xt->assign("t_datetime_notbox",$t_datetime_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">".mlang_message("EQUALS")."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">".mlang_message("MORE_THAN")."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">".mlang_message("LESS_THAN")."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">".mlang_message("EQUAL_OR_MORE")."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">".mlang_message("EQUAL_OR_LESS")."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">".mlang_message("BETWEEN")."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">".mlang_message("EMPTY")."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_t_datetime\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_t_datetime",$searchtype);
//	edit format
$editformats["t_datetime"]="Date";
// t_p_id 
$opt="";
$not=false;
$control_t_p_id=array();
$control_t_p_id["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["t_p_id"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["t_p_id"];
	$control_t_p_id["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["t_p_id"];
}
$control_t_p_id["func"]="xt_buildeditcontrol";
$control_t_p_id["params"]["field"]="t_p_id";
$control_t_p_id["params"]["mode"]="search";
$xt->assignbyref("t_p_id_editcontrol",$control_t_p_id);
$control1_t_p_id=$control_t_p_id;
$control1_t_p_id["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_t_p_id["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["t_p_id"];
$xt->assignbyref("t_p_id_editcontrol1",$control1_t_p_id);
	
$xt->assign_section("t_p_id_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"t_p_id\">","");
$t_p_id_notbox="name=\"not_t_p_id\"";
if($not)
	$t_p_id_notbox=" checked";
$xt->assign("t_p_id_notbox",$t_p_id_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">".mlang_message("EQUALS")."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_t_p_id\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_t_p_id",$searchtype);
//	edit format
$editformats["t_p_id"]="Lookup wizard";
// t_ts_id 
$opt="";
$not=false;
$control_t_ts_id=array();
$control_t_ts_id["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["t_ts_id"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["t_ts_id"];
	$control_t_ts_id["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["t_ts_id"];
}
$control_t_ts_id["func"]="xt_buildeditcontrol";
$control_t_ts_id["params"]["field"]="t_ts_id";
$control_t_ts_id["params"]["mode"]="search";
$xt->assignbyref("t_ts_id_editcontrol",$control_t_ts_id);
$control1_t_ts_id=$control_t_ts_id;
$control1_t_ts_id["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_t_ts_id["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["t_ts_id"];
$xt->assignbyref("t_ts_id_editcontrol1",$control1_t_ts_id);
	
$xt->assign_section("t_ts_id_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"t_ts_id\">","");
$t_ts_id_notbox="name=\"not_t_ts_id\"";
if($not)
	$t_ts_id_notbox=" checked";
$xt->assign("t_ts_id_notbox",$t_ts_id_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_t_ts_id\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_t_ts_id",$searchtype);
//	edit format
$editformats["t_ts_id"]="Text field";
// t_user 
$opt="";
$not=false;
$control_t_user=array();
$control_t_user["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["t_user"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["t_user"];
	$control_t_user["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["t_user"];
}
$control_t_user["func"]="xt_buildeditcontrol";
$control_t_user["params"]["field"]="t_user";
$control_t_user["params"]["mode"]="search";
$xt->assignbyref("t_user_editcontrol",$control_t_user);
$control1_t_user=$control_t_user;
$control1_t_user["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_t_user["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["t_user"];
$xt->assignbyref("t_user_editcontrol1",$control1_t_user);
	
$xt->assign_section("t_user_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"t_user\">","");
$t_user_notbox="name=\"not_t_user\"";
if($not)
	$t_user_notbox=" checked";
$xt->assign("t_user_notbox",$t_user_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_t_user\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_t_user",$searchtype);
//	edit format
$editformats["t_user"]="Text field";
// t_score 
$opt="";
$not=false;
$control_t_score=array();
$control_t_score["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["t_score"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["t_score"];
	$control_t_score["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["t_score"];
}
$control_t_score["func"]="xt_buildeditcontrol";
$control_t_score["params"]["field"]="t_score";
$control_t_score["params"]["mode"]="search";
$xt->assignbyref("t_score_editcontrol",$control_t_score);
$control1_t_score=$control_t_score;
$control1_t_score["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_t_score["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["t_score"];
$xt->assignbyref("t_score_editcontrol1",$control1_t_score);
	
$xt->assign_section("t_score_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"t_score\">","");
$t_score_notbox="name=\"not_t_score\"";
if($not)
	$t_score_notbox=" checked";
$xt->assign("t_score_notbox",$t_score_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_t_score\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_t_score",$searchtype);
//	edit format
$editformats["t_score"]="Text field";
// t_kills 
$opt="";
$not=false;
$control_t_kills=array();
$control_t_kills["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["t_kills"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["t_kills"];
	$control_t_kills["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["t_kills"];
}
$control_t_kills["func"]="xt_buildeditcontrol";
$control_t_kills["params"]["field"]="t_kills";
$control_t_kills["params"]["mode"]="search";
$xt->assignbyref("t_kills_editcontrol",$control_t_kills);
$control1_t_kills=$control_t_kills;
$control1_t_kills["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_t_kills["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["t_kills"];
$xt->assignbyref("t_kills_editcontrol1",$control1_t_kills);
	
$xt->assign_section("t_kills_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"t_kills\">","");
$t_kills_notbox="name=\"not_t_kills\"";
if($not)
	$t_kills_notbox=" checked";
$xt->assign("t_kills_notbox",$t_kills_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_t_kills\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_t_kills",$searchtype);
//	edit format
$editformats["t_kills"]="Text field";
// t_ip 
$opt="";
$not=false;
$control_t_ip=array();
$control_t_ip["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["t_ip"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["t_ip"];
	$control_t_ip["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["t_ip"];
}
$control_t_ip["func"]="xt_buildeditcontrol";
$control_t_ip["params"]["field"]="t_ip";
$control_t_ip["params"]["mode"]="search";
$xt->assignbyref("t_ip_editcontrol",$control_t_ip);
$control1_t_ip=$control_t_ip;
$control1_t_ip["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_t_ip["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["t_ip"];
$xt->assignbyref("t_ip_editcontrol1",$control1_t_ip);
	
$xt->assign_section("t_ip_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"t_ip\">","");
$t_ip_notbox="name=\"not_t_ip\"";
if($not)
	$t_ip_notbox=" checked";
$xt->assign("t_ip_notbox",$t_ip_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_t_ip\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_t_ip",$searchtype);
//	edit format
$editformats["t_ip"]="Text field";

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
$contents_block["begin"].="action=\"top_list.php\" ";
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
	
$templatefile = "top_search.htm";
if(function_exists("BeforeShowSearch"))
	BeforeShowSearch($xt,$templatefile);

$xt->display($templatefile);

?>