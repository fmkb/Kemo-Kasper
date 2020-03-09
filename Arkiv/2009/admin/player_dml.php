<?php

// Data functions for table player

// This script and data application were generated by AppGini 3.4 on 07-11-2007 at 10:07:19
// Download AppGini for free from http://www.bigprof.com/appgini/download/

function insert()
{
	global $HTTP_SERVER_VARS, $HTTP_GET_VARS, $HTTP_POST_VARS, $HTTP_POST_FILES, $Translation;
	
	if(get_magic_quotes_gpc())
	{
		$p_active = $HTTP_POST_VARS["p_active"];
		$p_location = $HTTP_POST_VARS["p_location"];
		$p_s_id = $HTTP_POST_VARS["p_s_id"];
		$p_first = $HTTP_POST_VARS["p_first"];
		$p_name = $HTTP_POST_VARS["p_name"];
		$p_adr = $HTTP_POST_VARS["p_adr"];
		$p_zip = $HTTP_POST_VARS["p_zip"];
		$p_mail = $HTTP_POST_VARS["p_mail"];
		$p_newsaccept = $HTTP_POST_VARS["p_newsaccept"];
		$p_score = $HTTP_POST_VARS["p_score"];
		$p_scorehigh = $HTTP_POST_VARS["p_scorehigh"];
		$p_games = $HTTP_POST_VARS["p_games"];
		$p_time = $HTTP_POST_VARS["p_time"];
		$p_win = $HTTP_POST_VARS["p_win"];
		$p_mk = $HTTP_POST_VARS["p_mk"];
		$p_born = $HTTP_POST_VARS["p_born"];
		$p_user = $HTTP_POST_VARS["p_user"];
		$p_pwd = $HTTP_POST_VARS["p_pwd"];
		$p_ip = $HTTP_POST_VARS["p_ip"];
		$p_datetime = $HTTP_POST_VARS["p_datetime"];
		$p_tscore = $HTTP_POST_VARS["p_tscore"];
		$p_tkills = $HTTP_POST_VARS["p_tkills"];
	}
	else
	{
		$p_active = addslashes($HTTP_POST_VARS["p_active"]);
		$p_location = addslashes($HTTP_POST_VARS["p_location"]);
		$p_s_id = addslashes($HTTP_POST_VARS["p_s_id"]);
		$p_first = addslashes($HTTP_POST_VARS["p_first"]);
		$p_name = addslashes($HTTP_POST_VARS["p_name"]);
		$p_adr = addslashes($HTTP_POST_VARS["p_adr"]);
		$p_zip = addslashes($HTTP_POST_VARS["p_zip"]);
		$p_mail = addslashes($HTTP_POST_VARS["p_mail"]);
		$p_newsaccept = addslashes($HTTP_POST_VARS["p_newsaccept"]);
		$p_score = addslashes($HTTP_POST_VARS["p_score"]);
		$p_scorehigh = addslashes($HTTP_POST_VARS["p_scorehigh"]);
		$p_games = addslashes($HTTP_POST_VARS["p_games"]);
		$p_time = addslashes($HTTP_POST_VARS["p_time"]);
		$p_win = addslashes($HTTP_POST_VARS["p_win"]);
		$p_mk = addslashes($HTTP_POST_VARS["p_mk"]);
		$p_born = addslashes($HTTP_POST_VARS["p_born"]);
		$p_user = addslashes($HTTP_POST_VARS["p_user"]);
		$p_pwd = addslashes($HTTP_POST_VARS["p_pwd"]);
		$p_ip = addslashes($HTTP_POST_VARS["p_ip"]);
		$p_datetime = addslashes($HTTP_POST_VARS["p_datetime"]);
		$p_tscore = addslashes($HTTP_POST_VARS["p_tscore"]);
		$p_tkills = addslashes($HTTP_POST_VARS["p_tkills"]);
	}
	if($p_zip == "") $p_zip = "0";
	if($p_score == "") $p_score = "0";
	if($p_scorehigh == "") $p_scorehigh = "0";
	if($p_games == "") $p_games = "0";
	if($p_win == "") $p_win = "0";
	if($p_tscore == "") $p_tscore = "0";
	if($p_tkills == "") $p_tkills = "0";
	
	sql("insert into player (p_active, p_location, p_s_id, p_first, p_name, p_adr, p_zip, p_mail, p_newsaccept, p_score, p_scorehigh, p_games, p_time, p_win, p_mk, p_born, p_user, p_pwd) values (" . (($p_active != "") ? "'$p_active'" : "NULL") . ", " . (($p_location != "") ? "'$p_location'" : "NULL") . ", " . (($p_s_id != "") ? "'$p_s_id'" : "NULL") . ", " . (($p_first != "") ? "'$p_first'" : "NULL") . ", " . (($p_name != "") ? "'$p_name'" : "NULL") . ", " . (($p_adr != "") ? "'$p_adr'" : "NULL") . ", " . (($p_zip != "") ? "'$p_zip'" : "NULL") . ", " . (($p_mail != "") ? "'$p_mail'" : "NULL") . ", " . (($p_newsaccept != "") ? "'$p_newsaccept'" : "NULL") . ", " . (($p_score != "") ? "'$p_score'" : "NULL") . ", " . (($p_scorehigh != "") ? "'$p_scorehigh'" : "NULL") . ", " . (($p_games != "") ? "'$p_games'" : "NULL") . ", " . (($p_time != "") ? "'$p_time'" : "NULL") . ", " . (($p_win != "") ? "'$p_win'" : "NULL") . ", " . (($p_mk != "") ? "'$p_mk'" : "NULL") . ", " . (($p_born != "") ? "'$p_born'" : "NULL") . ", " . (($p_user != "") ? "'$p_user'" : "NULL") . ", " . (($p_pwd != "") ? "'$p_pwd'" : "NULL") . ")");
	return mysql_insert_id();
}

