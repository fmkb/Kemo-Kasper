<?php

// Data functions for table raffle

// This script and data application were generated by AppGini 3.4 on 07-11-2007 at 10:07:20
// Download AppGini for free from http://www.bigprof.com/appgini/download/

function insert()
{
	global $HTTP_SERVER_VARS, $HTTP_GET_VARS, $HTTP_POST_VARS, $HTTP_POST_FILES, $Translation;
	
	if(get_magic_quotes_gpc())
	{
		$r_name = $HTTP_POST_VARS["r_name"];
		$r_text = $HTTP_POST_VARS["r_text"];
		$r_date = $HTTP_POST_VARS["r_dateYear"] . '-' . $HTTP_POST_VARS["r_dateMonth"] . '-' . $HTTP_POST_VARS["r_dateDay"];
		$r_p_id = $HTTP_POST_VARS["r_p_id"];
	}
	else
	{
		$r_name = addslashes($HTTP_POST_VARS["r_name"]);
		$r_text = addslashes($HTTP_POST_VARS["r_text"]);
		$r_date = addslashes($HTTP_POST_VARS["r_dateYear"]) . '-' . addslashes($HTTP_POST_VARS["r_dateMonth"]) . '-' . addslashes($HTTP_POST_VARS["r_dateDay"]);
		$r_p_id = addslashes($HTTP_POST_VARS["r_p_id"]);
	}
	$r_img = PrepareUploadedFile('r_img', 150000,'jpg|jpeg|gif|png', false, "");
	if($r_p_id == "") $r_p_id = "0";
	
	sql("insert into raffle (r_name, r_img, r_text, r_date, r_p_id) values (" . (($r_name != "") ? "'$r_name'" : "NULL") . ", " . (($r_img != "") ? "'$r_img'" : "NULL") . ", " . (($r_text != "") ? "'$r_text'" : "NULL") . ", " . (($r_date != "") ? "'$r_date'" : "NULL") . ", " . (($r_p_id != "") ? "'$r_p_id'" : "NULL") . ")");
	return mysql_insert_id();
}

function delete($selected_id, $AllowDeleteOfParents=false, $SkipChecks=false){
	// insure referential integrity ...
	global $Translation;


	// delete file stored in the 'r_img' field
	$res = sql("select r_img from raffle where r_id='".addslashes($selected_id)."'");
	if($row=@mysql_fetch_row($res)){
		if($row[0]!=''){
			@unlink(getUploadDir("").$row[0]);
		}
	}

	sql("delete from raffle where r_id='".addslashes($selected_id)."'");
}

function update($selected_id)
{
	global $HTTP_SERVER_VARS, $HTTP_GET_VARS, $HTTP_POST_VARS, $Translation;
	
	if(get_magic_quotes_gpc())
	{
		$r_name = $HTTP_POST_VARS["r_name"];
		$r_text = $HTTP_POST_VARS["r_text"];
		$r_date = $HTTP_POST_VARS["r_dateYear"] . '-' . $HTTP_POST_VARS["r_dateMonth"] . '-' . $HTTP_POST_VARS["r_dateDay"];
		$r_p_id = $HTTP_POST_VARS["r_p_id"];
	}
	else
	{
		$r_name = addslashes($HTTP_POST_VARS["r_name"]);
		$r_text = addslashes($HTTP_POST_VARS["r_text"]);
		$r_date = $HTTP_POST_VARS["r_dateYear"] . '-' . $HTTP_POST_VARS["r_dateMonth"] . '-' . $HTTP_POST_VARS["r_dateDay"];
		$r_p_id = addslashes($HTTP_POST_VARS["r_p_id"]);
	}

	if($HTTP_POST_VARS['r_img_remove'] == 1){
		$r_img = '';
		// delete file from server
		$res = sql("select r_img from raffle where r_id='$selected_id'");
		if($row=@mysql_fetch_row($res)){
			if($row[0]!=''){
				@unlink(getUploadDir("").$row[0]);
			}
		}
	}else{
		$r_img = PrepareUploadedFile('r_img', 150000, 'jpg|jpeg|gif|png', false, "");
		// delete file from server
		if($r_img != ''){
			$res = sql("select r_img from raffle where r_id='$selected_id'");
			if($row=@mysql_fetch_row($res)){
				if($row[0]!=''){
					@unlink(getUploadDir("").$row[0]);
				}
			}
		}
	}
	sql("update raffle set " . "r_name=" . (($r_name != "") ? "'$r_name'" : "NULL") . ", " . ($r_img!="" ? "r_img='$r_img'" : ($HTTP_POST_VARS['r_img_remove'] != 1 ? "r_img=r_img" : "r_img=NULL")) . ", " . "r_text=" . (($r_text != "") ? "'$r_text'" : "NULL") . ", " . "r_date=" . (($r_date != "") ? "'$r_date'" : "NULL") . ", " . "r_p_id=" . (($r_p_id != "") ? "'$r_p_id'" : "NULL") . " where r_id='".addslashes($selected_id)."'");
}

