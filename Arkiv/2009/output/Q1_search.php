<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/Q1_variables.php");
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
$includes.="var SUGGEST_TABLE = \"Q1_searchsuggest.php\";\r\n";
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
	document.getElementById('second_Q1_id').style.display =  
		document.forms.editform.elements['asearchopt_Q1_id'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_Q1_work').style.display =  
		document.forms.editform.elements['asearchopt_Q1_work'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_Q1_where').style.display =  
		document.forms.editform.elements['asearchopt_Q1_where'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_Q1_why').style.display =  
		document.forms.editform.elements['asearchopt_Q1_why'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_Q1_rating').style.display =  
		document.forms.editform.elements['asearchopt_Q1_rating'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_Q1_speakothers').style.display =  
		document.forms.editform.elements['asearchopt_Q1_speakothers'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_Q1_changedme').style.display =  
		document.forms.editform.elements['asearchopt_Q1_changedme'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_Q1_playoften').style.display =  
		document.forms.editform.elements['asearchopt_Q1_playoften'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_Q1_infolevel').style.display =  
		document.forms.editform.elements['asearchopt_Q1_infolevel'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_Q1_SNotice').style.display =  
		document.forms.editform.elements['asearchopt_Q1_SNotice'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_Q1_SNoticewhere').style.display =  
		document.forms.editform.elements['asearchopt_Q1_SNoticewhere'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_Q1_SNoticemainsponsor').style.display =  
		document.forms.editform.elements['asearchopt_Q1_SNoticemainsponsor'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_Q1_SNoticeteam').style.display =  
		document.forms.editform.elements['asearchopt_Q1_SNoticeteam'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_Q1_SNoticeother').style.display =  
		document.forms.editform.elements['asearchopt_Q1_SNoticeother'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_Q1_Shavechangedview').style.display =  
		document.forms.editform.elements['asearchopt_Q1_Shavechangedview'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_Q1_Sbuyproducts').style.display =  
		document.forms.editform.elements['asearchopt_Q1_Sbuyproducts'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_Q1_Snameothers').style.display =  
		document.forms.editform.elements['asearchopt_Q1_Snameothers'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_Q1_Comments').style.display =  
		document.forms.editform.elements['asearchopt_Q1_Comments'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_Q1_p_id').style.display =  
		document.forms.editform.elements['asearchopt_Q1_p_id'].value==\"Between\" ? '' : 'none'; 
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
	document.forms.editform.value_Q1_id.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_Q1_id,'advanced')};
	document.forms.editform.value1_Q1_id.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_Q1_id,'advanced1')};
	document.forms.editform.value_Q1_id.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_Q1_id,'advanced')};
	document.forms.editform.value1_Q1_id.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_Q1_id,'advanced1')};
	document.forms.editform.value_Q1_work.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_Q1_work,'advanced')};
	document.forms.editform.value1_Q1_work.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_Q1_work,'advanced1')};
	document.forms.editform.value_Q1_work.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_Q1_work,'advanced')};
	document.forms.editform.value1_Q1_work.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_Q1_work,'advanced1')};
	document.forms.editform.value_Q1_where.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_Q1_where,'advanced')};
	document.forms.editform.value1_Q1_where.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_Q1_where,'advanced1')};
	document.forms.editform.value_Q1_where.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_Q1_where,'advanced')};
	document.forms.editform.value1_Q1_where.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_Q1_where,'advanced1')};
	document.forms.editform.value_Q1_why.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_Q1_why,'advanced')};
	document.forms.editform.value1_Q1_why.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_Q1_why,'advanced1')};
	document.forms.editform.value_Q1_why.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_Q1_why,'advanced')};
	document.forms.editform.value1_Q1_why.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_Q1_why,'advanced1')};
	document.forms.editform.value_Q1_rating.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_Q1_rating,'advanced')};
	document.forms.editform.value1_Q1_rating.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_Q1_rating,'advanced1')};
	document.forms.editform.value_Q1_rating.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_Q1_rating,'advanced')};
	document.forms.editform.value1_Q1_rating.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_Q1_rating,'advanced1')};
	document.forms.editform.value_Q1_speakothers.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_Q1_speakothers,'advanced')};
	document.forms.editform.value1_Q1_speakothers.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_Q1_speakothers,'advanced1')};
	document.forms.editform.value_Q1_speakothers.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_Q1_speakothers,'advanced')};
	document.forms.editform.value1_Q1_speakothers.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_Q1_speakothers,'advanced1')};
	document.forms.editform.value_Q1_changedme.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_Q1_changedme,'advanced')};
	document.forms.editform.value1_Q1_changedme.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_Q1_changedme,'advanced1')};
	document.forms.editform.value_Q1_changedme.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_Q1_changedme,'advanced')};
	document.forms.editform.value1_Q1_changedme.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_Q1_changedme,'advanced1')};
	document.forms.editform.value_Q1_playoften.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_Q1_playoften,'advanced')};
	document.forms.editform.value1_Q1_playoften.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_Q1_playoften,'advanced1')};
	document.forms.editform.value_Q1_playoften.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_Q1_playoften,'advanced')};
	document.forms.editform.value1_Q1_playoften.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_Q1_playoften,'advanced1')};
	document.forms.editform.value_Q1_infolevel.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_Q1_infolevel,'advanced')};
	document.forms.editform.value1_Q1_infolevel.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_Q1_infolevel,'advanced1')};
	document.forms.editform.value_Q1_infolevel.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_Q1_infolevel,'advanced')};
	document.forms.editform.value1_Q1_infolevel.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_Q1_infolevel,'advanced1')};
	document.forms.editform.value_Q1_SNotice.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_Q1_SNotice,'advanced')};
	document.forms.editform.value1_Q1_SNotice.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_Q1_SNotice,'advanced1')};
	document.forms.editform.value_Q1_SNotice.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_Q1_SNotice,'advanced')};
	document.forms.editform.value1_Q1_SNotice.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_Q1_SNotice,'advanced1')};
	document.forms.editform.value_Q1_SNoticewhere.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_Q1_SNoticewhere,'advanced')};
	document.forms.editform.value1_Q1_SNoticewhere.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_Q1_SNoticewhere,'advanced1')};
	document.forms.editform.value_Q1_SNoticewhere.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_Q1_SNoticewhere,'advanced')};
	document.forms.editform.value1_Q1_SNoticewhere.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_Q1_SNoticewhere,'advanced1')};
	document.forms.editform.value_Q1_SNoticemainsponsor.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_Q1_SNoticemainsponsor,'advanced')};
	document.forms.editform.value1_Q1_SNoticemainsponsor.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_Q1_SNoticemainsponsor,'advanced1')};
	document.forms.editform.value_Q1_SNoticemainsponsor.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_Q1_SNoticemainsponsor,'advanced')};
	document.forms.editform.value1_Q1_SNoticemainsponsor.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_Q1_SNoticemainsponsor,'advanced1')};
	document.forms.editform.value_Q1_SNoticeteam.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_Q1_SNoticeteam,'advanced')};
	document.forms.editform.value1_Q1_SNoticeteam.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_Q1_SNoticeteam,'advanced1')};
	document.forms.editform.value_Q1_SNoticeteam.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_Q1_SNoticeteam,'advanced')};
	document.forms.editform.value1_Q1_SNoticeteam.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_Q1_SNoticeteam,'advanced1')};
	document.forms.editform.value_Q1_SNoticeother.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_Q1_SNoticeother,'advanced')};
	document.forms.editform.value1_Q1_SNoticeother.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_Q1_SNoticeother,'advanced1')};
	document.forms.editform.value_Q1_SNoticeother.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_Q1_SNoticeother,'advanced')};
	document.forms.editform.value1_Q1_SNoticeother.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_Q1_SNoticeother,'advanced1')};
	document.forms.editform.value_Q1_Shavechangedview.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_Q1_Shavechangedview,'advanced')};
	document.forms.editform.value1_Q1_Shavechangedview.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_Q1_Shavechangedview,'advanced1')};
	document.forms.editform.value_Q1_Shavechangedview.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_Q1_Shavechangedview,'advanced')};
	document.forms.editform.value1_Q1_Shavechangedview.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_Q1_Shavechangedview,'advanced1')};
	document.forms.editform.value_Q1_Sbuyproducts.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_Q1_Sbuyproducts,'advanced')};
	document.forms.editform.value1_Q1_Sbuyproducts.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_Q1_Sbuyproducts,'advanced1')};
	document.forms.editform.value_Q1_Sbuyproducts.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_Q1_Sbuyproducts,'advanced')};
	document.forms.editform.value1_Q1_Sbuyproducts.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_Q1_Sbuyproducts,'advanced1')};
	document.forms.editform.value_Q1_Snameothers.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_Q1_Snameothers,'advanced')};
	document.forms.editform.value1_Q1_Snameothers.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_Q1_Snameothers,'advanced1')};
	document.forms.editform.value_Q1_Snameothers.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_Q1_Snameothers,'advanced')};
	document.forms.editform.value1_Q1_Snameothers.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_Q1_Snameothers,'advanced1')};
	document.forms.editform.value_Q1_p_id.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_Q1_p_id,'advanced')};
	document.forms.editform.value1_Q1_p_id.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_Q1_p_id,'advanced1')};
	document.forms.editform.value_Q1_p_id.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_Q1_p_id,'advanced')};
	document.forms.editform.value1_Q1_p_id.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_Q1_p_id,'advanced1')};
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