function delete($selected_id, $AllowDeleteOfParents=false, $SkipChecks=false){
	// insure referential integrity ...
	global $Translation;

	// child table: Dialog
	$res = sql("select p_id from player where p_id='".addslashes($selected_id)."'");
	$p_id = mysql_fetch_row($res);
	$rires = sql("select count(1) from Dialog where d_from_p_id='".addslashes($p_id[0])."'");
	$rirow = mysql_fetch_row($rires);
	if($rirow[0] && !$AllowDeleteOfParents && !$SkipChecks){
		$RetMsg = $Translation["couldn't delete"];
		$RetMsg = str_replace("<RelatedRecords>", $rirow[0], $RetMsg);
		$RetMsg = str_replace("<TableName>", "Dialog", $RetMsg);
		return $RetMsg;
	}elseif($rirow[0] && $AllowDeleteOfParents && !$SkipChecks){
		$RetMsg = $Translation["confirm delete"];
		$RetMsg = str_replace("<RelatedRecords>", $rirow[0], $RetMsg);
		$RetMsg = str_replace("<TableName>", "Dialog", $RetMsg);
		$RetMsg = str_replace("<Delete>", "<input type=button class=button value=\"".$Translation['yes']."\" onClick=\"window.location='player_view.php?SelectedID=$selected_id&delete_x=1&confirmed=1';\">", $RetMsg);
		$RetMsg = str_replace("<Cancel>", "<input type=button class=button value=\"".$Translation['no']."\" onClick=\"window.location='player_view.php?SelectedID=$selected_id';\">", $RetMsg);
		return $RetMsg;
	}
	
	// child table: Dialog
	$res = sql("select p_id from player where p_id='".addslashes($selected_id)."'");
	$p_id = mysql_fetch_row($res);
	$rires = sql("select count(1) from Dialog where d_to_p_id='".addslashes($p_id[0])."'");
	$rirow = mysql_fetch_row($rires);
	if($rirow[0] && !$AllowDeleteOfParents && !$SkipChecks){
		$RetMsg = $Translation["couldn't delete"];
		$RetMsg = str_replace("<RelatedRecords>", $rirow[0], $RetMsg);
		$RetMsg = str_replace("<TableName>", "Dialog", $RetMsg);
		return $RetMsg;
	}elseif($rirow[0] && $AllowDeleteOfParents && !$SkipChecks){
		$RetMsg = $Translation["confirm delete"];
		$RetMsg = str_replace("<RelatedRecords>", $rirow[0], $RetMsg);
		$RetMsg = str_replace("<TableName>", "Dialog", $RetMsg);
		$RetMsg = str_replace("<Delete>", "<input type=button class=button value=\"".$Translation['yes']."\" onClick=\"window.location='player_view.php?SelectedID=$selected_id&delete_x=1&confirmed=1';\">", $RetMsg);
		$RetMsg = str_replace("<Cancel>", "<input type=button class=button value=\"".$Translation['no']."\" onClick=\"window.location='player_view.php?SelectedID=$selected_id';\">", $RetMsg);
		return $RetMsg;
	}
	
	// child table: Q1
	$res = sql("select p_id from player where p_id='".addslashes($selected_id)."'");
	$p_id = mysql_fetch_row($res);
	$rires = sql("select count(1) from Q1 where Q1_p_id='".addslashes($p_id[0])."'");
	$rirow = mysql_fetch_row($rires);
	if($rirow[0] && !$AllowDeleteOfParents && !$SkipChecks){
		$RetMsg = $Translation["couldn't delete"];
		$RetMsg = str_replace("<RelatedRecords>", $rirow[0], $RetMsg);
		$RetMsg = str_replace("<TableName>", "Q1", $RetMsg);
		return $RetMsg;
	}elseif($rirow[0] && $AllowDeleteOfParents && !$SkipChecks){
		$RetMsg = $Translation["confirm delete"];
		$RetMsg = str_replace("<RelatedRecords>", $rirow[0], $RetMsg);
		$RetMsg = str_replace("<TableName>", "Q1", $RetMsg);
		$RetMsg = str_replace("<Delete>", "<input type=button class=button value=\"".$Translation['yes']."\" onClick=\"window.location='player_view.php?SelectedID=$selected_id&delete_x=1&confirmed=1';\">", $RetMsg);
		$RetMsg = str_replace("<Cancel>", "<input type=button class=button value=\"".$Translation['no']."\" onClick=\"window.location='player_view.php?SelectedID=$selected_id';\">", $RetMsg);
		return $RetMsg;
	}
	

	sql("delete from player where p_id='".addslashes($selected_id)."'");
}