function form($selected_id = "", $AllowUpdate = 1, $AllowInsert = 1, $AllowDelete = 1)
{
	// function to return an editable form for a table records
	// and fill it with data of record whose ID is $selected_id. If $selected_id
	// is empty, an empty form is shown, with only an 'Add New'
	// button displayed.
	
	global $Translation;
	
	if(!$AllowInsert && $selected_id==""){  return ""; }
	
	
	// combobox: r_date
	$combo_r_date = new DateCombo;
	$combo_r_date->DateFormat = "mdy";
	$combo_r_date->MinYear = 1900;
	$combo_r_date->MaxYear = 2100;
	$combo_r_date->DefaultDate = date("Y-m-d");
	$combo_r_date->MonthNames = $Translation['month names'];
	$combo_r_date->CSSOptionClass = 'Option';
	$combo_r_date->CSSSelectedClass = 'SelectedOption';
	$combo_r_date->NamePrefix = 'r_date';
	$r_dateToday=" [<a href=\"javascript: return false;\" onClick=\"document.getElementById('r_dateYear').value='".date("Y")."'; document.getElementById('r_dateMonth').value='".date("m")."'; document.getElementById('r_dateDay').value='".date("d")."';\">".$Translation['today']."</a>]";

	if($selected_id)
	{
		$res = sql("select * from raffle where r_id='".addslashes($selected_id)."'");
		$row = mysql_fetch_array($res);
		$combo_r_date->DefaultDate = $row["r_date"];
	}
	
	// code for template based detail view forms
	
	// open the detail view template
	$templateCode=@implode('', @file('./raffle_templateDV.html'));
	
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
	$templateCode=str_replace('<%%COMBO(r_date)%%>', $combo_r_date->GetHTML().$r_dateToday, $templateCode);
	$templateCode=str_replace('<%%COMBOTEXT(r_date)%%>', $combo_r_date->GetHTML(true), $templateCode);
	
	// process images
	$templateCode=str_replace('<%%UPLOADFILE(r_id)%%>', '', $templateCode);
	$templateCode=str_replace('<%%UPLOADFILE(r_name)%%>', '', $templateCode);
	$templateCode=str_replace('<%%UPLOADFILE(r_img)%%>', '<input type=hidden name=MAX_FILE_SIZE value=150000>'.$Translation['upload image'].' <input type=file name="r_img" class=TextBox>', $templateCode);
	if($AllowUpdate && $row['r_img']!=''){
		$templateCode=str_replace('<%%REMOVEFILE(r_img)%%>', '<input type=checkbox name="r_img_remove" value="1"> <b class=Error>'.$Translation['remove image'].'</b>', $templateCode);
	}else{
		$templateCode=str_replace('<%%REMOVEFILE(r_img)%%>', '', $templateCode);
	}
	$templateCode=str_replace('<%%UPLOADFILE(r_text)%%>', '', $templateCode);
	$templateCode=str_replace('<%%UPLOADFILE(r_date)%%>', '', $templateCode);
	$templateCode=str_replace('<%%UPLOADFILE(r_p_id)%%>', '', $templateCode);
	
	// process values
	if($selected_id){
		$templateCode=str_replace('<%%VALUE(r_id)%%>', htmlspecialchars($row['r_id'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(r_id)%%>', htmlspecialchars($row['r_id'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(r_name)%%>', htmlspecialchars($row['r_name'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(r_name)%%>', htmlspecialchars($row['r_name'], ENT_QUOTES), $templateCode);
		$row['r_img']=($row['r_img']!=''?$row['r_img']:'blank.gif');
		$templateCode=str_replace('<%%VALUE(r_img)%%>', htmlspecialchars($row['r_img'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(r_img)%%>', htmlspecialchars($row['r_img'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(r_text)%%>', htmlspecialchars($row['r_text'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(r_text)%%>', htmlspecialchars($row['r_text'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(r_date)%%>', htmlspecialchars($row['r_date'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(r_date)%%>', htmlspecialchars($row['r_date'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(r_p_id)%%>', htmlspecialchars($row['r_p_id'], ENT_QUOTES), $templateCode);
		$templateCode=str_replace('<%%VALUE(r_p_id)%%>', htmlspecialchars($row['r_p_id'], ENT_QUOTES), $templateCode);
	}else{
		$templateCode=str_replace('<%%VALUE(r_id)%%>', '', $templateCode);
		$templateCode=str_replace('<%%VALUE(r_name)%%>', '', $templateCode);
		$templateCode=str_replace('<%%VALUE(r_img)%%>', 'blank.gif', $templateCode);
		$templateCode=str_replace('<%%VALUE(r_text)%%>', '', $templateCode);
		$templateCode=str_replace('<%%VALUE(r_date)%%>', '', $templateCode);
		$templateCode=str_replace('<%%VALUE(r_p_id)%%>', '', $templateCode);
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