// Q1_id 
$opt="";
$not=false;
$control_Q1_id=array();
$control_Q1_id["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["Q1_id"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["Q1_id"];
	$control_Q1_id["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["Q1_id"];
}
$control_Q1_id["func"]="xt_buildeditcontrol";
$control_Q1_id["params"]["field"]="Q1_id";
$control_Q1_id["params"]["mode"]="search";
$xt->assignbyref("Q1_id_editcontrol",$control_Q1_id);
$control1_Q1_id=$control_Q1_id;
$control1_Q1_id["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_Q1_id["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["Q1_id"];
$xt->assignbyref("Q1_id_editcontrol1",$control1_Q1_id);
	
$xt->assign_section("Q1_id_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"Q1_id\">","");
$Q1_id_notbox="name=\"not_Q1_id\"";
if($not)
	$Q1_id_notbox=" checked";
$xt->assign("Q1_id_notbox",$Q1_id_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_Q1_id\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_Q1_id",$searchtype);
//	edit format
$editformats["Q1_id"]="Text field";
// Q1_work 
$opt="";
$not=false;
$control_Q1_work=array();
$control_Q1_work["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["Q1_work"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["Q1_work"];
	$control_Q1_work["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["Q1_work"];
}
$control_Q1_work["func"]="xt_buildeditcontrol";
$control_Q1_work["params"]["field"]="Q1_work";
$control_Q1_work["params"]["mode"]="search";
$xt->assignbyref("Q1_work_editcontrol",$control_Q1_work);
$control1_Q1_work=$control_Q1_work;
$control1_Q1_work["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_Q1_work["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["Q1_work"];
$xt->assignbyref("Q1_work_editcontrol1",$control1_Q1_work);
	
$xt->assign_section("Q1_work_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"Q1_work\">","");
$Q1_work_notbox="name=\"not_Q1_work\"";
if($not)
	$Q1_work_notbox=" checked";
$xt->assign("Q1_work_notbox",$Q1_work_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_Q1_work\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_Q1_work",$searchtype);
//	edit format
$editformats["Q1_work"]="Text field";
// Q1_where 
$opt="";
$not=false;
$control_Q1_where=array();
$control_Q1_where["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["Q1_where"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["Q1_where"];
	$control_Q1_where["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["Q1_where"];
}
$control_Q1_where["func"]="xt_buildeditcontrol";
$control_Q1_where["params"]["field"]="Q1_where";
$control_Q1_where["params"]["mode"]="search";
$xt->assignbyref("Q1_where_editcontrol",$control_Q1_where);
$control1_Q1_where=$control_Q1_where;
$control1_Q1_where["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_Q1_where["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["Q1_where"];
$xt->assignbyref("Q1_where_editcontrol1",$control1_Q1_where);
	
$xt->assign_section("Q1_where_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"Q1_where\">","");
$Q1_where_notbox="name=\"not_Q1_where\"";
if($not)
	$Q1_where_notbox=" checked";
$xt->assign("Q1_where_notbox",$Q1_where_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_Q1_where\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_Q1_where",$searchtype);
//	edit format
$editformats["Q1_where"]="Text field";
// Q1_why 
$opt="";
$not=false;
$control_Q1_why=array();
$control_Q1_why["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["Q1_why"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["Q1_why"];
	$control_Q1_why["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["Q1_why"];
}
$control_Q1_why["func"]="xt_buildeditcontrol";
$control_Q1_why["params"]["field"]="Q1_why";
$control_Q1_why["params"]["mode"]="search";
$xt->assignbyref("Q1_why_editcontrol",$control_Q1_why);
$control1_Q1_why=$control_Q1_why;
$control1_Q1_why["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_Q1_why["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["Q1_why"];
$xt->assignbyref("Q1_why_editcontrol1",$control1_Q1_why);
	
$xt->assign_section("Q1_why_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"Q1_why\">","");
$Q1_why_notbox="name=\"not_Q1_why\"";
if($not)
	$Q1_why_notbox=" checked";
$xt->assign("Q1_why_notbox",$Q1_why_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_Q1_why\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_Q1_why",$searchtype);
//	edit format
$editformats["Q1_why"]="Text field";
// Q1_rating 
$opt="";
$not=false;
$control_Q1_rating=array();
$control_Q1_rating["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["Q1_rating"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["Q1_rating"];
	$control_Q1_rating["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["Q1_rating"];
}
$control_Q1_rating["func"]="xt_buildeditcontrol";
$control_Q1_rating["params"]["field"]="Q1_rating";
$control_Q1_rating["params"]["mode"]="search";
$xt->assignbyref("Q1_rating_editcontrol",$control_Q1_rating);
$control1_Q1_rating=$control_Q1_rating;
$control1_Q1_rating["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_Q1_rating["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["Q1_rating"];
$xt->assignbyref("Q1_rating_editcontrol1",$control1_Q1_rating);
	
$xt->assign_section("Q1_rating_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"Q1_rating\">","");
$Q1_rating_notbox="name=\"not_Q1_rating\"";
if($not)
	$Q1_rating_notbox=" checked";
$xt->assign("Q1_rating_notbox",$Q1_rating_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_Q1_rating\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_Q1_rating",$searchtype);
//	edit format
$editformats["Q1_rating"]="Text field";
// Q1_speakothers 
$opt="";
$not=false;
$control_Q1_speakothers=array();
$control_Q1_speakothers["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["Q1_speakothers"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["Q1_speakothers"];
	$control_Q1_speakothers["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["Q1_speakothers"];
}
$control_Q1_speakothers["func"]="xt_buildeditcontrol";
$control_Q1_speakothers["params"]["field"]="Q1_speakothers";
$control_Q1_speakothers["params"]["mode"]="search";
$xt->assignbyref("Q1_speakothers_editcontrol",$control_Q1_speakothers);
$control1_Q1_speakothers=$control_Q1_speakothers;
$control1_Q1_speakothers["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_Q1_speakothers["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["Q1_speakothers"];
$xt->assignbyref("Q1_speakothers_editcontrol1",$control1_Q1_speakothers);
	
$xt->assign_section("Q1_speakothers_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"Q1_speakothers\">","");
$Q1_speakothers_notbox="name=\"not_Q1_speakothers\"";
if($not)
	$Q1_speakothers_notbox=" checked";
$xt->assign("Q1_speakothers_notbox",$Q1_speakothers_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_Q1_speakothers\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_Q1_speakothers",$searchtype);
//	edit format
$editformats["Q1_speakothers"]="Text field";
// Q1_changedme 
$opt="";
$not=false;
$control_Q1_changedme=array();
$control_Q1_changedme["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["Q1_changedme"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["Q1_changedme"];
	$control_Q1_changedme["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["Q1_changedme"];
}
$control_Q1_changedme["func"]="xt_buildeditcontrol";
$control_Q1_changedme["params"]["field"]="Q1_changedme";
$control_Q1_changedme["params"]["mode"]="search";
$xt->assignbyref("Q1_changedme_editcontrol",$control_Q1_changedme);
$control1_Q1_changedme=$control_Q1_changedme;
$control1_Q1_changedme["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_Q1_changedme["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["Q1_changedme"];
$xt->assignbyref("Q1_changedme_editcontrol1",$control1_Q1_changedme);
	
$xt->assign_section("Q1_changedme_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"Q1_changedme\">","");
$Q1_changedme_notbox="name=\"not_Q1_changedme\"";
if($not)
	$Q1_changedme_notbox=" checked";
$xt->assign("Q1_changedme_notbox",$Q1_changedme_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_Q1_changedme\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_Q1_changedme",$searchtype);
//	edit format
$editformats["Q1_changedme"]="Text field";
// Q1_playoften 
$opt="";
$not=false;
$control_Q1_playoften=array();
$control_Q1_playoften["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["Q1_playoften"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["Q1_playoften"];
	$control_Q1_playoften["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["Q1_playoften"];
}
$control_Q1_playoften["func"]="xt_buildeditcontrol";
$control_Q1_playoften["params"]["field"]="Q1_playoften";
$control_Q1_playoften["params"]["mode"]="search";
$xt->assignbyref("Q1_playoften_editcontrol",$control_Q1_playoften);
$control1_Q1_playoften=$control_Q1_playoften;
$control1_Q1_playoften["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_Q1_playoften["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["Q1_playoften"];
$xt->assignbyref("Q1_playoften_editcontrol1",$control1_Q1_playoften);
	
$xt->assign_section("Q1_playoften_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"Q1_playoften\">","");
$Q1_playoften_notbox="name=\"not_Q1_playoften\"";
if($not)
	$Q1_playoften_notbox=" checked";
$xt->assign("Q1_playoften_notbox",$Q1_playoften_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_Q1_playoften\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_Q1_playoften",$searchtype);
//	edit format
$editformats["Q1_playoften"]="Text field";
// Q1_infolevel 
$opt="";
$not=false;
$control_Q1_infolevel=array();
$control_Q1_infolevel["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["Q1_infolevel"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["Q1_infolevel"];
	$control_Q1_infolevel["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["Q1_infolevel"];
}
$control_Q1_infolevel["func"]="xt_buildeditcontrol";
$control_Q1_infolevel["params"]["field"]="Q1_infolevel";
$control_Q1_infolevel["params"]["mode"]="search";
$xt->assignbyref("Q1_infolevel_editcontrol",$control_Q1_infolevel);
$control1_Q1_infolevel=$control_Q1_infolevel;
$control1_Q1_infolevel["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_Q1_infolevel["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["Q1_infolevel"];
$xt->assignbyref("Q1_infolevel_editcontrol1",$control1_Q1_infolevel);
	
$xt->assign_section("Q1_infolevel_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"Q1_infolevel\">","");
$Q1_infolevel_notbox="name=\"not_Q1_infolevel\"";
if($not)
	$Q1_infolevel_notbox=" checked";
$xt->assign("Q1_infolevel_notbox",$Q1_infolevel_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_Q1_infolevel\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_Q1_infolevel",$searchtype);
//	edit format
$editformats["Q1_infolevel"]="Text field";
// Q1_SNotice 
$opt="";
$not=false;
$control_Q1_SNotice=array();
$control_Q1_SNotice["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["Q1_SNotice"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["Q1_SNotice"];
	$control_Q1_SNotice["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["Q1_SNotice"];
}
$control_Q1_SNotice["func"]="xt_buildeditcontrol";
$control_Q1_SNotice["params"]["field"]="Q1_SNotice";
$control_Q1_SNotice["params"]["mode"]="search";
$xt->assignbyref("Q1_SNotice_editcontrol",$control_Q1_SNotice);
$control1_Q1_SNotice=$control_Q1_SNotice;
$control1_Q1_SNotice["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_Q1_SNotice["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["Q1_SNotice"];
$xt->assignbyref("Q1_SNotice_editcontrol1",$control1_Q1_SNotice);
	
$xt->assign_section("Q1_SNotice_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"Q1_SNotice\">","");
$Q1_SNotice_notbox="name=\"not_Q1_SNotice\"";
if($not)
	$Q1_SNotice_notbox=" checked";
$xt->assign("Q1_SNotice_notbox",$Q1_SNotice_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_Q1_SNotice\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_Q1_SNotice",$searchtype);
//	edit format
$editformats["Q1_SNotice"]="Text field";
// Q1_SNoticewhere 
$opt="";
$not=false;
$control_Q1_SNoticewhere=array();
$control_Q1_SNoticewhere["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["Q1_SNoticewhere"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["Q1_SNoticewhere"];
	$control_Q1_SNoticewhere["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["Q1_SNoticewhere"];
}
$control_Q1_SNoticewhere["func"]="xt_buildeditcontrol";
$control_Q1_SNoticewhere["params"]["field"]="Q1_SNoticewhere";
$control_Q1_SNoticewhere["params"]["mode"]="search";
$xt->assignbyref("Q1_SNoticewhere_editcontrol",$control_Q1_SNoticewhere);
$control1_Q1_SNoticewhere=$control_Q1_SNoticewhere;
$control1_Q1_SNoticewhere["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_Q1_SNoticewhere["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["Q1_SNoticewhere"];
$xt->assignbyref("Q1_SNoticewhere_editcontrol1",$control1_Q1_SNoticewhere);
	
$xt->assign_section("Q1_SNoticewhere_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"Q1_SNoticewhere\">","");
$Q1_SNoticewhere_notbox="name=\"not_Q1_SNoticewhere\"";
if($not)
	$Q1_SNoticewhere_notbox=" checked";
$xt->assign("Q1_SNoticewhere_notbox",$Q1_SNoticewhere_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_Q1_SNoticewhere\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_Q1_SNoticewhere",$searchtype);
//	edit format
$editformats["Q1_SNoticewhere"]="Text field";
// Q1_SNoticemainsponsor 
$opt="";
$not=false;
$control_Q1_SNoticemainsponsor=array();
$control_Q1_SNoticemainsponsor["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["Q1_SNoticemainsponsor"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["Q1_SNoticemainsponsor"];
	$control_Q1_SNoticemainsponsor["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["Q1_SNoticemainsponsor"];
}
$control_Q1_SNoticemainsponsor["func"]="xt_buildeditcontrol";
$control_Q1_SNoticemainsponsor["params"]["field"]="Q1_SNoticemainsponsor";
$control_Q1_SNoticemainsponsor["params"]["mode"]="search";
$xt->assignbyref("Q1_SNoticemainsponsor_editcontrol",$control_Q1_SNoticemainsponsor);
$control1_Q1_SNoticemainsponsor=$control_Q1_SNoticemainsponsor;
$control1_Q1_SNoticemainsponsor["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_Q1_SNoticemainsponsor["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["Q1_SNoticemainsponsor"];
$xt->assignbyref("Q1_SNoticemainsponsor_editcontrol1",$control1_Q1_SNoticemainsponsor);
	
$xt->assign_section("Q1_SNoticemainsponsor_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"Q1_SNoticemainsponsor\">","");
$Q1_SNoticemainsponsor_notbox="name=\"not_Q1_SNoticemainsponsor\"";
if($not)
	$Q1_SNoticemainsponsor_notbox=" checked";
$xt->assign("Q1_SNoticemainsponsor_notbox",$Q1_SNoticemainsponsor_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_Q1_SNoticemainsponsor\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_Q1_SNoticemainsponsor",$searchtype);
//	edit format
$editformats["Q1_SNoticemainsponsor"]="Text field";
// Q1_SNoticeteam 
$opt="";
$not=false;
$control_Q1_SNoticeteam=array();
$control_Q1_SNoticeteam["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["Q1_SNoticeteam"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["Q1_SNoticeteam"];
	$control_Q1_SNoticeteam["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["Q1_SNoticeteam"];
}
$control_Q1_SNoticeteam["func"]="xt_buildeditcontrol";
$control_Q1_SNoticeteam["params"]["field"]="Q1_SNoticeteam";
$control_Q1_SNoticeteam["params"]["mode"]="search";
$xt->assignbyref("Q1_SNoticeteam_editcontrol",$control_Q1_SNoticeteam);
$control1_Q1_SNoticeteam=$control_Q1_SNoticeteam;
$control1_Q1_SNoticeteam["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_Q1_SNoticeteam["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["Q1_SNoticeteam"];
$xt->assignbyref("Q1_SNoticeteam_editcontrol1",$control1_Q1_SNoticeteam);
	
$xt->assign_section("Q1_SNoticeteam_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"Q1_SNoticeteam\">","");
$Q1_SNoticeteam_notbox="name=\"not_Q1_SNoticeteam\"";
if($not)
	$Q1_SNoticeteam_notbox=" checked";
$xt->assign("Q1_SNoticeteam_notbox",$Q1_SNoticeteam_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_Q1_SNoticeteam\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_Q1_SNoticeteam",$searchtype);
//	edit format
$editformats["Q1_SNoticeteam"]="Text field";
// Q1_SNoticeother 
$opt="";
$not=false;
$control_Q1_SNoticeother=array();
$control_Q1_SNoticeother["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["Q1_SNoticeother"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["Q1_SNoticeother"];
	$control_Q1_SNoticeother["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["Q1_SNoticeother"];
}
$control_Q1_SNoticeother["func"]="xt_buildeditcontrol";
$control_Q1_SNoticeother["params"]["field"]="Q1_SNoticeother";
$control_Q1_SNoticeother["params"]["mode"]="search";
$xt->assignbyref("Q1_SNoticeother_editcontrol",$control_Q1_SNoticeother);
$control1_Q1_SNoticeother=$control_Q1_SNoticeother;
$control1_Q1_SNoticeother["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_Q1_SNoticeother["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["Q1_SNoticeother"];
$xt->assignbyref("Q1_SNoticeother_editcontrol1",$control1_Q1_SNoticeother);
	
$xt->assign_section("Q1_SNoticeother_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"Q1_SNoticeother\">","");
$Q1_SNoticeother_notbox="name=\"not_Q1_SNoticeother\"";
if($not)
	$Q1_SNoticeother_notbox=" checked";
$xt->assign("Q1_SNoticeother_notbox",$Q1_SNoticeother_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_Q1_SNoticeother\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_Q1_SNoticeother",$searchtype);
//	edit format
$editformats["Q1_SNoticeother"]="Text field";
// Q1_Shavechangedview 
$opt="";
$not=false;
$control_Q1_Shavechangedview=array();
$control_Q1_Shavechangedview["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["Q1_Shavechangedview"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["Q1_Shavechangedview"];
	$control_Q1_Shavechangedview["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["Q1_Shavechangedview"];
}
$control_Q1_Shavechangedview["func"]="xt_buildeditcontrol";
$control_Q1_Shavechangedview["params"]["field"]="Q1_Shavechangedview";
$control_Q1_Shavechangedview["params"]["mode"]="search";
$xt->assignbyref("Q1_Shavechangedview_editcontrol",$control_Q1_Shavechangedview);
$control1_Q1_Shavechangedview=$control_Q1_Shavechangedview;
$control1_Q1_Shavechangedview["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_Q1_Shavechangedview["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["Q1_Shavechangedview"];
$xt->assignbyref("Q1_Shavechangedview_editcontrol1",$control1_Q1_Shavechangedview);
	
$xt->assign_section("Q1_Shavechangedview_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"Q1_Shavechangedview\">","");
$Q1_Shavechangedview_notbox="name=\"not_Q1_Shavechangedview\"";
if($not)
	$Q1_Shavechangedview_notbox=" checked";
$xt->assign("Q1_Shavechangedview_notbox",$Q1_Shavechangedview_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_Q1_Shavechangedview\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_Q1_Shavechangedview",$searchtype);
//	edit format
$editformats["Q1_Shavechangedview"]="Text field";
// Q1_Sbuyproducts 
$opt="";
$not=false;
$control_Q1_Sbuyproducts=array();
$control_Q1_Sbuyproducts["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["Q1_Sbuyproducts"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["Q1_Sbuyproducts"];
	$control_Q1_Sbuyproducts["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["Q1_Sbuyproducts"];
}
$control_Q1_Sbuyproducts["func"]="xt_buildeditcontrol";
$control_Q1_Sbuyproducts["params"]["field"]="Q1_Sbuyproducts";
$control_Q1_Sbuyproducts["params"]["mode"]="search";
$xt->assignbyref("Q1_Sbuyproducts_editcontrol",$control_Q1_Sbuyproducts);
$control1_Q1_Sbuyproducts=$control_Q1_Sbuyproducts;
$control1_Q1_Sbuyproducts["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_Q1_Sbuyproducts["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["Q1_Sbuyproducts"];
$xt->assignbyref("Q1_Sbuyproducts_editcontrol1",$control1_Q1_Sbuyproducts);
	
$xt->assign_section("Q1_Sbuyproducts_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"Q1_Sbuyproducts\">","");
$Q1_Sbuyproducts_notbox="name=\"not_Q1_Sbuyproducts\"";
if($not)
	$Q1_Sbuyproducts_notbox=" checked";
$xt->assign("Q1_Sbuyproducts_notbox",$Q1_Sbuyproducts_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_Q1_Sbuyproducts\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_Q1_Sbuyproducts",$searchtype);
//	edit format
$editformats["Q1_Sbuyproducts"]="Text field";
// Q1_Snameothers 
$opt="";
$not=false;
$control_Q1_Snameothers=array();
$control_Q1_Snameothers["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["Q1_Snameothers"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["Q1_Snameothers"];
	$control_Q1_Snameothers["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["Q1_Snameothers"];
}
$control_Q1_Snameothers["func"]="xt_buildeditcontrol";
$control_Q1_Snameothers["params"]["field"]="Q1_Snameothers";
$control_Q1_Snameothers["params"]["mode"]="search";
$xt->assignbyref("Q1_Snameothers_editcontrol",$control_Q1_Snameothers);
$control1_Q1_Snameothers=$control_Q1_Snameothers;
$control1_Q1_Snameothers["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_Q1_Snameothers["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["Q1_Snameothers"];
$xt->assignbyref("Q1_Snameothers_editcontrol1",$control1_Q1_Snameothers);
	
$xt->assign_section("Q1_Snameothers_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"Q1_Snameothers\">","");
$Q1_Snameothers_notbox="name=\"not_Q1_Snameothers\"";
if($not)
	$Q1_Snameothers_notbox=" checked";
$xt->assign("Q1_Snameothers_notbox",$Q1_Snameothers_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_Q1_Snameothers\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_Q1_Snameothers",$searchtype);
//	edit format
$editformats["Q1_Snameothers"]="Text field";
// Q1_Comments 
$opt="";
$not=false;
$control_Q1_Comments=array();
$control_Q1_Comments["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["Q1_Comments"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["Q1_Comments"];
	$control_Q1_Comments["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["Q1_Comments"];
}
$control_Q1_Comments["func"]="xt_buildeditcontrol";
$control_Q1_Comments["params"]["field"]="Q1_Comments";
$control_Q1_Comments["params"]["mode"]="search";
$xt->assignbyref("Q1_Comments_editcontrol",$control_Q1_Comments);
$control1_Q1_Comments=$control_Q1_Comments;
$control1_Q1_Comments["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_Q1_Comments["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["Q1_Comments"];
$xt->assignbyref("Q1_Comments_editcontrol1",$control1_Q1_Comments);
	
$xt->assign_section("Q1_Comments_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"Q1_Comments\">","");
$Q1_Comments_notbox="name=\"not_Q1_Comments\"";
if($not)
	$Q1_Comments_notbox=" checked";
$xt->assign("Q1_Comments_notbox",$Q1_Comments_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_Q1_Comments\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_Q1_Comments",$searchtype);
//	edit format
$editformats["Q1_Comments"]=EDIT_FORMAT_TEXT_FIELD;
// Q1_p_id 
$opt="";
$not=false;
$control_Q1_p_id=array();
$control_Q1_p_id["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["Q1_p_id"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["Q1_p_id"];
	$control_Q1_p_id["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["Q1_p_id"];
}
$control_Q1_p_id["func"]="xt_buildeditcontrol";
$control_Q1_p_id["params"]["field"]="Q1_p_id";
$control_Q1_p_id["params"]["mode"]="search";
$xt->assignbyref("Q1_p_id_editcontrol",$control_Q1_p_id);
$control1_Q1_p_id=$control_Q1_p_id;
$control1_Q1_p_id["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_Q1_p_id["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["Q1_p_id"];
$xt->assignbyref("Q1_p_id_editcontrol1",$control1_Q1_p_id);
	
$xt->assign_section("Q1_p_id_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"Q1_p_id\">","");
$Q1_p_id_notbox="name=\"not_Q1_p_id\"";
if($not)
	$Q1_p_id_notbox=" checked";
$xt->assign("Q1_p_id_notbox",$Q1_p_id_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_Q1_p_id\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_Q1_p_id",$searchtype);
//	edit format
$editformats["Q1_p_id"]="Text field";

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
$contents_block["begin"].="action=\"Q1_list.php\" ";
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
	
$templatefile = "Q1_search.htm";
if(function_exists("BeforeShowSearch"))
	BeforeShowSearch($xt,$templatefile);

$xt->display($templatefile);

?>