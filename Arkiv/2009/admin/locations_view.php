<?php
// This script and data application were generated by AppGini 3.4 on 07-11-2007 at 10:07:19
// Download AppGini for free from http://www.bigprof.com/appgini/download/

	include(dirname(__FILE__)."/language.php");
	include(dirname(__FILE__)."/lib.php");
	include(dirname(__FILE__)."/locations_dml.php");
	
	$x = new DataList;
	if($HTTP_POST_VARS["Filter_x"] != ""  || $HTTP_POST_VARS['CSV_x'] != "")
	{
		// Query used in filters page and CSV output
		$x->Query = "select xstatus2.xs_text as 'L_active', locations.l_name as 'L_name', locations.l_adr as 'L_adr', ZZIP5.ZZCity as 'L_zip', locations.l_mail as 'L_mail', locations.l_desc as 'L_desc', locations.l_phone1 as 'L_phone1', locations.l_phone2 as 'L_phone2', locations.l_open as 'L_open', locations.l_close as 'L_close' from locations LEFT JOIN xstatus as xstatus2 ON locations.l_active=xstatus2.xs_id LEFT JOIN ZZIP as ZZIP5 ON locations.l_zip=ZZIP5.ZZIP ";
	}
	else
	{
		// Query used in table view
		$x->Query = "select locations.l_id as 'L_id', concat(xstatus2.xs_text, '') as 'L_active', locations.l_name as 'L_name', locations.l_adr as 'L_adr', concat(ZZIP5.ZZCity, '') as 'L_zip', locations.l_mail as 'L_mail', locations.l_desc as 'L_desc', locations.l_phone1 as 'L_phone1', locations.l_phone2 as 'L_phone2', locations.l_open as 'L_open', locations.l_close as 'L_close' from locations LEFT JOIN xstatus as xstatus2 ON locations.l_active=xstatus2.xs_id LEFT JOIN ZZIP as ZZIP5 ON locations.l_zip=ZZIP5.ZZIP ";
	}
	
	// handle date sorting correctly
	// end of date sorting handler
	
	$x->DataHeight = 150;
	$x->AllowSelection = 1;
	$x->AllowDelete = 1;
	$x->AllowDeleteOfParents = 0;
	$x->AllowInsert = 1;
	$x->AllowUpdate = 1;
	$x->AllowFilters = 1;
	$x->AllowSavingFilters = 0;
	$x->AllowSorting = 1;
	$x->AllowNavigation = 1;
	$x->AllowPrinting = 1;
	$x->AllowCSV = 0;
	$x->HideTableView = 0;
	$x->RecordsPerPage = 10;
	$x->QuickSearch = 3;
	$x->QuickSearchText = $Translation["quick search"];
	$x->ScriptFileName = "locations_view.php";
	$x->RedirectAfterInsert = "locations_view.php?SelectedID=#ID#";
	$x->TableTitle = "Locations";
	$x->PrimaryKey = "locations.l_id";

	$x->ColWidth   = array(150, 150, 150);
	$x->ColCaption = array("L_active", "L_name", "L_phone1");
	$x->ColNumber  = array(2, 3, 8);

	$x->Template = 'locations_templateTV.html';
	$x->SelectedTemplate = 'locations_templateTVS.html';
	$x->ShowTableHeader = 1;
	$x->ShowRecordSlots = 1;
	$x->HighlightColor = '#FFF0C2';

	// uncomment the following line to display the detail view in a separate page
	// include(dirname(__FILE__)."/separateDVTV.php");
	$x->Render();
	
	include(dirname(__FILE__)."/header.php");
	echo $x->HTML;
	include(dirname(__FILE__)."/footer.php");
?>