function update($selected_id)
{
	global $HTTP_SERVER_VARS, $HTTP_GET_VARS, $HTTP_POST_VARS, $Translation;
	
	if(get_magic_quotes_gpc())
	{
		$p_active = $HTTP_POST_VARS["p_active"];
		$p_location = $HTTP_POST_VARS["p_location"];
		$p_s_id = $HTTP_POST_VARS["p_s_id"];
		$p_first = $HTTP_POST_VARS["p_first"];
		$p_name = $HTTP_POST_VARS["p_name"];
		$p_adr = $HTTP_POST_VARS["p_adr"];
		$p_zip = $HTTP_POST_VARS["p_zip"];
		$p_mail = $HTTP_POST_VARS["p_mail"];
		$p_newsaccept = $HTTP_POST_VARS["p_newsaccept"];
		$p_score = $HTTP_POST_VARS["p_score"];
		$p_scorehigh = $HTTP_POST_VARS["p_scorehigh"];
		$p_games = $HTTP_POST_VARS["p_games"];
		$p_time = $HTTP_POST_VARS["p_time"];
		$p_win = $HTTP_POST_VARS["p_win"];
		$p_mk = $HTTP_POST_VARS["p_mk"];
		$p_born = $HTTP_POST_VARS["p_born"];
		$p_user = $HTTP_POST_VARS["p_user"];
		$p_pwd = $HTTP_POST_VARS["p_pwd"];
		$p_ip = $HTTP_POST_VARS["p_ip"];
		$p_datetime = $HTTP_POST_VARS["p_datetime"];
		$p_tscore = $HTTP_POST_VARS["p_tscore"];
		$p_tkills = $HTTP_POST_VARS["p_tkills"];
	}
	else
	{
		$p_active = addslashes($HTTP_POST_VARS["p_active"]);
		$p_location = addslashes($HTTP_POST_VARS["p_location"]);
		$p_s_id = addslashes($HTTP_POST_VARS["p_s_id"]);
		$p_first = addslashes($HTTP_POST_VARS["p_first"]);
		$p_name = addslashes($HTTP_POST_VARS["p_name"]);
		$p_adr = addslashes($HTTP_POST_VARS["p_adr"]);
		$p_zip = addslashes($HTTP_POST_VARS["p_zip"]);
		$p_mail = addslashes($HTTP_POST_VARS["p_mail"]);
		$p_newsaccept = addslashes($HTTP_POST_VARS["p_newsaccept"]);
		$p_score = addslashes($HTTP_POST_VARS["p_score"]);
		$p_scorehigh = addslashes($HTTP_POST_VARS["p_scorehigh"]);
		$p_games = addslashes($HTTP_POST_VARS["p_games"]);
		$p_time = addslashes($HTTP_POST_VARS["p_time"]);
		$p_win = addslashes($HTTP_POST_VARS["p_win"]);
		$p_mk = addslashes($HTTP_POST_VARS["p_mk"]);
		$p_born = addslashes($HTTP_POST_VARS["p_born"]);
		$p_user = addslashes($HTTP_POST_VARS["p_user"]);
		$p_pwd = addslashes($HTTP_POST_VARS["p_pwd"]);
		$p_ip = addslashes($HTTP_POST_VARS["p_ip"]);
		$p_datetime = addslashes($HTTP_POST_VARS["p_datetime"]);
		$p_tscore = addslashes($HTTP_POST_VARS["p_tscore"]);
		$p_tkills = addslashes($HTTP_POST_VARS["p_tkills"]);
	}

	sql("update player set " . "p_active=" . (($p_active != "") ? "'$p_active'" : "NULL") . ", " . "p_location=" . (($p_location != "") ? "'$p_location'" : "NULL") . ", " . "p_s_id=" . (($p_s_id != "") ? "'$p_s_id'" : "NULL") . ", " . "p_first=" . (($p_first != "") ? "'$p_first'" : "NULL") . ", " . "p_name=" . (($p_name != "") ? "'$p_name'" : "NULL") . ", " . "p_adr=" . (($p_adr != "") ? "'$p_adr'" : "NULL") . ", " . "p_zip=" . (($p_zip != "") ? "'$p_zip'" : "NULL") . ", " . "p_mail=" . (($p_mail != "") ? "'$p_mail'" : "NULL") . ", " . "p_newsaccept=" . (($p_newsaccept != "") ? "'$p_newsaccept'" : "NULL") . ", " . "p_score=" . (($p_score != "") ? "'$p_score'" : "NULL") . ", " . "p_scorehigh=" . (($p_scorehigh != "") ? "'$p_scorehigh'" : "NULL") . ", " . "p_games=" . (($p_games != "") ? "'$p_games'" : "NULL") . ", " . "p_time=" . (($p_time != "") ? "'$p_time'" : "NULL") . ", " . "p_win=" . (($p_win != "") ? "'$p_win'" : "NULL") . ", " . "p_mk=" . (($p_mk != "") ? "'$p_mk'" : "NULL") . ", " . "p_born=" . (($p_born != "") ? "'$p_born'" : "NULL") . ", " . "p_user=" . (($p_user != "") ? "'$p_user'" : "NULL") . ", " . "p_pwd=" . (($p_pwd != "") ? "'$p_pwd'" : "NULL") . " where p_id='".addslashes($selected_id)."'");
}

