<?php

	// IMPORTANT:
	// ==========
	// When translating, only translate the strings that are
	// TO THE RIGHT OF the equal sign (=).
	//
	// Do NOT translate the strings between square brackets ([])
	//
	// Also, leave the text between < and > untranslated.
	// =====================================================
		

	// datalist.php
	$Translation["powered by"] = "Powered by";
	$Translation["quick search"] = "Quick Search";
	$Translation["records x to y of z"] = "Records <FirstRecord> to <LastRecord> of <RecordCount>";
	$Translation["filters"] = "Filters";
	$Translation["filter"] = "Filter";
	$Translation["filtered field"] = "Filtered field";
	$Translation["comparison operator"] = "Comparison Operator";
	$Translation["comparison value"] = "Comparison Value";
	$Translation["and"] = "And";
	$Translation["or"] = "Or";
	$Translation["equal to"] = "Equal to";
	$Translation["not equal to"] = "Not equal to";
	$Translation["greater than"] = "Greater than";
	$Translation["greater than or equal to"] = "Greater than or equal to";
	$Translation["less than"] = "Less than";
	$Translation["less than or equal to"] = "Less than or equal to";
	$Translation["like"] = "Like";
	$Translation["not like"] = "Not like";
	$Translation["is empty"] = "Is empty";
	$Translation["is not empty"] = "Is not empty";
	$Translation["apply filters"] = "Apply filters";
	$Translation["save filters"] = "Save and apply filters";
	$Translation["saved filters title"] = "HTML Code For The Applied Filters";
	$Translation["saved filters instructions"] = "Copy the code below and paste it to an HTML file to save the filter you just defined so that you can return to it at any time in the future without having to redefine it. You can save this HTML code on your computer or on any server and access this prefiltered table view through it.";
	$Translation["hide code"] = "Hide this code";
	$Translation["printer friendly view"] = "Printer-friendly view";
	$Translation["save as csv"] = "Download as csv file (comma-separated values)";
	$Translation["edit filters"] = "Edit filters";
	$Translation["clear filters"] = "Clear filters";
	$Translation['order by'] = 'Order by';
	$Translation['go to page'] = 'Go to page:';
	$Translation['none'] = 'None';

	// _dml.php
	$Translation["are you sure?"] = "Are you sure you want to delete this record?";
	$Translation["add new record"] = "Add new record";
	$Translation["update record"] = "Update record";
	$Translation["delete record"] = "Delete record";
	$Translation["deselect record"] = "Deselect record";
	$Translation["couldn't delete"] = "Couldn't delete record due to presence of <RelatedRecords> related record(s) in table '<TableName>'";
	$Translation["confirm delete"] = "This record has <RelatedRecords> related record(s) in table '<TableName>'. Do you still want to delete it? <Delete> &nbsp; <Cancel>";
	$Translation["yes"] = "Yes";
	$Translation["no"] = "No";
	$Translation["pkfield empty"] = " field is a primary key field and cannot be empty.";
	$Translation["upload image"] = "Upload new file ";
	$Translation["select image"] = "Select an image ";
	$Translation["remove image"] = "Remove file";
	$Translation["month names"] = "January,February,March,April,May,June,July,August,September,October,November,December";
	$Translation["field not null"] = "You can't leave this field empty.";
	$Translation["*"] = "*";
	$Translation['today'] = "Today";

	// lib.php
	$Translation["select a table"] = "Jump to ...";
	$Translation["homepage"] = "Homepage";
	$Translation["error:"] = "Error:";
	$Translation["sql error:"] = "SQL error:";
	$Translation["query:"] = "Query:";
	$Translation["< back"] = "&lt; Back";
	$Translation["if you haven't set up"] = "If you haven't set up the database yet, you can do so by clicking <a href='setup.php'>here</a>.";
	
	// setup.php
	$Translation["goto start page"] = "Back to start page";
	$Translation["no db connection"] = "Couldn't establish a database connection.";
	$Translation["no db name"] = "Couldn't access the database named '<DBName>' on this server.";
	$Translation["provide connection data"] = "Please provide the following data to connect to the database:";
	$Translation["mysql server"] = "MySQL server (host)";
	$Translation["mysql username"] = "MySQL Username";
	$Translation["mysql password"] = "MySQL password";
	$Translation["mysql db"] = "Database name";
	$Translation["connect"] = "Connect";
	$Translation["couldnt save config"] = "Couldn't save connection data into 'config.php'.<br>Please make sure that the folder:<br>'".dirname(__FILE__)."'<br>is writable (chmod 775 or chmod 777).";
	$Translation["setup performed"] = "Setup already performed on";
	$Translation["delete md5"] = "If you want to force setup to run again, you should first delete the file 'setup.md5' from this folder.";
	$Translation["table exists"] = "Table <b><TableName></b> exists, containing <NumRecords> records.";
	$Translation["failed"] = "Failed";
	$Translation["ok"] = "Ok";
	$Translation["mysql said"] = "MySQL said:";
	$Translation["table uptodate"] = "Table is up-to-date.";
	$Translation["couldnt count"] = "Couldn't count records of table <b><TableName></b>";
	$Translation["creating table"] = "Creating table <b><TableName></b> ... ";
	
	// separateDVTV.php
	$Translation['please wait'] = "Please wait";

	// general config
	// DO NOT TRANSLATE THE FOLLOWING
	$Translation["ImageFolder"] = "./images/";

?>