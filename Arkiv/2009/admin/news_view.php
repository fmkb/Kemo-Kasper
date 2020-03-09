<?php
// This script and data application were generated by AppGini 3.4 on 07-11-2007 at 10:07:19
// Download AppGini for free from http://www.bigprof.com/appgini/download/

	include(dirname(__FILE__)."/language.php");
	include(dirname(__FILE__)."/lib.php");
	include(dirname(__FILE__)."/news_dml.php");
	
	$x = new DataList;
	if($HTTP_POST_VARS["Filter_x"] != ""  || $HTTP_POST_VARS['CSV_x'] != "")
	{
		// Query used in filters page and CSV output
		$x->Query = "select xstatus2.xs_text as 'N_active', news.n_start as 'N_start', news.n_end as 'N_end', news.n_date as 'N_date', news.n_head as 'N_head', news.n_text as 'N_text', news.n_link as 'N_link', news.n_file as 'N_file', news.n_type as 'N_type 1/2' from news LEFT JOIN xstatus as xstatus2 ON news.n_active=xstatus2.xs_id ";
	}
	else
	{
		// Query used in table view
		$x->Query = "select news.n_id as 'N_id', concat(xstatus2.xs_text, '') as 'N_active', if(news.n_start,date_format(news.n_start,'%m/%d/%Y'),'') as 'N_start', if(news.n_end,date_format(news.n_end,'%m/%d/%Y'),'') as 'N_end', if(news.n_date,date_format(news.n_date,'%m/%d/%Y'),'') as 'N_date', news.n_head as 'N_head', news.n_text as 'N_text', news.n_link as 'N_link', news.n_file as 'N_file', news.n_type as 'N_type 1/2' from news LEFT JOIN xstatus as xstatus2 ON news.n_active=xstatus2.xs_id ";
	}
	
	// handle date sorting correctly
	if($HTTP_POST_VARS['SortField']=='3' || $HTTP_POST_VARS['SortField']=='news.n_start'){
		$HTTP_POST_VARS['SortField']='news.n_start';
		$SortFieldNumeric=3;
	}
	if($HTTP_GET_VARS['SortField']=='3' || $HTTP_GET_VARS['SortField']=='news.n_start'){
		$HTTP_GET_VARS['SortField']='news.n_start';
		$SortFieldNumeric=3;
	}
	if($HTTP_POST_VARS['SortField']=='4' || $HTTP_POST_VARS['SortField']=='news.n_end'){
		$HTTP_POST_VARS['SortField']='news.n_end';
		$SortFieldNumeric=4;
	}
	if($HTTP_GET_VARS['SortField']=='4' || $HTTP_GET_VARS['SortField']=='news.n_end'){
		$HTTP_GET_VARS['SortField']='news.n_end';
		$SortFieldNumeric=4;
	}
	if($HTTP_POST_VARS['SortField']=='5' || $HTTP_POST_VARS['SortField']=='news.n_date'){
		$HTTP_POST_VARS['SortField']='news.n_date';
		$SortFieldNumeric=5;
	}
	if($HTTP_GET_VARS['SortField']=='5' || $HTTP_GET_VARS['SortField']=='news.n_date'){
		$HTTP_GET_VARS['SortField']='news.n_date';
		$SortFieldNumeric=5;
	}
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
	$x->ScriptFileName = "news_view.php";
	$x->RedirectAfterInsert = "news_view.php?SelectedID=#ID#";
	$x->TableTitle = "News";
	$x->PrimaryKey = "news.n_id";

	$x->ColWidth   = array(150, 150, 150, 150, 150, 150, 150, 50);
	$x->ColCaption = array("N_active", "N_start", "N_end", "N_date", "N_head", "N_link", "N_file", "N_type 1/2");
	$x->ColNumber  = array(2, 3, 4, 5, 6, 8, 9, 10);

	$x->Template = 'news_templateTV.html';
	$x->SelectedTemplate = 'news_templateTVS.html';
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