<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/player_variables.php");
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
$includes.="var SUGGEST_TABLE = \"player_searchsuggest.php\";\r\n";
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
	document.getElementById('second_p_active').style.display =  
		document.forms.editform.elements['asearchopt_p_active'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_p_s_id').style.display =  
		document.forms.editform.elements['asearchopt_p_s_id'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_p_first').style.display =  
		document.forms.editform.elements['asearchopt_p_first'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_p_name').style.display =  
		document.forms.editform.elements['asearchopt_p_name'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_p_adr').style.display =  
		document.forms.editform.elements['asearchopt_p_adr'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_p_zip').style.display =  
		document.forms.editform.elements['asearchopt_p_zip'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_p_country').style.display =  
		document.forms.editform.elements['asearchopt_p_country'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_p_mail').style.display =  
		document.forms.editform.elements['asearchopt_p_mail'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_p_mobile').style.display =  
		document.forms.editform.elements['asearchopt_p_mobile'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_p_newsaccept').style.display =  
		document.forms.editform.elements['asearchopt_p_newsaccept'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_p_score').style.display =  
		document.forms.editform.elements['asearchopt_p_score'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_p_scorehigh').style.display =  
		document.forms.editform.elements['asearchopt_p_scorehigh'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_p_games').style.display =  
		document.forms.editform.elements['asearchopt_p_games'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_p_win').style.display =  
		document.forms.editform.elements['asearchopt_p_win'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_p_mk').style.display =  
		document.forms.editform.elements['asearchopt_p_mk'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_p_born').style.display =  
		document.forms.editform.elements['asearchopt_p_born'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_p_user').style.display =  
		document.forms.editform.elements['asearchopt_p_user'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_p_pwd').style.display =  
		document.forms.editform.elements['asearchopt_p_pwd'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_p_ip').style.display =  
		document.forms.editform.elements['asearchopt_p_ip'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_p_datetime').style.display =  
		document.forms.editform.elements['asearchopt_p_datetime'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_p_tscore').style.display =  
		document.forms.editform.elements['asearchopt_p_tscore'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_p_tkills').style.display =  
		document.forms.editform.elements['asearchopt_p_tkills'].value==\"Between\" ? '' : 'none'; 
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
	document.forms.editform.value_p_first.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_p_first,'advanced')};
	document.forms.editform.value1_p_first.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_p_first,'advanced1')};
	document.forms.editform.value_p_first.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_p_first,'advanced')};
	document.forms.editform.value1_p_first.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_p_first,'advanced1')};
	document.forms.editform.value_p_name.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_p_name,'advanced')};
	document.forms.editform.value1_p_name.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_p_name,'advanced1')};
	document.forms.editform.value_p_name.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_p_name,'advanced')};
	document.forms.editform.value1_p_name.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_p_name,'advanced1')};
	document.forms.editform.value_p_adr.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_p_adr,'advanced')};
	document.forms.editform.value1_p_adr.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_p_adr,'advanced1')};
	document.forms.editform.value_p_adr.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_p_adr,'advanced')};
	document.forms.editform.value1_p_adr.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_p_adr,'advanced1')};
	document.forms.editform.value_p_zip.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_p_zip,'advanced')};
	document.forms.editform.value1_p_zip.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_p_zip,'advanced1')};
	document.forms.editform.value_p_zip.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_p_zip,'advanced')};
	document.forms.editform.value1_p_zip.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_p_zip,'advanced1')};
	document.forms.editform.value_p_mail.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_p_mail,'advanced')};
	document.forms.editform.value1_p_mail.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_p_mail,'advanced1')};
	document.forms.editform.value_p_mail.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_p_mail,'advanced')};
	document.forms.editform.value1_p_mail.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_p_mail,'advanced1')};
	document.forms.editform.value_p_score.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_p_score,'advanced')};
	document.forms.editform.value1_p_score.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_p_score,'advanced1')};
	document.forms.editform.value_p_score.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_p_score,'advanced')};
	document.forms.editform.value1_p_score.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_p_score,'advanced1')};
	document.forms.editform.value_p_scorehigh.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_p_scorehigh,'advanced')};
	document.forms.editform.value1_p_scorehigh.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_p_scorehigh,'advanced1')};
	document.forms.editform.value_p_scorehigh.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_p_scorehigh,'advanced')};
	document.forms.editform.value1_p_scorehigh.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_p_scorehigh,'advanced1')};
	document.forms.editform.value_p_games.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_p_games,'advanced')};
	document.forms.editform.value1_p_games.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_p_games,'advanced1')};
	document.forms.editform.value_p_games.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_p_games,'advanced')};
	document.forms.editform.value1_p_games.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_p_games,'advanced1')};
	document.forms.editform.value_p_win.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_p_win,'advanced')};
	document.forms.editform.value1_p_win.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_p_win,'advanced1')};
	document.forms.editform.value_p_win.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_p_win,'advanced')};
	document.forms.editform.value1_p_win.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_p_win,'advanced1')};
	document.forms.editform.value_p_mk.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_p_mk,'advanced')};
	document.forms.editform.value1_p_mk.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_p_mk,'advanced1')};
	document.forms.editform.value_p_mk.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_p_mk,'advanced')};
	document.forms.editform.value1_p_mk.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_p_mk,'advanced1')};
	document.forms.editform.value_p_born.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_p_born,'advanced')};
	document.forms.editform.value1_p_born.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_p_born,'advanced1')};
	document.forms.editform.value_p_born.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_p_born,'advanced')};
	document.forms.editform.value1_p_born.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_p_born,'advanced1')};
	document.forms.editform.value_p_user.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_p_user,'advanced')};
	document.forms.editform.value1_p_user.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_p_user,'advanced1')};
	document.forms.editform.value_p_user.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_p_user,'advanced')};
	document.forms.editform.value1_p_user.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_p_user,'advanced1')};
	document.forms.editform.value_p_pwd.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_p_pwd,'advanced')};
	document.forms.editform.value1_p_pwd.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_p_pwd,'advanced1')};
	document.forms.editform.value_p_pwd.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_p_pwd,'advanced')};
	document.forms.editform.value1_p_pwd.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_p_pwd,'advanced1')};
	document.forms.editform.value_p_ip.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_p_ip,'advanced')};
	document.forms.editform.value1_p_ip.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_p_ip,'advanced1')};
	document.forms.editform.value_p_ip.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_p_ip,'advanced')};
	document.forms.editform.value1_p_ip.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_p_ip,'advanced1')};
	document.forms.editform.value_p_tscore.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_p_tscore,'advanced')};
	document.forms.editform.value1_p_tscore.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_p_tscore,'advanced1')};
	document.forms.editform.value_p_tscore.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_p_tscore,'advanced')};
	document.forms.editform.value1_p_tscore.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_p_tscore,'advanced1')};
	document.forms.editform.value_p_tkills.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_p_tkills,'advanced')};
	document.forms.editform.value1_p_tkills.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_p_tkills,'advanced1')};
	document.forms.editform.value_p_tkills.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_p_tkills,'advanced')};
	document.forms.editform.value1_p_tkills.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_p_tkills,'advanced1')};
	document.forms.editform.value_p_country.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_p_country,'advanced')};
	document.forms.editform.value1_p_country.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_p_country,'advanced1')};
	document.forms.editform.value_p_country.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_p_country,'advanced')};
	document.forms.editform.value1_p_country.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_p_country,'advanced1')};
	document.forms.editform.value_p_mobile.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_p_mobile,'advanced')};
	document.forms.editform.value1_p_mobile.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_p_mobile,'advanced1')};
	document.forms.editform.value_p_mobile.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_p_mobile,'advanced')};
	document.forms.editform.value1_p_mobile.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_p_mobile,'advanced1')};
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