function form($selected_id = "", $AllowUpdate = 1, $AllowInsert = 1, $AllowDelete = 1)
{
	// function to return an editable form for a table records
	// and fill it with data of record whose ID is $selected_id. If $selected_id
	// is empty, an empty form is shown, with only an 'Add New'
	// button displayed.
	
	global $Translation;
	
	if(!$AllowInsert && $selected_id==""){  return ""; }
	
	
	// combobox: p_active
	$combo_p_active = new DataCombo;
	$combo_p_active->Query = "select xs_id, concat(xs_text, '') from xstatus order by 2";
	$combo_p_active->SelectName = "p_active";
	// combobox: p_location
	$combo_p_location = new DataCombo;
	$combo_p_location->Query = "select l_id, concat(l_name, '') from locations order by 2";
	$combo_p_location->SelectName = "p_location";
	// combobox: p_s_id
	$combo_p_s_id = new DataCombo;
	$combo_p_s_id->Query = "select ts_id, concat(ts_name, '') from teams order by 2";
	$combo_p_s_id->SelectName = "p_s_id";
	// combobox: p_zip
	$combo_p_zip = new DataCombo;
	$combo_p_zip->Query = "select ZZIP, concat(ZZIP, '', ZZCity) from ZZIP order by 2";
	$combo_p_zip->SelectName = "p_zip";
	// combobox: p_mk
	$combo_p_mk = new Combo;
	$combo_p_mk->ListType = 2;
	$combo_p_mk->ListBoxHeight = 10;
	$combo_p_mk->RadiosPerLine = 1;
	$combo_p_mk->ListItem = explode(";;", "D;;P");
	$combo_p_mk->ListData = explode(";;", "D;;P");
	$combo_p_mk->SelectName = "p_mk";

	if($selected_id)
	{
		$res = sql("select * from player where p_id='".addslashes($selected_id)."'");
		$row = mysql_fetch_array($res);
		$combo_p_active->SelectedData = $row["p_active"];
		$combo_p_location->SelectedData = $row["p_location"];
		$combo_p_s_id->SelectedData = $row["p_s_id"];
		$combo_p_zip->SelectedData = $row["p_zip"];
		$combo_p_mk->SelectedData = $row["p_mk"];
	}
	$combo_p_active->Render();
	$combo_p_location->Render();
	$combo_p_s_id->Render();
	$combo_p_zip->Render();
	$combo_p_mk->Render();
	
	// code for template based detail view forms
	
	// open the detail view template
	$templateCode=@implode('', @file('./player_templateDV.html'));
	
	// process form title
	$templateCode=str_replace('<%%DETAIL_VIEW_TITLE%%>', 'Detail View', $templateCode);
	// process buttons
	if($AllowInsert){
		$templateCode=str_replace('<%%INSERT_BUTTON%%>', "<input type=image src=insert.gif name=insert alt='" . $Translation['add new record'] . "'>", $templateCode);
	}else{
		$templateCode=str_replace('<%%INSERT_BUTTON%%>', '', $templateCode);
	}
	if($selected_id){
		if($AllowUpdate){
			$templateCode=str_replace('<%%UPDATE_BUTTON%%>', "<input type=image src=update.gif vspace=1 name=update alt='" . $Translation["update record"] . "'>", $templateCode);
		}else{
			$templateCode=str_replace('<%%UPDATE_BUTTON%%>', '', $templateCode);
		}
		if($AllowDelete){
			$templateCode=str_replace('<%%DELETE_BUTTON%%>', "<input type=image src=delete.gif vspace=1 name=delete alt='" . $Translation['delete record'] . "' onClick=\"return confirm('" . $Translation['are you sure?'] . "');\">", $templateCode);
		}else{
			$templateCode=str_replace('<%%DELETE_BUTTON%%>', '', $templateCode);
		}
		$templateCode=str_replace('<%%DESELECT_BUTTON%%>', "<input type=image src=deselect.gif vspace=1 name=deselect alt='" . $Translation['deselect record'] . "'>", $templateCode);
	}else{
		$templateCode=str_replace('<%%UPDATE_BUTTON%%>', '', $templateCode);
		$templateCode=str_replace('<%%DELETE_BUTTON%%>', '', $templateCode);
		$templateCode=str_replace('<%%DESELECT_BUTTON%%>', '', $templateCode);
	}
	
	// process combos
	$templateCode=str_replace('<%%COMBO(p_active)%%>', $combo_p_active->HTML, $templateCode);
	$templateCode=str_replace('<%%COMBOTEXT(p_active)%%>', $combo_p_active->MatchText, $templateCode);
	$templateCode=str_replace('<%%COMBO(p_location)%%>', $combo_p_location->HTML, $templateCode);
	$templateCode=str_replace('<%%COMBOTEXT(p_location)%%>', $combo_p_location->MatchText, $templateCode);
	$templateCode=str_replace('<%%COMBO(p_s_id)%%>', $combo_p_s_id->HTML, $templateCode);
	$templateCode=str_replace('<%%COMBOTEXT(p_s_id)%%>', $combo_p_s_id->MatchText, $templateCode);
	$templateCode=str_replace('<%%COMBO(p_zip)%%>', $combo_p_zip->HTML, $templateCode);
	$templateCode=str_replace('<%%COMBOTEXT(p_zip)%%>', $combo_p_zip->MatchText, $templateCode);
	$templateCode=str_replace('<%%COMBO(p_mk)%%>', $combo_p_mk->HTML, $templateCode);
	$templateCode=str_replace('<%%COMBOTEXT(p_mk)%%>', $combo_p_mk->SelectedData, $templateCode);
	
	// process images
	$templateCode=str_replace('<%%UPLOADFILE(p_id)%%>', '', $templateCode);
	$templateCode=str_replace('<%%UPLOADFILE(p_active)%%>', '', $templateCode);
	$templateCode=str_replace('<%%UPLOADFILE(p_location)%%>', '', $templateCode);
	$templateCode=str_replace('<%%UPLOADFILE(p_s_id)%%>', '', $templateCode);
	$templateCode=str_replace('<%%UPLOADFILE(p_first)%%>', '', $templateCode);
	$templateCode=str_replace('<%%UPLOADFILE(p_name)%%>', '', $templateCode);
	$templateCode=str_replace('<%%UPLOADFILE(p_adr)%%>', '', $templateCode);
	$templateCode=str_replace('<%%UPLOADFILE(p_zip)%%>', '', $templateCode);
	$templateCode=str_replace('<%%UPLOADFILE(p_mail)%%>', '', $templateCode);
	$templateCode=str_replace('<%%UPLOADFILE(p_newsaccept)%%>', '', $templateCode);
	$templateCode=str_replace('<%%UPLOADFILE(p_score)%%>', '', $templateCode);
	$templateCode=str_replace('<%%UPLOADFILE(p_scorehigh)%%>', '', $templateCode);
	$templateCode=str_replace('<%%UPLOADFILE(p_games)%%>', '', $templateCode);
	$templateCode=str_replace('<%%UPLOADFILE(p_time)%%>', '', $templateCode);
	$templateCode=str_replace('<%%UPLOADFILE(p_win)%%>', '', $templateCode);
	$templateCode=str_replace('<%%UPLOADFILE(p_mk)%%>', '', $templateCode);
	$templateCode=str_replace('<%%UPLOADFILE(p_born)%%>', '', $templateCode);
	$templateCode=str_replace('<%%UPLOADFILE(p_user)%%>', '', $templateCode);
	$templateCode=str_replace('<%%UPLOADFILE(p_pwd)%%>', '', $templateCode);
	$templateCode=str_replace('<%%UPLOADFILE(p_ip)%%>', '', $templateCode);
	$templateCode=str_replace('<%%UPLOADFILE(p_datetime)%%>', '', $templateCode);
	$templateCode=str_replace('<%%UPLOADFILE(p_tscore)%%>', '', $templateCode);
	$templateCode=str_replace('<%%UPLOADFILE(p_tkills)%%>', '', $templateCode);
	
	// process values
	if($selected_id){
		$templateCode=str_replace('<%%VALUE(p_id)%%>', htmlspecialchars($row['p_id'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(p_id)%%>', htmlspecialchars($row['p_id'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(p_active)%%>', htmlspecialchars($row['p_active'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%CHECKED(p_active)%%>', ($row['p_active'] ? "checked" : ""), $templateCode);
		$templateCode=str_replace('<%%VALUE(p_location)%%>', htmlspecialchars($row['p_location'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(p_location)%%>', htmlspecialchars($row['p_location'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(p_s_id)%%>', htmlspecialchars($row['p_s_id'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(p_s_id)%%>', htmlspecialchars($row['p_s_id'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(p_first)%%>', htmlspecialchars($row['p_first'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(p_first)%%>', htmlspecialchars($row['p_first'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(p_name)%%>', htmlspecialchars($row['p_name'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(p_name)%%>', htmlspecialchars($row['p_name'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(p_adr)%%>', htmlspecialchars($row['p_adr'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(p_adr)%%>', htmlspecialchars($row['p_adr'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(p_zip)%%>', htmlspecialchars($row['p_zip'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(p_zip)%%>', htmlspecialchars($row['p_zip'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(p_mail)%%>', htmlspecialchars($row['p_mail'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(p_mail)%%>', htmlspecialchars($row['p_mail'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(p_newsaccept)%%>', htmlspecialchars($row['p_newsaccept'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%CHECKED(p_newsaccept)%%>', ($row['p_newsaccept'] ? "checked" : ""), $templateCode);
		$templateCode=str_replace('<%%VALUE(p_score)%%>', htmlspecialchars($row['p_score'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(p_score)%%>', htmlspecialchars($row['p_score'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(p_scorehigh)%%>', htmlspecialchars($row['p_scorehigh'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(p_scorehigh)%%>', htmlspecialchars($row['p_scorehigh'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(p_games)%%>', htmlspecialchars($row['p_games'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(p_games)%%>', htmlspecialchars($row['p_games'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(p_time)%%>', htmlspecialchars($row['p_time'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(p_time)%%>', htmlspecialchars($row['p_time'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(p_win)%%>', htmlspecialchars($row['p_win'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(p_win)%%>', htmlspecialchars($row['p_win'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(p_mk)%%>', htmlspecialchars($row['p_mk'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(p_mk)%%>', htmlspecialchars($row['p_mk'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(p_born)%%>', htmlspecialchars($row['p_born'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(p_born)%%>', htmlspecialchars($row['p_born'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(p_user)%%>', htmlspecialchars($row['p_user'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(p_user)%%>', htmlspecialchars($row['p_user'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(p_pwd)%%>', htmlspecialchars($row['p_pwd'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(p_pwd)%%>', htmlspecialchars($row['p_pwd'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(p_ip)%%>', htmlspecialchars($row['p_ip'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(p_ip)%%>', htmlspecialchars($row['p_ip'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(p_datetime)%%>', htmlspecialchars($row['p_datetime'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(p_datetime)%%>', htmlspecialchars($row['p_datetime'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(p_tscore)%%>', htmlspecialchars($row['p_tscore'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(p_tscore)%%>', htmlspecialchars($row['p_tscore'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(p_tkills)%%>', htmlspecialchars($row['p_tkills'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(p_tkills)%%>', htmlspecialchars($row['p_tkills'], ENT_QUOTES), $templateCode);
	}else{
		$templateCode=str_replace('<%%VALUE(p_id)%%>', '', $templateCode);
		$templateCode=str_replace('<%%CHECKED(p_active)%%>', '', $templateCode);
		$templateCode=str_replace('<%%VALUE(p_location)%%>', '', $templateCode);
		$templateCode=str_replace('<%%VALUE(p_s_id)%%>', '', $templateCode);
		$templateCode=str_replace('<%%VALUE(p_first)%%>', '', $templateCode);
		$templateCode=str_replace('<%%VALUE(p_name)%%>', '', $templateCode);
		$templateCode=str_replace('<%%VALUE(p_adr)%%>', '', $templateCode);
		$templateCode=str_replace('<%%VALUE(p_zip)%%>', '', $templateCode);
		$templateCode=str_replace('<%%VALUE(p_mail)%%>', '', $templateCode);
		$templateCode=str_replace('<%%CHECKED(p_newsaccept)%%>', '', $templateCode);
		$templateCode=str_replace('<%%VALUE(p_score)%%>', '', $templateCode);
		$templateCode=str_replace('<%%VALUE(p_scorehigh)%%>', '', $templateCode);
		$templateCode=str_replace('<%%VALUE(p_games)%%>', '', $templateCode);
		$templateCode=str_replace('<%%VALUE(p_time)%%>', '', $templateCode);
		$templateCode=str_replace('<%%VALUE(p_win)%%>', '', $templateCode);
		$templateCode=str_replace('<%%VALUE(p_mk)%%>', '', $templateCode);
		$templateCode=str_replace('<%%VALUE(p_born)%%>', '', $templateCode);
		$templateCode=str_replace('<%%VALUE(p_user)%%>', '', $templateCode);
		$templateCode=str_replace('<%%VALUE(p_pwd)%%>', '', $templateCode);
		$templateCode=str_replace('<%%VALUE(p_ip)%%>', '', $templateCode);
		$templateCode=str_replace('<%%VALUE(p_datetime)%%>', '', $templateCode);
		$templateCode=str_replace('<%%VALUE(p_tscore)%%>', '', $templateCode);
		$templateCode=str_replace('<%%VALUE(p_tkills)%%>', '', $templateCode);
	}
	
	// process translations
	foreach($Translation as $symbol=>$trans){
		$templateCode=str_replace("<%%TRANSLATION($symbol)%%>", $trans, $templateCode);
	}
	
	// finally, clear scrap
	$templateCode=str_replace('<%%', '<!--', $templateCode);
	$templateCode=str_replace('%%>', '-->', $templateCode);

	return $templateCode;
}
?>