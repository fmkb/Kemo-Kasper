<?php
// This script and data application were generated by AppGini 3.4 on 07-11-2007 at 10:07:19
// Download AppGini for free from http://www.bigprof.com/appgini/download/

	include(dirname(__FILE__)."/language.php");
	include(dirname(__FILE__)."/lib.php");
	include(dirname(__FILE__)."/teams_dml.php");
	
	$x = new DataList;
	if($HTTP_POST_VARS["Filter_x"] != ""  || $HTTP_POST_VARS['CSV_x'] != "")
	{
		// Query used in filters page and CSV output
		$x->Query = "select teams.ts_name as 'Name', teams.ts_p0 as 'Open=1/Closed=0', teams.ts_p1 as 'Ts_p1', teams.ts_p2 as 'Ts_p2', teams.ts_p3 as 'Ts_p3', teams.ts_p4 as 'Ts_p4', teams.ts_p5 as 'Ts_p5', teams.ts_p6 as 'Ts_p6', teams.ts_p7 as 'Ts_p7', teams.ts_p8 as 'Ts_p8', teams.ts_p9 as 'Ts_p9', sponsor13.s_name as 'Ts_s_id', teams.ts_Score as 'Ts_Score' from teams LEFT JOIN sponsor as sponsor13 ON teams.ts_s_id=sponsor13.s_id ";
	}
	else
	{
		// Query used in table view
		$x->Query = "select teams.ts_id as 'T_id', teams.ts_name as 'Name', teams.ts_p0 as 'Open=1/Closed=0', teams.ts_p1 as 'Ts_p1', teams.ts_p2 as 'Ts_p2', teams.ts_p3 as 'Ts_p3', teams.ts_p4 as 'Ts_p4', teams.ts_p5 as 'Ts_p5', teams.ts_p6 as 'Ts_p6', teams.ts_p7 as 'Ts_p7', teams.ts_p8 as 'Ts_p8', teams.ts_p9 as 'Ts_p9', concat(sponsor13.s_name, '') as 'Ts_s_id', teams.ts_Score as 'Ts_Score' from teams LEFT JOIN sponsor as sponsor13 ON teams.ts_s_id=sponsor13.s_id ";
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
	$x->ScriptFileName = "teams_view.php";
	$x->RedirectAfterInsert = "teams_view.php?SelectedID=#ID#";
	$x->TableTitle = "Teams";
	$x->PrimaryKey = "teams.ts_id";

	$x->ColWidth   = array(150, 150, 150, 150);
	$x->ColCaption = array("Name", "Open=1/Closed=0", "Ts_s_id", "Ts_Score");
	$x->ColNumber  = array(2, 3, 13, 14);

	$x->Template = 'teams_templateTV.html';
	$x->SelectedTemplate = 'teams_templateTVS.html';
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