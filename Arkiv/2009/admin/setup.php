<?php
// This script and data application were generated by AppGini 3.4 on 07-11-2007 at 10:07:20
// Download AppGini for free from http://www.bigprof.com/appgini/download/


	include(dirname(__FILE__)."/language.php");
	
	$divFormat="<div style=\"text-align: center; font-weight: bold; font-family: verdana,arial; font-size: 15px;\">";
	$backHome="$divFormat<a href=\"./\">".$Translation["goto start page"]."</a></div>";
	
	if(!extension_loaded('mysql')){
		echo "<div class=error>ERROR: PHP is not configured to connect to MySQL on this machine. Please see <a href=http://www.php.net/manual/en/ref.mysql.php>this page</a> for help on how to configure MySQL.</div>";
		exit;
	}

	// check this file's MD5 to make sure it wasn't called before
	$prevMD5=@implode('', @file("./setup.md5"));
	$thisMD5=md5(@implode('', @file("./setup.php")));
	if($thisMD5==$prevMD5){
		echo "$divFormat ".$Translation["setup performed"]." ".date("r", filemtime("./setup.md5")).".<br><br></div>";
		echo "$divFormat<div style=\"font-size: 10px;\">".$Translation["delete md5"]."</div></div><br><br>";
		echo $backHome;
		exit;
	}
	
	// connect to the database
	if(is_array($HTTP_POST_VARS)){
		$dbServer=$HTTP_POST_VARS['dbServer'];
		$dbUsername=$HTTP_POST_VARS['dbUsername'];
		$dbPassword=$HTTP_POST_VARS['dbPassword'];
		$dbDatabase=$HTTP_POST_VARS['dbDatabase'];
	}else{
		$dbServer=$_POST['dbServer'];
		$dbUsername=$_POST['dbUsername'];
		$dbPassword=$_POST['dbPassword'];
		$dbDatabase=$_POST['dbDatabase'];
	}
	$noDB=0;
	if(!@mysql_connect($dbServer, $dbUsername, $dbPassword)){
		$noDB=1;
	}elseif(!@mysql_select_db($dbDatabase)){
		if(!@mysql_query("create database `$dbDatabase`")){
			$noDB=2;
		}else{
			if(!@mysql_select_db($dbDatabase)){
				$noDB=2;
			}
		}
	}
	
	// if no connection established, ask for connection data
	if($noDB){
		if($dbServer!=''){
			echo $divFormat."<div style=\"color: red;\">".($noDB==1 ? $Translation["no db connection"] : str_replace("<DBName>", $dbDatabase, $Translation["no db name"]))."</div>"."</div>";
		}
		
		?>
		<form method="post" action="setup.php">
			<?php echo $divFormat; ?>
				<?php echo $Translation["provide connection data"]; ?>
				<br><br><center>
				<table bgcolor="#FFE4E1" style="border: solid silver 1px;" width="400">
					<tr>
						<td align="right"><?php echo $divFormat; ?><?php echo $Translation["mysql server"]; ?></div></td>
						<td><input type="text" name="dbServer" size="20"></td>
						</tr>
					<tr>
						<td align="right"><?php echo $divFormat; ?><?php echo $Translation["mysql username"]; ?></div></td>
						<td><input type="text" name="dbUsername" size="10"></td>
						</tr>
					<tr>
						<td align="right"><?php echo $divFormat; ?><?php echo $Translation["mysql password"]; ?></div></td>
						<td><input type="password" name="dbPassword" size="10"></td>
						</tr>
					<tr>
						<td align="right"><?php echo $divFormat; ?><?php echo $Translation["mysql db"]; ?></div></td>
						<td><input type="text" name="dbDatabase" size="15"></td>
						</tr>
					<tr>
						<td align="right"></td>
						<td><input type="submit" value="<?php echo $Translation["connect"]; ?>"></td>
						</tr>
					</table>
					</center><br><div style="font-size: 10px;">Powered by <a href="http://www.bigprof.com/appgini/" target=_blank>BigProf AppGini 3.4</a></div>
				</div>
			</form>
		<?php
		exit;
	}else{
		// if connection is successful, save parameters into config.php
		if(!$fp=@fopen("./config.php", "w")){
			echo $divFormat."<div style=\"color: red;\">".$Translation["couldnt save config"]."</div></div><br>";
			echo $backHome;
			exit;
		}else{
			fwrite($fp, "<?php\n");
			fwrite($fp, "\t\$dbServer=\"$dbServer\";\n");
			fwrite($fp, "\t\$dbUsername=\"$dbUsername\";\n");
			fwrite($fp, "\t\$dbPassword=\"$dbPassword\";\n");
			fwrite($fp, "\t\$dbDatabase=\"$dbDatabase\";\n");
			fwrite($fp, "?>");
			fclose($fp);
			
		}
	}
	
	// set up tables
	setupTable("player", "create table if not exists `player` ( `p_id` INT unsigned not null auto_increment , primary key (`p_id`), `p_active` TINYINT unsigned , `p_location` INT unsigned , `p_s_id` INT unsigned , `p_first` CHAR(30) , `p_name` CHAR(30) , `p_adr` CHAR(30) , `p_zip` INT(4) unsigned default '0' , `p_mail` CHAR(50) , `p_newsaccept` TINYINT unsigned , `p_score` INT unsigned default '0' , `p_scorehigh` INT unsigned default '0' , `p_games` INT unsigned default '0' , `p_time` TIME , `p_win` INT unsigned default '0' , `p_mk` CHAR(1) , `p_born` YEAR , `p_user` CHAR(12) , `p_pwd` CHAR(12) , `p_ip` CHAR(40) , `p_datetime` TIMESTAMP , `p_tscore` INT unsigned default '0' , `p_tkills` INT unsigned default '0' )");
	setupTable("sponsor", "create table if not exists `sponsor` ( `s_id` INT unsigned not null auto_increment , primary key (`s_id`), `s_active` TINYINT unsigned , `s_name` CHAR(30) , `s_contact` CHAR(30) , `s_adr` CHAR(30) , `s_zip` INT(4) unsigned default '0' , `s_phone1` CHAR(30) , `s_phone2` CHAR(30) , `s_total` DECIMAL(10,2) default '0' , `s_paid` DECIMAL(10,2) default '0' , `s_logo` CHAR(255) , `s_banner` CHAR(255) , `s_www` CHAR(255) , `s_mail` CHAR(30) , `s_cmt` TEXT )");
	setupTable("news", "create table if not exists `news` ( `n_id` INT unsigned not null auto_increment , primary key (`n_id`), `n_active` TINYINT unsigned , `n_start` DATE , `n_end` DATE , `n_date` DATE , `n_head` CHAR(50) , `n_text` TEXT , `n_link` CHAR(255) , `n_file` CHAR(255) , `n_type` INT unsigned )");
	setupTable("locations", "create table if not exists `locations` ( `l_id` INT unsigned not null auto_increment , primary key (`l_id`), `l_active` TINYINT unsigned , `l_name` CHAR(30) , `l_adr` CHAR(30) , `l_zip` INT(4) unsigned default '0' , `l_mail` CHAR(30) , `l_desc` TEXT , `l_phone1` CHAR(30) , `l_phone2` CHAR(30) , `l_open` CHAR(30) , `l_close` CHAR(30) )");
	setupTable("xstatus", "create table if not exists `xstatus` ( `xs_id` TINYINT unsigned not null auto_increment , primary key (`xs_id`), `xs_text` CHAR(30) default '\'\'' )");
	setupTable("mails", "create table if not exists `mails` ( `m_id` INT unsigned not null auto_increment , primary key (`m_id`), `m_name` CHAR(30) , `m_body` TEXT )");
	setupTable("raffle", "create table if not exists `raffle` ( `r_id` INT unsigned not null auto_increment , primary key (`r_id`), `r_name` CHAR(255) , `r_img` CHAR(255) , `r_text` TEXT , `r_date` DATE , `r_p_id` INT unsigned default '0' )");
	setupTable("ZZIP", "create table if not exists `ZZIP` ( `ZZIP` INT(4) unsigned not null default '0' , primary key (`ZZIP`), `ZZCity` CHAR(30) )");
	setupTable("top", "create table if not exists `top` ( `t_id` INT unsigned not null auto_increment , primary key (`t_id`), `t_user` VARCHAR(20) , `t_score` INT unsigned , `t_datetime` TIMESTAMP , `t_ip` CHAR(15) , `t_p_id` INT unsigned , `t_ts_id` INT unsigned , `t_kills` INT unsigned default '0' )");
	setupTable("teams", "create table if not exists `teams` ( `ts_id` INT unsigned not null auto_increment , primary key (`ts_id`), `ts_name` CHAR(30) , `ts_p0` INT unsigned , `ts_p1` INT unsigned , `ts_p2` INT unsigned , `ts_p3` INT unsigned , `ts_p4` INT unsigned , `ts_p5` INT unsigned , `ts_p6` INT unsigned , `ts_p7` INT unsigned , `ts_p8` INT unsigned , `ts_p9` INT unsigned , `ts_s_id` INT unsigned , `ts_Score` INT unsigned default '0' )");
	setupTable("Dialog", "create table if not exists `Dialog` ( `d_id` INT unsigned not null auto_increment , primary key (`d_id`), `d_from_p_id` INT unsigned , `d_to_p_id` INT unsigned , `d_message` TEXT )");
	setupTable("Q1", "create table if not exists `Q1` ( `Q1_id` INT unsigned not null auto_increment , primary key (`Q1_id`), `Q1_work` TINYINT unsigned default '0' , `Q1_where` TINYINT unsigned default '0' , `Q1_why` TINYINT unsigned default '0' , `Q1_rating` INT unsigned default '0' , `Q1_speakothers` TINYINT unsigned default '0' , `Q1_changedme` TINYINT unsigned default '0' , `Q1_playoften` TINYINT unsigned default '0' , `Q1_infolevel` TINYINT unsigned default '0' , `Q1_SNotice` TINYINT unsigned default '0' , `Q1_SNoticewhere` TINYINT unsigned default '0' , `Q1_SNoticemainsponsor` INT unsigned , `Q1_SNoticeteam` INT unsigned default '0' , `Q1_SNoticeother` TINYINT unsigned default '0' , `Q1_Shavechangedview` TINYINT unsigned default '0' , `Q1_Sbuyproducts` TINYINT unsigned default '0' , `Q1_Snameothers` CHAR(255) , `Q1_Comments` TINYTEXT , `Q1_p_id` INT unsigned default '0' )", array("ALTER TABLE `Q1` ADD `field1` INT","ALTER TABLE `Q1` CHANGE `field1` `Q1_p_ip` INT ","ALTER TABLE `Q1` CHANGE `Q1_p_ip` `Q1_p_ip` INT not null "," ALTER TABLE `Q1` CHANGE `Q1_p_ip` `Q1_p_ip` INT unsigned not null ","ALTER TABLE `Q1` ADD `field2` INT","ALTER TABLE `Q1` CHANGE `field2` `Q1_work` INT "," ALTER TABLE `Q1` CHANGE `Q1_work` `Q1_work` INT unsigned "," ALTER TABLE `Q1` CHANGE `Q1_work` `Q1_work` INT unsigned default '0' "," ALTER TABLE `Q1` CHANGE `Q1_work` `Q1_work` TINYINT unsigned default '0' ","ALTER TABLE `Q1` ADD `field3` INT","ALTER TABLE `Q1` CHANGE `field3` `Q1_where` INT "," ALTER TABLE `Q1` CHANGE `Q1_where` `Q1_where` TINYINT "," ALTER TABLE `Q1` CHANGE `Q1_where` `Q1_where` TINYINT default '0' ","ALTER TABLE `Q1` ADD `field4` INT","ALTER TABLE `Q1` CHANGE `field4` `Q1_why` INT "," ALTER TABLE `Q1` CHANGE `Q1_why` `Q1_why` TINYINT "," ALTER TABLE `Q1` CHANGE `Q1_why` `Q1_why` TINYINT unsigned "," ALTER TABLE `Q1` CHANGE `Q1_why` `Q1_why` TINYINT unsigned default '0' "," ALTER TABLE `Q1` CHANGE `Q1_where` `Q1_where` TINYINT unsigned default '0' ","ALTER TABLE `Q1` ADD `field5` INT","ALTER TABLE `Q1` CHANGE `field5` `Q1_rating` INT "," ALTER TABLE `Q1` CHANGE `Q1_rating` `Q1_rating` INT unsigned "," ALTER TABLE `Q1` CHANGE `Q1_rating` `Q1_rating` INT unsigned default '0' ","ALTER TABLE `Q1` ADD `field6` INT","ALTER TABLE `Q1` CHANGE `field6` `Q1_speakothers` INT "," ALTER TABLE `Q1` CHANGE `Q1_speakothers` `Q1_speakothers` TINYINT "," ALTER TABLE `Q1` CHANGE `Q1_speakothers` `Q1_speakothers` TINYINT unsigned "," ALTER TABLE `Q1` CHANGE `Q1_speakothers` `Q1_speakothers` TINYINT unsigned default '0' ","ALTER TABLE `Q1` ADD `field7` INT","ALTER TABLE `Q1` CHANGE `field7` `Q1_changedme` INT ","ALTER TABLE `Q1` ADD `field8` INT","ALTER TABLE `Q1` CHANGE `field8` `Q1_playoften` INT "," ALTER TABLE `Q1` CHANGE `Q1_playoften` `Q1_playoften` TINYINT "," ALTER TABLE `Q1` CHANGE `Q1_changedme` `Q1_changedme` TINYINT "," ALTER TABLE `Q1` CHANGE `Q1_changedme` `Q1_changedme` TINYINT default '0' "," ALTER TABLE `Q1` CHANGE `Q1_changedme` `Q1_changedme` TINYINT unsigned default '0' "," ALTER TABLE `Q1` CHANGE `Q1_playoften` `Q1_playoften` TINYINT unsigned "," ALTER TABLE `Q1` CHANGE `Q1_playoften` `Q1_playoften` TINYINT unsigned default '0' ","ALTER TABLE `Q1` ADD `field9` INT","ALTER TABLE `Q1` CHANGE `field9` `Q1_infolevel` INT "," ALTER TABLE `Q1` CHANGE `Q1_infolevel` `Q1_infolevel` INT unsigned "," ALTER TABLE `Q1` CHANGE `Q1_infolevel` `Q1_infolevel` INT unsigned default '0' "," ALTER TABLE `Q1` CHANGE `Q1_infolevel` `Q1_infolevel` TINYINT unsigned default '0' ","ALTER TABLE `Q1` ADD `field10` INT","ALTER TABLE `Q1` CHANGE `field10` `Q1_SNotice` INT "," ALTER TABLE `Q1` CHANGE `Q1_SNotice` `Q1_SNotice` TINYINT "," ALTER TABLE `Q1` CHANGE `Q1_SNotice` `Q1_SNotice` TINYINT unsigned "," ALTER TABLE `Q1` CHANGE `Q1_SNotice` `Q1_SNotice` TINYINT unsigned default '0' ","ALTER TABLE `Q1` ADD `field11` INT","ALTER TABLE `Q1` CHANGE `field11` `Q1_SNoticewhere` INT "," ALTER TABLE `Q1` CHANGE `Q1_SNoticewhere` `Q1_SNoticewhere` TINYINT "," ALTER TABLE `Q1` CHANGE `Q1_SNoticewhere` `Q1_SNoticewhere` TINYINT default '0' "," ALTER TABLE `Q1` CHANGE `Q1_SNoticewhere` `Q1_SNoticewhere` TINYINT unsigned default '0' ","ALTER TABLE `Q1` ADD `field12` INT","ALTER TABLE `Q1` CHANGE `field12` `Q1_SNoticemainsponsor` INT "," ALTER TABLE `Q1` CHANGE `Q1_SNoticemainsponsor` `Q1_SNoticemainsponsor` INT unsigned "," ALTER TABLE `Q1` CHANGE `Q1_SNoticemainsponsor` `Q1_SNoticemainsponsor` INT "," ALTER TABLE `Q1` CHANGE `Q1_SNoticemainsponsor` `Q1_SNoticemainsponsor` INT unsigned ","ALTER TABLE `Q1` ADD `field13` INT","ALTER TABLE `Q1` CHANGE `field13` `Q1_SNoticeteam` INT "," ALTER TABLE `Q1` CHANGE `Q1_SNoticeteam` `Q1_SNoticeteam` INT unsigned default '0' ","ALTER TABLE `Q1` ADD `field14` INT","ALTER TABLE `Q1` CHANGE `field14` `Q1_SNoticeother` INT "," ALTER TABLE `Q1` CHANGE `Q1_SNoticeother` `Q1_SNoticeother` TINYINT "," ALTER TABLE `Q1` CHANGE `Q1_SNoticeother` `Q1_SNoticeother` TINYINT unsigned "," ALTER TABLE `Q1` CHANGE `Q1_SNoticeother` `Q1_SNoticeother` TINYINT unsigned default '0' ","ALTER TABLE `Q1` ADD `field15` INT","ALTER TABLE `Q1` CHANGE `field15` `Q1_Shavechangedview` INT "," ALTER TABLE `Q1` CHANGE `Q1_Shavechangedview` `Q1_Shavechangedview` TINYINT "," ALTER TABLE `Q1` CHANGE `Q1_Shavechangedview` `Q1_Shavechangedview` TINYINT default '0' "," ALTER TABLE `Q1` CHANGE `Q1_Shavechangedview` `Q1_Shavechangedview` TINYINT unsigned default '0' ","ALTER TABLE `Q1` ADD `field16` INT","ALTER TABLE `Q1` CHANGE `field16` `Q1_Sbuyproducts` INT "," ALTER TABLE `Q1` CHANGE `Q1_Sbuyproducts` `Q1_Sbuyproducts` TINYINT "," ALTER TABLE `Q1` CHANGE `Q1_Sbuyproducts` `Q1_Sbuyproducts` TINYINT unsigned "," ALTER TABLE `Q1` CHANGE `Q1_Sbuyproducts` `Q1_Sbuyproducts` TINYINT unsigned default '0' ","ALTER TABLE `Q1` ADD `field17` INT","ALTER TABLE `Q1` CHANGE `field17` `Q1_Snameothers` INT "," ALTER TABLE `Q1` CHANGE `Q1_Snameothers` `Q1_Snameothers` CHAR(1) "," ALTER TABLE `Q1` CHANGE `Q1_Snameothers` `Q1_Snameothers` CHAR(255) ","ALTER TABLE `Q1` ADD `field18` INT","ALTER TABLE `Q1` CHANGE `field18` `Q1_Comments` INT "," ALTER TABLE `Q1` CHANGE `Q1_Comments` `Q1_Comments` TINYTEXT ","ALTER TABLE `Q1` CHANGE `Q1_p_ip` `Q1_id` INT unsigned not null "," ALTER TABLE `Q1` CHANGE `Q1_id` `Q1_id` INT unsigned not null auto_increment ","ALTER TABLE `Q1` ADD `field19` INT","ALTER TABLE `Q1` CHANGE `field19` `Q1_p_id` INT "," ALTER TABLE `Q1` CHANGE `Q1_p_id` `Q1_p_id` INT unsigned "," ALTER TABLE `Q1` CHANGE `Q1_p_id` `Q1_p_id` INT unsigned default '0' ","ALTER TABLE `Q1` ADD PRIMARY KEY (`Q1_id`)"));
	
	// save MD5
	if($fp=@fopen("./setup.md5", "w")){
		fwrite($fp, $thisMD5);
		fclose($fp);
	}
	
	// go to index
	echo $backHome;
	
	// ------------------------------------------
	
	function setupTable($tableName, $createSQL='', $arrAlter=''){
		global $Translation;
		
		echo "<div style=\"padding: 5px; border-bottom:solid 1px silver; font-family: verdana, arial; font-size: 10px;\">";
		if($res=@mysql_query("select count(1) from `$tableName`")){
			if($row=@mysql_fetch_array($res)){
				echo str_replace("<TableName>", $tableName, str_replace("<NumRecords>", $row[0],$Translation["table exists"]));
				if(is_array($arrAlter)){
					echo '<br>';
					foreach($arrAlter as $alter){
						if($alter!=''){
							echo "$alter ... ";
							if(!@mysql_query($alter)){
								echo "<font color=red>".$Translation["failed"]."</font><br>";
								echo "<font color=red>".$Translation["mysql said"]." ".mysql_error()."</font><br>";
							}else{
								echo "<font color=green>".$Translation["ok"]."</font><br>";
							}
						}
					}
				}else{
					echo $Translation["table uptodate"];
				}
			}else{
				echo str_replace("<TableName>", $tableName, $Translation["couldnt count"]);
			}
		}else{
			echo str_replace("<TableName>", $tableName, $Translation["creating table"]);
			if(!@mysql_query($createSQL)){
				echo "<font color=red>".$Translation["failed"]."</font><br>";
				echo "<font color=red>".$Translation["mysql said"].mysql_error()."</font>";
			}else{
				echo "<font color=green>".$Translation["ok"]."</font>";
			}
		}
		
		echo "</div>";
	}
?>