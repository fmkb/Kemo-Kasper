<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/sponsor_variables.php");
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
$includes.="var SUGGEST_TABLE = \"sponsor_searchsuggest.php\";\r\n";
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
	document.getElementById('second_s_active').style.display =  
		document.forms.editform.elements['asearchopt_s_active'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_s_name').style.display =  
		document.forms.editform.elements['asearchopt_s_name'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_s_contact').style.display =  
		document.forms.editform.elements['asearchopt_s_contact'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_s_adr').style.display =  
		document.forms.editform.elements['asearchopt_s_adr'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_s_zip').style.display =  
		document.forms.editform.elements['asearchopt_s_zip'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_s_country').style.display =  
		document.forms.editform.elements['asearchopt_s_country'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_s_phone1').style.display =  
		document.forms.editform.elements['asearchopt_s_phone1'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_s_phone2').style.display =  
		document.forms.editform.elements['asearchopt_s_phone2'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_s_total').style.display =  
		document.forms.editform.elements['asearchopt_s_total'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_s_paid').style.display =  
		document.forms.editform.elements['asearchopt_s_paid'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_s_www').style.display =  
		document.forms.editform.elements['asearchopt_s_www'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_s_mail').style.display =  
		document.forms.editform.elements['asearchopt_s_mail'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_s_cmt').style.display =  
		document.forms.editform.elements['asearchopt_s_cmt'].value==\"Between\" ? '' : 'none'; 
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
	document.forms.editform.value_s_name.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_s_name,'advanced')};
	document.forms.editform.value1_s_name.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_s_name,'advanced1')};
	document.forms.editform.value_s_name.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_s_name,'advanced')};
	document.forms.editform.value1_s_name.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_s_name,'advanced1')};
	document.forms.editform.value_s_contact.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_s_contact,'advanced')};
	document.forms.editform.value1_s_contact.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_s_contact,'advanced1')};
	document.forms.editform.value_s_contact.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_s_contact,'advanced')};
	document.forms.editform.value1_s_contact.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_s_contact,'advanced1')};
	document.forms.editform.value_s_adr.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_s_adr,'advanced')};
	document.forms.editform.value1_s_adr.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_s_adr,'advanced1')};
	document.forms.editform.value_s_adr.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_s_adr,'advanced')};
	document.forms.editform.value1_s_adr.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_s_adr,'advanced1')};
	document.forms.editform.value_s_zip.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_s_zip,'advanced')};
	document.forms.editform.value1_s_zip.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_s_zip,'advanced1')};
	document.forms.editform.value_s_zip.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_s_zip,'advanced')};
	document.forms.editform.value1_s_zip.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_s_zip,'advanced1')};
	document.forms.editform.value_s_phone1.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_s_phone1,'advanced')};
	document.forms.editform.value1_s_phone1.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_s_phone1,'advanced1')};
	document.forms.editform.value_s_phone1.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_s_phone1,'advanced')};
	document.forms.editform.value1_s_phone1.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_s_phone1,'advanced1')};
	document.forms.editform.value_s_phone2.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_s_phone2,'advanced')};
	document.forms.editform.value1_s_phone2.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_s_phone2,'advanced1')};
	document.forms.editform.value_s_phone2.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_s_phone2,'advanced')};
	document.forms.editform.value1_s_phone2.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_s_phone2,'advanced1')};
	document.forms.editform.value_s_total.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_s_total,'advanced')};
	document.forms.editform.value1_s_total.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_s_total,'advanced1')};
	document.forms.editform.value_s_total.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_s_total,'advanced')};
	document.forms.editform.value1_s_total.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_s_total,'advanced1')};
	document.forms.editform.value_s_paid.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_s_paid,'advanced')};
	document.forms.editform.value1_s_paid.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_s_paid,'advanced1')};
	document.forms.editform.value_s_paid.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_s_paid,'advanced')};
	document.forms.editform.value1_s_paid.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_s_paid,'advanced1')};
	document.forms.editform.value_s_www.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_s_www,'advanced')};
	document.forms.editform.value1_s_www.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_s_www,'advanced1')};
	document.forms.editform.value_s_www.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_s_www,'advanced')};
	document.forms.editform.value1_s_www.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_s_www,'advanced1')};
	document.forms.editform.value_s_mail.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_s_mail,'advanced')};
	document.forms.editform.value1_s_mail.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_s_mail,'advanced1')};
	document.forms.editform.value_s_mail.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_s_mail,'advanced')};
	document.forms.editform.value1_s_mail.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_s_mail,'advanced1')};
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