// p_active 
$opt="";
$not=false;
$control_p_active=array();
$control_p_active["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["p_active"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["p_active"];
	$control_p_active["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["p_active"];
}
$control_p_active["func"]="xt_buildeditcontrol";
$control_p_active["params"]["field"]="p_active";
$control_p_active["params"]["mode"]="search";
$xt->assignbyref("p_active_editcontrol",$control_p_active);
$control1_p_active=$control_p_active;
$control1_p_active["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_p_active["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["p_active"];
$xt->assignbyref("p_active_editcontrol1",$control1_p_active);
	
$xt->assign_section("p_active_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"p_active\">","");
$p_active_notbox="name=\"not_p_active\"";
if($not)
	$p_active_notbox=" checked";
$xt->assign("p_active_notbox",$p_active_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">".mlang_message("EQUALS")."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_p_active\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_p_active",$searchtype);
//	edit format
$editformats["p_active"]="Lookup wizard";
// p_s_id 
$opt="";
$not=false;
$control_p_s_id=array();
$control_p_s_id["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["p_s_id"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["p_s_id"];
	$control_p_s_id["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["p_s_id"];
}
$control_p_s_id["func"]="xt_buildeditcontrol";
$control_p_s_id["params"]["field"]="p_s_id";
$control_p_s_id["params"]["mode"]="search";
$xt->assignbyref("p_s_id_editcontrol",$control_p_s_id);
$control1_p_s_id=$control_p_s_id;
$control1_p_s_id["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_p_s_id["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["p_s_id"];
$xt->assignbyref("p_s_id_editcontrol1",$control1_p_s_id);
	
$xt->assign_section("p_s_id_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"p_s_id\">","");
$p_s_id_notbox="name=\"not_p_s_id\"";
if($not)
	$p_s_id_notbox=" checked";
$xt->assign("p_s_id_notbox",$p_s_id_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">".mlang_message("EQUALS")."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_p_s_id\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_p_s_id",$searchtype);
//	edit format
$editformats["p_s_id"]="Lookup wizard";
// p_first 
$opt="";
$not=false;
$control_p_first=array();
$control_p_first["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["p_first"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["p_first"];
	$control_p_first["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["p_first"];
}
$control_p_first["func"]="xt_buildeditcontrol";
$control_p_first["params"]["field"]="p_first";
$control_p_first["params"]["mode"]="search";
$xt->assignbyref("p_first_editcontrol",$control_p_first);
$control1_p_first=$control_p_first;
$control1_p_first["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_p_first["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["p_first"];
$xt->assignbyref("p_first_editcontrol1",$control1_p_first);
	
$xt->assign_section("p_first_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"p_first\">","");
$p_first_notbox="name=\"not_p_first\"";
if($not)
	$p_first_notbox=" checked";
$xt->assign("p_first_notbox",$p_first_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_p_first\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_p_first",$searchtype);
//	edit format
$editformats["p_first"]="Text field";
// p_name 
$opt="";
$not=false;
$control_p_name=array();
$control_p_name["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["p_name"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["p_name"];
	$control_p_name["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["p_name"];
}
$control_p_name["func"]="xt_buildeditcontrol";
$control_p_name["params"]["field"]="p_name";
$control_p_name["params"]["mode"]="search";
$xt->assignbyref("p_name_editcontrol",$control_p_name);
$control1_p_name=$control_p_name;
$control1_p_name["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_p_name["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["p_name"];
$xt->assignbyref("p_name_editcontrol1",$control1_p_name);
	
$xt->assign_section("p_name_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"p_name\">","");
$p_name_notbox="name=\"not_p_name\"";
if($not)
	$p_name_notbox=" checked";
$xt->assign("p_name_notbox",$p_name_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_p_name\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_p_name",$searchtype);
//	edit format
$editformats["p_name"]="Text field";
// p_adr 
$opt="";
$not=false;
$control_p_adr=array();
$control_p_adr["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["p_adr"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["p_adr"];
	$control_p_adr["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["p_adr"];
}
$control_p_adr["func"]="xt_buildeditcontrol";
$control_p_adr["params"]["field"]="p_adr";
$control_p_adr["params"]["mode"]="search";
$xt->assignbyref("p_adr_editcontrol",$control_p_adr);
$control1_p_adr=$control_p_adr;
$control1_p_adr["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_p_adr["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["p_adr"];
$xt->assignbyref("p_adr_editcontrol1",$control1_p_adr);
	
$xt->assign_section("p_adr_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"p_adr\">","");
$p_adr_notbox="name=\"not_p_adr\"";
if($not)
	$p_adr_notbox=" checked";
$xt->assign("p_adr_notbox",$p_adr_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_p_adr\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_p_adr",$searchtype);
//	edit format
$editformats["p_adr"]="Text field";
// p_zip 
$opt="";
$not=false;
$control_p_zip=array();
$control_p_zip["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["p_zip"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["p_zip"];
	$control_p_zip["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["p_zip"];
}
$control_p_zip["func"]="xt_buildeditcontrol";
$control_p_zip["params"]["field"]="p_zip";
$control_p_zip["params"]["mode"]="search";
$xt->assignbyref("p_zip_editcontrol",$control_p_zip);
$control1_p_zip=$control_p_zip;
$control1_p_zip["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_p_zip["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["p_zip"];
$xt->assignbyref("p_zip_editcontrol1",$control1_p_zip);
	
$xt->assign_section("p_zip_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"p_zip\">","");
$p_zip_notbox="name=\"not_p_zip\"";
if($not)
	$p_zip_notbox=" checked";
$xt->assign("p_zip_notbox",$p_zip_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_p_zip\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_p_zip",$searchtype);
//	edit format
$editformats["p_zip"]="Text field";
// p_country 
$opt="";
$not=false;
$control_p_country=array();
$control_p_country["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["p_country"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["p_country"];
	$control_p_country["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["p_country"];
}
$control_p_country["func"]="xt_buildeditcontrol";
$control_p_country["params"]["field"]="p_country";
$control_p_country["params"]["mode"]="search";
$xt->assignbyref("p_country_editcontrol",$control_p_country);
$control1_p_country=$control_p_country;
$control1_p_country["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_p_country["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["p_country"];
$xt->assignbyref("p_country_editcontrol1",$control1_p_country);
	
$xt->assign_section("p_country_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"p_country\">","");
$p_country_notbox="name=\"not_p_country\"";
if($not)
	$p_country_notbox=" checked";
$xt->assign("p_country_notbox",$p_country_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_p_country\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_p_country",$searchtype);
//	edit format
$editformats["p_country"]="Text field";
// p_mail 
$opt="";
$not=false;
$control_p_mail=array();
$control_p_mail["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["p_mail"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["p_mail"];
	$control_p_mail["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["p_mail"];
}
$control_p_mail["func"]="xt_buildeditcontrol";
$control_p_mail["params"]["field"]="p_mail";
$control_p_mail["params"]["mode"]="search";
$xt->assignbyref("p_mail_editcontrol",$control_p_mail);
$control1_p_mail=$control_p_mail;
$control1_p_mail["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_p_mail["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["p_mail"];
$xt->assignbyref("p_mail_editcontrol1",$control1_p_mail);
	
$xt->assign_section("p_mail_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"p_mail\">","");
$p_mail_notbox="name=\"not_p_mail\"";
if($not)
	$p_mail_notbox=" checked";
$xt->assign("p_mail_notbox",$p_mail_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_p_mail\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_p_mail",$searchtype);
//	edit format
$editformats["p_mail"]="Text field";
// p_mobile 
$opt="";
$not=false;
$control_p_mobile=array();
$control_p_mobile["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["p_mobile"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["p_mobile"];
	$control_p_mobile["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["p_mobile"];
}
$control_p_mobile["func"]="xt_buildeditcontrol";
$control_p_mobile["params"]["field"]="p_mobile";
$control_p_mobile["params"]["mode"]="search";
$xt->assignbyref("p_mobile_editcontrol",$control_p_mobile);
$control1_p_mobile=$control_p_mobile;
$control1_p_mobile["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_p_mobile["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["p_mobile"];
$xt->assignbyref("p_mobile_editcontrol1",$control1_p_mobile);
	
$xt->assign_section("p_mobile_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"p_mobile\">","");
$p_mobile_notbox="name=\"not_p_mobile\"";
if($not)
	$p_mobile_notbox=" checked";
$xt->assign("p_mobile_notbox",$p_mobile_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_p_mobile\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_p_mobile",$searchtype);
//	edit format
$editformats["p_mobile"]="Text field";
// p_newsaccept 
$opt="";
$not=false;
$control_p_newsaccept=array();
$control_p_newsaccept["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["p_newsaccept"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["p_newsaccept"];
	$control_p_newsaccept["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["p_newsaccept"];
}
$control_p_newsaccept["func"]="xt_buildeditcontrol";
$control_p_newsaccept["params"]["field"]="p_newsaccept";
$control_p_newsaccept["params"]["mode"]="search";
$xt->assignbyref("p_newsaccept_editcontrol",$control_p_newsaccept);
$control1_p_newsaccept=$control_p_newsaccept;
$control1_p_newsaccept["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_p_newsaccept["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["p_newsaccept"];
$xt->assignbyref("p_newsaccept_editcontrol1",$control1_p_newsaccept);
	
$xt->assign_section("p_newsaccept_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"p_newsaccept\">","");
$p_newsaccept_notbox="name=\"not_p_newsaccept\"";
if($not)
	$p_newsaccept_notbox=" checked";
$xt->assign("p_newsaccept_notbox",$p_newsaccept_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">".mlang_message("EQUALS")."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_p_newsaccept\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_p_newsaccept",$searchtype);
//	edit format
$editformats["p_newsaccept"]="Checkbox";
// p_score 
$opt="";
$not=false;
$control_p_score=array();
$control_p_score["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["p_score"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["p_score"];
	$control_p_score["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["p_score"];
}
$control_p_score["func"]="xt_buildeditcontrol";
$control_p_score["params"]["field"]="p_score";
$control_p_score["params"]["mode"]="search";
$xt->assignbyref("p_score_editcontrol",$control_p_score);
$control1_p_score=$control_p_score;
$control1_p_score["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_p_score["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["p_score"];
$xt->assignbyref("p_score_editcontrol1",$control1_p_score);
	
$xt->assign_section("p_score_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"p_score\">","");
$p_score_notbox="name=\"not_p_score\"";
if($not)
	$p_score_notbox=" checked";
$xt->assign("p_score_notbox",$p_score_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_p_score\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_p_score",$searchtype);
//	edit format
$editformats["p_score"]="Text field";
// p_scorehigh 
$opt="";
$not=false;
$control_p_scorehigh=array();
$control_p_scorehigh["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["p_scorehigh"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["p_scorehigh"];
	$control_p_scorehigh["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["p_scorehigh"];
}
$control_p_scorehigh["func"]="xt_buildeditcontrol";
$control_p_scorehigh["params"]["field"]="p_scorehigh";
$control_p_scorehigh["params"]["mode"]="search";
$xt->assignbyref("p_scorehigh_editcontrol",$control_p_scorehigh);
$control1_p_scorehigh=$control_p_scorehigh;
$control1_p_scorehigh["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_p_scorehigh["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["p_scorehigh"];
$xt->assignbyref("p_scorehigh_editcontrol1",$control1_p_scorehigh);
	
$xt->assign_section("p_scorehigh_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"p_scorehigh\">","");
$p_scorehigh_notbox="name=\"not_p_scorehigh\"";
if($not)
	$p_scorehigh_notbox=" checked";
$xt->assign("p_scorehigh_notbox",$p_scorehigh_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_p_scorehigh\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_p_scorehigh",$searchtype);
//	edit format
$editformats["p_scorehigh"]="Text field";
// p_games 
$opt="";
$not=false;
$control_p_games=array();
$control_p_games["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["p_games"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["p_games"];
	$control_p_games["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["p_games"];
}
$control_p_games["func"]="xt_buildeditcontrol";
$control_p_games["params"]["field"]="p_games";
$control_p_games["params"]["mode"]="search";
$xt->assignbyref("p_games_editcontrol",$control_p_games);
$control1_p_games=$control_p_games;
$control1_p_games["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_p_games["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["p_games"];
$xt->assignbyref("p_games_editcontrol1",$control1_p_games);
	
$xt->assign_section("p_games_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"p_games\">","");
$p_games_notbox="name=\"not_p_games\"";
if($not)
	$p_games_notbox=" checked";
$xt->assign("p_games_notbox",$p_games_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_p_games\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_p_games",$searchtype);
//	edit format
$editformats["p_games"]="Text field";
// p_win 
$opt="";
$not=false;
$control_p_win=array();
$control_p_win["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["p_win"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["p_win"];
	$control_p_win["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["p_win"];
}
$control_p_win["func"]="xt_buildeditcontrol";
$control_p_win["params"]["field"]="p_win";
$control_p_win["params"]["mode"]="search";
$xt->assignbyref("p_win_editcontrol",$control_p_win);
$control1_p_win=$control_p_win;
$control1_p_win["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_p_win["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["p_win"];
$xt->assignbyref("p_win_editcontrol1",$control1_p_win);
	
$xt->assign_section("p_win_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"p_win\">","");
$p_win_notbox="name=\"not_p_win\"";
if($not)
	$p_win_notbox=" checked";
$xt->assign("p_win_notbox",$p_win_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_p_win\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_p_win",$searchtype);
//	edit format
$editformats["p_win"]="Text field";
// p_mk 
$opt="";
$not=false;
$control_p_mk=array();
$control_p_mk["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["p_mk"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["p_mk"];
	$control_p_mk["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["p_mk"];
}
$control_p_mk["func"]="xt_buildeditcontrol";
$control_p_mk["params"]["field"]="p_mk";
$control_p_mk["params"]["mode"]="search";
$xt->assignbyref("p_mk_editcontrol",$control_p_mk);
$control1_p_mk=$control_p_mk;
$control1_p_mk["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_p_mk["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["p_mk"];
$xt->assignbyref("p_mk_editcontrol1",$control1_p_mk);
	
$xt->assign_section("p_mk_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"p_mk\">","");
$p_mk_notbox="name=\"not_p_mk\"";
if($not)
	$p_mk_notbox=" checked";
$xt->assign("p_mk_notbox",$p_mk_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_p_mk\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_p_mk",$searchtype);
//	edit format
$editformats["p_mk"]="Text field";
// p_born 
$opt="";
$not=false;
$control_p_born=array();
$control_p_born["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["p_born"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["p_born"];
	$control_p_born["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["p_born"];
}
$control_p_born["func"]="xt_buildeditcontrol";
$control_p_born["params"]["field"]="p_born";
$control_p_born["params"]["mode"]="search";
$xt->assignbyref("p_born_editcontrol",$control_p_born);
$control1_p_born=$control_p_born;
$control1_p_born["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_p_born["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["p_born"];
$xt->assignbyref("p_born_editcontrol1",$control1_p_born);
	
$xt->assign_section("p_born_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"p_born\">","");
$p_born_notbox="name=\"not_p_born\"";
if($not)
	$p_born_notbox=" checked";
$xt->assign("p_born_notbox",$p_born_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_p_born\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_p_born",$searchtype);
//	edit format
$editformats["p_born"]="Text field";
// p_user 
$opt="";
$not=false;
$control_p_user=array();
$control_p_user["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["p_user"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["p_user"];
	$control_p_user["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["p_user"];
}
$control_p_user["func"]="xt_buildeditcontrol";
$control_p_user["params"]["field"]="p_user";
$control_p_user["params"]["mode"]="search";
$xt->assignbyref("p_user_editcontrol",$control_p_user);
$control1_p_user=$control_p_user;
$control1_p_user["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_p_user["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["p_user"];
$xt->assignbyref("p_user_editcontrol1",$control1_p_user);
	
$xt->assign_section("p_user_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"p_user\">","");
$p_user_notbox="name=\"not_p_user\"";
if($not)
	$p_user_notbox=" checked";
$xt->assign("p_user_notbox",$p_user_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_p_user\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_p_user",$searchtype);
//	edit format
$editformats["p_user"]="Text field";
// p_pwd 
$opt="";
$not=false;
$control_p_pwd=array();
$control_p_pwd["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["p_pwd"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["p_pwd"];
	$control_p_pwd["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["p_pwd"];
}
$control_p_pwd["func"]="xt_buildeditcontrol";
$control_p_pwd["params"]["field"]="p_pwd";
$control_p_pwd["params"]["mode"]="search";
$xt->assignbyref("p_pwd_editcontrol",$control_p_pwd);
$control1_p_pwd=$control_p_pwd;
$control1_p_pwd["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_p_pwd["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["p_pwd"];
$xt->assignbyref("p_pwd_editcontrol1",$control1_p_pwd);
	
$xt->assign_section("p_pwd_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"p_pwd\">","");
$p_pwd_notbox="name=\"not_p_pwd\"";
if($not)
	$p_pwd_notbox=" checked";
$xt->assign("p_pwd_notbox",$p_pwd_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_p_pwd\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_p_pwd",$searchtype);
//	edit format
$editformats["p_pwd"]="Text field";
// p_ip 
$opt="";
$not=false;
$control_p_ip=array();
$control_p_ip["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["p_ip"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["p_ip"];
	$control_p_ip["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["p_ip"];
}
$control_p_ip["func"]="xt_buildeditcontrol";
$control_p_ip["params"]["field"]="p_ip";
$control_p_ip["params"]["mode"]="search";
$xt->assignbyref("p_ip_editcontrol",$control_p_ip);
$control1_p_ip=$control_p_ip;
$control1_p_ip["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_p_ip["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["p_ip"];
$xt->assignbyref("p_ip_editcontrol1",$control1_p_ip);
	
$xt->assign_section("p_ip_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"p_ip\">","");
$p_ip_notbox="name=\"not_p_ip\"";
if($not)
	$p_ip_notbox=" checked";
$xt->assign("p_ip_notbox",$p_ip_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_p_ip\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_p_ip",$searchtype);
//	edit format
$editformats["p_ip"]="Text field";
// p_datetime 
$opt="";
$not=false;
$control_p_datetime=array();
$control_p_datetime["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["p_datetime"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["p_datetime"];
	$control_p_datetime["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["p_datetime"];
}
$control_p_datetime["func"]="xt_buildeditcontrol";
$control_p_datetime["params"]["field"]="p_datetime";
$control_p_datetime["params"]["mode"]="search";
$xt->assignbyref("p_datetime_editcontrol",$control_p_datetime);
$control1_p_datetime=$control_p_datetime;
$control1_p_datetime["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_p_datetime["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["p_datetime"];
$xt->assignbyref("p_datetime_editcontrol1",$control1_p_datetime);
	
$xt->assign_section("p_datetime_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"p_datetime\">","");
$p_datetime_notbox="name=\"not_p_datetime\"";
if($not)
	$p_datetime_notbox=" checked";
$xt->assign("p_datetime_notbox",$p_datetime_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">".mlang_message("EQUALS")."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">".mlang_message("MORE_THAN")."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">".mlang_message("LESS_THAN")."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">".mlang_message("EQUAL_OR_MORE")."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">".mlang_message("EQUAL_OR_LESS")."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">".mlang_message("BETWEEN")."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">".mlang_message("EMPTY")."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_p_datetime\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_p_datetime",$searchtype);
//	edit format
$editformats["p_datetime"]="Date";
// p_tscore 
$opt="";
$not=false;
$control_p_tscore=array();
$control_p_tscore["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["p_tscore"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["p_tscore"];
	$control_p_tscore["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["p_tscore"];
}
$control_p_tscore["func"]="xt_buildeditcontrol";
$control_p_tscore["params"]["field"]="p_tscore";
$control_p_tscore["params"]["mode"]="search";
$xt->assignbyref("p_tscore_editcontrol",$control_p_tscore);
$control1_p_tscore=$control_p_tscore;
$control1_p_tscore["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_p_tscore["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["p_tscore"];
$xt->assignbyref("p_tscore_editcontrol1",$control1_p_tscore);
	
$xt->assign_section("p_tscore_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"p_tscore\">","");
$p_tscore_notbox="name=\"not_p_tscore\"";
if($not)
	$p_tscore_notbox=" checked";
$xt->assign("p_tscore_notbox",$p_tscore_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_p_tscore\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_p_tscore",$searchtype);
//	edit format
$editformats["p_tscore"]="Text field";
// p_tkills 
$opt="";
$not=false;
$control_p_tkills=array();
$control_p_tkills["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["p_tkills"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["p_tkills"];
	$control_p_tkills["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["p_tkills"];
}
$control_p_tkills["func"]="xt_buildeditcontrol";
$control_p_tkills["params"]["field"]="p_tkills";
$control_p_tkills["params"]["mode"]="search";
$xt->assignbyref("p_tkills_editcontrol",$control_p_tkills);
$control1_p_tkills=$control_p_tkills;
$control1_p_tkills["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_p_tkills["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["p_tkills"];
$xt->assignbyref("p_tkills_editcontrol1",$control1_p_tkills);
	
$xt->assign_section("p_tkills_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"p_tkills\">","");
$p_tkills_notbox="name=\"not_p_tkills\"";
if($not)
	$p_tkills_notbox=" checked";
$xt->assign("p_tkills_notbox",$p_tkills_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_p_tkills\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_p_tkills",$searchtype);
//	edit format
$editformats["p_tkills"]="Text field";

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
$contents_block["begin"].="action=\"player_list.php\" ";
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
	
$templatefile = "player_search.htm";
if(function_exists("BeforeShowSearch"))
	BeforeShowSearch($xt,$templatefile);

$xt->display($templatefile);

?>