// s_active 
$opt="";
$not=false;
$control_s_active=array();
$control_s_active["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["s_active"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["s_active"];
	$control_s_active["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["s_active"];
}
$control_s_active["func"]="xt_buildeditcontrol";
$control_s_active["params"]["field"]="s_active";
$control_s_active["params"]["mode"]="search";
$xt->assignbyref("s_active_editcontrol",$control_s_active);
$control1_s_active=$control_s_active;
$control1_s_active["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_s_active["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["s_active"];
$xt->assignbyref("s_active_editcontrol1",$control1_s_active);
	
$xt->assign_section("s_active_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"s_active\">","");
$s_active_notbox="name=\"not_s_active\"";
if($not)
	$s_active_notbox=" checked";
$xt->assign("s_active_notbox",$s_active_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">".mlang_message("EQUALS")."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_s_active\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_s_active",$searchtype);
//	edit format
$editformats["s_active"]="Lookup wizard";
// s_name 
$opt="";
$not=false;
$control_s_name=array();
$control_s_name["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["s_name"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["s_name"];
	$control_s_name["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["s_name"];
}
$control_s_name["func"]="xt_buildeditcontrol";
$control_s_name["params"]["field"]="s_name";
$control_s_name["params"]["mode"]="search";
$xt->assignbyref("s_name_editcontrol",$control_s_name);
$control1_s_name=$control_s_name;
$control1_s_name["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_s_name["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["s_name"];
$xt->assignbyref("s_name_editcontrol1",$control1_s_name);
	
$xt->assign_section("s_name_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"s_name\">","");
$s_name_notbox="name=\"not_s_name\"";
if($not)
	$s_name_notbox=" checked";
$xt->assign("s_name_notbox",$s_name_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_s_name\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_s_name",$searchtype);
//	edit format
$editformats["s_name"]="Text field";
// s_contact 
$opt="";
$not=false;
$control_s_contact=array();
$control_s_contact["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["s_contact"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["s_contact"];
	$control_s_contact["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["s_contact"];
}
$control_s_contact["func"]="xt_buildeditcontrol";
$control_s_contact["params"]["field"]="s_contact";
$control_s_contact["params"]["mode"]="search";
$xt->assignbyref("s_contact_editcontrol",$control_s_contact);
$control1_s_contact=$control_s_contact;
$control1_s_contact["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_s_contact["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["s_contact"];
$xt->assignbyref("s_contact_editcontrol1",$control1_s_contact);
	
$xt->assign_section("s_contact_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"s_contact\">","");
$s_contact_notbox="name=\"not_s_contact\"";
if($not)
	$s_contact_notbox=" checked";
$xt->assign("s_contact_notbox",$s_contact_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_s_contact\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_s_contact",$searchtype);
//	edit format
$editformats["s_contact"]="Text field";
// s_adr 
$opt="";
$not=false;
$control_s_adr=array();
$control_s_adr["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["s_adr"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["s_adr"];
	$control_s_adr["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["s_adr"];
}
$control_s_adr["func"]="xt_buildeditcontrol";
$control_s_adr["params"]["field"]="s_adr";
$control_s_adr["params"]["mode"]="search";
$xt->assignbyref("s_adr_editcontrol",$control_s_adr);
$control1_s_adr=$control_s_adr;
$control1_s_adr["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_s_adr["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["s_adr"];
$xt->assignbyref("s_adr_editcontrol1",$control1_s_adr);
	
$xt->assign_section("s_adr_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"s_adr\">","");
$s_adr_notbox="name=\"not_s_adr\"";
if($not)
	$s_adr_notbox=" checked";
$xt->assign("s_adr_notbox",$s_adr_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_s_adr\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_s_adr",$searchtype);
//	edit format
$editformats["s_adr"]="Text field";
// s_zip 
$opt="";
$not=false;
$control_s_zip=array();
$control_s_zip["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["s_zip"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["s_zip"];
	$control_s_zip["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["s_zip"];
}
$control_s_zip["func"]="xt_buildeditcontrol";
$control_s_zip["params"]["field"]="s_zip";
$control_s_zip["params"]["mode"]="search";
$xt->assignbyref("s_zip_editcontrol",$control_s_zip);
$control1_s_zip=$control_s_zip;
$control1_s_zip["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_s_zip["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["s_zip"];
$xt->assignbyref("s_zip_editcontrol1",$control1_s_zip);
	
$xt->assign_section("s_zip_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"s_zip\">","");
$s_zip_notbox="name=\"not_s_zip\"";
if($not)
	$s_zip_notbox=" checked";
$xt->assign("s_zip_notbox",$s_zip_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_s_zip\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_s_zip",$searchtype);
//	edit format
$editformats["s_zip"]="Text field";
// s_country 
$opt="";
$not=false;
$control_s_country=array();
$control_s_country["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["s_country"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["s_country"];
	$control_s_country["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["s_country"];
}
$control_s_country["func"]="xt_buildeditcontrol";
$control_s_country["params"]["field"]="s_country";
$control_s_country["params"]["mode"]="search";
$xt->assignbyref("s_country_editcontrol",$control_s_country);
$control1_s_country=$control_s_country;
$control1_s_country["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_s_country["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["s_country"];
$xt->assignbyref("s_country_editcontrol1",$control1_s_country);
	
$xt->assign_section("s_country_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"s_country\">","");
$s_country_notbox="name=\"not_s_country\"";
if($not)
	$s_country_notbox=" checked";
$xt->assign("s_country_notbox",$s_country_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">".mlang_message("EQUALS")."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_s_country\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_s_country",$searchtype);
//	edit format
$editformats["s_country"]="Lookup wizard";
// s_phone1 
$opt="";
$not=false;
$control_s_phone1=array();
$control_s_phone1["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["s_phone1"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["s_phone1"];
	$control_s_phone1["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["s_phone1"];
}
$control_s_phone1["func"]="xt_buildeditcontrol";
$control_s_phone1["params"]["field"]="s_phone1";
$control_s_phone1["params"]["mode"]="search";
$xt->assignbyref("s_phone1_editcontrol",$control_s_phone1);
$control1_s_phone1=$control_s_phone1;
$control1_s_phone1["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_s_phone1["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["s_phone1"];
$xt->assignbyref("s_phone1_editcontrol1",$control1_s_phone1);
	
$xt->assign_section("s_phone1_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"s_phone1\">","");
$s_phone1_notbox="name=\"not_s_phone1\"";
if($not)
	$s_phone1_notbox=" checked";
$xt->assign("s_phone1_notbox",$s_phone1_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_s_phone1\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_s_phone1",$searchtype);
//	edit format
$editformats["s_phone1"]="Text field";
// s_phone2 
$opt="";
$not=false;
$control_s_phone2=array();
$control_s_phone2["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["s_phone2"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["s_phone2"];
	$control_s_phone2["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["s_phone2"];
}
$control_s_phone2["func"]="xt_buildeditcontrol";
$control_s_phone2["params"]["field"]="s_phone2";
$control_s_phone2["params"]["mode"]="search";
$xt->assignbyref("s_phone2_editcontrol",$control_s_phone2);
$control1_s_phone2=$control_s_phone2;
$control1_s_phone2["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_s_phone2["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["s_phone2"];
$xt->assignbyref("s_phone2_editcontrol1",$control1_s_phone2);
	
$xt->assign_section("s_phone2_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"s_phone2\">","");
$s_phone2_notbox="name=\"not_s_phone2\"";
if($not)
	$s_phone2_notbox=" checked";
$xt->assign("s_phone2_notbox",$s_phone2_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_s_phone2\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_s_phone2",$searchtype);
//	edit format
$editformats["s_phone2"]="Text field";
// s_total 
$opt="";
$not=false;
$control_s_total=array();
$control_s_total["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["s_total"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["s_total"];
	$control_s_total["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["s_total"];
}
$control_s_total["func"]="xt_buildeditcontrol";
$control_s_total["params"]["field"]="s_total";
$control_s_total["params"]["mode"]="search";
$xt->assignbyref("s_total_editcontrol",$control_s_total);
$control1_s_total=$control_s_total;
$control1_s_total["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_s_total["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["s_total"];
$xt->assignbyref("s_total_editcontrol1",$control1_s_total);
	
$xt->assign_section("s_total_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"s_total\">","");
$s_total_notbox="name=\"not_s_total\"";
if($not)
	$s_total_notbox=" checked";
$xt->assign("s_total_notbox",$s_total_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_s_total\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_s_total",$searchtype);
//	edit format
$editformats["s_total"]="Text field";
// s_paid 
$opt="";
$not=false;
$control_s_paid=array();
$control_s_paid["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["s_paid"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["s_paid"];
	$control_s_paid["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["s_paid"];
}
$control_s_paid["func"]="xt_buildeditcontrol";
$control_s_paid["params"]["field"]="s_paid";
$control_s_paid["params"]["mode"]="search";
$xt->assignbyref("s_paid_editcontrol",$control_s_paid);
$control1_s_paid=$control_s_paid;
$control1_s_paid["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_s_paid["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["s_paid"];
$xt->assignbyref("s_paid_editcontrol1",$control1_s_paid);
	
$xt->assign_section("s_paid_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"s_paid\">","");
$s_paid_notbox="name=\"not_s_paid\"";
if($not)
	$s_paid_notbox=" checked";
$xt->assign("s_paid_notbox",$s_paid_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_s_paid\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_s_paid",$searchtype);
//	edit format
$editformats["s_paid"]="Text field";
// s_www 
$opt="";
$not=false;
$control_s_www=array();
$control_s_www["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["s_www"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["s_www"];
	$control_s_www["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["s_www"];
}
$control_s_www["func"]="xt_buildeditcontrol";
$control_s_www["params"]["field"]="s_www";
$control_s_www["params"]["mode"]="search";
$xt->assignbyref("s_www_editcontrol",$control_s_www);
$control1_s_www=$control_s_www;
$control1_s_www["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_s_www["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["s_www"];
$xt->assignbyref("s_www_editcontrol1",$control1_s_www);
	
$xt->assign_section("s_www_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"s_www\">","");
$s_www_notbox="name=\"not_s_www\"";
if($not)
	$s_www_notbox=" checked";
$xt->assign("s_www_notbox",$s_www_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_s_www\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_s_www",$searchtype);
//	edit format
$editformats["s_www"]="Text field";
// s_mail 
$opt="";
$not=false;
$control_s_mail=array();
$control_s_mail["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["s_mail"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["s_mail"];
	$control_s_mail["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["s_mail"];
}
$control_s_mail["func"]="xt_buildeditcontrol";
$control_s_mail["params"]["field"]="s_mail";
$control_s_mail["params"]["mode"]="search";
$xt->assignbyref("s_mail_editcontrol",$control_s_mail);
$control1_s_mail=$control_s_mail;
$control1_s_mail["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_s_mail["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["s_mail"];
$xt->assignbyref("s_mail_editcontrol1",$control1_s_mail);
	
$xt->assign_section("s_mail_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"s_mail\">","");
$s_mail_notbox="name=\"not_s_mail\"";
if($not)
	$s_mail_notbox=" checked";
$xt->assign("s_mail_notbox",$s_mail_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_s_mail\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_s_mail",$searchtype);
//	edit format
$editformats["s_mail"]="Text field";
// s_cmt 
$opt="";
$not=false;
$control_s_cmt=array();
$control_s_cmt["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["s_cmt"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["s_cmt"];
	$control_s_cmt["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["s_cmt"];
}
$control_s_cmt["func"]="xt_buildeditcontrol";
$control_s_cmt["params"]["field"]="s_cmt";
$control_s_cmt["params"]["mode"]="search";
$xt->assignbyref("s_cmt_editcontrol",$control_s_cmt);
$control1_s_cmt=$control_s_cmt;
$control1_s_cmt["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_s_cmt["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["s_cmt"];
$xt->assignbyref("s_cmt_editcontrol1",$control1_s_cmt);
	
$xt->assign_section("s_cmt_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"s_cmt\">","");
$s_cmt_notbox="name=\"not_s_cmt\"";
if($not)
	$s_cmt_notbox=" checked";
$xt->assign("s_cmt_notbox",$s_cmt_notbox);

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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_s_cmt\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_s_cmt",$searchtype);
//	edit format
$editformats["s_cmt"]=EDIT_FORMAT_TEXT_FIELD;

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
$contents_block["begin"].="action=\"sponsor_list.php\" ";
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
	
$templatefile = "sponsor_search.htm";
if(function_exists("BeforeShowSearch"))
	BeforeShowSearch($xt,$templatefile);

$xt->display($templatefile);

?>