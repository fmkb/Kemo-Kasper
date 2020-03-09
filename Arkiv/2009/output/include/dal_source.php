<?php
function CustomQuery($dalSQL)
{
	global $conn;
	$rs = db_query($dalSQL,$conn);
	//$data = db_fetch_array($rs);
	return $rs;
}

function UsersTableName()
{
	return "";
}

class tDAL
{
	var $xstatus;
	var $Dialog;
	var $mails;
	var $news;
	var $raffle;
	var $sponsor;
	var $top;
	var $teams;
	var $player;
	var $xcountries;
}

$dal = new tDAL;


class class_xstatus
{
	var $m_TableName;
	var $m_GoodTableName;
	var $m_xs_id;
	var $m_xs_text;

	var $Param = array();
	var $Value = array();
	
	var $xs_id = array();
	var $xs_text = array();
	
	var $m_ChangedFields;
	var $m_UpdateParam;
	var $m_UpdateValue;

	
	function class_xstatus()
	{
		$this->m_TableName = "xstatus";
		$this->m_GoodTableName = AddTableWrappers($this->m_TableName);	
	}

function TableName()
{
	$this->m_TableName = "xstatus";
	$this->m_GoodTableName = AddTableWrappers($this->m_TableName);
	return $this->m_GoodTableName;
} 

function Add() 
{
	global $conn;

	$insertFields="";
	$insertValues="";
		

		if ($this->xs_id)
		{
			$this->Value["xs_id"] = $this->xs_id;
		    $this->m_xs_id = $this->xs_id;
		}	

		if ($this->xs_text)
		{
			$this->Value["xs_text"] = $this->xs_text;
		    $this->m_xs_text = $this->xs_text;
		}	
	
		if ($this->Value["xs_id"])
		    $this->m_xs_id = $this->Value["xs_id"];	
		if ($this->Value["xs_text"])
		    $this->m_xs_text = $this->Value["xs_text"];	

		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("xs_id"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("xs_id").",";
			if (NeedQuotes(16))
				$insertValues.= "'".db_addslashes($this->Value["xs_id"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["xs_id"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("xs_text"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("xs_text").",";
			if (NeedQuotes(129))
				$insertValues.= "'".db_addslashes($this->Value["xs_text"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["xs_text"]) . ",";		

		}

		
	if ($insertFields!="" && $insertValues!="")		
	{
		$insertFields = substr($insertFields,0,-1);
		$insertValues = substr($insertValues,0,-1);
		$dalSQL = "insert into ".$this->m_GoodTableName." (".$insertFields.") values (".$insertValues.")";
		db_exec($dalSQL,$conn);
	}
	
        unset($this->Value["xs_id"]);
        unset($this->Value["xs_text"]);
   // unset($this->m_ChangedFields);

	}
	
function Delete()
{
	global $conn;
	$deleteFields="";

		if ($this->xs_id)
		{
			$this->Value["xs_id"] = $this->xs_id;
			$this->m_xs_id = $this->xs_id;
		}	
		if ($this->xs_text)
		{
			$this->Value["xs_text"] = $this->xs_text;
			$this->m_xs_text = $this->xs_text;
		}	
	
		if ($this->Value["xs_id"])
		    $this->m_xs_id = $this->Value["xs_id"];	
		if ($this->Value["xs_text"])
		    $this->m_xs_text = $this->Value["xs_text"];	

		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("xs_id"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("xs_id").",";
			if (NeedQuotes(16))
				$deleteFields.= AddFieldWrappers("xs_id")."='".db_addslashes($this->Value["xs_id"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("xs_id")."=".db_addslashes($this->Value["xs_id"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("xs_text"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("xs_text").",";
			if (NeedQuotes(129))
				$deleteFields.= AddFieldWrappers("xs_text")."='".db_addslashes($this->Value["xs_text"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("xs_text")."=".db_addslashes($this->Value["xs_text"]) . " and ";		
		}

	if ($deleteFields)
	{
		$deleteFields = substr($deleteFields,0,-5);
		$dalSQL = "delete from ".$this->m_GoodTableName." where ".$deleteFields;
		db_exec($dalSQL,$conn);
	}
	
	    unset($this->Value["xs_id"]);
	    unset($this->Value["xs_text"]);
		
	//unset($this->Value);	
}

function Update()
{
	global $conn;
	
	$updateParam = "";
	$updateValue = "";
	
		if ($this->xs_id)
		{
			if (1==1)
				$this->Param["xs_id"] = $this->xs_id;
			else
				$this->Value["xs_id"] = $this->xs_id;
			$this->m_xs_id = $this->xs_id;
		}	
		if ($this->xs_text)
		{
			if (0==1)
				$this->Param["xs_text"] = $this->xs_text;
			else
				$this->Value["xs_text"] = $this->xs_text;
			$this->m_xs_text = $this->xs_text;
		}	
	
		if ($this->Value["xs_id"])
		    $this->m_xs_id = $this->Value["xs_id"];		
		if ($this->Value["xs_text"])
		    $this->m_xs_text = $this->Value["xs_text"];		
	
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("xs_id"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(16))
					$updateValue.= AddFieldWrappers("xs_id")."='".$this->Value["xs_id"]."', ";
			else
					$updateValue.= AddFieldWrappers("xs_id")."=".$this->Value["xs_id"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("xs_id"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(16))
					$updateParam.= AddFieldWrappers("xs_id")."='".db_addslashes($this->Param["xs_id"])."' and ";
			else
					$updateParam.= AddFieldWrappers("xs_id")."=".db_addslashes($this->Param["xs_id"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("xs_text"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(129))
					$updateValue.= AddFieldWrappers("xs_text")."='".$this->Value["xs_text"]."', ";
			else
					$updateValue.= AddFieldWrappers("xs_text")."=".$this->Value["xs_text"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("xs_text"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(129))
					$updateParam.= AddFieldWrappers("xs_text")."='".db_addslashes($this->Param["xs_text"])."' and ";
			else
					$updateParam.= AddFieldWrappers("xs_text")."=".db_addslashes($this->Param["xs_text"])." and ";
		}
		
	
	if ($updateParam)
		$updateParam = substr($updateParam,0,-5);
	if ($updateValue)
		$updateValue = substr($updateValue,0,-2);
		
	if ($updateValue)
	{
		$dalSQL = "update ".$this->m_GoodTableName." set ".$updateValue." where ".$updateParam;
		db_exec($dalSQL,$conn);
	}
	
        unset($this->Value["xs_id"]);
		unset($this->Param["xs_id"]);
        unset($this->Value["xs_text"]);
		unset($this->Param["xs_text"]);
	
}

function QueryAll()
{
	global $conn;
	$dal_variables="";
		$dal_variables.= AddFieldWrappers("xs_id").", ";
		$dal_variables.= AddFieldWrappers("xs_text").", ";
	$dal_variables = substr($dal_variables,0,-2);
	$dalSQL = "select ".$dal_variables." from ".$this->m_GoodTableName;
	$rs = db_query($dalSQL,$conn);
	$data = db_fetch_array($rs);
	return $data;
}

function Query($swhere,$orderby)
{
	global $conn;
	$dal_variables="";
		$dal_variables.= AddFieldWrappers("xs_id").", ";
		$dal_variables.= AddFieldWrappers("xs_text").", ";
	$dal_variables = substr($dal_variables,0,-2);
	if ($swhere)
		$swhere = " where ".$swhere;
	if ($orderby)
		$orderby = " order by ".$orderby;
	
	$dalSQL = "select ".$dal_variables." from ".$this->m_GoodTableName.$swhere.$orderby;
	$rs = db_query($dalSQL,$conn);
	$data = db_fetch_array($rs);
	return $data; 	
}

function FetchByID()
{
	global $conn;
	$dal_variables="";
	$dal_where="";
        $dal_variables.= AddFieldWrappers("xs_id").", ";
        $dal_variables.= AddFieldWrappers("xs_text").", ";
	$dal_variables = substr($dal_variables,0,-2);
	
		if ($this->xs_id)
			$this->Param["xs_id"] = $this->xs_id;	
		if ($this->xs_text)
			$this->Param["xs_text"] = $this->xs_text;	
	
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("xs_id") && 1==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(16))
				$dal_where.= AddFieldWrappers("xs_id")."='".db_addslashes($this->Param["xs_id"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("xs_id")."=".db_addslashes($this->Param["xs_id"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("xs_text") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(129))
				$dal_where.= AddFieldWrappers("xs_text")."='".db_addslashes($this->Param["xs_text"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("xs_text")."=".db_addslashes($this->Param["xs_text"]) . " and ";
	
	if ($dal_where)
		$dal_where = " where ".substr($dal_where,0,-5);
	
	$dalSQL = "select ".$dal_variables." from ".$this->m_GoodTableName.$dal_where;
	$rs = db_query($dalSQL,$conn);
	$data = db_fetch_array($rs);
	return $data;
}
	
function Reset()
{
        unset($this->m_xs_id);
		unset($this->Value["xs_id"]);
		unset($this->Param["xs_id"]);
        unset($this->m_xs_text);
		unset($this->Value["xs_text"]);
		unset($this->Param["xs_text"]);
}	
	
}//end of class


$dal->xstatus = new class_xstatus();


class class_Dialog
{
	var $m_TableName;
	var $m_GoodTableName;
	var $m_d_id;
	var $m_d_from_p_id;
	var $m_d_to_p_id;
	var $m_d_message;
	var $m_d_datetime;
	var $m_d_msg_type;

	var $Param = array();
	var $Value = array();
	
	var $d_id = array();
	var $d_from_p_id = array();
	var $d_to_p_id = array();
	var $d_message = array();
	var $d_datetime = array();
	var $d_msg_type = array();
	
	var $m_ChangedFields;
	var $m_UpdateParam;
	var $m_UpdateValue;

	
	function class_Dialog()
	{
		$this->m_TableName = "Dialog";
		$this->m_GoodTableName = AddTableWrappers($this->m_TableName);	
	}

function TableName()
{
	$this->m_TableName = "Dialog";
	$this->m_GoodTableName = AddTableWrappers($this->m_TableName);
	return $this->m_GoodTableName;
} 

function Add() 
{
	global $conn;

	$insertFields="";
	$insertValues="";
		

		if ($this->d_id)
		{
			$this->Value["d_id"] = $this->d_id;
		    $this->m_d_id = $this->d_id;
		}	

		if ($this->d_from_p_id)
		{
			$this->Value["d_from_p_id"] = $this->d_from_p_id;
		    $this->m_d_from_p_id = $this->d_from_p_id;
		}	

		if ($this->d_to_p_id)
		{
			$this->Value["d_to_p_id"] = $this->d_to_p_id;
		    $this->m_d_to_p_id = $this->d_to_p_id;
		}	

		if ($this->d_message)
		{
			$this->Value["d_message"] = $this->d_message;
		    $this->m_d_message = $this->d_message;
		}	

		if ($this->d_datetime)
		{
			$this->Value["d_datetime"] = $this->d_datetime;
		    $this->m_d_datetime = $this->d_datetime;
		}	

		if ($this->d_msg_type)
		{
			$this->Value["d_msg_type"] = $this->d_msg_type;
		    $this->m_d_msg_type = $this->d_msg_type;
		}	
	
		if ($this->Value["d_id"])
		    $this->m_d_id = $this->Value["d_id"];	
		if ($this->Value["d_from_p_id"])
		    $this->m_d_from_p_id = $this->Value["d_from_p_id"];	
		if ($this->Value["d_to_p_id"])
		    $this->m_d_to_p_id = $this->Value["d_to_p_id"];	
		if ($this->Value["d_message"])
		    $this->m_d_message = $this->Value["d_message"];	
		if ($this->Value["d_datetime"])
		    $this->m_d_datetime = $this->Value["d_datetime"];	
		if ($this->Value["d_msg_type"])
		    $this->m_d_msg_type = $this->Value["d_msg_type"];	

		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("d_id"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("d_id").",";
			if (NeedQuotes(3))
				$insertValues.= "'".db_addslashes($this->Value["d_id"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["d_id"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("d_from_p_id"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("d_from_p_id").",";
			if (NeedQuotes(3))
				$insertValues.= "'".db_addslashes($this->Value["d_from_p_id"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["d_from_p_id"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("d_to_p_id"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("d_to_p_id").",";
			if (NeedQuotes(3))
				$insertValues.= "'".db_addslashes($this->Value["d_to_p_id"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["d_to_p_id"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("d_message"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("d_message").",";
			if (NeedQuotes(201))
				$insertValues.= "'".db_addslashes($this->Value["d_message"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["d_message"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("d_datetime"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("d_datetime").",";
			if (NeedQuotes(135))
				$insertValues.= "'".db_addslashes($this->Value["d_datetime"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["d_datetime"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("d_msg_type"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("d_msg_type").",";
			if (NeedQuotes(16))
				$insertValues.= "'".db_addslashes($this->Value["d_msg_type"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["d_msg_type"]) . ",";		

		}

		
	if ($insertFields!="" && $insertValues!="")		
	{
		$insertFields = substr($insertFields,0,-1);
		$insertValues = substr($insertValues,0,-1);
		$dalSQL = "insert into ".$this->m_GoodTableName." (".$insertFields.") values (".$insertValues.")";
		db_exec($dalSQL,$conn);
	}
	
        unset($this->Value["d_id"]);
        unset($this->Value["d_from_p_id"]);
        unset($this->Value["d_to_p_id"]);
        unset($this->Value["d_message"]);
        unset($this->Value["d_datetime"]);
        unset($this->Value["d_msg_type"]);
   // unset($this->m_ChangedFields);

	}
	
function Delete()
{
	global $conn;
	$deleteFields="";

		if ($this->d_id)
		{
			$this->Value["d_id"] = $this->d_id;
			$this->m_d_id = $this->d_id;
		}	
		if ($this->d_from_p_id)
		{
			$this->Value["d_from_p_id"] = $this->d_from_p_id;
			$this->m_d_from_p_id = $this->d_from_p_id;
		}	
		if ($this->d_to_p_id)
		{
			$this->Value["d_to_p_id"] = $this->d_to_p_id;
			$this->m_d_to_p_id = $this->d_to_p_id;
		}	
		if ($this->d_message)
		{
			$this->Value["d_message"] = $this->d_message;
			$this->m_d_message = $this->d_message;
		}	
		if ($this->d_datetime)
		{
			$this->Value["d_datetime"] = $this->d_datetime;
			$this->m_d_datetime = $this->d_datetime;
		}	
		if ($this->d_msg_type)
		{
			$this->Value["d_msg_type"] = $this->d_msg_type;
			$this->m_d_msg_type = $this->d_msg_type;
		}	
	
		if ($this->Value["d_id"])
		    $this->m_d_id = $this->Value["d_id"];	
		if ($this->Value["d_from_p_id"])
		    $this->m_d_from_p_id = $this->Value["d_from_p_id"];	
		if ($this->Value["d_to_p_id"])
		    $this->m_d_to_p_id = $this->Value["d_to_p_id"];	
		if ($this->Value["d_message"])
		    $this->m_d_message = $this->Value["d_message"];	
		if ($this->Value["d_datetime"])
		    $this->m_d_datetime = $this->Value["d_datetime"];	
		if ($this->Value["d_msg_type"])
		    $this->m_d_msg_type = $this->Value["d_msg_type"];	

		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("d_id"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("d_id").",";
			if (NeedQuotes(3))
				$deleteFields.= AddFieldWrappers("d_id")."='".db_addslashes($this->Value["d_id"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("d_id")."=".db_addslashes($this->Value["d_id"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("d_from_p_id"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("d_from_p_id").",";
			if (NeedQuotes(3))
				$deleteFields.= AddFieldWrappers("d_from_p_id")."='".db_addslashes($this->Value["d_from_p_id"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("d_from_p_id")."=".db_addslashes($this->Value["d_from_p_id"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("d_to_p_id"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("d_to_p_id").",";
			if (NeedQuotes(3))
				$deleteFields.= AddFieldWrappers("d_to_p_id")."='".db_addslashes($this->Value["d_to_p_id"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("d_to_p_id")."=".db_addslashes($this->Value["d_to_p_id"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("d_message"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("d_message").",";
			if (NeedQuotes(201))
				$deleteFields.= AddFieldWrappers("d_message")."='".db_addslashes($this->Value["d_message"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("d_message")."=".db_addslashes($this->Value["d_message"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("d_datetime"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("d_datetime").",";
			if (NeedQuotes(135))
				$deleteFields.= AddFieldWrappers("d_datetime")."='".db_addslashes($this->Value["d_datetime"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("d_datetime")."=".db_addslashes($this->Value["d_datetime"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("d_msg_type"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("d_msg_type").",";
			if (NeedQuotes(16))
				$deleteFields.= AddFieldWrappers("d_msg_type")."='".db_addslashes($this->Value["d_msg_type"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("d_msg_type")."=".db_addslashes($this->Value["d_msg_type"]) . " and ";		
		}

	if ($deleteFields)
	{
		$deleteFields = substr($deleteFields,0,-5);
		$dalSQL = "delete from ".$this->m_GoodTableName." where ".$deleteFields;
		db_exec($dalSQL,$conn);
	}
	
	    unset($this->Value["d_id"]);
	    unset($this->Value["d_from_p_id"]);
	    unset($this->Value["d_to_p_id"]);
	    unset($this->Value["d_message"]);
	    unset($this->Value["d_datetime"]);
	    unset($this->Value["d_msg_type"]);
		
	//unset($this->Value);	
}

function Update()
{
	global $conn;
	
	$updateParam = "";
	$updateValue = "";
	
		if ($this->d_id)
		{
			if (1==1)
				$this->Param["d_id"] = $this->d_id;
			else
				$this->Value["d_id"] = $this->d_id;
			$this->m_d_id = $this->d_id;
		}	
		if ($this->d_from_p_id)
		{
			if (0==1)
				$this->Param["d_from_p_id"] = $this->d_from_p_id;
			else
				$this->Value["d_from_p_id"] = $this->d_from_p_id;
			$this->m_d_from_p_id = $this->d_from_p_id;
		}	
		if ($this->d_to_p_id)
		{
			if (0==1)
				$this->Param["d_to_p_id"] = $this->d_to_p_id;
			else
				$this->Value["d_to_p_id"] = $this->d_to_p_id;
			$this->m_d_to_p_id = $this->d_to_p_id;
		}	
		if ($this->d_message)
		{
			if (0==1)
				$this->Param["d_message"] = $this->d_message;
			else
				$this->Value["d_message"] = $this->d_message;
			$this->m_d_message = $this->d_message;
		}	
		if ($this->d_datetime)
		{
			if (0==1)
				$this->Param["d_datetime"] = $this->d_datetime;
			else
				$this->Value["d_datetime"] = $this->d_datetime;
			$this->m_d_datetime = $this->d_datetime;
		}	
		if ($this->d_msg_type)
		{
			if (0==1)
				$this->Param["d_msg_type"] = $this->d_msg_type;
			else
				$this->Value["d_msg_type"] = $this->d_msg_type;
			$this->m_d_msg_type = $this->d_msg_type;
		}	
	
		if ($this->Value["d_id"])
		    $this->m_d_id = $this->Value["d_id"];		
		if ($this->Value["d_from_p_id"])
		    $this->m_d_from_p_id = $this->Value["d_from_p_id"];		
		if ($this->Value["d_to_p_id"])
		    $this->m_d_to_p_id = $this->Value["d_to_p_id"];		
		if ($this->Value["d_message"])
		    $this->m_d_message = $this->Value["d_message"];		
		if ($this->Value["d_datetime"])
		    $this->m_d_datetime = $this->Value["d_datetime"];		
		if ($this->Value["d_msg_type"])
		    $this->m_d_msg_type = $this->Value["d_msg_type"];		
	
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("d_id"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateValue.= AddFieldWrappers("d_id")."='".$this->Value["d_id"]."', ";
			else
					$updateValue.= AddFieldWrappers("d_id")."=".$this->Value["d_id"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("d_id"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateParam.= AddFieldWrappers("d_id")."='".db_addslashes($this->Param["d_id"])."' and ";
			else
					$updateParam.= AddFieldWrappers("d_id")."=".db_addslashes($this->Param["d_id"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("d_from_p_id"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateValue.= AddFieldWrappers("d_from_p_id")."='".$this->Value["d_from_p_id"]."', ";
			else
					$updateValue.= AddFieldWrappers("d_from_p_id")."=".$this->Value["d_from_p_id"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("d_from_p_id"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateParam.= AddFieldWrappers("d_from_p_id")."='".db_addslashes($this->Param["d_from_p_id"])."' and ";
			else
					$updateParam.= AddFieldWrappers("d_from_p_id")."=".db_addslashes($this->Param["d_from_p_id"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("d_to_p_id"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateValue.= AddFieldWrappers("d_to_p_id")."='".$this->Value["d_to_p_id"]."', ";
			else
					$updateValue.= AddFieldWrappers("d_to_p_id")."=".$this->Value["d_to_p_id"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("d_to_p_id"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateParam.= AddFieldWrappers("d_to_p_id")."='".db_addslashes($this->Param["d_to_p_id"])."' and ";
			else
					$updateParam.= AddFieldWrappers("d_to_p_id")."=".db_addslashes($this->Param["d_to_p_id"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("d_message"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(201))
					$updateValue.= AddFieldWrappers("d_message")."='".$this->Value["d_message"]."', ";
			else
					$updateValue.= AddFieldWrappers("d_message")."=".$this->Value["d_message"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("d_message"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(201))
					$updateParam.= AddFieldWrappers("d_message")."='".db_addslashes($this->Param["d_message"])."' and ";
			else
					$updateParam.= AddFieldWrappers("d_message")."=".db_addslashes($this->Param["d_message"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("d_datetime"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(135))
					$updateValue.= AddFieldWrappers("d_datetime")."='".$this->Value["d_datetime"]."', ";
			else
					$updateValue.= AddFieldWrappers("d_datetime")."=".$this->Value["d_datetime"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("d_datetime"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(135))
					$updateParam.= AddFieldWrappers("d_datetime")."='".db_addslashes($this->Param["d_datetime"])."' and ";
			else
					$updateParam.= AddFieldWrappers("d_datetime")."=".db_addslashes($this->Param["d_datetime"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("d_msg_type"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(16))
					$updateValue.= AddFieldWrappers("d_msg_type")."='".$this->Value["d_msg_type"]."', ";
			else
					$updateValue.= AddFieldWrappers("d_msg_type")."=".$this->Value["d_msg_type"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("d_msg_type"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(16))
					$updateParam.= AddFieldWrappers("d_msg_type")."='".db_addslashes($this->Param["d_msg_type"])."' and ";
			else
					$updateParam.= AddFieldWrappers("d_msg_type")."=".db_addslashes($this->Param["d_msg_type"])." and ";
		}
		
	
	if ($updateParam)
		$updateParam = substr($updateParam,0,-5);
	if ($updateValue)
		$updateValue = substr($updateValue,0,-2);
		
	if ($updateValue)
	{
		$dalSQL = "update ".$this->m_GoodTableName." set ".$updateValue." where ".$updateParam;
		db_exec($dalSQL,$conn);
	}
	
        unset($this->Value["d_id"]);
		unset($this->Param["d_id"]);
        unset($this->Value["d_from_p_id"]);
		unset($this->Param["d_from_p_id"]);
        unset($this->Value["d_to_p_id"]);
		unset($this->Param["d_to_p_id"]);
        unset($this->Value["d_message"]);
		unset($this->Param["d_message"]);
        unset($this->Value["d_datetime"]);
		unset($this->Param["d_datetime"]);
        unset($this->Value["d_msg_type"]);
		unset($this->Param["d_msg_type"]);
	
}

function QueryAll()
{
	global $conn;
	$dal_variables="";
		$dal_variables.= AddFieldWrappers("d_id").", ";
		$dal_variables.= AddFieldWrappers("d_from_p_id").", ";
		$dal_variables.= AddFieldWrappers("d_to_p_id").", ";
		$dal_variables.= AddFieldWrappers("d_message").", ";
		$dal_variables.= AddFieldWrappers("d_datetime").", ";
		$dal_variables.= AddFieldWrappers("d_msg_type").", ";
	$dal_variables = substr($dal_variables,0,-2);
	$dalSQL = "select ".$dal_variables." from ".$this->m_GoodTableName;
	$rs = db_query($dalSQL,$conn);
	$data = db_fetch_array($rs);
	return $data;
}

function Query($swhere,$orderby)
{
	global $conn;
	$dal_variables="";
		$dal_variables.= AddFieldWrappers("d_id").", ";
		$dal_variables.= AddFieldWrappers("d_from_p_id").", ";
		$dal_variables.= AddFieldWrappers("d_to_p_id").", ";
		$dal_variables.= AddFieldWrappers("d_message").", ";
		$dal_variables.= AddFieldWrappers("d_datetime").", ";
		$dal_variables.= AddFieldWrappers("d_msg_type").", ";
	$dal_variables = substr($dal_variables,0,-2);
	if ($swhere)
		$swhere = " where ".$swhere;
	if ($orderby)
		$orderby = " order by ".$orderby;
	
	$dalSQL = "select ".$dal_variables." from ".$this->m_GoodTableName.$swhere.$orderby;
	$rs = db_query($dalSQL,$conn);
	$data = db_fetch_array($rs);
	return $data; 	
}

function FetchByID()
{
	global $conn;
	$dal_variables="";
	$dal_where="";
        $dal_variables.= AddFieldWrappers("d_id").", ";
        $dal_variables.= AddFieldWrappers("d_from_p_id").", ";
        $dal_variables.= AddFieldWrappers("d_to_p_id").", ";
        $dal_variables.= AddFieldWrappers("d_message").", ";
        $dal_variables.= AddFieldWrappers("d_datetime").", ";
        $dal_variables.= AddFieldWrappers("d_msg_type").", ";
	$dal_variables = substr($dal_variables,0,-2);
	
		if ($this->d_id)
			$this->Param["d_id"] = $this->d_id;	
		if ($this->d_from_p_id)
			$this->Param["d_from_p_id"] = $this->d_from_p_id;	
		if ($this->d_to_p_id)
			$this->Param["d_to_p_id"] = $this->d_to_p_id;	
		if ($this->d_message)
			$this->Param["d_message"] = $this->d_message;	
		if ($this->d_datetime)
			$this->Param["d_datetime"] = $this->d_datetime;	
		if ($this->d_msg_type)
			$this->Param["d_msg_type"] = $this->d_msg_type;	
	
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("d_id") && 1==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(3))
				$dal_where.= AddFieldWrappers("d_id")."='".db_addslashes($this->Param["d_id"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("d_id")."=".db_addslashes($this->Param["d_id"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("d_from_p_id") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(3))
				$dal_where.= AddFieldWrappers("d_from_p_id")."='".db_addslashes($this->Param["d_from_p_id"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("d_from_p_id")."=".db_addslashes($this->Param["d_from_p_id"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("d_to_p_id") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(3))
				$dal_where.= AddFieldWrappers("d_to_p_id")."='".db_addslashes($this->Param["d_to_p_id"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("d_to_p_id")."=".db_addslashes($this->Param["d_to_p_id"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("d_message") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(201))
				$dal_where.= AddFieldWrappers("d_message")."='".db_addslashes($this->Param["d_message"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("d_message")."=".db_addslashes($this->Param["d_message"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("d_datetime") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(135))
				$dal_where.= AddFieldWrappers("d_datetime")."='".db_addslashes($this->Param["d_datetime"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("d_datetime")."=".db_addslashes($this->Param["d_datetime"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("d_msg_type") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(16))
				$dal_where.= AddFieldWrappers("d_msg_type")."='".db_addslashes($this->Param["d_msg_type"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("d_msg_type")."=".db_addslashes($this->Param["d_msg_type"]) . " and ";
	
	if ($dal_where)
		$dal_where = " where ".substr($dal_where,0,-5);
	
	$dalSQL = "select ".$dal_variables." from ".$this->m_GoodTableName.$dal_where;
	$rs = db_query($dalSQL,$conn);
	$data = db_fetch_array($rs);
	return $data;
}
	
function Reset()
{
        unset($this->m_d_id);
		unset($this->Value["d_id"]);
		unset($this->Param["d_id"]);
        unset($this->m_d_from_p_id);
		unset($this->Value["d_from_p_id"]);
		unset($this->Param["d_from_p_id"]);
        unset($this->m_d_to_p_id);
		unset($this->Value["d_to_p_id"]);
		unset($this->Param["d_to_p_id"]);
        unset($this->m_d_message);
		unset($this->Value["d_message"]);
		unset($this->Param["d_message"]);
        unset($this->m_d_datetime);
		unset($this->Value["d_datetime"]);
		unset($this->Param["d_datetime"]);
        unset($this->m_d_msg_type);
		unset($this->Value["d_msg_type"]);
		unset($this->Param["d_msg_type"]);
}	
	
}//end of class


$dal->Dialog = new class_Dialog();


class class_mails
{
	var $m_TableName;
	var $m_GoodTableName;
	var $m_m_id;
	var $m_m_name;
	var $m_m_body;

	var $Param = array();
	var $Value = array();
	
	var $fldm_id = array();
	var $fldm_name = array();
	var $fldm_body = array();
	
	var $m_ChangedFields;
	var $m_UpdateParam;
	var $m_UpdateValue;

	
	function class_mails()
	{
		$this->m_TableName = "mails";
		$this->m_GoodTableName = AddTableWrappers($this->m_TableName);	
	}

function TableName()
{
	$this->m_TableName = "mails";
	$this->m_GoodTableName = AddTableWrappers($this->m_TableName);
	return $this->m_GoodTableName;
} 

function Add() 
{
	global $conn;

	$insertFields="";
	$insertValues="";
		

		if ($this->fldm_id)
		{
			$this->Value["m_id"] = $this->fldm_id;
		    $this->m_m_id = $this->fldm_id;
		}	

		if ($this->fldm_name)
		{
			$this->Value["m_name"] = $this->fldm_name;
		    $this->m_m_name = $this->fldm_name;
		}	

		if ($this->fldm_body)
		{
			$this->Value["m_body"] = $this->fldm_body;
		    $this->m_m_body = $this->fldm_body;
		}	
	
		if ($this->Value["m_id"])
		    $this->m_m_id = $this->Value["m_id"];	
		if ($this->Value["m_name"])
		    $this->m_m_name = $this->Value["m_name"];	
		if ($this->Value["m_body"])
		    $this->m_m_body = $this->Value["m_body"];	

		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("m_id"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("m_id").",";
			if (NeedQuotes(3))
				$insertValues.= "'".db_addslashes($this->Value["m_id"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["m_id"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("m_name"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("m_name").",";
			if (NeedQuotes(200))
				$insertValues.= "'".db_addslashes($this->Value["m_name"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["m_name"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("m_body"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("m_body").",";
			if (NeedQuotes(201))
				$insertValues.= "'".db_addslashes($this->Value["m_body"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["m_body"]) . ",";		

		}

		
	if ($insertFields!="" && $insertValues!="")		
	{
		$insertFields = substr($insertFields,0,-1);
		$insertValues = substr($insertValues,0,-1);
		$dalSQL = "insert into ".$this->m_GoodTableName." (".$insertFields.") values (".$insertValues.")";
		db_exec($dalSQL,$conn);
	}
	
        unset($this->Value["m_id"]);
        unset($this->Value["m_name"]);
        unset($this->Value["m_body"]);
   // unset($this->m_ChangedFields);

	}
	
function Delete()
{
	global $conn;
	$deleteFields="";

		if ($this->fldm_id)
		{
			$this->Value["m_id"] = $this->fldm_id;
			$this->m_m_id = $this->fldm_id;
		}	
		if ($this->fldm_name)
		{
			$this->Value["m_name"] = $this->fldm_name;
			$this->m_m_name = $this->fldm_name;
		}	
		if ($this->fldm_body)
		{
			$this->Value["m_body"] = $this->fldm_body;
			$this->m_m_body = $this->fldm_body;
		}	
	
		if ($this->Value["m_id"])
		    $this->m_m_id = $this->Value["m_id"];	
		if ($this->Value["m_name"])
		    $this->m_m_name = $this->Value["m_name"];	
		if ($this->Value["m_body"])
		    $this->m_m_body = $this->Value["m_body"];	

		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("m_id"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("m_id").",";
			if (NeedQuotes(3))
				$deleteFields.= AddFieldWrappers("m_id")."='".db_addslashes($this->Value["m_id"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("m_id")."=".db_addslashes($this->Value["m_id"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("m_name"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("m_name").",";
			if (NeedQuotes(200))
				$deleteFields.= AddFieldWrappers("m_name")."='".db_addslashes($this->Value["m_name"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("m_name")."=".db_addslashes($this->Value["m_name"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("m_body"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("m_body").",";
			if (NeedQuotes(201))
				$deleteFields.= AddFieldWrappers("m_body")."='".db_addslashes($this->Value["m_body"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("m_body")."=".db_addslashes($this->Value["m_body"]) . " and ";		
		}

	if ($deleteFields)
	{
		$deleteFields = substr($deleteFields,0,-5);
		$dalSQL = "delete from ".$this->m_GoodTableName." where ".$deleteFields;
		db_exec($dalSQL,$conn);
	}
	
	    unset($this->Value["m_id"]);
	    unset($this->Value["m_name"]);
	    unset($this->Value["m_body"]);
		
	//unset($this->Value);	
}

function Update()
{
	global $conn;
	
	$updateParam = "";
	$updateValue = "";
	
		if ($this->fldm_id)
		{
			if (1==1)
				$this->Param["m_id"] = $this->fldm_id;
			else
				$this->Value["m_id"] = $this->fldm_id;
			$this->m_m_id = $this->fldm_id;
		}	
		if ($this->fldm_name)
		{
			if (0==1)
				$this->Param["m_name"] = $this->fldm_name;
			else
				$this->Value["m_name"] = $this->fldm_name;
			$this->m_m_name = $this->fldm_name;
		}	
		if ($this->fldm_body)
		{
			if (0==1)
				$this->Param["m_body"] = $this->fldm_body;
			else
				$this->Value["m_body"] = $this->fldm_body;
			$this->m_m_body = $this->fldm_body;
		}	
	
		if ($this->Value["m_id"])
		    $this->m_m_id = $this->Value["m_id"];		
		if ($this->Value["m_name"])
		    $this->m_m_name = $this->Value["m_name"];		
		if ($this->Value["m_body"])
		    $this->m_m_body = $this->Value["m_body"];		
	
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("m_id"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateValue.= AddFieldWrappers("m_id")."='".$this->Value["m_id"]."', ";
			else
					$updateValue.= AddFieldWrappers("m_id")."=".$this->Value["m_id"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("m_id"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateParam.= AddFieldWrappers("m_id")."='".db_addslashes($this->Param["m_id"])."' and ";
			else
					$updateParam.= AddFieldWrappers("m_id")."=".db_addslashes($this->Param["m_id"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("m_name"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateValue.= AddFieldWrappers("m_name")."='".$this->Value["m_name"]."', ";
			else
					$updateValue.= AddFieldWrappers("m_name")."=".$this->Value["m_name"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("m_name"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateParam.= AddFieldWrappers("m_name")."='".db_addslashes($this->Param["m_name"])."' and ";
			else
					$updateParam.= AddFieldWrappers("m_name")."=".db_addslashes($this->Param["m_name"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("m_body"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(201))
					$updateValue.= AddFieldWrappers("m_body")."='".$this->Value["m_body"]."', ";
			else
					$updateValue.= AddFieldWrappers("m_body")."=".$this->Value["m_body"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("m_body"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(201))
					$updateParam.= AddFieldWrappers("m_body")."='".db_addslashes($this->Param["m_body"])."' and ";
			else
					$updateParam.= AddFieldWrappers("m_body")."=".db_addslashes($this->Param["m_body"])." and ";
		}
		
	
	if ($updateParam)
		$updateParam = substr($updateParam,0,-5);
	if ($updateValue)
		$updateValue = substr($updateValue,0,-2);
		
	if ($updateValue)
	{
		$dalSQL = "update ".$this->m_GoodTableName." set ".$updateValue." where ".$updateParam;
		db_exec($dalSQL,$conn);
	}
	
        unset($this->Value["m_id"]);
		unset($this->Param["m_id"]);
        unset($this->Value["m_name"]);
		unset($this->Param["m_name"]);
        unset($this->Value["m_body"]);
		unset($this->Param["m_body"]);
	
}

function QueryAll()
{
	global $conn;
	$dal_variables="";
		$dal_variables.= AddFieldWrappers("m_id").", ";
		$dal_variables.= AddFieldWrappers("m_name").", ";
		$dal_variables.= AddFieldWrappers("m_body").", ";
	$dal_variables = substr($dal_variables,0,-2);
	$dalSQL = "select ".$dal_variables." from ".$this->m_GoodTableName;
	$rs = db_query($dalSQL,$conn);
	$data = db_fetch_array($rs);
	return $data;
}

function Query($swhere,$orderby)
{
	global $conn;
	$dal_variables="";
		$dal_variables.= AddFieldWrappers("m_id").", ";
		$dal_variables.= AddFieldWrappers("m_name").", ";
		$dal_variables.= AddFieldWrappers("m_body").", ";
	$dal_variables = substr($dal_variables,0,-2);
	if ($swhere)
		$swhere = " where ".$swhere;
	if ($orderby)
		$orderby = " order by ".$orderby;
	
	$dalSQL = "select ".$dal_variables." from ".$this->m_GoodTableName.$swhere.$orderby;
	$rs = db_query($dalSQL,$conn);
	$data = db_fetch_array($rs);
	return $data; 	
}

function FetchByID()
{
	global $conn;
	$dal_variables="";
	$dal_where="";
        $dal_variables.= AddFieldWrappers("m_id").", ";
        $dal_variables.= AddFieldWrappers("m_name").", ";
        $dal_variables.= AddFieldWrappers("m_body").", ";
	$dal_variables = substr($dal_variables,0,-2);
	
		if ($this->fldm_id)
			$this->Param["m_id"] = $this->fldm_id;	
		if ($this->fldm_name)
			$this->Param["m_name"] = $this->fldm_name;	
		if ($this->fldm_body)
			$this->Param["m_body"] = $this->fldm_body;	
	
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("m_id") && 1==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(3))
				$dal_where.= AddFieldWrappers("m_id")."='".db_addslashes($this->Param["m_id"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("m_id")."=".db_addslashes($this->Param["m_id"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("m_name") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(200))
				$dal_where.= AddFieldWrappers("m_name")."='".db_addslashes($this->Param["m_name"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("m_name")."=".db_addslashes($this->Param["m_name"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("m_body") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(201))
				$dal_where.= AddFieldWrappers("m_body")."='".db_addslashes($this->Param["m_body"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("m_body")."=".db_addslashes($this->Param["m_body"]) . " and ";
	
	if ($dal_where)
		$dal_where = " where ".substr($dal_where,0,-5);
	
	$dalSQL = "select ".$dal_variables." from ".$this->m_GoodTableName.$dal_where;
	$rs = db_query($dalSQL,$conn);
	$data = db_fetch_array($rs);
	return $data;
}
	
function Reset()
{
        unset($this->m_m_id);
		unset($this->Value["m_id"]);
		unset($this->Param["m_id"]);
        unset($this->m_m_name);
		unset($this->Value["m_name"]);
		unset($this->Param["m_name"]);
        unset($this->m_m_body);
		unset($this->Value["m_body"]);
		unset($this->Param["m_body"]);
}	
	
}//end of class


$dal->mails = new class_mails();


class class_news
{
	var $m_TableName;
	var $m_GoodTableName;
	var $m_n_id;
	var $m_n_active;
	var $m_n_start;
	var $m_n_end;
	var $m_n_date;
	var $m_n_head;
	var $m_n_teaser;
	var $m_n_text;
	var $m_n_link;
	var $m_n_file;
	var $m_n_type;
	var $m_n_country;

	var $Param = array();
	var $Value = array();
	
	var $n_id = array();
	var $n_active = array();
	var $n_start = array();
	var $n_end = array();
	var $n_date = array();
	var $n_head = array();
	var $n_teaser = array();
	var $n_text = array();
	var $n_link = array();
	var $n_file = array();
	var $n_type = array();
	var $n_country = array();
	
	var $m_ChangedFields;
	var $m_UpdateParam;
	var $m_UpdateValue;

	
	function class_news()
	{
		$this->m_TableName = "news";
		$this->m_GoodTableName = AddTableWrappers($this->m_TableName);	
	}

function TableName()
{
	$this->m_TableName = "news";
	$this->m_GoodTableName = AddTableWrappers($this->m_TableName);
	return $this->m_GoodTableName;
} 

function Add() 
{
	global $conn;

	$insertFields="";
	$insertValues="";
		

		if ($this->n_id)
		{
			$this->Value["n_id"] = $this->n_id;
		    $this->m_n_id = $this->n_id;
		}	

		if ($this->n_active)
		{
			$this->Value["n_active"] = $this->n_active;
		    $this->m_n_active = $this->n_active;
		}	

		if ($this->n_start)
		{
			$this->Value["n_start"] = $this->n_start;
		    $this->m_n_start = $this->n_start;
		}	

		if ($this->n_end)
		{
			$this->Value["n_end"] = $this->n_end;
		    $this->m_n_end = $this->n_end;
		}	

		if ($this->n_date)
		{
			$this->Value["n_date"] = $this->n_date;
		    $this->m_n_date = $this->n_date;
		}	

		if ($this->n_head)
		{
			$this->Value["n_head"] = $this->n_head;
		    $this->m_n_head = $this->n_head;
		}	

		if ($this->n_teaser)
		{
			$this->Value["n_teaser"] = $this->n_teaser;
		    $this->m_n_teaser = $this->n_teaser;
		}	

		if ($this->n_text)
		{
			$this->Value["n_text"] = $this->n_text;
		    $this->m_n_text = $this->n_text;
		}	

		if ($this->n_link)
		{
			$this->Value["n_link"] = $this->n_link;
		    $this->m_n_link = $this->n_link;
		}	

		if ($this->n_file)
		{
			$this->Value["n_file"] = $this->n_file;
		    $this->m_n_file = $this->n_file;
		}	

		if ($this->n_type)
		{
			$this->Value["n_type"] = $this->n_type;
		    $this->m_n_type = $this->n_type;
		}	

		if ($this->n_country)
		{
			$this->Value["n_country"] = $this->n_country;
		    $this->m_n_country = $this->n_country;
		}	
	
		if ($this->Value["n_id"])
		    $this->m_n_id = $this->Value["n_id"];	
		if ($this->Value["n_active"])
		    $this->m_n_active = $this->Value["n_active"];	
		if ($this->Value["n_start"])
		    $this->m_n_start = $this->Value["n_start"];	
		if ($this->Value["n_end"])
		    $this->m_n_end = $this->Value["n_end"];	
		if ($this->Value["n_date"])
		    $this->m_n_date = $this->Value["n_date"];	
		if ($this->Value["n_head"])
		    $this->m_n_head = $this->Value["n_head"];	
		if ($this->Value["n_teaser"])
		    $this->m_n_teaser = $this->Value["n_teaser"];	
		if ($this->Value["n_text"])
		    $this->m_n_text = $this->Value["n_text"];	
		if ($this->Value["n_link"])
		    $this->m_n_link = $this->Value["n_link"];	
		if ($this->Value["n_file"])
		    $this->m_n_file = $this->Value["n_file"];	
		if ($this->Value["n_type"])
		    $this->m_n_type = $this->Value["n_type"];	
		if ($this->Value["n_country"])
		    $this->m_n_country = $this->Value["n_country"];	

		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("n_id"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("n_id").",";
			if (NeedQuotes(3))
				$insertValues.= "'".db_addslashes($this->Value["n_id"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["n_id"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("n_active"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("n_active").",";
			if (NeedQuotes(16))
				$insertValues.= "'".db_addslashes($this->Value["n_active"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["n_active"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("n_start"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("n_start").",";
			if (NeedQuotes(7))
				$insertValues.= "'".db_addslashes($this->Value["n_start"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["n_start"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("n_end"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("n_end").",";
			if (NeedQuotes(7))
				$insertValues.= "'".db_addslashes($this->Value["n_end"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["n_end"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("n_date"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("n_date").",";
			if (NeedQuotes(7))
				$insertValues.= "'".db_addslashes($this->Value["n_date"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["n_date"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("n_head"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("n_head").",";
			if (NeedQuotes(200))
				$insertValues.= "'".db_addslashes($this->Value["n_head"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["n_head"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("n_teaser"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("n_teaser").",";
			if (NeedQuotes(201))
				$insertValues.= "'".db_addslashes($this->Value["n_teaser"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["n_teaser"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("n_text"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("n_text").",";
			if (NeedQuotes(201))
				$insertValues.= "'".db_addslashes($this->Value["n_text"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["n_text"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("n_link"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("n_link").",";
			if (NeedQuotes(200))
				$insertValues.= "'".db_addslashes($this->Value["n_link"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["n_link"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("n_file"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("n_file").",";
			if (NeedQuotes(200))
				$insertValues.= "'".db_addslashes($this->Value["n_file"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["n_file"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("n_type"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("n_type").",";
			if (NeedQuotes(3))
				$insertValues.= "'".db_addslashes($this->Value["n_type"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["n_type"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("n_country"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("n_country").",";
			if (NeedQuotes(129))
				$insertValues.= "'".db_addslashes($this->Value["n_country"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["n_country"]) . ",";		

		}

		
	if ($insertFields!="" && $insertValues!="")		
	{
		$insertFields = substr($insertFields,0,-1);
		$insertValues = substr($insertValues,0,-1);
		$dalSQL = "insert into ".$this->m_GoodTableName." (".$insertFields.") values (".$insertValues.")";
		db_exec($dalSQL,$conn);
	}
	
        unset($this->Value["n_id"]);
        unset($this->Value["n_active"]);
        unset($this->Value["n_start"]);
        unset($this->Value["n_end"]);
        unset($this->Value["n_date"]);
        unset($this->Value["n_head"]);
        unset($this->Value["n_teaser"]);
        unset($this->Value["n_text"]);
        unset($this->Value["n_link"]);
        unset($this->Value["n_file"]);
        unset($this->Value["n_type"]);
        unset($this->Value["n_country"]);
   // unset($this->m_ChangedFields);

	}
	
function Delete()
{
	global $conn;
	$deleteFields="";

		if ($this->n_id)
		{
			$this->Value["n_id"] = $this->n_id;
			$this->m_n_id = $this->n_id;
		}	
		if ($this->n_active)
		{
			$this->Value["n_active"] = $this->n_active;
			$this->m_n_active = $this->n_active;
		}	
		if ($this->n_start)
		{
			$this->Value["n_start"] = $this->n_start;
			$this->m_n_start = $this->n_start;
		}	
		if ($this->n_end)
		{
			$this->Value["n_end"] = $this->n_end;
			$this->m_n_end = $this->n_end;
		}	
		if ($this->n_date)
		{
			$this->Value["n_date"] = $this->n_date;
			$this->m_n_date = $this->n_date;
		}	
		if ($this->n_head)
		{
			$this->Value["n_head"] = $this->n_head;
			$this->m_n_head = $this->n_head;
		}	
		if ($this->n_teaser)
		{
			$this->Value["n_teaser"] = $this->n_teaser;
			$this->m_n_teaser = $this->n_teaser;
		}	
		if ($this->n_text)
		{
			$this->Value["n_text"] = $this->n_text;
			$this->m_n_text = $this->n_text;
		}	
		if ($this->n_link)
		{
			$this->Value["n_link"] = $this->n_link;
			$this->m_n_link = $this->n_link;
		}	
		if ($this->n_file)
		{
			$this->Value["n_file"] = $this->n_file;
			$this->m_n_file = $this->n_file;
		}	
		if ($this->n_type)
		{
			$this->Value["n_type"] = $this->n_type;
			$this->m_n_type = $this->n_type;
		}	
		if ($this->n_country)
		{
			$this->Value["n_country"] = $this->n_country;
			$this->m_n_country = $this->n_country;
		}	
	
		if ($this->Value["n_id"])
		    $this->m_n_id = $this->Value["n_id"];	
		if ($this->Value["n_active"])
		    $this->m_n_active = $this->Value["n_active"];	
		if ($this->Value["n_start"])
		    $this->m_n_start = $this->Value["n_start"];	
		if ($this->Value["n_end"])
		    $this->m_n_end = $this->Value["n_end"];	
		if ($this->Value["n_date"])
		    $this->m_n_date = $this->Value["n_date"];	
		if ($this->Value["n_head"])
		    $this->m_n_head = $this->Value["n_head"];	
		if ($this->Value["n_teaser"])
		    $this->m_n_teaser = $this->Value["n_teaser"];	
		if ($this->Value["n_text"])
		    $this->m_n_text = $this->Value["n_text"];	
		if ($this->Value["n_link"])
		    $this->m_n_link = $this->Value["n_link"];	
		if ($this->Value["n_file"])
		    $this->m_n_file = $this->Value["n_file"];	
		if ($this->Value["n_type"])
		    $this->m_n_type = $this->Value["n_type"];	
		if ($this->Value["n_country"])
		    $this->m_n_country = $this->Value["n_country"];	

		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("n_id"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("n_id").",";
			if (NeedQuotes(3))
				$deleteFields.= AddFieldWrappers("n_id")."='".db_addslashes($this->Value["n_id"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("n_id")."=".db_addslashes($this->Value["n_id"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("n_active"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("n_active").",";
			if (NeedQuotes(16))
				$deleteFields.= AddFieldWrappers("n_active")."='".db_addslashes($this->Value["n_active"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("n_active")."=".db_addslashes($this->Value["n_active"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("n_start"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("n_start").",";
			if (NeedQuotes(7))
				$deleteFields.= AddFieldWrappers("n_start")."='".db_addslashes($this->Value["n_start"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("n_start")."=".db_addslashes($this->Value["n_start"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("n_end"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("n_end").",";
			if (NeedQuotes(7))
				$deleteFields.= AddFieldWrappers("n_end")."='".db_addslashes($this->Value["n_end"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("n_end")."=".db_addslashes($this->Value["n_end"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("n_date"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("n_date").",";
			if (NeedQuotes(7))
				$deleteFields.= AddFieldWrappers("n_date")."='".db_addslashes($this->Value["n_date"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("n_date")."=".db_addslashes($this->Value["n_date"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("n_head"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("n_head").",";
			if (NeedQuotes(200))
				$deleteFields.= AddFieldWrappers("n_head")."='".db_addslashes($this->Value["n_head"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("n_head")."=".db_addslashes($this->Value["n_head"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("n_teaser"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("n_teaser").",";
			if (NeedQuotes(201))
				$deleteFields.= AddFieldWrappers("n_teaser")."='".db_addslashes($this->Value["n_teaser"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("n_teaser")."=".db_addslashes($this->Value["n_teaser"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("n_text"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("n_text").",";
			if (NeedQuotes(201))
				$deleteFields.= AddFieldWrappers("n_text")."='".db_addslashes($this->Value["n_text"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("n_text")."=".db_addslashes($this->Value["n_text"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("n_link"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("n_link").",";
			if (NeedQuotes(200))
				$deleteFields.= AddFieldWrappers("n_link")."='".db_addslashes($this->Value["n_link"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("n_link")."=".db_addslashes($this->Value["n_link"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("n_file"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("n_file").",";
			if (NeedQuotes(200))
				$deleteFields.= AddFieldWrappers("n_file")."='".db_addslashes($this->Value["n_file"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("n_file")."=".db_addslashes($this->Value["n_file"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("n_type"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("n_type").",";
			if (NeedQuotes(3))
				$deleteFields.= AddFieldWrappers("n_type")."='".db_addslashes($this->Value["n_type"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("n_type")."=".db_addslashes($this->Value["n_type"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("n_country"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("n_country").",";
			if (NeedQuotes(129))
				$deleteFields.= AddFieldWrappers("n_country")."='".db_addslashes($this->Value["n_country"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("n_country")."=".db_addslashes($this->Value["n_country"]) . " and ";		
		}

	if ($deleteFields)
	{
		$deleteFields = substr($deleteFields,0,-5);
		$dalSQL = "delete from ".$this->m_GoodTableName." where ".$deleteFields;
		db_exec($dalSQL,$conn);
	}
	
	    unset($this->Value["n_id"]);
	    unset($this->Value["n_active"]);
	    unset($this->Value["n_start"]);
	    unset($this->Value["n_end"]);
	    unset($this->Value["n_date"]);
	    unset($this->Value["n_head"]);
	    unset($this->Value["n_teaser"]);
	    unset($this->Value["n_text"]);
	    unset($this->Value["n_link"]);
	    unset($this->Value["n_file"]);
	    unset($this->Value["n_type"]);
	    unset($this->Value["n_country"]);
		
	//unset($this->Value);	
}

function Update()
{
	global $conn;
	
	$updateParam = "";
	$updateValue = "";
	
		if ($this->n_id)
		{
			if (1==1)
				$this->Param["n_id"] = $this->n_id;
			else
				$this->Value["n_id"] = $this->n_id;
			$this->m_n_id = $this->n_id;
		}	
		if ($this->n_active)
		{
			if (0==1)
				$this->Param["n_active"] = $this->n_active;
			else
				$this->Value["n_active"] = $this->n_active;
			$this->m_n_active = $this->n_active;
		}	
		if ($this->n_start)
		{
			if (0==1)
				$this->Param["n_start"] = $this->n_start;
			else
				$this->Value["n_start"] = $this->n_start;
			$this->m_n_start = $this->n_start;
		}	
		if ($this->n_end)
		{
			if (0==1)
				$this->Param["n_end"] = $this->n_end;
			else
				$this->Value["n_end"] = $this->n_end;
			$this->m_n_end = $this->n_end;
		}	
		if ($this->n_date)
		{
			if (0==1)
				$this->Param["n_date"] = $this->n_date;
			else
				$this->Value["n_date"] = $this->n_date;
			$this->m_n_date = $this->n_date;
		}	
		if ($this->n_head)
		{
			if (0==1)
				$this->Param["n_head"] = $this->n_head;
			else
				$this->Value["n_head"] = $this->n_head;
			$this->m_n_head = $this->n_head;
		}	
		if ($this->n_teaser)
		{
			if (0==1)
				$this->Param["n_teaser"] = $this->n_teaser;
			else
				$this->Value["n_teaser"] = $this->n_teaser;
			$this->m_n_teaser = $this->n_teaser;
		}	
		if ($this->n_text)
		{
			if (0==1)
				$this->Param["n_text"] = $this->n_text;
			else
				$this->Value["n_text"] = $this->n_text;
			$this->m_n_text = $this->n_text;
		}	
		if ($this->n_link)
		{
			if (0==1)
				$this->Param["n_link"] = $this->n_link;
			else
				$this->Value["n_link"] = $this->n_link;
			$this->m_n_link = $this->n_link;
		}	
		if ($this->n_file)
		{
			if (0==1)
				$this->Param["n_file"] = $this->n_file;
			else
				$this->Value["n_file"] = $this->n_file;
			$this->m_n_file = $this->n_file;
		}	
		if ($this->n_type)
		{
			if (0==1)
				$this->Param["n_type"] = $this->n_type;
			else
				$this->Value["n_type"] = $this->n_type;
			$this->m_n_type = $this->n_type;
		}	
		if ($this->n_country)
		{
			if (0==1)
				$this->Param["n_country"] = $this->n_country;
			else
				$this->Value["n_country"] = $this->n_country;
			$this->m_n_country = $this->n_country;
		}	
	
		if ($this->Value["n_id"])
		    $this->m_n_id = $this->Value["n_id"];		
		if ($this->Value["n_active"])
		    $this->m_n_active = $this->Value["n_active"];		
		if ($this->Value["n_start"])
		    $this->m_n_start = $this->Value["n_start"];		
		if ($this->Value["n_end"])
		    $this->m_n_end = $this->Value["n_end"];		
		if ($this->Value["n_date"])
		    $this->m_n_date = $this->Value["n_date"];		
		if ($this->Value["n_head"])
		    $this->m_n_head = $this->Value["n_head"];		
		if ($this->Value["n_teaser"])
		    $this->m_n_teaser = $this->Value["n_teaser"];		
		if ($this->Value["n_text"])
		    $this->m_n_text = $this->Value["n_text"];		
		if ($this->Value["n_link"])
		    $this->m_n_link = $this->Value["n_link"];		
		if ($this->Value["n_file"])
		    $this->m_n_file = $this->Value["n_file"];		
		if ($this->Value["n_type"])
		    $this->m_n_type = $this->Value["n_type"];		
		if ($this->Value["n_country"])
		    $this->m_n_country = $this->Value["n_country"];		
	
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("n_id"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateValue.= AddFieldWrappers("n_id")."='".$this->Value["n_id"]."', ";
			else
					$updateValue.= AddFieldWrappers("n_id")."=".$this->Value["n_id"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("n_id"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateParam.= AddFieldWrappers("n_id")."='".db_addslashes($this->Param["n_id"])."' and ";
			else
					$updateParam.= AddFieldWrappers("n_id")."=".db_addslashes($this->Param["n_id"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("n_active"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(16))
					$updateValue.= AddFieldWrappers("n_active")."='".$this->Value["n_active"]."', ";
			else
					$updateValue.= AddFieldWrappers("n_active")."=".$this->Value["n_active"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("n_active"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(16))
					$updateParam.= AddFieldWrappers("n_active")."='".db_addslashes($this->Param["n_active"])."' and ";
			else
					$updateParam.= AddFieldWrappers("n_active")."=".db_addslashes($this->Param["n_active"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("n_start"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(7))
					$updateValue.= AddFieldWrappers("n_start")."='".$this->Value["n_start"]."', ";
			else
					$updateValue.= AddFieldWrappers("n_start")."=".$this->Value["n_start"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("n_start"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(7))
					$updateParam.= AddFieldWrappers("n_start")."='".db_addslashes($this->Param["n_start"])."' and ";
			else
					$updateParam.= AddFieldWrappers("n_start")."=".db_addslashes($this->Param["n_start"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("n_end"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(7))
					$updateValue.= AddFieldWrappers("n_end")."='".$this->Value["n_end"]."', ";
			else
					$updateValue.= AddFieldWrappers("n_end")."=".$this->Value["n_end"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("n_end"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(7))
					$updateParam.= AddFieldWrappers("n_end")."='".db_addslashes($this->Param["n_end"])."' and ";
			else
					$updateParam.= AddFieldWrappers("n_end")."=".db_addslashes($this->Param["n_end"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("n_date"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(7))
					$updateValue.= AddFieldWrappers("n_date")."='".$this->Value["n_date"]."', ";
			else
					$updateValue.= AddFieldWrappers("n_date")."=".$this->Value["n_date"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("n_date"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(7))
					$updateParam.= AddFieldWrappers("n_date")."='".db_addslashes($this->Param["n_date"])."' and ";
			else
					$updateParam.= AddFieldWrappers("n_date")."=".db_addslashes($this->Param["n_date"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("n_head"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateValue.= AddFieldWrappers("n_head")."='".$this->Value["n_head"]."', ";
			else
					$updateValue.= AddFieldWrappers("n_head")."=".$this->Value["n_head"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("n_head"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateParam.= AddFieldWrappers("n_head")."='".db_addslashes($this->Param["n_head"])."' and ";
			else
					$updateParam.= AddFieldWrappers("n_head")."=".db_addslashes($this->Param["n_head"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("n_teaser"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(201))
					$updateValue.= AddFieldWrappers("n_teaser")."='".$this->Value["n_teaser"]."', ";
			else
					$updateValue.= AddFieldWrappers("n_teaser")."=".$this->Value["n_teaser"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("n_teaser"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(201))
					$updateParam.= AddFieldWrappers("n_teaser")."='".db_addslashes($this->Param["n_teaser"])."' and ";
			else
					$updateParam.= AddFieldWrappers("n_teaser")."=".db_addslashes($this->Param["n_teaser"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("n_text"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(201))
					$updateValue.= AddFieldWrappers("n_text")."='".$this->Value["n_text"]."', ";
			else
					$updateValue.= AddFieldWrappers("n_text")."=".$this->Value["n_text"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("n_text"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(201))
					$updateParam.= AddFieldWrappers("n_text")."='".db_addslashes($this->Param["n_text"])."' and ";
			else
					$updateParam.= AddFieldWrappers("n_text")."=".db_addslashes($this->Param["n_text"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("n_link"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateValue.= AddFieldWrappers("n_link")."='".$this->Value["n_link"]."', ";
			else
					$updateValue.= AddFieldWrappers("n_link")."=".$this->Value["n_link"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("n_link"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateParam.= AddFieldWrappers("n_link")."='".db_addslashes($this->Param["n_link"])."' and ";
			else
					$updateParam.= AddFieldWrappers("n_link")."=".db_addslashes($this->Param["n_link"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("n_file"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateValue.= AddFieldWrappers("n_file")."='".$this->Value["n_file"]."', ";
			else
					$updateValue.= AddFieldWrappers("n_file")."=".$this->Value["n_file"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("n_file"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateParam.= AddFieldWrappers("n_file")."='".db_addslashes($this->Param["n_file"])."' and ";
			else
					$updateParam.= AddFieldWrappers("n_file")."=".db_addslashes($this->Param["n_file"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("n_type"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateValue.= AddFieldWrappers("n_type")."='".$this->Value["n_type"]."', ";
			else
					$updateValue.= AddFieldWrappers("n_type")."=".$this->Value["n_type"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("n_type"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateParam.= AddFieldWrappers("n_type")."='".db_addslashes($this->Param["n_type"])."' and ";
			else
					$updateParam.= AddFieldWrappers("n_type")."=".db_addslashes($this->Param["n_type"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("n_country"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(129))
					$updateValue.= AddFieldWrappers("n_country")."='".$this->Value["n_country"]."', ";
			else
					$updateValue.= AddFieldWrappers("n_country")."=".$this->Value["n_country"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("n_country"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(129))
					$updateParam.= AddFieldWrappers("n_country")."='".db_addslashes($this->Param["n_country"])."' and ";
			else
					$updateParam.= AddFieldWrappers("n_country")."=".db_addslashes($this->Param["n_country"])." and ";
		}
		
	
	if ($updateParam)
		$updateParam = substr($updateParam,0,-5);
	if ($updateValue)
		$updateValue = substr($updateValue,0,-2);
		
	if ($updateValue)
	{
		$dalSQL = "update ".$this->m_GoodTableName." set ".$updateValue." where ".$updateParam;
		db_exec($dalSQL,$conn);
	}
	
        unset($this->Value["n_id"]);
		unset($this->Param["n_id"]);
        unset($this->Value["n_active"]);
		unset($this->Param["n_active"]);
        unset($this->Value["n_start"]);
		unset($this->Param["n_start"]);
        unset($this->Value["n_end"]);
		unset($this->Param["n_end"]);
        unset($this->Value["n_date"]);
		unset($this->Param["n_date"]);
        unset($this->Value["n_head"]);
		unset($this->Param["n_head"]);
        unset($this->Value["n_teaser"]);
		unset($this->Param["n_teaser"]);
        unset($this->Value["n_text"]);
		unset($this->Param["n_text"]);
        unset($this->Value["n_link"]);
		unset($this->Param["n_link"]);
        unset($this->Value["n_file"]);
		unset($this->Param["n_file"]);
        unset($this->Value["n_type"]);
		unset($this->Param["n_type"]);
        unset($this->Value["n_country"]);
		unset($this->Param["n_country"]);
	
}

function QueryAll()
{
	global $conn;
	$dal_variables="";
		$dal_variables.= AddFieldWrappers("n_id").", ";
		$dal_variables.= AddFieldWrappers("n_active").", ";
		$dal_variables.= AddFieldWrappers("n_start").", ";
		$dal_variables.= AddFieldWrappers("n_end").", ";
		$dal_variables.= AddFieldWrappers("n_date").", ";
		$dal_variables.= AddFieldWrappers("n_head").", ";
		$dal_variables.= AddFieldWrappers("n_teaser").", ";
		$dal_variables.= AddFieldWrappers("n_text").", ";
		$dal_variables.= AddFieldWrappers("n_link").", ";
		$dal_variables.= AddFieldWrappers("n_file").", ";
		$dal_variables.= AddFieldWrappers("n_type").", ";
		$dal_variables.= AddFieldWrappers("n_country").", ";
	$dal_variables = substr($dal_variables,0,-2);
	$dalSQL = "select ".$dal_variables." from ".$this->m_GoodTableName;
	$rs = db_query($dalSQL,$conn);
	$data = db_fetch_array($rs);
	return $data;
}

function Query($swhere,$orderby)
{
	global $conn;
	$dal_variables="";
		$dal_variables.= AddFieldWrappers("n_id").", ";
		$dal_variables.= AddFieldWrappers("n_active").", ";
		$dal_variables.= AddFieldWrappers("n_start").", ";
		$dal_variables.= AddFieldWrappers("n_end").", ";
		$dal_variables.= AddFieldWrappers("n_date").", ";
		$dal_variables.= AddFieldWrappers("n_head").", ";
		$dal_variables.= AddFieldWrappers("n_teaser").", ";
		$dal_variables.= AddFieldWrappers("n_text").", ";
		$dal_variables.= AddFieldWrappers("n_link").", ";
		$dal_variables.= AddFieldWrappers("n_file").", ";
		$dal_variables.= AddFieldWrappers("n_type").", ";
		$dal_variables.= AddFieldWrappers("n_country").", ";
	$dal_variables = substr($dal_variables,0,-2);
	if ($swhere)
		$swhere = " where ".$swhere;
	if ($orderby)
		$orderby = " order by ".$orderby;
	
	$dalSQL = "select ".$dal_variables." from ".$this->m_GoodTableName.$swhere.$orderby;
	$rs = db_query($dalSQL,$conn);
	$data = db_fetch_array($rs);
	return $data; 	
}

function FetchByID()
{
	global $conn;
	$dal_variables="";
	$dal_where="";
        $dal_variables.= AddFieldWrappers("n_id").", ";
        $dal_variables.= AddFieldWrappers("n_active").", ";
        $dal_variables.= AddFieldWrappers("n_start").", ";
        $dal_variables.= AddFieldWrappers("n_end").", ";
        $dal_variables.= AddFieldWrappers("n_date").", ";
        $dal_variables.= AddFieldWrappers("n_head").", ";
        $dal_variables.= AddFieldWrappers("n_teaser").", ";
        $dal_variables.= AddFieldWrappers("n_text").", ";
        $dal_variables.= AddFieldWrappers("n_link").", ";
        $dal_variables.= AddFieldWrappers("n_file").", ";
        $dal_variables.= AddFieldWrappers("n_type").", ";
        $dal_variables.= AddFieldWrappers("n_country").", ";
	$dal_variables = substr($dal_variables,0,-2);
	
		if ($this->n_id)
			$this->Param["n_id"] = $this->n_id;	
		if ($this->n_active)
			$this->Param["n_active"] = $this->n_active;	
		if ($this->n_start)
			$this->Param["n_start"] = $this->n_start;	
		if ($this->n_end)
			$this->Param["n_end"] = $this->n_end;	
		if ($this->n_date)
			$this->Param["n_date"] = $this->n_date;	
		if ($this->n_head)
			$this->Param["n_head"] = $this->n_head;	
		if ($this->n_teaser)
			$this->Param["n_teaser"] = $this->n_teaser;	
		if ($this->n_text)
			$this->Param["n_text"] = $this->n_text;	
		if ($this->n_link)
			$this->Param["n_link"] = $this->n_link;	
		if ($this->n_file)
			$this->Param["n_file"] = $this->n_file;	
		if ($this->n_type)
			$this->Param["n_type"] = $this->n_type;	
		if ($this->n_country)
			$this->Param["n_country"] = $this->n_country;	
	
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("n_id") && 1==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(3))
				$dal_where.= AddFieldWrappers("n_id")."='".db_addslashes($this->Param["n_id"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("n_id")."=".db_addslashes($this->Param["n_id"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("n_active") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(16))
				$dal_where.= AddFieldWrappers("n_active")."='".db_addslashes($this->Param["n_active"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("n_active")."=".db_addslashes($this->Param["n_active"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("n_start") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(7))
				$dal_where.= AddFieldWrappers("n_start")."='".db_addslashes($this->Param["n_start"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("n_start")."=".db_addslashes($this->Param["n_start"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("n_end") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(7))
				$dal_where.= AddFieldWrappers("n_end")."='".db_addslashes($this->Param["n_end"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("n_end")."=".db_addslashes($this->Param["n_end"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("n_date") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(7))
				$dal_where.= AddFieldWrappers("n_date")."='".db_addslashes($this->Param["n_date"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("n_date")."=".db_addslashes($this->Param["n_date"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("n_head") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(200))
				$dal_where.= AddFieldWrappers("n_head")."='".db_addslashes($this->Param["n_head"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("n_head")."=".db_addslashes($this->Param["n_head"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("n_teaser") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(201))
				$dal_where.= AddFieldWrappers("n_teaser")."='".db_addslashes($this->Param["n_teaser"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("n_teaser")."=".db_addslashes($this->Param["n_teaser"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("n_text") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(201))
				$dal_where.= AddFieldWrappers("n_text")."='".db_addslashes($this->Param["n_text"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("n_text")."=".db_addslashes($this->Param["n_text"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("n_link") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(200))
				$dal_where.= AddFieldWrappers("n_link")."='".db_addslashes($this->Param["n_link"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("n_link")."=".db_addslashes($this->Param["n_link"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("n_file") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(200))
				$dal_where.= AddFieldWrappers("n_file")."='".db_addslashes($this->Param["n_file"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("n_file")."=".db_addslashes($this->Param["n_file"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("n_type") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(3))
				$dal_where.= AddFieldWrappers("n_type")."='".db_addslashes($this->Param["n_type"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("n_type")."=".db_addslashes($this->Param["n_type"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("n_country") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(129))
				$dal_where.= AddFieldWrappers("n_country")."='".db_addslashes($this->Param["n_country"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("n_country")."=".db_addslashes($this->Param["n_country"]) . " and ";
	
	if ($dal_where)
		$dal_where = " where ".substr($dal_where,0,-5);
	
	$dalSQL = "select ".$dal_variables." from ".$this->m_GoodTableName.$dal_where;
	$rs = db_query($dalSQL,$conn);
	$data = db_fetch_array($rs);
	return $data;
}
	
function Reset()
{
        unset($this->m_n_id);
		unset($this->Value["n_id"]);
		unset($this->Param["n_id"]);
        unset($this->m_n_active);
		unset($this->Value["n_active"]);
		unset($this->Param["n_active"]);
        unset($this->m_n_start);
		unset($this->Value["n_start"]);
		unset($this->Param["n_start"]);
        unset($this->m_n_end);
		unset($this->Value["n_end"]);
		unset($this->Param["n_end"]);
        unset($this->m_n_date);
		unset($this->Value["n_date"]);
		unset($this->Param["n_date"]);
        unset($this->m_n_head);
		unset($this->Value["n_head"]);
		unset($this->Param["n_head"]);
        unset($this->m_n_teaser);
		unset($this->Value["n_teaser"]);
		unset($this->Param["n_teaser"]);
        unset($this->m_n_text);
		unset($this->Value["n_text"]);
		unset($this->Param["n_text"]);
        unset($this->m_n_link);
		unset($this->Value["n_link"]);
		unset($this->Param["n_link"]);
        unset($this->m_n_file);
		unset($this->Value["n_file"]);
		unset($this->Param["n_file"]);
        unset($this->m_n_type);
		unset($this->Value["n_type"]);
		unset($this->Param["n_type"]);
        unset($this->m_n_country);
		unset($this->Value["n_country"]);
		unset($this->Param["n_country"]);
}	
	
}//end of class


$dal->news = new class_news();


class class_raffle
{
	var $m_TableName;
	var $m_GoodTableName;
	var $m_r_id;
	var $m_r_name;
	var $m_r_img;
	var $m_r_text;
	var $m_r_date;
	var $m_r_p_id;

	var $Param = array();
	var $Value = array();
	
	var $r_id = array();
	var $r_name = array();
	var $r_img = array();
	var $r_text = array();
	var $r_date = array();
	var $r_p_id = array();
	
	var $m_ChangedFields;
	var $m_UpdateParam;
	var $m_UpdateValue;

	
	function class_raffle()
	{
		$this->m_TableName = "raffle";
		$this->m_GoodTableName = AddTableWrappers($this->m_TableName);	
	}

function TableName()
{
	$this->m_TableName = "raffle";
	$this->m_GoodTableName = AddTableWrappers($this->m_TableName);
	return $this->m_GoodTableName;
} 

function Add() 
{
	global $conn;

	$insertFields="";
	$insertValues="";
		

		if ($this->r_id)
		{
			$this->Value["r_id"] = $this->r_id;
		    $this->m_r_id = $this->r_id;
		}	

		if ($this->r_name)
		{
			$this->Value["r_name"] = $this->r_name;
		    $this->m_r_name = $this->r_name;
		}	

		if ($this->r_img)
		{
			$this->Value["r_img"] = $this->r_img;
		    $this->m_r_img = $this->r_img;
		}	

		if ($this->r_text)
		{
			$this->Value["r_text"] = $this->r_text;
		    $this->m_r_text = $this->r_text;
		}	

		if ($this->r_date)
		{
			$this->Value["r_date"] = $this->r_date;
		    $this->m_r_date = $this->r_date;
		}	

		if ($this->r_p_id)
		{
			$this->Value["r_p_id"] = $this->r_p_id;
		    $this->m_r_p_id = $this->r_p_id;
		}	
	
		if ($this->Value["r_id"])
		    $this->m_r_id = $this->Value["r_id"];	
		if ($this->Value["r_name"])
		    $this->m_r_name = $this->Value["r_name"];	
		if ($this->Value["r_img"])
		    $this->m_r_img = $this->Value["r_img"];	
		if ($this->Value["r_text"])
		    $this->m_r_text = $this->Value["r_text"];	
		if ($this->Value["r_date"])
		    $this->m_r_date = $this->Value["r_date"];	
		if ($this->Value["r_p_id"])
		    $this->m_r_p_id = $this->Value["r_p_id"];	

		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("r_id"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("r_id").",";
			if (NeedQuotes(3))
				$insertValues.= "'".db_addslashes($this->Value["r_id"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["r_id"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("r_name"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("r_name").",";
			if (NeedQuotes(200))
				$insertValues.= "'".db_addslashes($this->Value["r_name"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["r_name"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("r_img"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("r_img").",";
			if (NeedQuotes(200))
				$insertValues.= "'".db_addslashes($this->Value["r_img"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["r_img"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("r_text"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("r_text").",";
			if (NeedQuotes(201))
				$insertValues.= "'".db_addslashes($this->Value["r_text"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["r_text"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("r_date"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("r_date").",";
			if (NeedQuotes(7))
				$insertValues.= "'".db_addslashes($this->Value["r_date"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["r_date"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("r_p_id"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("r_p_id").",";
			if (NeedQuotes(3))
				$insertValues.= "'".db_addslashes($this->Value["r_p_id"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["r_p_id"]) . ",";		

		}

		
	if ($insertFields!="" && $insertValues!="")		
	{
		$insertFields = substr($insertFields,0,-1);
		$insertValues = substr($insertValues,0,-1);
		$dalSQL = "insert into ".$this->m_GoodTableName." (".$insertFields.") values (".$insertValues.")";
		db_exec($dalSQL,$conn);
	}
	
        unset($this->Value["r_id"]);
        unset($this->Value["r_name"]);
        unset($this->Value["r_img"]);
        unset($this->Value["r_text"]);
        unset($this->Value["r_date"]);
        unset($this->Value["r_p_id"]);
   // unset($this->m_ChangedFields);

	}
	
function Delete()
{
	global $conn;
	$deleteFields="";

		if ($this->r_id)
		{
			$this->Value["r_id"] = $this->r_id;
			$this->m_r_id = $this->r_id;
		}	
		if ($this->r_name)
		{
			$this->Value["r_name"] = $this->r_name;
			$this->m_r_name = $this->r_name;
		}	
		if ($this->r_img)
		{
			$this->Value["r_img"] = $this->r_img;
			$this->m_r_img = $this->r_img;
		}	
		if ($this->r_text)
		{
			$this->Value["r_text"] = $this->r_text;
			$this->m_r_text = $this->r_text;
		}	
		if ($this->r_date)
		{
			$this->Value["r_date"] = $this->r_date;
			$this->m_r_date = $this->r_date;
		}	
		if ($this->r_p_id)
		{
			$this->Value["r_p_id"] = $this->r_p_id;
			$this->m_r_p_id = $this->r_p_id;
		}	
	
		if ($this->Value["r_id"])
		    $this->m_r_id = $this->Value["r_id"];	
		if ($this->Value["r_name"])
		    $this->m_r_name = $this->Value["r_name"];	
		if ($this->Value["r_img"])
		    $this->m_r_img = $this->Value["r_img"];	
		if ($this->Value["r_text"])
		    $this->m_r_text = $this->Value["r_text"];	
		if ($this->Value["r_date"])
		    $this->m_r_date = $this->Value["r_date"];	
		if ($this->Value["r_p_id"])
		    $this->m_r_p_id = $this->Value["r_p_id"];	

		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("r_id"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("r_id").",";
			if (NeedQuotes(3))
				$deleteFields.= AddFieldWrappers("r_id")."='".db_addslashes($this->Value["r_id"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("r_id")."=".db_addslashes($this->Value["r_id"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("r_name"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("r_name").",";
			if (NeedQuotes(200))
				$deleteFields.= AddFieldWrappers("r_name")."='".db_addslashes($this->Value["r_name"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("r_name")."=".db_addslashes($this->Value["r_name"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("r_img"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("r_img").",";
			if (NeedQuotes(200))
				$deleteFields.= AddFieldWrappers("r_img")."='".db_addslashes($this->Value["r_img"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("r_img")."=".db_addslashes($this->Value["r_img"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("r_text"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("r_text").",";
			if (NeedQuotes(201))
				$deleteFields.= AddFieldWrappers("r_text")."='".db_addslashes($this->Value["r_text"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("r_text")."=".db_addslashes($this->Value["r_text"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("r_date"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("r_date").",";
			if (NeedQuotes(7))
				$deleteFields.= AddFieldWrappers("r_date")."='".db_addslashes($this->Value["r_date"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("r_date")."=".db_addslashes($this->Value["r_date"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("r_p_id"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("r_p_id").",";
			if (NeedQuotes(3))
				$deleteFields.= AddFieldWrappers("r_p_id")."='".db_addslashes($this->Value["r_p_id"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("r_p_id")."=".db_addslashes($this->Value["r_p_id"]) . " and ";		
		}

	if ($deleteFields)
	{
		$deleteFields = substr($deleteFields,0,-5);
		$dalSQL = "delete from ".$this->m_GoodTableName." where ".$deleteFields;
		db_exec($dalSQL,$conn);
	}
	
	    unset($this->Value["r_id"]);
	    unset($this->Value["r_name"]);
	    unset($this->Value["r_img"]);
	    unset($this->Value["r_text"]);
	    unset($this->Value["r_date"]);
	    unset($this->Value["r_p_id"]);
		
	//unset($this->Value);	
}

function Update()
{
	global $conn;
	
	$updateParam = "";
	$updateValue = "";
	
		if ($this->r_id)
		{
			if (1==1)
				$this->Param["r_id"] = $this->r_id;
			else
				$this->Value["r_id"] = $this->r_id;
			$this->m_r_id = $this->r_id;
		}	
		if ($this->r_name)
		{
			if (0==1)
				$this->Param["r_name"] = $this->r_name;
			else
				$this->Value["r_name"] = $this->r_name;
			$this->m_r_name = $this->r_name;
		}	
		if ($this->r_img)
		{
			if (0==1)
				$this->Param["r_img"] = $this->r_img;
			else
				$this->Value["r_img"] = $this->r_img;
			$this->m_r_img = $this->r_img;
		}	
		if ($this->r_text)
		{
			if (0==1)
				$this->Param["r_text"] = $this->r_text;
			else
				$this->Value["r_text"] = $this->r_text;
			$this->m_r_text = $this->r_text;
		}	
		if ($this->r_date)
		{
			if (0==1)
				$this->Param["r_date"] = $this->r_date;
			else
				$this->Value["r_date"] = $this->r_date;
			$this->m_r_date = $this->r_date;
		}	
		if ($this->r_p_id)
		{
			if (0==1)
				$this->Param["r_p_id"] = $this->r_p_id;
			else
				$this->Value["r_p_id"] = $this->r_p_id;
			$this->m_r_p_id = $this->r_p_id;
		}	
	
		if ($this->Value["r_id"])
		    $this->m_r_id = $this->Value["r_id"];		
		if ($this->Value["r_name"])
		    $this->m_r_name = $this->Value["r_name"];		
		if ($this->Value["r_img"])
		    $this->m_r_img = $this->Value["r_img"];		
		if ($this->Value["r_text"])
		    $this->m_r_text = $this->Value["r_text"];		
		if ($this->Value["r_date"])
		    $this->m_r_date = $this->Value["r_date"];		
		if ($this->Value["r_p_id"])
		    $this->m_r_p_id = $this->Value["r_p_id"];		
	
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("r_id"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateValue.= AddFieldWrappers("r_id")."='".$this->Value["r_id"]."', ";
			else
					$updateValue.= AddFieldWrappers("r_id")."=".$this->Value["r_id"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("r_id"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateParam.= AddFieldWrappers("r_id")."='".db_addslashes($this->Param["r_id"])."' and ";
			else
					$updateParam.= AddFieldWrappers("r_id")."=".db_addslashes($this->Param["r_id"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("r_name"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateValue.= AddFieldWrappers("r_name")."='".$this->Value["r_name"]."', ";
			else
					$updateValue.= AddFieldWrappers("r_name")."=".$this->Value["r_name"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("r_name"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateParam.= AddFieldWrappers("r_name")."='".db_addslashes($this->Param["r_name"])."' and ";
			else
					$updateParam.= AddFieldWrappers("r_name")."=".db_addslashes($this->Param["r_name"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("r_img"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateValue.= AddFieldWrappers("r_img")."='".$this->Value["r_img"]."', ";
			else
					$updateValue.= AddFieldWrappers("r_img")."=".$this->Value["r_img"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("r_img"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateParam.= AddFieldWrappers("r_img")."='".db_addslashes($this->Param["r_img"])."' and ";
			else
					$updateParam.= AddFieldWrappers("r_img")."=".db_addslashes($this->Param["r_img"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("r_text"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(201))
					$updateValue.= AddFieldWrappers("r_text")."='".$this->Value["r_text"]."', ";
			else
					$updateValue.= AddFieldWrappers("r_text")."=".$this->Value["r_text"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("r_text"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(201))
					$updateParam.= AddFieldWrappers("r_text")."='".db_addslashes($this->Param["r_text"])."' and ";
			else
					$updateParam.= AddFieldWrappers("r_text")."=".db_addslashes($this->Param["r_text"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("r_date"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(7))
					$updateValue.= AddFieldWrappers("r_date")."='".$this->Value["r_date"]."', ";
			else
					$updateValue.= AddFieldWrappers("r_date")."=".$this->Value["r_date"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("r_date"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(7))
					$updateParam.= AddFieldWrappers("r_date")."='".db_addslashes($this->Param["r_date"])."' and ";
			else
					$updateParam.= AddFieldWrappers("r_date")."=".db_addslashes($this->Param["r_date"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("r_p_id"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateValue.= AddFieldWrappers("r_p_id")."='".$this->Value["r_p_id"]."', ";
			else
					$updateValue.= AddFieldWrappers("r_p_id")."=".$this->Value["r_p_id"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("r_p_id"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateParam.= AddFieldWrappers("r_p_id")."='".db_addslashes($this->Param["r_p_id"])."' and ";
			else
					$updateParam.= AddFieldWrappers("r_p_id")."=".db_addslashes($this->Param["r_p_id"])." and ";
		}
		
	
	if ($updateParam)
		$updateParam = substr($updateParam,0,-5);
	if ($updateValue)
		$updateValue = substr($updateValue,0,-2);
		
	if ($updateValue)
	{
		$dalSQL = "update ".$this->m_GoodTableName." set ".$updateValue." where ".$updateParam;
		db_exec($dalSQL,$conn);
	}
	
        unset($this->Value["r_id"]);
		unset($this->Param["r_id"]);
        unset($this->Value["r_name"]);
		unset($this->Param["r_name"]);
        unset($this->Value["r_img"]);
		unset($this->Param["r_img"]);
        unset($this->Value["r_text"]);
		unset($this->Param["r_text"]);
        unset($this->Value["r_date"]);
		unset($this->Param["r_date"]);
        unset($this->Value["r_p_id"]);
		unset($this->Param["r_p_id"]);
	
}

function QueryAll()
{
	global $conn;
	$dal_variables="";
		$dal_variables.= AddFieldWrappers("r_id").", ";
		$dal_variables.= AddFieldWrappers("r_name").", ";
		$dal_variables.= AddFieldWrappers("r_img").", ";
		$dal_variables.= AddFieldWrappers("r_text").", ";
		$dal_variables.= AddFieldWrappers("r_date").", ";
		$dal_variables.= AddFieldWrappers("r_p_id").", ";
	$dal_variables = substr($dal_variables,0,-2);
	$dalSQL = "select ".$dal_variables." from ".$this->m_GoodTableName;
	$rs = db_query($dalSQL,$conn);
	$data = db_fetch_array($rs);
	return $data;
}

function Query($swhere,$orderby)
{
	global $conn;
	$dal_variables="";
		$dal_variables.= AddFieldWrappers("r_id").", ";
		$dal_variables.= AddFieldWrappers("r_name").", ";
		$dal_variables.= AddFieldWrappers("r_img").", ";
		$dal_variables.= AddFieldWrappers("r_text").", ";
		$dal_variables.= AddFieldWrappers("r_date").", ";
		$dal_variables.= AddFieldWrappers("r_p_id").", ";
	$dal_variables = substr($dal_variables,0,-2);
	if ($swhere)
		$swhere = " where ".$swhere;
	if ($orderby)
		$orderby = " order by ".$orderby;
	
	$dalSQL = "select ".$dal_variables." from ".$this->m_GoodTableName.$swhere.$orderby;
	$rs = db_query($dalSQL,$conn);
	$data = db_fetch_array($rs);
	return $data; 	
}

function FetchByID()
{
	global $conn;
	$dal_variables="";
	$dal_where="";
        $dal_variables.= AddFieldWrappers("r_id").", ";
        $dal_variables.= AddFieldWrappers("r_name").", ";
        $dal_variables.= AddFieldWrappers("r_img").", ";
        $dal_variables.= AddFieldWrappers("r_text").", ";
        $dal_variables.= AddFieldWrappers("r_date").", ";
        $dal_variables.= AddFieldWrappers("r_p_id").", ";
	$dal_variables = substr($dal_variables,0,-2);
	
		if ($this->r_id)
			$this->Param["r_id"] = $this->r_id;	
		if ($this->r_name)
			$this->Param["r_name"] = $this->r_name;	
		if ($this->r_img)
			$this->Param["r_img"] = $this->r_img;	
		if ($this->r_text)
			$this->Param["r_text"] = $this->r_text;	
		if ($this->r_date)
			$this->Param["r_date"] = $this->r_date;	
		if ($this->r_p_id)
			$this->Param["r_p_id"] = $this->r_p_id;	
	
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("r_id") && 1==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(3))
				$dal_where.= AddFieldWrappers("r_id")."='".db_addslashes($this->Param["r_id"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("r_id")."=".db_addslashes($this->Param["r_id"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("r_name") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(200))
				$dal_where.= AddFieldWrappers("r_name")."='".db_addslashes($this->Param["r_name"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("r_name")."=".db_addslashes($this->Param["r_name"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("r_img") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(200))
				$dal_where.= AddFieldWrappers("r_img")."='".db_addslashes($this->Param["r_img"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("r_img")."=".db_addslashes($this->Param["r_img"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("r_text") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(201))
				$dal_where.= AddFieldWrappers("r_text")."='".db_addslashes($this->Param["r_text"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("r_text")."=".db_addslashes($this->Param["r_text"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("r_date") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(7))
				$dal_where.= AddFieldWrappers("r_date")."='".db_addslashes($this->Param["r_date"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("r_date")."=".db_addslashes($this->Param["r_date"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("r_p_id") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(3))
				$dal_where.= AddFieldWrappers("r_p_id")."='".db_addslashes($this->Param["r_p_id"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("r_p_id")."=".db_addslashes($this->Param["r_p_id"]) . " and ";
	
	if ($dal_where)
		$dal_where = " where ".substr($dal_where,0,-5);
	
	$dalSQL = "select ".$dal_variables." from ".$this->m_GoodTableName.$dal_where;
	$rs = db_query($dalSQL,$conn);
	$data = db_fetch_array($rs);
	return $data;
}
	
function Reset()
{
        unset($this->m_r_id);
		unset($this->Value["r_id"]);
		unset($this->Param["r_id"]);
        unset($this->m_r_name);
		unset($this->Value["r_name"]);
		unset($this->Param["r_name"]);
        unset($this->m_r_img);
		unset($this->Value["r_img"]);
		unset($this->Param["r_img"]);
        unset($this->m_r_text);
		unset($this->Value["r_text"]);
		unset($this->Param["r_text"]);
        unset($this->m_r_date);
		unset($this->Value["r_date"]);
		unset($this->Param["r_date"]);
        unset($this->m_r_p_id);
		unset($this->Value["r_p_id"]);
		unset($this->Param["r_p_id"]);
}	
	
}//end of class


$dal->raffle = new class_raffle();


class class_sponsor
{
	var $m_TableName;
	var $m_GoodTableName;
	var $m_s_id;
	var $m_s_active;
	var $m_s_name;
	var $m_s_contact;
	var $m_s_adr;
	var $m_s_zip;
	var $m_s_phone1;
	var $m_s_phone2;
	var $m_s_total;
	var $m_s_paid;
	var $m_s_logo;
	var $m_s_banner;
	var $m_s_www;
	var $m_s_mail;
	var $m_s_cmt;
	var $m_s_country;

	var $Param = array();
	var $Value = array();
	
	var $s_id = array();
	var $s_active = array();
	var $s_name = array();
	var $s_contact = array();
	var $s_adr = array();
	var $s_zip = array();
	var $s_phone1 = array();
	var $s_phone2 = array();
	var $s_total = array();
	var $s_paid = array();
	var $s_logo = array();
	var $s_banner = array();
	var $s_www = array();
	var $s_mail = array();
	var $s_cmt = array();
	var $s_country = array();
	
	var $m_ChangedFields;
	var $m_UpdateParam;
	var $m_UpdateValue;

	
	function class_sponsor()
	{
		$this->m_TableName = "sponsor";
		$this->m_GoodTableName = AddTableWrappers($this->m_TableName);	
	}

function TableName()
{
	$this->m_TableName = "sponsor";
	$this->m_GoodTableName = AddTableWrappers($this->m_TableName);
	return $this->m_GoodTableName;
} 

function Add() 
{
	global $conn;

	$insertFields="";
	$insertValues="";
		

		if ($this->s_id)
		{
			$this->Value["s_id"] = $this->s_id;
		    $this->m_s_id = $this->s_id;
		}	

		if ($this->s_active)
		{
			$this->Value["s_active"] = $this->s_active;
		    $this->m_s_active = $this->s_active;
		}	

		if ($this->s_name)
		{
			$this->Value["s_name"] = $this->s_name;
		    $this->m_s_name = $this->s_name;
		}	

		if ($this->s_contact)
		{
			$this->Value["s_contact"] = $this->s_contact;
		    $this->m_s_contact = $this->s_contact;
		}	

		if ($this->s_adr)
		{
			$this->Value["s_adr"] = $this->s_adr;
		    $this->m_s_adr = $this->s_adr;
		}	

		if ($this->s_zip)
		{
			$this->Value["s_zip"] = $this->s_zip;
		    $this->m_s_zip = $this->s_zip;
		}	

		if ($this->s_phone1)
		{
			$this->Value["s_phone1"] = $this->s_phone1;
		    $this->m_s_phone1 = $this->s_phone1;
		}	

		if ($this->s_phone2)
		{
			$this->Value["s_phone2"] = $this->s_phone2;
		    $this->m_s_phone2 = $this->s_phone2;
		}	

		if ($this->s_total)
		{
			$this->Value["s_total"] = $this->s_total;
		    $this->m_s_total = $this->s_total;
		}	

		if ($this->s_paid)
		{
			$this->Value["s_paid"] = $this->s_paid;
		    $this->m_s_paid = $this->s_paid;
		}	

		if ($this->s_logo)
		{
			$this->Value["s_logo"] = $this->s_logo;
		    $this->m_s_logo = $this->s_logo;
		}	

		if ($this->s_banner)
		{
			$this->Value["s_banner"] = $this->s_banner;
		    $this->m_s_banner = $this->s_banner;
		}	

		if ($this->s_www)
		{
			$this->Value["s_www"] = $this->s_www;
		    $this->m_s_www = $this->s_www;
		}	

		if ($this->s_mail)
		{
			$this->Value["s_mail"] = $this->s_mail;
		    $this->m_s_mail = $this->s_mail;
		}	

		if ($this->s_cmt)
		{
			$this->Value["s_cmt"] = $this->s_cmt;
		    $this->m_s_cmt = $this->s_cmt;
		}	

		if ($this->s_country)
		{
			$this->Value["s_country"] = $this->s_country;
		    $this->m_s_country = $this->s_country;
		}	
	
		if ($this->Value["s_id"])
		    $this->m_s_id = $this->Value["s_id"];	
		if ($this->Value["s_active"])
		    $this->m_s_active = $this->Value["s_active"];	
		if ($this->Value["s_name"])
		    $this->m_s_name = $this->Value["s_name"];	
		if ($this->Value["s_contact"])
		    $this->m_s_contact = $this->Value["s_contact"];	
		if ($this->Value["s_adr"])
		    $this->m_s_adr = $this->Value["s_adr"];	
		if ($this->Value["s_zip"])
		    $this->m_s_zip = $this->Value["s_zip"];	
		if ($this->Value["s_phone1"])
		    $this->m_s_phone1 = $this->Value["s_phone1"];	
		if ($this->Value["s_phone2"])
		    $this->m_s_phone2 = $this->Value["s_phone2"];	
		if ($this->Value["s_total"])
		    $this->m_s_total = $this->Value["s_total"];	
		if ($this->Value["s_paid"])
		    $this->m_s_paid = $this->Value["s_paid"];	
		if ($this->Value["s_logo"])
		    $this->m_s_logo = $this->Value["s_logo"];	
		if ($this->Value["s_banner"])
		    $this->m_s_banner = $this->Value["s_banner"];	
		if ($this->Value["s_www"])
		    $this->m_s_www = $this->Value["s_www"];	
		if ($this->Value["s_mail"])
		    $this->m_s_mail = $this->Value["s_mail"];	
		if ($this->Value["s_cmt"])
		    $this->m_s_cmt = $this->Value["s_cmt"];	
		if ($this->Value["s_country"])
		    $this->m_s_country = $this->Value["s_country"];	

		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_id"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("s_id").",";
			if (NeedQuotes(3))
				$insertValues.= "'".db_addslashes($this->Value["s_id"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["s_id"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_active"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("s_active").",";
			if (NeedQuotes(16))
				$insertValues.= "'".db_addslashes($this->Value["s_active"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["s_active"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_name"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("s_name").",";
			if (NeedQuotes(200))
				$insertValues.= "'".db_addslashes($this->Value["s_name"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["s_name"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_contact"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("s_contact").",";
			if (NeedQuotes(200))
				$insertValues.= "'".db_addslashes($this->Value["s_contact"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["s_contact"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_adr"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("s_adr").",";
			if (NeedQuotes(200))
				$insertValues.= "'".db_addslashes($this->Value["s_adr"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["s_adr"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_zip"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("s_zip").",";
			if (NeedQuotes(3))
				$insertValues.= "'".db_addslashes($this->Value["s_zip"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["s_zip"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_phone1"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("s_phone1").",";
			if (NeedQuotes(200))
				$insertValues.= "'".db_addslashes($this->Value["s_phone1"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["s_phone1"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_phone2"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("s_phone2").",";
			if (NeedQuotes(200))
				$insertValues.= "'".db_addslashes($this->Value["s_phone2"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["s_phone2"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_total"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("s_total").",";
			if (NeedQuotes(14))
				$insertValues.= "'".db_addslashes($this->Value["s_total"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["s_total"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_paid"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("s_paid").",";
			if (NeedQuotes(14))
				$insertValues.= "'".db_addslashes($this->Value["s_paid"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["s_paid"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_logo"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("s_logo").",";
			if (NeedQuotes(200))
				$insertValues.= "'".db_addslashes($this->Value["s_logo"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["s_logo"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_banner"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("s_banner").",";
			if (NeedQuotes(200))
				$insertValues.= "'".db_addslashes($this->Value["s_banner"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["s_banner"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_www"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("s_www").",";
			if (NeedQuotes(200))
				$insertValues.= "'".db_addslashes($this->Value["s_www"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["s_www"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_mail"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("s_mail").",";
			if (NeedQuotes(200))
				$insertValues.= "'".db_addslashes($this->Value["s_mail"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["s_mail"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_cmt"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("s_cmt").",";
			if (NeedQuotes(201))
				$insertValues.= "'".db_addslashes($this->Value["s_cmt"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["s_cmt"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_country"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("s_country").",";
			if (NeedQuotes(129))
				$insertValues.= "'".db_addslashes($this->Value["s_country"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["s_country"]) . ",";		

		}

		
	if ($insertFields!="" && $insertValues!="")		
	{
		$insertFields = substr($insertFields,0,-1);
		$insertValues = substr($insertValues,0,-1);
		$dalSQL = "insert into ".$this->m_GoodTableName." (".$insertFields.") values (".$insertValues.")";
		db_exec($dalSQL,$conn);
	}
	
        unset($this->Value["s_id"]);
        unset($this->Value["s_active"]);
        unset($this->Value["s_name"]);
        unset($this->Value["s_contact"]);
        unset($this->Value["s_adr"]);
        unset($this->Value["s_zip"]);
        unset($this->Value["s_phone1"]);
        unset($this->Value["s_phone2"]);
        unset($this->Value["s_total"]);
        unset($this->Value["s_paid"]);
        unset($this->Value["s_logo"]);
        unset($this->Value["s_banner"]);
        unset($this->Value["s_www"]);
        unset($this->Value["s_mail"]);
        unset($this->Value["s_cmt"]);
        unset($this->Value["s_country"]);
   // unset($this->m_ChangedFields);

	}
	
function Delete()
{
	global $conn;
	$deleteFields="";

		if ($this->s_id)
		{
			$this->Value["s_id"] = $this->s_id;
			$this->m_s_id = $this->s_id;
		}	
		if ($this->s_active)
		{
			$this->Value["s_active"] = $this->s_active;
			$this->m_s_active = $this->s_active;
		}	
		if ($this->s_name)
		{
			$this->Value["s_name"] = $this->s_name;
			$this->m_s_name = $this->s_name;
		}	
		if ($this->s_contact)
		{
			$this->Value["s_contact"] = $this->s_contact;
			$this->m_s_contact = $this->s_contact;
		}	
		if ($this->s_adr)
		{
			$this->Value["s_adr"] = $this->s_adr;
			$this->m_s_adr = $this->s_adr;
		}	
		if ($this->s_zip)
		{
			$this->Value["s_zip"] = $this->s_zip;
			$this->m_s_zip = $this->s_zip;
		}	
		if ($this->s_phone1)
		{
			$this->Value["s_phone1"] = $this->s_phone1;
			$this->m_s_phone1 = $this->s_phone1;
		}	
		if ($this->s_phone2)
		{
			$this->Value["s_phone2"] = $this->s_phone2;
			$this->m_s_phone2 = $this->s_phone2;
		}	
		if ($this->s_total)
		{
			$this->Value["s_total"] = $this->s_total;
			$this->m_s_total = $this->s_total;
		}	
		if ($this->s_paid)
		{
			$this->Value["s_paid"] = $this->s_paid;
			$this->m_s_paid = $this->s_paid;
		}	
		if ($this->s_logo)
		{
			$this->Value["s_logo"] = $this->s_logo;
			$this->m_s_logo = $this->s_logo;
		}	
		if ($this->s_banner)
		{
			$this->Value["s_banner"] = $this->s_banner;
			$this->m_s_banner = $this->s_banner;
		}	
		if ($this->s_www)
		{
			$this->Value["s_www"] = $this->s_www;
			$this->m_s_www = $this->s_www;
		}	
		if ($this->s_mail)
		{
			$this->Value["s_mail"] = $this->s_mail;
			$this->m_s_mail = $this->s_mail;
		}	
		if ($this->s_cmt)
		{
			$this->Value["s_cmt"] = $this->s_cmt;
			$this->m_s_cmt = $this->s_cmt;
		}	
		if ($this->s_country)
		{
			$this->Value["s_country"] = $this->s_country;
			$this->m_s_country = $this->s_country;
		}	
	
		if ($this->Value["s_id"])
		    $this->m_s_id = $this->Value["s_id"];	
		if ($this->Value["s_active"])
		    $this->m_s_active = $this->Value["s_active"];	
		if ($this->Value["s_name"])
		    $this->m_s_name = $this->Value["s_name"];	
		if ($this->Value["s_contact"])
		    $this->m_s_contact = $this->Value["s_contact"];	
		if ($this->Value["s_adr"])
		    $this->m_s_adr = $this->Value["s_adr"];	
		if ($this->Value["s_zip"])
		    $this->m_s_zip = $this->Value["s_zip"];	
		if ($this->Value["s_phone1"])
		    $this->m_s_phone1 = $this->Value["s_phone1"];	
		if ($this->Value["s_phone2"])
		    $this->m_s_phone2 = $this->Value["s_phone2"];	
		if ($this->Value["s_total"])
		    $this->m_s_total = $this->Value["s_total"];	
		if ($this->Value["s_paid"])
		    $this->m_s_paid = $this->Value["s_paid"];	
		if ($this->Value["s_logo"])
		    $this->m_s_logo = $this->Value["s_logo"];	
		if ($this->Value["s_banner"])
		    $this->m_s_banner = $this->Value["s_banner"];	
		if ($this->Value["s_www"])
		    $this->m_s_www = $this->Value["s_www"];	
		if ($this->Value["s_mail"])
		    $this->m_s_mail = $this->Value["s_mail"];	
		if ($this->Value["s_cmt"])
		    $this->m_s_cmt = $this->Value["s_cmt"];	
		if ($this->Value["s_country"])
		    $this->m_s_country = $this->Value["s_country"];	

		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_id"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("s_id").",";
			if (NeedQuotes(3))
				$deleteFields.= AddFieldWrappers("s_id")."='".db_addslashes($this->Value["s_id"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("s_id")."=".db_addslashes($this->Value["s_id"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_active"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("s_active").",";
			if (NeedQuotes(16))
				$deleteFields.= AddFieldWrappers("s_active")."='".db_addslashes($this->Value["s_active"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("s_active")."=".db_addslashes($this->Value["s_active"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_name"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("s_name").",";
			if (NeedQuotes(200))
				$deleteFields.= AddFieldWrappers("s_name")."='".db_addslashes($this->Value["s_name"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("s_name")."=".db_addslashes($this->Value["s_name"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_contact"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("s_contact").",";
			if (NeedQuotes(200))
				$deleteFields.= AddFieldWrappers("s_contact")."='".db_addslashes($this->Value["s_contact"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("s_contact")."=".db_addslashes($this->Value["s_contact"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_adr"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("s_adr").",";
			if (NeedQuotes(200))
				$deleteFields.= AddFieldWrappers("s_adr")."='".db_addslashes($this->Value["s_adr"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("s_adr")."=".db_addslashes($this->Value["s_adr"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_zip"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("s_zip").",";
			if (NeedQuotes(3))
				$deleteFields.= AddFieldWrappers("s_zip")."='".db_addslashes($this->Value["s_zip"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("s_zip")."=".db_addslashes($this->Value["s_zip"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_phone1"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("s_phone1").",";
			if (NeedQuotes(200))
				$deleteFields.= AddFieldWrappers("s_phone1")."='".db_addslashes($this->Value["s_phone1"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("s_phone1")."=".db_addslashes($this->Value["s_phone1"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_phone2"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("s_phone2").",";
			if (NeedQuotes(200))
				$deleteFields.= AddFieldWrappers("s_phone2")."='".db_addslashes($this->Value["s_phone2"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("s_phone2")."=".db_addslashes($this->Value["s_phone2"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_total"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("s_total").",";
			if (NeedQuotes(14))
				$deleteFields.= AddFieldWrappers("s_total")."='".db_addslashes($this->Value["s_total"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("s_total")."=".db_addslashes($this->Value["s_total"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_paid"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("s_paid").",";
			if (NeedQuotes(14))
				$deleteFields.= AddFieldWrappers("s_paid")."='".db_addslashes($this->Value["s_paid"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("s_paid")."=".db_addslashes($this->Value["s_paid"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_logo"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("s_logo").",";
			if (NeedQuotes(200))
				$deleteFields.= AddFieldWrappers("s_logo")."='".db_addslashes($this->Value["s_logo"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("s_logo")."=".db_addslashes($this->Value["s_logo"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_banner"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("s_banner").",";
			if (NeedQuotes(200))
				$deleteFields.= AddFieldWrappers("s_banner")."='".db_addslashes($this->Value["s_banner"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("s_banner")."=".db_addslashes($this->Value["s_banner"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_www"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("s_www").",";
			if (NeedQuotes(200))
				$deleteFields.= AddFieldWrappers("s_www")."='".db_addslashes($this->Value["s_www"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("s_www")."=".db_addslashes($this->Value["s_www"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_mail"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("s_mail").",";
			if (NeedQuotes(200))
				$deleteFields.= AddFieldWrappers("s_mail")."='".db_addslashes($this->Value["s_mail"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("s_mail")."=".db_addslashes($this->Value["s_mail"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_cmt"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("s_cmt").",";
			if (NeedQuotes(201))
				$deleteFields.= AddFieldWrappers("s_cmt")."='".db_addslashes($this->Value["s_cmt"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("s_cmt")."=".db_addslashes($this->Value["s_cmt"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_country"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("s_country").",";
			if (NeedQuotes(129))
				$deleteFields.= AddFieldWrappers("s_country")."='".db_addslashes($this->Value["s_country"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("s_country")."=".db_addslashes($this->Value["s_country"]) . " and ";		
		}

	if ($deleteFields)
	{
		$deleteFields = substr($deleteFields,0,-5);
		$dalSQL = "delete from ".$this->m_GoodTableName." where ".$deleteFields;
		db_exec($dalSQL,$conn);
	}
	
	    unset($this->Value["s_id"]);
	    unset($this->Value["s_active"]);
	    unset($this->Value["s_name"]);
	    unset($this->Value["s_contact"]);
	    unset($this->Value["s_adr"]);
	    unset($this->Value["s_zip"]);
	    unset($this->Value["s_phone1"]);
	    unset($this->Value["s_phone2"]);
	    unset($this->Value["s_total"]);
	    unset($this->Value["s_paid"]);
	    unset($this->Value["s_logo"]);
	    unset($this->Value["s_banner"]);
	    unset($this->Value["s_www"]);
	    unset($this->Value["s_mail"]);
	    unset($this->Value["s_cmt"]);
	    unset($this->Value["s_country"]);
		
	//unset($this->Value);	
}

function Update()
{
	global $conn;
	
	$updateParam = "";
	$updateValue = "";
	
		if ($this->s_id)
		{
			if (1==1)
				$this->Param["s_id"] = $this->s_id;
			else
				$this->Value["s_id"] = $this->s_id;
			$this->m_s_id = $this->s_id;
		}	
		if ($this->s_active)
		{
			if (0==1)
				$this->Param["s_active"] = $this->s_active;
			else
				$this->Value["s_active"] = $this->s_active;
			$this->m_s_active = $this->s_active;
		}	
		if ($this->s_name)
		{
			if (0==1)
				$this->Param["s_name"] = $this->s_name;
			else
				$this->Value["s_name"] = $this->s_name;
			$this->m_s_name = $this->s_name;
		}	
		if ($this->s_contact)
		{
			if (0==1)
				$this->Param["s_contact"] = $this->s_contact;
			else
				$this->Value["s_contact"] = $this->s_contact;
			$this->m_s_contact = $this->s_contact;
		}	
		if ($this->s_adr)
		{
			if (0==1)
				$this->Param["s_adr"] = $this->s_adr;
			else
				$this->Value["s_adr"] = $this->s_adr;
			$this->m_s_adr = $this->s_adr;
		}	
		if ($this->s_zip)
		{
			if (0==1)
				$this->Param["s_zip"] = $this->s_zip;
			else
				$this->Value["s_zip"] = $this->s_zip;
			$this->m_s_zip = $this->s_zip;
		}	
		if ($this->s_phone1)
		{
			if (0==1)
				$this->Param["s_phone1"] = $this->s_phone1;
			else
				$this->Value["s_phone1"] = $this->s_phone1;
			$this->m_s_phone1 = $this->s_phone1;
		}	
		if ($this->s_phone2)
		{
			if (0==1)
				$this->Param["s_phone2"] = $this->s_phone2;
			else
				$this->Value["s_phone2"] = $this->s_phone2;
			$this->m_s_phone2 = $this->s_phone2;
		}	
		if ($this->s_total)
		{
			if (0==1)
				$this->Param["s_total"] = $this->s_total;
			else
				$this->Value["s_total"] = $this->s_total;
			$this->m_s_total = $this->s_total;
		}	
		if ($this->s_paid)
		{
			if (0==1)
				$this->Param["s_paid"] = $this->s_paid;
			else
				$this->Value["s_paid"] = $this->s_paid;
			$this->m_s_paid = $this->s_paid;
		}	
		if ($this->s_logo)
		{
			if (0==1)
				$this->Param["s_logo"] = $this->s_logo;
			else
				$this->Value["s_logo"] = $this->s_logo;
			$this->m_s_logo = $this->s_logo;
		}	
		if ($this->s_banner)
		{
			if (0==1)
				$this->Param["s_banner"] = $this->s_banner;
			else
				$this->Value["s_banner"] = $this->s_banner;
			$this->m_s_banner = $this->s_banner;
		}	
		if ($this->s_www)
		{
			if (0==1)
				$this->Param["s_www"] = $this->s_www;
			else
				$this->Value["s_www"] = $this->s_www;
			$this->m_s_www = $this->s_www;
		}	
		if ($this->s_mail)
		{
			if (0==1)
				$this->Param["s_mail"] = $this->s_mail;
			else
				$this->Value["s_mail"] = $this->s_mail;
			$this->m_s_mail = $this->s_mail;
		}	
		if ($this->s_cmt)
		{
			if (0==1)
				$this->Param["s_cmt"] = $this->s_cmt;
			else
				$this->Value["s_cmt"] = $this->s_cmt;
			$this->m_s_cmt = $this->s_cmt;
		}	
		if ($this->s_country)
		{
			if (0==1)
				$this->Param["s_country"] = $this->s_country;
			else
				$this->Value["s_country"] = $this->s_country;
			$this->m_s_country = $this->s_country;
		}	
	
		if ($this->Value["s_id"])
		    $this->m_s_id = $this->Value["s_id"];		
		if ($this->Value["s_active"])
		    $this->m_s_active = $this->Value["s_active"];		
		if ($this->Value["s_name"])
		    $this->m_s_name = $this->Value["s_name"];		
		if ($this->Value["s_contact"])
		    $this->m_s_contact = $this->Value["s_contact"];		
		if ($this->Value["s_adr"])
		    $this->m_s_adr = $this->Value["s_adr"];		
		if ($this->Value["s_zip"])
		    $this->m_s_zip = $this->Value["s_zip"];		
		if ($this->Value["s_phone1"])
		    $this->m_s_phone1 = $this->Value["s_phone1"];		
		if ($this->Value["s_phone2"])
		    $this->m_s_phone2 = $this->Value["s_phone2"];		
		if ($this->Value["s_total"])
		    $this->m_s_total = $this->Value["s_total"];		
		if ($this->Value["s_paid"])
		    $this->m_s_paid = $this->Value["s_paid"];		
		if ($this->Value["s_logo"])
		    $this->m_s_logo = $this->Value["s_logo"];		
		if ($this->Value["s_banner"])
		    $this->m_s_banner = $this->Value["s_banner"];		
		if ($this->Value["s_www"])
		    $this->m_s_www = $this->Value["s_www"];		
		if ($this->Value["s_mail"])
		    $this->m_s_mail = $this->Value["s_mail"];		
		if ($this->Value["s_cmt"])
		    $this->m_s_cmt = $this->Value["s_cmt"];		
		if ($this->Value["s_country"])
		    $this->m_s_country = $this->Value["s_country"];		
	
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_id"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateValue.= AddFieldWrappers("s_id")."='".$this->Value["s_id"]."', ";
			else
					$updateValue.= AddFieldWrappers("s_id")."=".$this->Value["s_id"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("s_id"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateParam.= AddFieldWrappers("s_id")."='".db_addslashes($this->Param["s_id"])."' and ";
			else
					$updateParam.= AddFieldWrappers("s_id")."=".db_addslashes($this->Param["s_id"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_active"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(16))
					$updateValue.= AddFieldWrappers("s_active")."='".$this->Value["s_active"]."', ";
			else
					$updateValue.= AddFieldWrappers("s_active")."=".$this->Value["s_active"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("s_active"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(16))
					$updateParam.= AddFieldWrappers("s_active")."='".db_addslashes($this->Param["s_active"])."' and ";
			else
					$updateParam.= AddFieldWrappers("s_active")."=".db_addslashes($this->Param["s_active"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_name"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateValue.= AddFieldWrappers("s_name")."='".$this->Value["s_name"]."', ";
			else
					$updateValue.= AddFieldWrappers("s_name")."=".$this->Value["s_name"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("s_name"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateParam.= AddFieldWrappers("s_name")."='".db_addslashes($this->Param["s_name"])."' and ";
			else
					$updateParam.= AddFieldWrappers("s_name")."=".db_addslashes($this->Param["s_name"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_contact"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateValue.= AddFieldWrappers("s_contact")."='".$this->Value["s_contact"]."', ";
			else
					$updateValue.= AddFieldWrappers("s_contact")."=".$this->Value["s_contact"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("s_contact"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateParam.= AddFieldWrappers("s_contact")."='".db_addslashes($this->Param["s_contact"])."' and ";
			else
					$updateParam.= AddFieldWrappers("s_contact")."=".db_addslashes($this->Param["s_contact"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_adr"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateValue.= AddFieldWrappers("s_adr")."='".$this->Value["s_adr"]."', ";
			else
					$updateValue.= AddFieldWrappers("s_adr")."=".$this->Value["s_adr"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("s_adr"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateParam.= AddFieldWrappers("s_adr")."='".db_addslashes($this->Param["s_adr"])."' and ";
			else
					$updateParam.= AddFieldWrappers("s_adr")."=".db_addslashes($this->Param["s_adr"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_zip"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateValue.= AddFieldWrappers("s_zip")."='".$this->Value["s_zip"]."', ";
			else
					$updateValue.= AddFieldWrappers("s_zip")."=".$this->Value["s_zip"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("s_zip"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateParam.= AddFieldWrappers("s_zip")."='".db_addslashes($this->Param["s_zip"])."' and ";
			else
					$updateParam.= AddFieldWrappers("s_zip")."=".db_addslashes($this->Param["s_zip"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_phone1"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateValue.= AddFieldWrappers("s_phone1")."='".$this->Value["s_phone1"]."', ";
			else
					$updateValue.= AddFieldWrappers("s_phone1")."=".$this->Value["s_phone1"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("s_phone1"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateParam.= AddFieldWrappers("s_phone1")."='".db_addslashes($this->Param["s_phone1"])."' and ";
			else
					$updateParam.= AddFieldWrappers("s_phone1")."=".db_addslashes($this->Param["s_phone1"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_phone2"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateValue.= AddFieldWrappers("s_phone2")."='".$this->Value["s_phone2"]."', ";
			else
					$updateValue.= AddFieldWrappers("s_phone2")."=".$this->Value["s_phone2"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("s_phone2"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateParam.= AddFieldWrappers("s_phone2")."='".db_addslashes($this->Param["s_phone2"])."' and ";
			else
					$updateParam.= AddFieldWrappers("s_phone2")."=".db_addslashes($this->Param["s_phone2"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_total"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(14))
					$updateValue.= AddFieldWrappers("s_total")."='".$this->Value["s_total"]."', ";
			else
					$updateValue.= AddFieldWrappers("s_total")."=".$this->Value["s_total"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("s_total"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(14))
					$updateParam.= AddFieldWrappers("s_total")."='".db_addslashes($this->Param["s_total"])."' and ";
			else
					$updateParam.= AddFieldWrappers("s_total")."=".db_addslashes($this->Param["s_total"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_paid"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(14))
					$updateValue.= AddFieldWrappers("s_paid")."='".$this->Value["s_paid"]."', ";
			else
					$updateValue.= AddFieldWrappers("s_paid")."=".$this->Value["s_paid"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("s_paid"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(14))
					$updateParam.= AddFieldWrappers("s_paid")."='".db_addslashes($this->Param["s_paid"])."' and ";
			else
					$updateParam.= AddFieldWrappers("s_paid")."=".db_addslashes($this->Param["s_paid"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_logo"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateValue.= AddFieldWrappers("s_logo")."='".$this->Value["s_logo"]."', ";
			else
					$updateValue.= AddFieldWrappers("s_logo")."=".$this->Value["s_logo"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("s_logo"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateParam.= AddFieldWrappers("s_logo")."='".db_addslashes($this->Param["s_logo"])."' and ";
			else
					$updateParam.= AddFieldWrappers("s_logo")."=".db_addslashes($this->Param["s_logo"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_banner"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateValue.= AddFieldWrappers("s_banner")."='".$this->Value["s_banner"]."', ";
			else
					$updateValue.= AddFieldWrappers("s_banner")."=".$this->Value["s_banner"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("s_banner"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateParam.= AddFieldWrappers("s_banner")."='".db_addslashes($this->Param["s_banner"])."' and ";
			else
					$updateParam.= AddFieldWrappers("s_banner")."=".db_addslashes($this->Param["s_banner"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_www"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateValue.= AddFieldWrappers("s_www")."='".$this->Value["s_www"]."', ";
			else
					$updateValue.= AddFieldWrappers("s_www")."=".$this->Value["s_www"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("s_www"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateParam.= AddFieldWrappers("s_www")."='".db_addslashes($this->Param["s_www"])."' and ";
			else
					$updateParam.= AddFieldWrappers("s_www")."=".db_addslashes($this->Param["s_www"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_mail"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateValue.= AddFieldWrappers("s_mail")."='".$this->Value["s_mail"]."', ";
			else
					$updateValue.= AddFieldWrappers("s_mail")."=".$this->Value["s_mail"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("s_mail"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateParam.= AddFieldWrappers("s_mail")."='".db_addslashes($this->Param["s_mail"])."' and ";
			else
					$updateParam.= AddFieldWrappers("s_mail")."=".db_addslashes($this->Param["s_mail"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_cmt"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(201))
					$updateValue.= AddFieldWrappers("s_cmt")."='".$this->Value["s_cmt"]."', ";
			else
					$updateValue.= AddFieldWrappers("s_cmt")."=".$this->Value["s_cmt"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("s_cmt"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(201))
					$updateParam.= AddFieldWrappers("s_cmt")."='".db_addslashes($this->Param["s_cmt"])."' and ";
			else
					$updateParam.= AddFieldWrappers("s_cmt")."=".db_addslashes($this->Param["s_cmt"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("s_country"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(129))
					$updateValue.= AddFieldWrappers("s_country")."='".$this->Value["s_country"]."', ";
			else
					$updateValue.= AddFieldWrappers("s_country")."=".$this->Value["s_country"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("s_country"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(129))
					$updateParam.= AddFieldWrappers("s_country")."='".db_addslashes($this->Param["s_country"])."' and ";
			else
					$updateParam.= AddFieldWrappers("s_country")."=".db_addslashes($this->Param["s_country"])." and ";
		}
		
	
	if ($updateParam)
		$updateParam = substr($updateParam,0,-5);
	if ($updateValue)
		$updateValue = substr($updateValue,0,-2);
		
	if ($updateValue)
	{
		$dalSQL = "update ".$this->m_GoodTableName." set ".$updateValue." where ".$updateParam;
		db_exec($dalSQL,$conn);
	}
	
        unset($this->Value["s_id"]);
		unset($this->Param["s_id"]);
        unset($this->Value["s_active"]);
		unset($this->Param["s_active"]);
        unset($this->Value["s_name"]);
		unset($this->Param["s_name"]);
        unset($this->Value["s_contact"]);
		unset($this->Param["s_contact"]);
        unset($this->Value["s_adr"]);
		unset($this->Param["s_adr"]);
        unset($this->Value["s_zip"]);
		unset($this->Param["s_zip"]);
        unset($this->Value["s_phone1"]);
		unset($this->Param["s_phone1"]);
        unset($this->Value["s_phone2"]);
		unset($this->Param["s_phone2"]);
        unset($this->Value["s_total"]);
		unset($this->Param["s_total"]);
        unset($this->Value["s_paid"]);
		unset($this->Param["s_paid"]);
        unset($this->Value["s_logo"]);
		unset($this->Param["s_logo"]);
        unset($this->Value["s_banner"]);
		unset($this->Param["s_banner"]);
        unset($this->Value["s_www"]);
		unset($this->Param["s_www"]);
        unset($this->Value["s_mail"]);
		unset($this->Param["s_mail"]);
        unset($this->Value["s_cmt"]);
		unset($this->Param["s_cmt"]);
        unset($this->Value["s_country"]);
		unset($this->Param["s_country"]);
	
}

function QueryAll()
{
	global $conn;
	$dal_variables="";
		$dal_variables.= AddFieldWrappers("s_id").", ";
		$dal_variables.= AddFieldWrappers("s_active").", ";
		$dal_variables.= AddFieldWrappers("s_name").", ";
		$dal_variables.= AddFieldWrappers("s_contact").", ";
		$dal_variables.= AddFieldWrappers("s_adr").", ";
		$dal_variables.= AddFieldWrappers("s_zip").", ";
		$dal_variables.= AddFieldWrappers("s_phone1").", ";
		$dal_variables.= AddFieldWrappers("s_phone2").", ";
		$dal_variables.= AddFieldWrappers("s_total").", ";
		$dal_variables.= AddFieldWrappers("s_paid").", ";
		$dal_variables.= AddFieldWrappers("s_logo").", ";
		$dal_variables.= AddFieldWrappers("s_banner").", ";
		$dal_variables.= AddFieldWrappers("s_www").", ";
		$dal_variables.= AddFieldWrappers("s_mail").", ";
		$dal_variables.= AddFieldWrappers("s_cmt").", ";
		$dal_variables.= AddFieldWrappers("s_country").", ";
	$dal_variables = substr($dal_variables,0,-2);
	$dalSQL = "select ".$dal_variables." from ".$this->m_GoodTableName;
	$rs = db_query($dalSQL,$conn);
	$data = db_fetch_array($rs);
	return $data;
}

function Query($swhere,$orderby)
{
	global $conn;
	$dal_variables="";
		$dal_variables.= AddFieldWrappers("s_id").", ";
		$dal_variables.= AddFieldWrappers("s_active").", ";
		$dal_variables.= AddFieldWrappers("s_name").", ";
		$dal_variables.= AddFieldWrappers("s_contact").", ";
		$dal_variables.= AddFieldWrappers("s_adr").", ";
		$dal_variables.= AddFieldWrappers("s_zip").", ";
		$dal_variables.= AddFieldWrappers("s_phone1").", ";
		$dal_variables.= AddFieldWrappers("s_phone2").", ";
		$dal_variables.= AddFieldWrappers("s_total").", ";
		$dal_variables.= AddFieldWrappers("s_paid").", ";
		$dal_variables.= AddFieldWrappers("s_logo").", ";
		$dal_variables.= AddFieldWrappers("s_banner").", ";
		$dal_variables.= AddFieldWrappers("s_www").", ";
		$dal_variables.= AddFieldWrappers("s_mail").", ";
		$dal_variables.= AddFieldWrappers("s_cmt").", ";
		$dal_variables.= AddFieldWrappers("s_country").", ";
	$dal_variables = substr($dal_variables,0,-2);
	if ($swhere)
		$swhere = " where ".$swhere;
	if ($orderby)
		$orderby = " order by ".$orderby;
	
	$dalSQL = "select ".$dal_variables." from ".$this->m_GoodTableName.$swhere.$orderby;
	$rs = db_query($dalSQL,$conn);
	$data = db_fetch_array($rs);
	return $data; 	
}

function FetchByID()
{
	global $conn;
	$dal_variables="";
	$dal_where="";
        $dal_variables.= AddFieldWrappers("s_id").", ";
        $dal_variables.= AddFieldWrappers("s_active").", ";
        $dal_variables.= AddFieldWrappers("s_name").", ";
        $dal_variables.= AddFieldWrappers("s_contact").", ";
        $dal_variables.= AddFieldWrappers("s_adr").", ";
        $dal_variables.= AddFieldWrappers("s_zip").", ";
        $dal_variables.= AddFieldWrappers("s_phone1").", ";
        $dal_variables.= AddFieldWrappers("s_phone2").", ";
        $dal_variables.= AddFieldWrappers("s_total").", ";
        $dal_variables.= AddFieldWrappers("s_paid").", ";
        $dal_variables.= AddFieldWrappers("s_logo").", ";
        $dal_variables.= AddFieldWrappers("s_banner").", ";
        $dal_variables.= AddFieldWrappers("s_www").", ";
        $dal_variables.= AddFieldWrappers("s_mail").", ";
        $dal_variables.= AddFieldWrappers("s_cmt").", ";
        $dal_variables.= AddFieldWrappers("s_country").", ";
	$dal_variables = substr($dal_variables,0,-2);
	
		if ($this->s_id)
			$this->Param["s_id"] = $this->s_id;	
		if ($this->s_active)
			$this->Param["s_active"] = $this->s_active;	
		if ($this->s_name)
			$this->Param["s_name"] = $this->s_name;	
		if ($this->s_contact)
			$this->Param["s_contact"] = $this->s_contact;	
		if ($this->s_adr)
			$this->Param["s_adr"] = $this->s_adr;	
		if ($this->s_zip)
			$this->Param["s_zip"] = $this->s_zip;	
		if ($this->s_phone1)
			$this->Param["s_phone1"] = $this->s_phone1;	
		if ($this->s_phone2)
			$this->Param["s_phone2"] = $this->s_phone2;	
		if ($this->s_total)
			$this->Param["s_total"] = $this->s_total;	
		if ($this->s_paid)
			$this->Param["s_paid"] = $this->s_paid;	
		if ($this->s_logo)
			$this->Param["s_logo"] = $this->s_logo;	
		if ($this->s_banner)
			$this->Param["s_banner"] = $this->s_banner;	
		if ($this->s_www)
			$this->Param["s_www"] = $this->s_www;	
		if ($this->s_mail)
			$this->Param["s_mail"] = $this->s_mail;	
		if ($this->s_cmt)
			$this->Param["s_cmt"] = $this->s_cmt;	
		if ($this->s_country)
			$this->Param["s_country"] = $this->s_country;	
	
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("s_id") && 1==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(3))
				$dal_where.= AddFieldWrappers("s_id")."='".db_addslashes($this->Param["s_id"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("s_id")."=".db_addslashes($this->Param["s_id"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("s_active") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(16))
				$dal_where.= AddFieldWrappers("s_active")."='".db_addslashes($this->Param["s_active"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("s_active")."=".db_addslashes($this->Param["s_active"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("s_name") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(200))
				$dal_where.= AddFieldWrappers("s_name")."='".db_addslashes($this->Param["s_name"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("s_name")."=".db_addslashes($this->Param["s_name"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("s_contact") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(200))
				$dal_where.= AddFieldWrappers("s_contact")."='".db_addslashes($this->Param["s_contact"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("s_contact")."=".db_addslashes($this->Param["s_contact"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("s_adr") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(200))
				$dal_where.= AddFieldWrappers("s_adr")."='".db_addslashes($this->Param["s_adr"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("s_adr")."=".db_addslashes($this->Param["s_adr"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("s_zip") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(3))
				$dal_where.= AddFieldWrappers("s_zip")."='".db_addslashes($this->Param["s_zip"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("s_zip")."=".db_addslashes($this->Param["s_zip"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("s_phone1") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(200))
				$dal_where.= AddFieldWrappers("s_phone1")."='".db_addslashes($this->Param["s_phone1"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("s_phone1")."=".db_addslashes($this->Param["s_phone1"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("s_phone2") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(200))
				$dal_where.= AddFieldWrappers("s_phone2")."='".db_addslashes($this->Param["s_phone2"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("s_phone2")."=".db_addslashes($this->Param["s_phone2"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("s_total") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(14))
				$dal_where.= AddFieldWrappers("s_total")."='".db_addslashes($this->Param["s_total"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("s_total")."=".db_addslashes($this->Param["s_total"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("s_paid") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(14))
				$dal_where.= AddFieldWrappers("s_paid")."='".db_addslashes($this->Param["s_paid"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("s_paid")."=".db_addslashes($this->Param["s_paid"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("s_logo") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(200))
				$dal_where.= AddFieldWrappers("s_logo")."='".db_addslashes($this->Param["s_logo"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("s_logo")."=".db_addslashes($this->Param["s_logo"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("s_banner") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(200))
				$dal_where.= AddFieldWrappers("s_banner")."='".db_addslashes($this->Param["s_banner"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("s_banner")."=".db_addslashes($this->Param["s_banner"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("s_www") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(200))
				$dal_where.= AddFieldWrappers("s_www")."='".db_addslashes($this->Param["s_www"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("s_www")."=".db_addslashes($this->Param["s_www"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("s_mail") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(200))
				$dal_where.= AddFieldWrappers("s_mail")."='".db_addslashes($this->Param["s_mail"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("s_mail")."=".db_addslashes($this->Param["s_mail"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("s_cmt") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(201))
				$dal_where.= AddFieldWrappers("s_cmt")."='".db_addslashes($this->Param["s_cmt"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("s_cmt")."=".db_addslashes($this->Param["s_cmt"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("s_country") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(129))
				$dal_where.= AddFieldWrappers("s_country")."='".db_addslashes($this->Param["s_country"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("s_country")."=".db_addslashes($this->Param["s_country"]) . " and ";
	
	if ($dal_where)
		$dal_where = " where ".substr($dal_where,0,-5);
	
	$dalSQL = "select ".$dal_variables." from ".$this->m_GoodTableName.$dal_where;
	$rs = db_query($dalSQL,$conn);
	$data = db_fetch_array($rs);
	return $data;
}
	
function Reset()
{
        unset($this->m_s_id);
		unset($this->Value["s_id"]);
		unset($this->Param["s_id"]);
        unset($this->m_s_active);
		unset($this->Value["s_active"]);
		unset($this->Param["s_active"]);
        unset($this->m_s_name);
		unset($this->Value["s_name"]);
		unset($this->Param["s_name"]);
        unset($this->m_s_contact);
		unset($this->Value["s_contact"]);
		unset($this->Param["s_contact"]);
        unset($this->m_s_adr);
		unset($this->Value["s_adr"]);
		unset($this->Param["s_adr"]);
        unset($this->m_s_zip);
		unset($this->Value["s_zip"]);
		unset($this->Param["s_zip"]);
        unset($this->m_s_phone1);
		unset($this->Value["s_phone1"]);
		unset($this->Param["s_phone1"]);
        unset($this->m_s_phone2);
		unset($this->Value["s_phone2"]);
		unset($this->Param["s_phone2"]);
        unset($this->m_s_total);
		unset($this->Value["s_total"]);
		unset($this->Param["s_total"]);
        unset($this->m_s_paid);
		unset($this->Value["s_paid"]);
		unset($this->Param["s_paid"]);
        unset($this->m_s_logo);
		unset($this->Value["s_logo"]);
		unset($this->Param["s_logo"]);
        unset($this->m_s_banner);
		unset($this->Value["s_banner"]);
		unset($this->Param["s_banner"]);
        unset($this->m_s_www);
		unset($this->Value["s_www"]);
		unset($this->Param["s_www"]);
        unset($this->m_s_mail);
		unset($this->Value["s_mail"]);
		unset($this->Param["s_mail"]);
        unset($this->m_s_cmt);
		unset($this->Value["s_cmt"]);
		unset($this->Param["s_cmt"]);
        unset($this->m_s_country);
		unset($this->Value["s_country"]);
		unset($this->Param["s_country"]);
}	
	
}//end of class


$dal->sponsor = new class_sponsor();


class class_top
{
	var $m_TableName;
	var $m_GoodTableName;
	var $m_t_id;
	var $m_t_user;
	var $m_t_score;
	var $m_t_datetime;
	var $m_t_ip;
	var $m_t_p_id;
	var $m_t_ts_id;
	var $m_t_kills;

	var $Param = array();
	var $Value = array();
	
	var $t_id = array();
	var $t_user = array();
	var $t_score = array();
	var $t_datetime = array();
	var $t_ip = array();
	var $t_p_id = array();
	var $t_ts_id = array();
	var $t_kills = array();
	
	var $m_ChangedFields;
	var $m_UpdateParam;
	var $m_UpdateValue;

	
	function class_top()
	{
		$this->m_TableName = "top";
		$this->m_GoodTableName = AddTableWrappers($this->m_TableName);	
	}

function TableName()
{
	$this->m_TableName = "top";
	$this->m_GoodTableName = AddTableWrappers($this->m_TableName);
	return $this->m_GoodTableName;
} 

function Add() 
{
	global $conn;

	$insertFields="";
	$insertValues="";
		

		if ($this->t_id)
		{
			$this->Value["t_id"] = $this->t_id;
		    $this->m_t_id = $this->t_id;
		}	

		if ($this->t_user)
		{
			$this->Value["t_user"] = $this->t_user;
		    $this->m_t_user = $this->t_user;
		}	

		if ($this->t_score)
		{
			$this->Value["t_score"] = $this->t_score;
		    $this->m_t_score = $this->t_score;
		}	

		if ($this->t_datetime)
		{
			$this->Value["t_datetime"] = $this->t_datetime;
		    $this->m_t_datetime = $this->t_datetime;
		}	

		if ($this->t_ip)
		{
			$this->Value["t_ip"] = $this->t_ip;
		    $this->m_t_ip = $this->t_ip;
		}	

		if ($this->t_p_id)
		{
			$this->Value["t_p_id"] = $this->t_p_id;
		    $this->m_t_p_id = $this->t_p_id;
		}	

		if ($this->t_ts_id)
		{
			$this->Value["t_ts_id"] = $this->t_ts_id;
		    $this->m_t_ts_id = $this->t_ts_id;
		}	

		if ($this->t_kills)
		{
			$this->Value["t_kills"] = $this->t_kills;
		    $this->m_t_kills = $this->t_kills;
		}	
	
		if ($this->Value["t_id"])
		    $this->m_t_id = $this->Value["t_id"];	
		if ($this->Value["t_user"])
		    $this->m_t_user = $this->Value["t_user"];	
		if ($this->Value["t_score"])
		    $this->m_t_score = $this->Value["t_score"];	
		if ($this->Value["t_datetime"])
		    $this->m_t_datetime = $this->Value["t_datetime"];	
		if ($this->Value["t_ip"])
		    $this->m_t_ip = $this->Value["t_ip"];	
		if ($this->Value["t_p_id"])
		    $this->m_t_p_id = $this->Value["t_p_id"];	
		if ($this->Value["t_ts_id"])
		    $this->m_t_ts_id = $this->Value["t_ts_id"];	
		if ($this->Value["t_kills"])
		    $this->m_t_kills = $this->Value["t_kills"];	

		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("t_id"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("t_id").",";
			if (NeedQuotes(3))
				$insertValues.= "'".db_addslashes($this->Value["t_id"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["t_id"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("t_user"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("t_user").",";
			if (NeedQuotes(200))
				$insertValues.= "'".db_addslashes($this->Value["t_user"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["t_user"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("t_score"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("t_score").",";
			if (NeedQuotes(3))
				$insertValues.= "'".db_addslashes($this->Value["t_score"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["t_score"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("t_datetime"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("t_datetime").",";
			if (NeedQuotes(135))
				$insertValues.= "'".db_addslashes($this->Value["t_datetime"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["t_datetime"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("t_ip"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("t_ip").",";
			if (NeedQuotes(200))
				$insertValues.= "'".db_addslashes($this->Value["t_ip"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["t_ip"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("t_p_id"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("t_p_id").",";
			if (NeedQuotes(3))
				$insertValues.= "'".db_addslashes($this->Value["t_p_id"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["t_p_id"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("t_ts_id"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("t_ts_id").",";
			if (NeedQuotes(3))
				$insertValues.= "'".db_addslashes($this->Value["t_ts_id"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["t_ts_id"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("t_kills"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("t_kills").",";
			if (NeedQuotes(3))
				$insertValues.= "'".db_addslashes($this->Value["t_kills"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["t_kills"]) . ",";		

		}

		
	if ($insertFields!="" && $insertValues!="")		
	{
		$insertFields = substr($insertFields,0,-1);
		$insertValues = substr($insertValues,0,-1);
		$dalSQL = "insert into ".$this->m_GoodTableName." (".$insertFields.") values (".$insertValues.")";
		db_exec($dalSQL,$conn);
	}
	
        unset($this->Value["t_id"]);
        unset($this->Value["t_user"]);
        unset($this->Value["t_score"]);
        unset($this->Value["t_datetime"]);
        unset($this->Value["t_ip"]);
        unset($this->Value["t_p_id"]);
        unset($this->Value["t_ts_id"]);
        unset($this->Value["t_kills"]);
   // unset($this->m_ChangedFields);

	}
	
function Delete()
{
	global $conn;
	$deleteFields="";

		if ($this->t_id)
		{
			$this->Value["t_id"] = $this->t_id;
			$this->m_t_id = $this->t_id;
		}	
		if ($this->t_user)
		{
			$this->Value["t_user"] = $this->t_user;
			$this->m_t_user = $this->t_user;
		}	
		if ($this->t_score)
		{
			$this->Value["t_score"] = $this->t_score;
			$this->m_t_score = $this->t_score;
		}	
		if ($this->t_datetime)
		{
			$this->Value["t_datetime"] = $this->t_datetime;
			$this->m_t_datetime = $this->t_datetime;
		}	
		if ($this->t_ip)
		{
			$this->Value["t_ip"] = $this->t_ip;
			$this->m_t_ip = $this->t_ip;
		}	
		if ($this->t_p_id)
		{
			$this->Value["t_p_id"] = $this->t_p_id;
			$this->m_t_p_id = $this->t_p_id;
		}	
		if ($this->t_ts_id)
		{
			$this->Value["t_ts_id"] = $this->t_ts_id;
			$this->m_t_ts_id = $this->t_ts_id;
		}	
		if ($this->t_kills)
		{
			$this->Value["t_kills"] = $this->t_kills;
			$this->m_t_kills = $this->t_kills;
		}	
	
		if ($this->Value["t_id"])
		    $this->m_t_id = $this->Value["t_id"];	
		if ($this->Value["t_user"])
		    $this->m_t_user = $this->Value["t_user"];	
		if ($this->Value["t_score"])
		    $this->m_t_score = $this->Value["t_score"];	
		if ($this->Value["t_datetime"])
		    $this->m_t_datetime = $this->Value["t_datetime"];	
		if ($this->Value["t_ip"])
		    $this->m_t_ip = $this->Value["t_ip"];	
		if ($this->Value["t_p_id"])
		    $this->m_t_p_id = $this->Value["t_p_id"];	
		if ($this->Value["t_ts_id"])
		    $this->m_t_ts_id = $this->Value["t_ts_id"];	
		if ($this->Value["t_kills"])
		    $this->m_t_kills = $this->Value["t_kills"];	

		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("t_id"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("t_id").",";
			if (NeedQuotes(3))
				$deleteFields.= AddFieldWrappers("t_id")."='".db_addslashes($this->Value["t_id"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("t_id")."=".db_addslashes($this->Value["t_id"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("t_user"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("t_user").",";
			if (NeedQuotes(200))
				$deleteFields.= AddFieldWrappers("t_user")."='".db_addslashes($this->Value["t_user"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("t_user")."=".db_addslashes($this->Value["t_user"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("t_score"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("t_score").",";
			if (NeedQuotes(3))
				$deleteFields.= AddFieldWrappers("t_score")."='".db_addslashes($this->Value["t_score"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("t_score")."=".db_addslashes($this->Value["t_score"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("t_datetime"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("t_datetime").",";
			if (NeedQuotes(135))
				$deleteFields.= AddFieldWrappers("t_datetime")."='".db_addslashes($this->Value["t_datetime"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("t_datetime")."=".db_addslashes($this->Value["t_datetime"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("t_ip"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("t_ip").",";
			if (NeedQuotes(200))
				$deleteFields.= AddFieldWrappers("t_ip")."='".db_addslashes($this->Value["t_ip"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("t_ip")."=".db_addslashes($this->Value["t_ip"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("t_p_id"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("t_p_id").",";
			if (NeedQuotes(3))
				$deleteFields.= AddFieldWrappers("t_p_id")."='".db_addslashes($this->Value["t_p_id"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("t_p_id")."=".db_addslashes($this->Value["t_p_id"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("t_ts_id"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("t_ts_id").",";
			if (NeedQuotes(3))
				$deleteFields.= AddFieldWrappers("t_ts_id")."='".db_addslashes($this->Value["t_ts_id"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("t_ts_id")."=".db_addslashes($this->Value["t_ts_id"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("t_kills"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("t_kills").",";
			if (NeedQuotes(3))
				$deleteFields.= AddFieldWrappers("t_kills")."='".db_addslashes($this->Value["t_kills"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("t_kills")."=".db_addslashes($this->Value["t_kills"]) . " and ";		
		}

	if ($deleteFields)
	{
		$deleteFields = substr($deleteFields,0,-5);
		$dalSQL = "delete from ".$this->m_GoodTableName." where ".$deleteFields;
		db_exec($dalSQL,$conn);
	}
	
	    unset($this->Value["t_id"]);
	    unset($this->Value["t_user"]);
	    unset($this->Value["t_score"]);
	    unset($this->Value["t_datetime"]);
	    unset($this->Value["t_ip"]);
	    unset($this->Value["t_p_id"]);
	    unset($this->Value["t_ts_id"]);
	    unset($this->Value["t_kills"]);
		
	//unset($this->Value);	
}

function Update()
{
	global $conn;
	
	$updateParam = "";
	$updateValue = "";
	
		if ($this->t_id)
		{
			if (1==1)
				$this->Param["t_id"] = $this->t_id;
			else
				$this->Value["t_id"] = $this->t_id;
			$this->m_t_id = $this->t_id;
		}	
		if ($this->t_user)
		{
			if (0==1)
				$this->Param["t_user"] = $this->t_user;
			else
				$this->Value["t_user"] = $this->t_user;
			$this->m_t_user = $this->t_user;
		}	
		if ($this->t_score)
		{
			if (0==1)
				$this->Param["t_score"] = $this->t_score;
			else
				$this->Value["t_score"] = $this->t_score;
			$this->m_t_score = $this->t_score;
		}	
		if ($this->t_datetime)
		{
			if (0==1)
				$this->Param["t_datetime"] = $this->t_datetime;
			else
				$this->Value["t_datetime"] = $this->t_datetime;
			$this->m_t_datetime = $this->t_datetime;
		}	
		if ($this->t_ip)
		{
			if (0==1)
				$this->Param["t_ip"] = $this->t_ip;
			else
				$this->Value["t_ip"] = $this->t_ip;
			$this->m_t_ip = $this->t_ip;
		}	
		if ($this->t_p_id)
		{
			if (0==1)
				$this->Param["t_p_id"] = $this->t_p_id;
			else
				$this->Value["t_p_id"] = $this->t_p_id;
			$this->m_t_p_id = $this->t_p_id;
		}	
		if ($this->t_ts_id)
		{
			if (0==1)
				$this->Param["t_ts_id"] = $this->t_ts_id;
			else
				$this->Value["t_ts_id"] = $this->t_ts_id;
			$this->m_t_ts_id = $this->t_ts_id;
		}	
		if ($this->t_kills)
		{
			if (0==1)
				$this->Param["t_kills"] = $this->t_kills;
			else
				$this->Value["t_kills"] = $this->t_kills;
			$this->m_t_kills = $this->t_kills;
		}	
	
		if ($this->Value["t_id"])
		    $this->m_t_id = $this->Value["t_id"];		
		if ($this->Value["t_user"])
		    $this->m_t_user = $this->Value["t_user"];		
		if ($this->Value["t_score"])
		    $this->m_t_score = $this->Value["t_score"];		
		if ($this->Value["t_datetime"])
		    $this->m_t_datetime = $this->Value["t_datetime"];		
		if ($this->Value["t_ip"])
		    $this->m_t_ip = $this->Value["t_ip"];		
		if ($this->Value["t_p_id"])
		    $this->m_t_p_id = $this->Value["t_p_id"];		
		if ($this->Value["t_ts_id"])
		    $this->m_t_ts_id = $this->Value["t_ts_id"];		
		if ($this->Value["t_kills"])
		    $this->m_t_kills = $this->Value["t_kills"];		
	
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("t_id"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateValue.= AddFieldWrappers("t_id")."='".$this->Value["t_id"]."', ";
			else
					$updateValue.= AddFieldWrappers("t_id")."=".$this->Value["t_id"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("t_id"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateParam.= AddFieldWrappers("t_id")."='".db_addslashes($this->Param["t_id"])."' and ";
			else
					$updateParam.= AddFieldWrappers("t_id")."=".db_addslashes($this->Param["t_id"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("t_user"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateValue.= AddFieldWrappers("t_user")."='".$this->Value["t_user"]."', ";
			else
					$updateValue.= AddFieldWrappers("t_user")."=".$this->Value["t_user"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("t_user"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateParam.= AddFieldWrappers("t_user")."='".db_addslashes($this->Param["t_user"])."' and ";
			else
					$updateParam.= AddFieldWrappers("t_user")."=".db_addslashes($this->Param["t_user"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("t_score"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateValue.= AddFieldWrappers("t_score")."='".$this->Value["t_score"]."', ";
			else
					$updateValue.= AddFieldWrappers("t_score")."=".$this->Value["t_score"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("t_score"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateParam.= AddFieldWrappers("t_score")."='".db_addslashes($this->Param["t_score"])."' and ";
			else
					$updateParam.= AddFieldWrappers("t_score")."=".db_addslashes($this->Param["t_score"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("t_datetime"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(135))
					$updateValue.= AddFieldWrappers("t_datetime")."='".$this->Value["t_datetime"]."', ";
			else
					$updateValue.= AddFieldWrappers("t_datetime")."=".$this->Value["t_datetime"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("t_datetime"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(135))
					$updateParam.= AddFieldWrappers("t_datetime")."='".db_addslashes($this->Param["t_datetime"])."' and ";
			else
					$updateParam.= AddFieldWrappers("t_datetime")."=".db_addslashes($this->Param["t_datetime"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("t_ip"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateValue.= AddFieldWrappers("t_ip")."='".$this->Value["t_ip"]."', ";
			else
					$updateValue.= AddFieldWrappers("t_ip")."=".$this->Value["t_ip"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("t_ip"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateParam.= AddFieldWrappers("t_ip")."='".db_addslashes($this->Param["t_ip"])."' and ";
			else
					$updateParam.= AddFieldWrappers("t_ip")."=".db_addslashes($this->Param["t_ip"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("t_p_id"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateValue.= AddFieldWrappers("t_p_id")."='".$this->Value["t_p_id"]."', ";
			else
					$updateValue.= AddFieldWrappers("t_p_id")."=".$this->Value["t_p_id"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("t_p_id"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateParam.= AddFieldWrappers("t_p_id")."='".db_addslashes($this->Param["t_p_id"])."' and ";
			else
					$updateParam.= AddFieldWrappers("t_p_id")."=".db_addslashes($this->Param["t_p_id"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("t_ts_id"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateValue.= AddFieldWrappers("t_ts_id")."='".$this->Value["t_ts_id"]."', ";
			else
					$updateValue.= AddFieldWrappers("t_ts_id")."=".$this->Value["t_ts_id"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("t_ts_id"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateParam.= AddFieldWrappers("t_ts_id")."='".db_addslashes($this->Param["t_ts_id"])."' and ";
			else
					$updateParam.= AddFieldWrappers("t_ts_id")."=".db_addslashes($this->Param["t_ts_id"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("t_kills"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateValue.= AddFieldWrappers("t_kills")."='".$this->Value["t_kills"]."', ";
			else
					$updateValue.= AddFieldWrappers("t_kills")."=".$this->Value["t_kills"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("t_kills"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateParam.= AddFieldWrappers("t_kills")."='".db_addslashes($this->Param["t_kills"])."' and ";
			else
					$updateParam.= AddFieldWrappers("t_kills")."=".db_addslashes($this->Param["t_kills"])." and ";
		}
		
	
	if ($updateParam)
		$updateParam = substr($updateParam,0,-5);
	if ($updateValue)
		$updateValue = substr($updateValue,0,-2);
		
	if ($updateValue)
	{
		$dalSQL = "update ".$this->m_GoodTableName." set ".$updateValue." where ".$updateParam;
		db_exec($dalSQL,$conn);
	}
	
        unset($this->Value["t_id"]);
		unset($this->Param["t_id"]);
        unset($this->Value["t_user"]);
		unset($this->Param["t_user"]);
        unset($this->Value["t_score"]);
		unset($this->Param["t_score"]);
        unset($this->Value["t_datetime"]);
		unset($this->Param["t_datetime"]);
        unset($this->Value["t_ip"]);
		unset($this->Param["t_ip"]);
        unset($this->Value["t_p_id"]);
		unset($this->Param["t_p_id"]);
        unset($this->Value["t_ts_id"]);
		unset($this->Param["t_ts_id"]);
        unset($this->Value["t_kills"]);
		unset($this->Param["t_kills"]);
	
}

function QueryAll()
{
	global $conn;
	$dal_variables="";
		$dal_variables.= AddFieldWrappers("t_id").", ";
		$dal_variables.= AddFieldWrappers("t_user").", ";
		$dal_variables.= AddFieldWrappers("t_score").", ";
		$dal_variables.= AddFieldWrappers("t_datetime").", ";
		$dal_variables.= AddFieldWrappers("t_ip").", ";
		$dal_variables.= AddFieldWrappers("t_p_id").", ";
		$dal_variables.= AddFieldWrappers("t_ts_id").", ";
		$dal_variables.= AddFieldWrappers("t_kills").", ";
	$dal_variables = substr($dal_variables,0,-2);
	$dalSQL = "select ".$dal_variables." from ".$this->m_GoodTableName;
	$rs = db_query($dalSQL,$conn);
	$data = db_fetch_array($rs);
	return $data;
}

function Query($swhere,$orderby)
{
	global $conn;
	$dal_variables="";
		$dal_variables.= AddFieldWrappers("t_id").", ";
		$dal_variables.= AddFieldWrappers("t_user").", ";
		$dal_variables.= AddFieldWrappers("t_score").", ";
		$dal_variables.= AddFieldWrappers("t_datetime").", ";
		$dal_variables.= AddFieldWrappers("t_ip").", ";
		$dal_variables.= AddFieldWrappers("t_p_id").", ";
		$dal_variables.= AddFieldWrappers("t_ts_id").", ";
		$dal_variables.= AddFieldWrappers("t_kills").", ";
	$dal_variables = substr($dal_variables,0,-2);
	if ($swhere)
		$swhere = " where ".$swhere;
	if ($orderby)
		$orderby = " order by ".$orderby;
	
	$dalSQL = "select ".$dal_variables." from ".$this->m_GoodTableName.$swhere.$orderby;
	$rs = db_query($dalSQL,$conn);
	$data = db_fetch_array($rs);
	return $data; 	
}

function FetchByID()
{
	global $conn;
	$dal_variables="";
	$dal_where="";
        $dal_variables.= AddFieldWrappers("t_id").", ";
        $dal_variables.= AddFieldWrappers("t_user").", ";
        $dal_variables.= AddFieldWrappers("t_score").", ";
        $dal_variables.= AddFieldWrappers("t_datetime").", ";
        $dal_variables.= AddFieldWrappers("t_ip").", ";
        $dal_variables.= AddFieldWrappers("t_p_id").", ";
        $dal_variables.= AddFieldWrappers("t_ts_id").", ";
        $dal_variables.= AddFieldWrappers("t_kills").", ";
	$dal_variables = substr($dal_variables,0,-2);
	
		if ($this->t_id)
			$this->Param["t_id"] = $this->t_id;	
		if ($this->t_user)
			$this->Param["t_user"] = $this->t_user;	
		if ($this->t_score)
			$this->Param["t_score"] = $this->t_score;	
		if ($this->t_datetime)
			$this->Param["t_datetime"] = $this->t_datetime;	
		if ($this->t_ip)
			$this->Param["t_ip"] = $this->t_ip;	
		if ($this->t_p_id)
			$this->Param["t_p_id"] = $this->t_p_id;	
		if ($this->t_ts_id)
			$this->Param["t_ts_id"] = $this->t_ts_id;	
		if ($this->t_kills)
			$this->Param["t_kills"] = $this->t_kills;	
	
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("t_id") && 1==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(3))
				$dal_where.= AddFieldWrappers("t_id")."='".db_addslashes($this->Param["t_id"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("t_id")."=".db_addslashes($this->Param["t_id"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("t_user") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(200))
				$dal_where.= AddFieldWrappers("t_user")."='".db_addslashes($this->Param["t_user"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("t_user")."=".db_addslashes($this->Param["t_user"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("t_score") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(3))
				$dal_where.= AddFieldWrappers("t_score")."='".db_addslashes($this->Param["t_score"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("t_score")."=".db_addslashes($this->Param["t_score"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("t_datetime") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(135))
				$dal_where.= AddFieldWrappers("t_datetime")."='".db_addslashes($this->Param["t_datetime"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("t_datetime")."=".db_addslashes($this->Param["t_datetime"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("t_ip") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(200))
				$dal_where.= AddFieldWrappers("t_ip")."='".db_addslashes($this->Param["t_ip"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("t_ip")."=".db_addslashes($this->Param["t_ip"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("t_p_id") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(3))
				$dal_where.= AddFieldWrappers("t_p_id")."='".db_addslashes($this->Param["t_p_id"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("t_p_id")."=".db_addslashes($this->Param["t_p_id"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("t_ts_id") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(3))
				$dal_where.= AddFieldWrappers("t_ts_id")."='".db_addslashes($this->Param["t_ts_id"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("t_ts_id")."=".db_addslashes($this->Param["t_ts_id"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("t_kills") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(3))
				$dal_where.= AddFieldWrappers("t_kills")."='".db_addslashes($this->Param["t_kills"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("t_kills")."=".db_addslashes($this->Param["t_kills"]) . " and ";
	
	if ($dal_where)
		$dal_where = " where ".substr($dal_where,0,-5);
	
	$dalSQL = "select ".$dal_variables." from ".$this->m_GoodTableName.$dal_where;
	$rs = db_query($dalSQL,$conn);
	$data = db_fetch_array($rs);
	return $data;
}
	
function Reset()
{
        unset($this->m_t_id);
		unset($this->Value["t_id"]);
		unset($this->Param["t_id"]);
        unset($this->m_t_user);
		unset($this->Value["t_user"]);
		unset($this->Param["t_user"]);
        unset($this->m_t_score);
		unset($this->Value["t_score"]);
		unset($this->Param["t_score"]);
        unset($this->m_t_datetime);
		unset($this->Value["t_datetime"]);
		unset($this->Param["t_datetime"]);
        unset($this->m_t_ip);
		unset($this->Value["t_ip"]);
		unset($this->Param["t_ip"]);
        unset($this->m_t_p_id);
		unset($this->Value["t_p_id"]);
		unset($this->Param["t_p_id"]);
        unset($this->m_t_ts_id);
		unset($this->Value["t_ts_id"]);
		unset($this->Param["t_ts_id"]);
        unset($this->m_t_kills);
		unset($this->Value["t_kills"]);
		unset($this->Param["t_kills"]);
}	
	
}//end of class


$dal->top = new class_top();


class class_teams
{
	var $m_TableName;
	var $m_GoodTableName;
	var $m_ts_id;
	var $m_ts_name;
	var $m_ts_p0;
	var $m_ts_p1;
	var $m_ts_p2;
	var $m_ts_p3;
	var $m_ts_p4;
	var $m_ts_p5;
	var $m_ts_p6;
	var $m_ts_p7;
	var $m_ts_p8;
	var $m_ts_p9;
	var $m_ts_s_id;
	var $m_ts_Score;

	var $Param = array();
	var $Value = array();
	
	var $ts_id = array();
	var $ts_name = array();
	var $ts_p0 = array();
	var $ts_p1 = array();
	var $ts_p2 = array();
	var $ts_p3 = array();
	var $ts_p4 = array();
	var $ts_p5 = array();
	var $ts_p6 = array();
	var $ts_p7 = array();
	var $ts_p8 = array();
	var $ts_p9 = array();
	var $ts_s_id = array();
	var $ts_Score = array();
	
	var $m_ChangedFields;
	var $m_UpdateParam;
	var $m_UpdateValue;

	
	function class_teams()
	{
		$this->m_TableName = "teams";
		$this->m_GoodTableName = AddTableWrappers($this->m_TableName);	
	}

function TableName()
{
	$this->m_TableName = "teams";
	$this->m_GoodTableName = AddTableWrappers($this->m_TableName);
	return $this->m_GoodTableName;
} 

function Add() 
{
	global $conn;

	$insertFields="";
	$insertValues="";
		

		if ($this->ts_id)
		{
			$this->Value["ts_id"] = $this->ts_id;
		    $this->m_ts_id = $this->ts_id;
		}	

		if ($this->ts_name)
		{
			$this->Value["ts_name"] = $this->ts_name;
		    $this->m_ts_name = $this->ts_name;
		}	

		if ($this->ts_p0)
		{
			$this->Value["ts_p0"] = $this->ts_p0;
		    $this->m_ts_p0 = $this->ts_p0;
		}	

		if ($this->ts_p1)
		{
			$this->Value["ts_p1"] = $this->ts_p1;
		    $this->m_ts_p1 = $this->ts_p1;
		}	

		if ($this->ts_p2)
		{
			$this->Value["ts_p2"] = $this->ts_p2;
		    $this->m_ts_p2 = $this->ts_p2;
		}	

		if ($this->ts_p3)
		{
			$this->Value["ts_p3"] = $this->ts_p3;
		    $this->m_ts_p3 = $this->ts_p3;
		}	

		if ($this->ts_p4)
		{
			$this->Value["ts_p4"] = $this->ts_p4;
		    $this->m_ts_p4 = $this->ts_p4;
		}	

		if ($this->ts_p5)
		{
			$this->Value["ts_p5"] = $this->ts_p5;
		    $this->m_ts_p5 = $this->ts_p5;
		}	

		if ($this->ts_p6)
		{
			$this->Value["ts_p6"] = $this->ts_p6;
		    $this->m_ts_p6 = $this->ts_p6;
		}	

		if ($this->ts_p7)
		{
			$this->Value["ts_p7"] = $this->ts_p7;
		    $this->m_ts_p7 = $this->ts_p7;
		}	

		if ($this->ts_p8)
		{
			$this->Value["ts_p8"] = $this->ts_p8;
		    $this->m_ts_p8 = $this->ts_p8;
		}	

		if ($this->ts_p9)
		{
			$this->Value["ts_p9"] = $this->ts_p9;
		    $this->m_ts_p9 = $this->ts_p9;
		}	

		if ($this->ts_s_id)
		{
			$this->Value["ts_s_id"] = $this->ts_s_id;
		    $this->m_ts_s_id = $this->ts_s_id;
		}	

		if ($this->ts_Score)
		{
			$this->Value["ts_Score"] = $this->ts_Score;
		    $this->m_ts_Score = $this->ts_Score;
		}	
	
		if ($this->Value["ts_id"])
		    $this->m_ts_id = $this->Value["ts_id"];	
		if ($this->Value["ts_name"])
		    $this->m_ts_name = $this->Value["ts_name"];	
		if ($this->Value["ts_p0"])
		    $this->m_ts_p0 = $this->Value["ts_p0"];	
		if ($this->Value["ts_p1"])
		    $this->m_ts_p1 = $this->Value["ts_p1"];	
		if ($this->Value["ts_p2"])
		    $this->m_ts_p2 = $this->Value["ts_p2"];	
		if ($this->Value["ts_p3"])
		    $this->m_ts_p3 = $this->Value["ts_p3"];	
		if ($this->Value["ts_p4"])
		    $this->m_ts_p4 = $this->Value["ts_p4"];	
		if ($this->Value["ts_p5"])
		    $this->m_ts_p5 = $this->Value["ts_p5"];	
		if ($this->Value["ts_p6"])
		    $this->m_ts_p6 = $this->Value["ts_p6"];	
		if ($this->Value["ts_p7"])
		    $this->m_ts_p7 = $this->Value["ts_p7"];	
		if ($this->Value["ts_p8"])
		    $this->m_ts_p8 = $this->Value["ts_p8"];	
		if ($this->Value["ts_p9"])
		    $this->m_ts_p9 = $this->Value["ts_p9"];	
		if ($this->Value["ts_s_id"])
		    $this->m_ts_s_id = $this->Value["ts_s_id"];	
		if ($this->Value["ts_Score"])
		    $this->m_ts_Score = $this->Value["ts_Score"];	

		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_id"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("ts_id").",";
			if (NeedQuotes(3))
				$insertValues.= "'".db_addslashes($this->Value["ts_id"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["ts_id"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_name"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("ts_name").",";
			if (NeedQuotes(129))
				$insertValues.= "'".db_addslashes($this->Value["ts_name"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["ts_name"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p0"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("ts_p0").",";
			if (NeedQuotes(3))
				$insertValues.= "'".db_addslashes($this->Value["ts_p0"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["ts_p0"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p1"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("ts_p1").",";
			if (NeedQuotes(3))
				$insertValues.= "'".db_addslashes($this->Value["ts_p1"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["ts_p1"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p2"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("ts_p2").",";
			if (NeedQuotes(3))
				$insertValues.= "'".db_addslashes($this->Value["ts_p2"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["ts_p2"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p3"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("ts_p3").",";
			if (NeedQuotes(3))
				$insertValues.= "'".db_addslashes($this->Value["ts_p3"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["ts_p3"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p4"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("ts_p4").",";
			if (NeedQuotes(3))
				$insertValues.= "'".db_addslashes($this->Value["ts_p4"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["ts_p4"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p5"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("ts_p5").",";
			if (NeedQuotes(3))
				$insertValues.= "'".db_addslashes($this->Value["ts_p5"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["ts_p5"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p6"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("ts_p6").",";
			if (NeedQuotes(3))
				$insertValues.= "'".db_addslashes($this->Value["ts_p6"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["ts_p6"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p7"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("ts_p7").",";
			if (NeedQuotes(3))
				$insertValues.= "'".db_addslashes($this->Value["ts_p7"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["ts_p7"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p8"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("ts_p8").",";
			if (NeedQuotes(3))
				$insertValues.= "'".db_addslashes($this->Value["ts_p8"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["ts_p8"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p9"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("ts_p9").",";
			if (NeedQuotes(3))
				$insertValues.= "'".db_addslashes($this->Value["ts_p9"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["ts_p9"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_s_id"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("ts_s_id").",";
			if (NeedQuotes(3))
				$insertValues.= "'".db_addslashes($this->Value["ts_s_id"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["ts_s_id"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_Score"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("ts_Score").",";
			if (NeedQuotes(3))
				$insertValues.= "'".db_addslashes($this->Value["ts_Score"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["ts_Score"]) . ",";		

		}

		
	if ($insertFields!="" && $insertValues!="")		
	{
		$insertFields = substr($insertFields,0,-1);
		$insertValues = substr($insertValues,0,-1);
		$dalSQL = "insert into ".$this->m_GoodTableName." (".$insertFields.") values (".$insertValues.")";
		db_exec($dalSQL,$conn);
	}
	
        unset($this->Value["ts_id"]);
        unset($this->Value["ts_name"]);
        unset($this->Value["ts_p0"]);
        unset($this->Value["ts_p1"]);
        unset($this->Value["ts_p2"]);
        unset($this->Value["ts_p3"]);
        unset($this->Value["ts_p4"]);
        unset($this->Value["ts_p5"]);
        unset($this->Value["ts_p6"]);
        unset($this->Value["ts_p7"]);
        unset($this->Value["ts_p8"]);
        unset($this->Value["ts_p9"]);
        unset($this->Value["ts_s_id"]);
        unset($this->Value["ts_Score"]);
   // unset($this->m_ChangedFields);

	}
	
function Delete()
{
	global $conn;
	$deleteFields="";

		if ($this->ts_id)
		{
			$this->Value["ts_id"] = $this->ts_id;
			$this->m_ts_id = $this->ts_id;
		}	
		if ($this->ts_name)
		{
			$this->Value["ts_name"] = $this->ts_name;
			$this->m_ts_name = $this->ts_name;
		}	
		if ($this->ts_p0)
		{
			$this->Value["ts_p0"] = $this->ts_p0;
			$this->m_ts_p0 = $this->ts_p0;
		}	
		if ($this->ts_p1)
		{
			$this->Value["ts_p1"] = $this->ts_p1;
			$this->m_ts_p1 = $this->ts_p1;
		}	
		if ($this->ts_p2)
		{
			$this->Value["ts_p2"] = $this->ts_p2;
			$this->m_ts_p2 = $this->ts_p2;
		}	
		if ($this->ts_p3)
		{
			$this->Value["ts_p3"] = $this->ts_p3;
			$this->m_ts_p3 = $this->ts_p3;
		}	
		if ($this->ts_p4)
		{
			$this->Value["ts_p4"] = $this->ts_p4;
			$this->m_ts_p4 = $this->ts_p4;
		}	
		if ($this->ts_p5)
		{
			$this->Value["ts_p5"] = $this->ts_p5;
			$this->m_ts_p5 = $this->ts_p5;
		}	
		if ($this->ts_p6)
		{
			$this->Value["ts_p6"] = $this->ts_p6;
			$this->m_ts_p6 = $this->ts_p6;
		}	
		if ($this->ts_p7)
		{
			$this->Value["ts_p7"] = $this->ts_p7;
			$this->m_ts_p7 = $this->ts_p7;
		}	
		if ($this->ts_p8)
		{
			$this->Value["ts_p8"] = $this->ts_p8;
			$this->m_ts_p8 = $this->ts_p8;
		}	
		if ($this->ts_p9)
		{
			$this->Value["ts_p9"] = $this->ts_p9;
			$this->m_ts_p9 = $this->ts_p9;
		}	
		if ($this->ts_s_id)
		{
			$this->Value["ts_s_id"] = $this->ts_s_id;
			$this->m_ts_s_id = $this->ts_s_id;
		}	
		if ($this->ts_Score)
		{
			$this->Value["ts_Score"] = $this->ts_Score;
			$this->m_ts_Score = $this->ts_Score;
		}	
	
		if ($this->Value["ts_id"])
		    $this->m_ts_id = $this->Value["ts_id"];	
		if ($this->Value["ts_name"])
		    $this->m_ts_name = $this->Value["ts_name"];	
		if ($this->Value["ts_p0"])
		    $this->m_ts_p0 = $this->Value["ts_p0"];	
		if ($this->Value["ts_p1"])
		    $this->m_ts_p1 = $this->Value["ts_p1"];	
		if ($this->Value["ts_p2"])
		    $this->m_ts_p2 = $this->Value["ts_p2"];	
		if ($this->Value["ts_p3"])
		    $this->m_ts_p3 = $this->Value["ts_p3"];	
		if ($this->Value["ts_p4"])
		    $this->m_ts_p4 = $this->Value["ts_p4"];	
		if ($this->Value["ts_p5"])
		    $this->m_ts_p5 = $this->Value["ts_p5"];	
		if ($this->Value["ts_p6"])
		    $this->m_ts_p6 = $this->Value["ts_p6"];	
		if ($this->Value["ts_p7"])
		    $this->m_ts_p7 = $this->Value["ts_p7"];	
		if ($this->Value["ts_p8"])
		    $this->m_ts_p8 = $this->Value["ts_p8"];	
		if ($this->Value["ts_p9"])
		    $this->m_ts_p9 = $this->Value["ts_p9"];	
		if ($this->Value["ts_s_id"])
		    $this->m_ts_s_id = $this->Value["ts_s_id"];	
		if ($this->Value["ts_Score"])
		    $this->m_ts_Score = $this->Value["ts_Score"];	

		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_id"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("ts_id").",";
			if (NeedQuotes(3))
				$deleteFields.= AddFieldWrappers("ts_id")."='".db_addslashes($this->Value["ts_id"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("ts_id")."=".db_addslashes($this->Value["ts_id"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_name"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("ts_name").",";
			if (NeedQuotes(129))
				$deleteFields.= AddFieldWrappers("ts_name")."='".db_addslashes($this->Value["ts_name"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("ts_name")."=".db_addslashes($this->Value["ts_name"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p0"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("ts_p0").",";
			if (NeedQuotes(3))
				$deleteFields.= AddFieldWrappers("ts_p0")."='".db_addslashes($this->Value["ts_p0"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("ts_p0")."=".db_addslashes($this->Value["ts_p0"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p1"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("ts_p1").",";
			if (NeedQuotes(3))
				$deleteFields.= AddFieldWrappers("ts_p1")."='".db_addslashes($this->Value["ts_p1"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("ts_p1")."=".db_addslashes($this->Value["ts_p1"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p2"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("ts_p2").",";
			if (NeedQuotes(3))
				$deleteFields.= AddFieldWrappers("ts_p2")."='".db_addslashes($this->Value["ts_p2"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("ts_p2")."=".db_addslashes($this->Value["ts_p2"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p3"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("ts_p3").",";
			if (NeedQuotes(3))
				$deleteFields.= AddFieldWrappers("ts_p3")."='".db_addslashes($this->Value["ts_p3"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("ts_p3")."=".db_addslashes($this->Value["ts_p3"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p4"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("ts_p4").",";
			if (NeedQuotes(3))
				$deleteFields.= AddFieldWrappers("ts_p4")."='".db_addslashes($this->Value["ts_p4"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("ts_p4")."=".db_addslashes($this->Value["ts_p4"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p5"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("ts_p5").",";
			if (NeedQuotes(3))
				$deleteFields.= AddFieldWrappers("ts_p5")."='".db_addslashes($this->Value["ts_p5"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("ts_p5")."=".db_addslashes($this->Value["ts_p5"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p6"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("ts_p6").",";
			if (NeedQuotes(3))
				$deleteFields.= AddFieldWrappers("ts_p6")."='".db_addslashes($this->Value["ts_p6"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("ts_p6")."=".db_addslashes($this->Value["ts_p6"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p7"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("ts_p7").",";
			if (NeedQuotes(3))
				$deleteFields.= AddFieldWrappers("ts_p7")."='".db_addslashes($this->Value["ts_p7"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("ts_p7")."=".db_addslashes($this->Value["ts_p7"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p8"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("ts_p8").",";
			if (NeedQuotes(3))
				$deleteFields.= AddFieldWrappers("ts_p8")."='".db_addslashes($this->Value["ts_p8"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("ts_p8")."=".db_addslashes($this->Value["ts_p8"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p9"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("ts_p9").",";
			if (NeedQuotes(3))
				$deleteFields.= AddFieldWrappers("ts_p9")."='".db_addslashes($this->Value["ts_p9"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("ts_p9")."=".db_addslashes($this->Value["ts_p9"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_s_id"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("ts_s_id").",";
			if (NeedQuotes(3))
				$deleteFields.= AddFieldWrappers("ts_s_id")."='".db_addslashes($this->Value["ts_s_id"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("ts_s_id")."=".db_addslashes($this->Value["ts_s_id"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_Score"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("ts_Score").",";
			if (NeedQuotes(3))
				$deleteFields.= AddFieldWrappers("ts_Score")."='".db_addslashes($this->Value["ts_Score"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("ts_Score")."=".db_addslashes($this->Value["ts_Score"]) . " and ";		
		}

	if ($deleteFields)
	{
		$deleteFields = substr($deleteFields,0,-5);
		$dalSQL = "delete from ".$this->m_GoodTableName." where ".$deleteFields;
		db_exec($dalSQL,$conn);
	}
	
	    unset($this->Value["ts_id"]);
	    unset($this->Value["ts_name"]);
	    unset($this->Value["ts_p0"]);
	    unset($this->Value["ts_p1"]);
	    unset($this->Value["ts_p2"]);
	    unset($this->Value["ts_p3"]);
	    unset($this->Value["ts_p4"]);
	    unset($this->Value["ts_p5"]);
	    unset($this->Value["ts_p6"]);
	    unset($this->Value["ts_p7"]);
	    unset($this->Value["ts_p8"]);
	    unset($this->Value["ts_p9"]);
	    unset($this->Value["ts_s_id"]);
	    unset($this->Value["ts_Score"]);
		
	//unset($this->Value);	
}

function Update()
{
	global $conn;
	
	$updateParam = "";
	$updateValue = "";
	
		if ($this->ts_id)
		{
			if (1==1)
				$this->Param["ts_id"] = $this->ts_id;
			else
				$this->Value["ts_id"] = $this->ts_id;
			$this->m_ts_id = $this->ts_id;
		}	
		if ($this->ts_name)
		{
			if (0==1)
				$this->Param["ts_name"] = $this->ts_name;
			else
				$this->Value["ts_name"] = $this->ts_name;
			$this->m_ts_name = $this->ts_name;
		}	
		if ($this->ts_p0)
		{
			if (0==1)
				$this->Param["ts_p0"] = $this->ts_p0;
			else
				$this->Value["ts_p0"] = $this->ts_p0;
			$this->m_ts_p0 = $this->ts_p0;
		}	
		if ($this->ts_p1)
		{
			if (0==1)
				$this->Param["ts_p1"] = $this->ts_p1;
			else
				$this->Value["ts_p1"] = $this->ts_p1;
			$this->m_ts_p1 = $this->ts_p1;
		}	
		if ($this->ts_p2)
		{
			if (0==1)
				$this->Param["ts_p2"] = $this->ts_p2;
			else
				$this->Value["ts_p2"] = $this->ts_p2;
			$this->m_ts_p2 = $this->ts_p2;
		}	
		if ($this->ts_p3)
		{
			if (0==1)
				$this->Param["ts_p3"] = $this->ts_p3;
			else
				$this->Value["ts_p3"] = $this->ts_p3;
			$this->m_ts_p3 = $this->ts_p3;
		}	
		if ($this->ts_p4)
		{
			if (0==1)
				$this->Param["ts_p4"] = $this->ts_p4;
			else
				$this->Value["ts_p4"] = $this->ts_p4;
			$this->m_ts_p4 = $this->ts_p4;
		}	
		if ($this->ts_p5)
		{
			if (0==1)
				$this->Param["ts_p5"] = $this->ts_p5;
			else
				$this->Value["ts_p5"] = $this->ts_p5;
			$this->m_ts_p5 = $this->ts_p5;
		}	
		if ($this->ts_p6)
		{
			if (0==1)
				$this->Param["ts_p6"] = $this->ts_p6;
			else
				$this->Value["ts_p6"] = $this->ts_p6;
			$this->m_ts_p6 = $this->ts_p6;
		}	
		if ($this->ts_p7)
		{
			if (0==1)
				$this->Param["ts_p7"] = $this->ts_p7;
			else
				$this->Value["ts_p7"] = $this->ts_p7;
			$this->m_ts_p7 = $this->ts_p7;
		}	
		if ($this->ts_p8)
		{
			if (0==1)
				$this->Param["ts_p8"] = $this->ts_p8;
			else
				$this->Value["ts_p8"] = $this->ts_p8;
			$this->m_ts_p8 = $this->ts_p8;
		}	
		if ($this->ts_p9)
		{
			if (0==1)
				$this->Param["ts_p9"] = $this->ts_p9;
			else
				$this->Value["ts_p9"] = $this->ts_p9;
			$this->m_ts_p9 = $this->ts_p9;
		}	
		if ($this->ts_s_id)
		{
			if (0==1)
				$this->Param["ts_s_id"] = $this->ts_s_id;
			else
				$this->Value["ts_s_id"] = $this->ts_s_id;
			$this->m_ts_s_id = $this->ts_s_id;
		}	
		if ($this->ts_Score)
		{
			if (0==1)
				$this->Param["ts_Score"] = $this->ts_Score;
			else
				$this->Value["ts_Score"] = $this->ts_Score;
			$this->m_ts_Score = $this->ts_Score;
		}	
	
		if ($this->Value["ts_id"])
		    $this->m_ts_id = $this->Value["ts_id"];		
		if ($this->Value["ts_name"])
		    $this->m_ts_name = $this->Value["ts_name"];		
		if ($this->Value["ts_p0"])
		    $this->m_ts_p0 = $this->Value["ts_p0"];		
		if ($this->Value["ts_p1"])
		    $this->m_ts_p1 = $this->Value["ts_p1"];		
		if ($this->Value["ts_p2"])
		    $this->m_ts_p2 = $this->Value["ts_p2"];		
		if ($this->Value["ts_p3"])
		    $this->m_ts_p3 = $this->Value["ts_p3"];		
		if ($this->Value["ts_p4"])
		    $this->m_ts_p4 = $this->Value["ts_p4"];		
		if ($this->Value["ts_p5"])
		    $this->m_ts_p5 = $this->Value["ts_p5"];		
		if ($this->Value["ts_p6"])
		    $this->m_ts_p6 = $this->Value["ts_p6"];		
		if ($this->Value["ts_p7"])
		    $this->m_ts_p7 = $this->Value["ts_p7"];		
		if ($this->Value["ts_p8"])
		    $this->m_ts_p8 = $this->Value["ts_p8"];		
		if ($this->Value["ts_p9"])
		    $this->m_ts_p9 = $this->Value["ts_p9"];		
		if ($this->Value["ts_s_id"])
		    $this->m_ts_s_id = $this->Value["ts_s_id"];		
		if ($this->Value["ts_Score"])
		    $this->m_ts_Score = $this->Value["ts_Score"];		
	
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_id"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateValue.= AddFieldWrappers("ts_id")."='".$this->Value["ts_id"]."', ";
			else
					$updateValue.= AddFieldWrappers("ts_id")."=".$this->Value["ts_id"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_id"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateParam.= AddFieldWrappers("ts_id")."='".db_addslashes($this->Param["ts_id"])."' and ";
			else
					$updateParam.= AddFieldWrappers("ts_id")."=".db_addslashes($this->Param["ts_id"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_name"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(129))
					$updateValue.= AddFieldWrappers("ts_name")."='".$this->Value["ts_name"]."', ";
			else
					$updateValue.= AddFieldWrappers("ts_name")."=".$this->Value["ts_name"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_name"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(129))
					$updateParam.= AddFieldWrappers("ts_name")."='".db_addslashes($this->Param["ts_name"])."' and ";
			else
					$updateParam.= AddFieldWrappers("ts_name")."=".db_addslashes($this->Param["ts_name"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p0"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateValue.= AddFieldWrappers("ts_p0")."='".$this->Value["ts_p0"]."', ";
			else
					$updateValue.= AddFieldWrappers("ts_p0")."=".$this->Value["ts_p0"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p0"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateParam.= AddFieldWrappers("ts_p0")."='".db_addslashes($this->Param["ts_p0"])."' and ";
			else
					$updateParam.= AddFieldWrappers("ts_p0")."=".db_addslashes($this->Param["ts_p0"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p1"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateValue.= AddFieldWrappers("ts_p1")."='".$this->Value["ts_p1"]."', ";
			else
					$updateValue.= AddFieldWrappers("ts_p1")."=".$this->Value["ts_p1"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p1"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateParam.= AddFieldWrappers("ts_p1")."='".db_addslashes($this->Param["ts_p1"])."' and ";
			else
					$updateParam.= AddFieldWrappers("ts_p1")."=".db_addslashes($this->Param["ts_p1"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p2"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateValue.= AddFieldWrappers("ts_p2")."='".$this->Value["ts_p2"]."', ";
			else
					$updateValue.= AddFieldWrappers("ts_p2")."=".$this->Value["ts_p2"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p2"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateParam.= AddFieldWrappers("ts_p2")."='".db_addslashes($this->Param["ts_p2"])."' and ";
			else
					$updateParam.= AddFieldWrappers("ts_p2")."=".db_addslashes($this->Param["ts_p2"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p3"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateValue.= AddFieldWrappers("ts_p3")."='".$this->Value["ts_p3"]."', ";
			else
					$updateValue.= AddFieldWrappers("ts_p3")."=".$this->Value["ts_p3"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p3"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateParam.= AddFieldWrappers("ts_p3")."='".db_addslashes($this->Param["ts_p3"])."' and ";
			else
					$updateParam.= AddFieldWrappers("ts_p3")."=".db_addslashes($this->Param["ts_p3"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p4"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateValue.= AddFieldWrappers("ts_p4")."='".$this->Value["ts_p4"]."', ";
			else
					$updateValue.= AddFieldWrappers("ts_p4")."=".$this->Value["ts_p4"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p4"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateParam.= AddFieldWrappers("ts_p4")."='".db_addslashes($this->Param["ts_p4"])."' and ";
			else
					$updateParam.= AddFieldWrappers("ts_p4")."=".db_addslashes($this->Param["ts_p4"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p5"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateValue.= AddFieldWrappers("ts_p5")."='".$this->Value["ts_p5"]."', ";
			else
					$updateValue.= AddFieldWrappers("ts_p5")."=".$this->Value["ts_p5"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p5"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateParam.= AddFieldWrappers("ts_p5")."='".db_addslashes($this->Param["ts_p5"])."' and ";
			else
					$updateParam.= AddFieldWrappers("ts_p5")."=".db_addslashes($this->Param["ts_p5"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p6"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateValue.= AddFieldWrappers("ts_p6")."='".$this->Value["ts_p6"]."', ";
			else
					$updateValue.= AddFieldWrappers("ts_p6")."=".$this->Value["ts_p6"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p6"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateParam.= AddFieldWrappers("ts_p6")."='".db_addslashes($this->Param["ts_p6"])."' and ";
			else
					$updateParam.= AddFieldWrappers("ts_p6")."=".db_addslashes($this->Param["ts_p6"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p7"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateValue.= AddFieldWrappers("ts_p7")."='".$this->Value["ts_p7"]."', ";
			else
					$updateValue.= AddFieldWrappers("ts_p7")."=".$this->Value["ts_p7"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p7"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateParam.= AddFieldWrappers("ts_p7")."='".db_addslashes($this->Param["ts_p7"])."' and ";
			else
					$updateParam.= AddFieldWrappers("ts_p7")."=".db_addslashes($this->Param["ts_p7"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p8"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateValue.= AddFieldWrappers("ts_p8")."='".$this->Value["ts_p8"]."', ";
			else
					$updateValue.= AddFieldWrappers("ts_p8")."=".$this->Value["ts_p8"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p8"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateParam.= AddFieldWrappers("ts_p8")."='".db_addslashes($this->Param["ts_p8"])."' and ";
			else
					$updateParam.= AddFieldWrappers("ts_p8")."=".db_addslashes($this->Param["ts_p8"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p9"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateValue.= AddFieldWrappers("ts_p9")."='".$this->Value["ts_p9"]."', ";
			else
					$updateValue.= AddFieldWrappers("ts_p9")."=".$this->Value["ts_p9"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p9"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateParam.= AddFieldWrappers("ts_p9")."='".db_addslashes($this->Param["ts_p9"])."' and ";
			else
					$updateParam.= AddFieldWrappers("ts_p9")."=".db_addslashes($this->Param["ts_p9"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_s_id"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateValue.= AddFieldWrappers("ts_s_id")."='".$this->Value["ts_s_id"]."', ";
			else
					$updateValue.= AddFieldWrappers("ts_s_id")."=".$this->Value["ts_s_id"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_s_id"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateParam.= AddFieldWrappers("ts_s_id")."='".db_addslashes($this->Param["ts_s_id"])."' and ";
			else
					$updateParam.= AddFieldWrappers("ts_s_id")."=".db_addslashes($this->Param["ts_s_id"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_Score"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateValue.= AddFieldWrappers("ts_Score")."='".$this->Value["ts_Score"]."', ";
			else
					$updateValue.= AddFieldWrappers("ts_Score")."=".$this->Value["ts_Score"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_Score"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateParam.= AddFieldWrappers("ts_Score")."='".db_addslashes($this->Param["ts_Score"])."' and ";
			else
					$updateParam.= AddFieldWrappers("ts_Score")."=".db_addslashes($this->Param["ts_Score"])." and ";
		}
		
	
	if ($updateParam)
		$updateParam = substr($updateParam,0,-5);
	if ($updateValue)
		$updateValue = substr($updateValue,0,-2);
		
	if ($updateValue)
	{
		$dalSQL = "update ".$this->m_GoodTableName." set ".$updateValue." where ".$updateParam;
		db_exec($dalSQL,$conn);
	}
	
        unset($this->Value["ts_id"]);
		unset($this->Param["ts_id"]);
        unset($this->Value["ts_name"]);
		unset($this->Param["ts_name"]);
        unset($this->Value["ts_p0"]);
		unset($this->Param["ts_p0"]);
        unset($this->Value["ts_p1"]);
		unset($this->Param["ts_p1"]);
        unset($this->Value["ts_p2"]);
		unset($this->Param["ts_p2"]);
        unset($this->Value["ts_p3"]);
		unset($this->Param["ts_p3"]);
        unset($this->Value["ts_p4"]);
		unset($this->Param["ts_p4"]);
        unset($this->Value["ts_p5"]);
		unset($this->Param["ts_p5"]);
        unset($this->Value["ts_p6"]);
		unset($this->Param["ts_p6"]);
        unset($this->Value["ts_p7"]);
		unset($this->Param["ts_p7"]);
        unset($this->Value["ts_p8"]);
		unset($this->Param["ts_p8"]);
        unset($this->Value["ts_p9"]);
		unset($this->Param["ts_p9"]);
        unset($this->Value["ts_s_id"]);
		unset($this->Param["ts_s_id"]);
        unset($this->Value["ts_Score"]);
		unset($this->Param["ts_Score"]);
	
}

function QueryAll()
{
	global $conn;
	$dal_variables="";
		$dal_variables.= AddFieldWrappers("ts_id").", ";
		$dal_variables.= AddFieldWrappers("ts_name").", ";
		$dal_variables.= AddFieldWrappers("ts_p0").", ";
		$dal_variables.= AddFieldWrappers("ts_p1").", ";
		$dal_variables.= AddFieldWrappers("ts_p2").", ";
		$dal_variables.= AddFieldWrappers("ts_p3").", ";
		$dal_variables.= AddFieldWrappers("ts_p4").", ";
		$dal_variables.= AddFieldWrappers("ts_p5").", ";
		$dal_variables.= AddFieldWrappers("ts_p6").", ";
		$dal_variables.= AddFieldWrappers("ts_p7").", ";
		$dal_variables.= AddFieldWrappers("ts_p8").", ";
		$dal_variables.= AddFieldWrappers("ts_p9").", ";
		$dal_variables.= AddFieldWrappers("ts_s_id").", ";
		$dal_variables.= AddFieldWrappers("ts_Score").", ";
	$dal_variables = substr($dal_variables,0,-2);
	$dalSQL = "select ".$dal_variables." from ".$this->m_GoodTableName;
	$rs = db_query($dalSQL,$conn);
	$data = db_fetch_array($rs);
	return $data;
}

function Query($swhere,$orderby)
{
	global $conn;
	$dal_variables="";
		$dal_variables.= AddFieldWrappers("ts_id").", ";
		$dal_variables.= AddFieldWrappers("ts_name").", ";
		$dal_variables.= AddFieldWrappers("ts_p0").", ";
		$dal_variables.= AddFieldWrappers("ts_p1").", ";
		$dal_variables.= AddFieldWrappers("ts_p2").", ";
		$dal_variables.= AddFieldWrappers("ts_p3").", ";
		$dal_variables.= AddFieldWrappers("ts_p4").", ";
		$dal_variables.= AddFieldWrappers("ts_p5").", ";
		$dal_variables.= AddFieldWrappers("ts_p6").", ";
		$dal_variables.= AddFieldWrappers("ts_p7").", ";
		$dal_variables.= AddFieldWrappers("ts_p8").", ";
		$dal_variables.= AddFieldWrappers("ts_p9").", ";
		$dal_variables.= AddFieldWrappers("ts_s_id").", ";
		$dal_variables.= AddFieldWrappers("ts_Score").", ";
	$dal_variables = substr($dal_variables,0,-2);
	if ($swhere)
		$swhere = " where ".$swhere;
	if ($orderby)
		$orderby = " order by ".$orderby;
	
	$dalSQL = "select ".$dal_variables." from ".$this->m_GoodTableName.$swhere.$orderby;
	$rs = db_query($dalSQL,$conn);
	$data = db_fetch_array($rs);
	return $data; 	
}

function FetchByID()
{
	global $conn;
	$dal_variables="";
	$dal_where="";
        $dal_variables.= AddFieldWrappers("ts_id").", ";
        $dal_variables.= AddFieldWrappers("ts_name").", ";
        $dal_variables.= AddFieldWrappers("ts_p0").", ";
        $dal_variables.= AddFieldWrappers("ts_p1").", ";
        $dal_variables.= AddFieldWrappers("ts_p2").", ";
        $dal_variables.= AddFieldWrappers("ts_p3").", ";
        $dal_variables.= AddFieldWrappers("ts_p4").", ";
        $dal_variables.= AddFieldWrappers("ts_p5").", ";
        $dal_variables.= AddFieldWrappers("ts_p6").", ";
        $dal_variables.= AddFieldWrappers("ts_p7").", ";
        $dal_variables.= AddFieldWrappers("ts_p8").", ";
        $dal_variables.= AddFieldWrappers("ts_p9").", ";
        $dal_variables.= AddFieldWrappers("ts_s_id").", ";
        $dal_variables.= AddFieldWrappers("ts_Score").", ";
	$dal_variables = substr($dal_variables,0,-2);
	
		if ($this->ts_id)
			$this->Param["ts_id"] = $this->ts_id;	
		if ($this->ts_name)
			$this->Param["ts_name"] = $this->ts_name;	
		if ($this->ts_p0)
			$this->Param["ts_p0"] = $this->ts_p0;	
		if ($this->ts_p1)
			$this->Param["ts_p1"] = $this->ts_p1;	
		if ($this->ts_p2)
			$this->Param["ts_p2"] = $this->ts_p2;	
		if ($this->ts_p3)
			$this->Param["ts_p3"] = $this->ts_p3;	
		if ($this->ts_p4)
			$this->Param["ts_p4"] = $this->ts_p4;	
		if ($this->ts_p5)
			$this->Param["ts_p5"] = $this->ts_p5;	
		if ($this->ts_p6)
			$this->Param["ts_p6"] = $this->ts_p6;	
		if ($this->ts_p7)
			$this->Param["ts_p7"] = $this->ts_p7;	
		if ($this->ts_p8)
			$this->Param["ts_p8"] = $this->ts_p8;	
		if ($this->ts_p9)
			$this->Param["ts_p9"] = $this->ts_p9;	
		if ($this->ts_s_id)
			$this->Param["ts_s_id"] = $this->ts_s_id;	
		if ($this->ts_Score)
			$this->Param["ts_Score"] = $this->ts_Score;	
	
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_id") && 1==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(3))
				$dal_where.= AddFieldWrappers("ts_id")."='".db_addslashes($this->Param["ts_id"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("ts_id")."=".db_addslashes($this->Param["ts_id"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_name") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(129))
				$dal_where.= AddFieldWrappers("ts_name")."='".db_addslashes($this->Param["ts_name"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("ts_name")."=".db_addslashes($this->Param["ts_name"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p0") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(3))
				$dal_where.= AddFieldWrappers("ts_p0")."='".db_addslashes($this->Param["ts_p0"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("ts_p0")."=".db_addslashes($this->Param["ts_p0"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p1") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(3))
				$dal_where.= AddFieldWrappers("ts_p1")."='".db_addslashes($this->Param["ts_p1"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("ts_p1")."=".db_addslashes($this->Param["ts_p1"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p2") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(3))
				$dal_where.= AddFieldWrappers("ts_p2")."='".db_addslashes($this->Param["ts_p2"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("ts_p2")."=".db_addslashes($this->Param["ts_p2"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p3") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(3))
				$dal_where.= AddFieldWrappers("ts_p3")."='".db_addslashes($this->Param["ts_p3"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("ts_p3")."=".db_addslashes($this->Param["ts_p3"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p4") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(3))
				$dal_where.= AddFieldWrappers("ts_p4")."='".db_addslashes($this->Param["ts_p4"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("ts_p4")."=".db_addslashes($this->Param["ts_p4"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p5") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(3))
				$dal_where.= AddFieldWrappers("ts_p5")."='".db_addslashes($this->Param["ts_p5"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("ts_p5")."=".db_addslashes($this->Param["ts_p5"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p6") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(3))
				$dal_where.= AddFieldWrappers("ts_p6")."='".db_addslashes($this->Param["ts_p6"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("ts_p6")."=".db_addslashes($this->Param["ts_p6"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p7") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(3))
				$dal_where.= AddFieldWrappers("ts_p7")."='".db_addslashes($this->Param["ts_p7"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("ts_p7")."=".db_addslashes($this->Param["ts_p7"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p8") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(3))
				$dal_where.= AddFieldWrappers("ts_p8")."='".db_addslashes($this->Param["ts_p8"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("ts_p8")."=".db_addslashes($this->Param["ts_p8"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_p9") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(3))
				$dal_where.= AddFieldWrappers("ts_p9")."='".db_addslashes($this->Param["ts_p9"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("ts_p9")."=".db_addslashes($this->Param["ts_p9"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_s_id") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(3))
				$dal_where.= AddFieldWrappers("ts_s_id")."='".db_addslashes($this->Param["ts_s_id"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("ts_s_id")."=".db_addslashes($this->Param["ts_s_id"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("ts_Score") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(3))
				$dal_where.= AddFieldWrappers("ts_Score")."='".db_addslashes($this->Param["ts_Score"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("ts_Score")."=".db_addslashes($this->Param["ts_Score"]) . " and ";
	
	if ($dal_where)
		$dal_where = " where ".substr($dal_where,0,-5);
	
	$dalSQL = "select ".$dal_variables." from ".$this->m_GoodTableName.$dal_where;
	$rs = db_query($dalSQL,$conn);
	$data = db_fetch_array($rs);
	return $data;
}
	
function Reset()
{
        unset($this->m_ts_id);
		unset($this->Value["ts_id"]);
		unset($this->Param["ts_id"]);
        unset($this->m_ts_name);
		unset($this->Value["ts_name"]);
		unset($this->Param["ts_name"]);
        unset($this->m_ts_p0);
		unset($this->Value["ts_p0"]);
		unset($this->Param["ts_p0"]);
        unset($this->m_ts_p1);
		unset($this->Value["ts_p1"]);
		unset($this->Param["ts_p1"]);
        unset($this->m_ts_p2);
		unset($this->Value["ts_p2"]);
		unset($this->Param["ts_p2"]);
        unset($this->m_ts_p3);
		unset($this->Value["ts_p3"]);
		unset($this->Param["ts_p3"]);
        unset($this->m_ts_p4);
		unset($this->Value["ts_p4"]);
		unset($this->Param["ts_p4"]);
        unset($this->m_ts_p5);
		unset($this->Value["ts_p5"]);
		unset($this->Param["ts_p5"]);
        unset($this->m_ts_p6);
		unset($this->Value["ts_p6"]);
		unset($this->Param["ts_p6"]);
        unset($this->m_ts_p7);
		unset($this->Value["ts_p7"]);
		unset($this->Param["ts_p7"]);
        unset($this->m_ts_p8);
		unset($this->Value["ts_p8"]);
		unset($this->Param["ts_p8"]);
        unset($this->m_ts_p9);
		unset($this->Value["ts_p9"]);
		unset($this->Param["ts_p9"]);
        unset($this->m_ts_s_id);
		unset($this->Value["ts_s_id"]);
		unset($this->Param["ts_s_id"]);
        unset($this->m_ts_Score);
		unset($this->Value["ts_Score"]);
		unset($this->Param["ts_Score"]);
}	
	
}//end of class


$dal->teams = new class_teams();


class class_player
{
	var $m_TableName;
	var $m_GoodTableName;
	var $m_p_id;
	var $m_p_active;
	var $m_p_location;
	var $m_p_s_id;
	var $m_p_first;
	var $m_p_name;
	var $m_p_adr;
	var $m_p_zip;
	var $m_p_mail;
	var $m_p_newsaccept;
	var $m_p_score;
	var $m_p_scorehigh;
	var $m_p_games;
	var $m_p_time;
	var $m_p_win;
	var $m_p_mk;
	var $m_p_born;
	var $m_p_user;
	var $m_p_pwd;
	var $m_p_ip;
	var $m_p_datetime;
	var $m_p_tscore;
	var $m_p_tkills;
	var $m_p_news;
	var $m_p_country;
	var $m_p_mobile;
	var $m_p_avatar;

	var $Param = array();
	var $Value = array();
	
	var $p_id = array();
	var $p_active = array();
	var $p_location = array();
	var $p_s_id = array();
	var $p_first = array();
	var $p_name = array();
	var $p_adr = array();
	var $p_zip = array();
	var $p_mail = array();
	var $p_newsaccept = array();
	var $p_score = array();
	var $p_scorehigh = array();
	var $p_games = array();
	var $p_time = array();
	var $p_win = array();
	var $p_mk = array();
	var $p_born = array();
	var $p_user = array();
	var $p_pwd = array();
	var $p_ip = array();
	var $p_datetime = array();
	var $p_tscore = array();
	var $p_tkills = array();
	var $p_news = array();
	var $p_country = array();
	var $p_mobile = array();
	var $p_avatar = array();
	
	var $m_ChangedFields;
	var $m_UpdateParam;
	var $m_UpdateValue;

	
	function class_player()
	{
		$this->m_TableName = "player";
		$this->m_GoodTableName = AddTableWrappers($this->m_TableName);	
	}

function TableName()
{
	$this->m_TableName = "player";
	$this->m_GoodTableName = AddTableWrappers($this->m_TableName);
	return $this->m_GoodTableName;
} 

function Add() 
{
	global $conn;

	$insertFields="";
	$insertValues="";
		

		if ($this->p_id)
		{
			$this->Value["p_id"] = $this->p_id;
		    $this->m_p_id = $this->p_id;
		}	

		if ($this->p_active)
		{
			$this->Value["p_active"] = $this->p_active;
		    $this->m_p_active = $this->p_active;
		}	

		if ($this->p_location)
		{
			$this->Value["p_location"] = $this->p_location;
		    $this->m_p_location = $this->p_location;
		}	

		if ($this->p_s_id)
		{
			$this->Value["p_s_id"] = $this->p_s_id;
		    $this->m_p_s_id = $this->p_s_id;
		}	

		if ($this->p_first)
		{
			$this->Value["p_first"] = $this->p_first;
		    $this->m_p_first = $this->p_first;
		}	

		if ($this->p_name)
		{
			$this->Value["p_name"] = $this->p_name;
		    $this->m_p_name = $this->p_name;
		}	

		if ($this->p_adr)
		{
			$this->Value["p_adr"] = $this->p_adr;
		    $this->m_p_adr = $this->p_adr;
		}	

		if ($this->p_zip)
		{
			$this->Value["p_zip"] = $this->p_zip;
		    $this->m_p_zip = $this->p_zip;
		}	

		if ($this->p_mail)
		{
			$this->Value["p_mail"] = $this->p_mail;
		    $this->m_p_mail = $this->p_mail;
		}	

		if ($this->p_newsaccept)
		{
			$this->Value["p_newsaccept"] = $this->p_newsaccept;
		    $this->m_p_newsaccept = $this->p_newsaccept;
		}	

		if ($this->p_score)
		{
			$this->Value["p_score"] = $this->p_score;
		    $this->m_p_score = $this->p_score;
		}	

		if ($this->p_scorehigh)
		{
			$this->Value["p_scorehigh"] = $this->p_scorehigh;
		    $this->m_p_scorehigh = $this->p_scorehigh;
		}	

		if ($this->p_games)
		{
			$this->Value["p_games"] = $this->p_games;
		    $this->m_p_games = $this->p_games;
		}	

		if ($this->p_time)
		{
			$this->Value["p_time"] = $this->p_time;
		    $this->m_p_time = $this->p_time;
		}	

		if ($this->p_win)
		{
			$this->Value["p_win"] = $this->p_win;
		    $this->m_p_win = $this->p_win;
		}	

		if ($this->p_mk)
		{
			$this->Value["p_mk"] = $this->p_mk;
		    $this->m_p_mk = $this->p_mk;
		}	

		if ($this->p_born)
		{
			$this->Value["p_born"] = $this->p_born;
		    $this->m_p_born = $this->p_born;
		}	

		if ($this->p_user)
		{
			$this->Value["p_user"] = $this->p_user;
		    $this->m_p_user = $this->p_user;
		}	

		if ($this->p_pwd)
		{
			$this->Value["p_pwd"] = $this->p_pwd;
		    $this->m_p_pwd = $this->p_pwd;
		}	

		if ($this->p_ip)
		{
			$this->Value["p_ip"] = $this->p_ip;
		    $this->m_p_ip = $this->p_ip;
		}	

		if ($this->p_datetime)
		{
			$this->Value["p_datetime"] = $this->p_datetime;
		    $this->m_p_datetime = $this->p_datetime;
		}	

		if ($this->p_tscore)
		{
			$this->Value["p_tscore"] = $this->p_tscore;
		    $this->m_p_tscore = $this->p_tscore;
		}	

		if ($this->p_tkills)
		{
			$this->Value["p_tkills"] = $this->p_tkills;
		    $this->m_p_tkills = $this->p_tkills;
		}	

		if ($this->p_news)
		{
			$this->Value["p_news"] = $this->p_news;
		    $this->m_p_news = $this->p_news;
		}	

		if ($this->p_country)
		{
			$this->Value["p_country"] = $this->p_country;
		    $this->m_p_country = $this->p_country;
		}	

		if ($this->p_mobile)
		{
			$this->Value["p_mobile"] = $this->p_mobile;
		    $this->m_p_mobile = $this->p_mobile;
		}	

		if ($this->p_avatar)
		{
			$this->Value["p_avatar"] = $this->p_avatar;
		    $this->m_p_avatar = $this->p_avatar;
		}	
	
		if ($this->Value["p_id"])
		    $this->m_p_id = $this->Value["p_id"];	
		if ($this->Value["p_active"])
		    $this->m_p_active = $this->Value["p_active"];	
		if ($this->Value["p_location"])
		    $this->m_p_location = $this->Value["p_location"];	
		if ($this->Value["p_s_id"])
		    $this->m_p_s_id = $this->Value["p_s_id"];	
		if ($this->Value["p_first"])
		    $this->m_p_first = $this->Value["p_first"];	
		if ($this->Value["p_name"])
		    $this->m_p_name = $this->Value["p_name"];	
		if ($this->Value["p_adr"])
		    $this->m_p_adr = $this->Value["p_adr"];	
		if ($this->Value["p_zip"])
		    $this->m_p_zip = $this->Value["p_zip"];	
		if ($this->Value["p_mail"])
		    $this->m_p_mail = $this->Value["p_mail"];	
		if ($this->Value["p_newsaccept"])
		    $this->m_p_newsaccept = $this->Value["p_newsaccept"];	
		if ($this->Value["p_score"])
		    $this->m_p_score = $this->Value["p_score"];	
		if ($this->Value["p_scorehigh"])
		    $this->m_p_scorehigh = $this->Value["p_scorehigh"];	
		if ($this->Value["p_games"])
		    $this->m_p_games = $this->Value["p_games"];	
		if ($this->Value["p_time"])
		    $this->m_p_time = $this->Value["p_time"];	
		if ($this->Value["p_win"])
		    $this->m_p_win = $this->Value["p_win"];	
		if ($this->Value["p_mk"])
		    $this->m_p_mk = $this->Value["p_mk"];	
		if ($this->Value["p_born"])
		    $this->m_p_born = $this->Value["p_born"];	
		if ($this->Value["p_user"])
		    $this->m_p_user = $this->Value["p_user"];	
		if ($this->Value["p_pwd"])
		    $this->m_p_pwd = $this->Value["p_pwd"];	
		if ($this->Value["p_ip"])
		    $this->m_p_ip = $this->Value["p_ip"];	
		if ($this->Value["p_datetime"])
		    $this->m_p_datetime = $this->Value["p_datetime"];	
		if ($this->Value["p_tscore"])
		    $this->m_p_tscore = $this->Value["p_tscore"];	
		if ($this->Value["p_tkills"])
		    $this->m_p_tkills = $this->Value["p_tkills"];	
		if ($this->Value["p_news"])
		    $this->m_p_news = $this->Value["p_news"];	
		if ($this->Value["p_country"])
		    $this->m_p_country = $this->Value["p_country"];	
		if ($this->Value["p_mobile"])
		    $this->m_p_mobile = $this->Value["p_mobile"];	
		if ($this->Value["p_avatar"])
		    $this->m_p_avatar = $this->Value["p_avatar"];	

		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_id"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("p_id").",";
			if (NeedQuotes(3))
				$insertValues.= "'".db_addslashes($this->Value["p_id"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["p_id"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_active"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("p_active").",";
			if (NeedQuotes(16))
				$insertValues.= "'".db_addslashes($this->Value["p_active"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["p_active"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_location"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("p_location").",";
			if (NeedQuotes(3))
				$insertValues.= "'".db_addslashes($this->Value["p_location"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["p_location"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_s_id"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("p_s_id").",";
			if (NeedQuotes(3))
				$insertValues.= "'".db_addslashes($this->Value["p_s_id"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["p_s_id"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_first"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("p_first").",";
			if (NeedQuotes(129))
				$insertValues.= "'".db_addslashes($this->Value["p_first"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["p_first"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_name"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("p_name").",";
			if (NeedQuotes(129))
				$insertValues.= "'".db_addslashes($this->Value["p_name"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["p_name"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_adr"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("p_adr").",";
			if (NeedQuotes(129))
				$insertValues.= "'".db_addslashes($this->Value["p_adr"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["p_adr"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_zip"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("p_zip").",";
			if (NeedQuotes(3))
				$insertValues.= "'".db_addslashes($this->Value["p_zip"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["p_zip"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_mail"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("p_mail").",";
			if (NeedQuotes(129))
				$insertValues.= "'".db_addslashes($this->Value["p_mail"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["p_mail"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_newsaccept"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("p_newsaccept").",";
			if (NeedQuotes(16))
				$insertValues.= "'".db_addslashes($this->Value["p_newsaccept"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["p_newsaccept"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_score"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("p_score").",";
			if (NeedQuotes(3))
				$insertValues.= "'".db_addslashes($this->Value["p_score"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["p_score"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_scorehigh"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("p_scorehigh").",";
			if (NeedQuotes(3))
				$insertValues.= "'".db_addslashes($this->Value["p_scorehigh"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["p_scorehigh"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_games"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("p_games").",";
			if (NeedQuotes(3))
				$insertValues.= "'".db_addslashes($this->Value["p_games"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["p_games"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_time"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("p_time").",";
			if (NeedQuotes(134))
				$insertValues.= "'".db_addslashes($this->Value["p_time"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["p_time"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_win"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("p_win").",";
			if (NeedQuotes(3))
				$insertValues.= "'".db_addslashes($this->Value["p_win"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["p_win"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_mk"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("p_mk").",";
			if (NeedQuotes(129))
				$insertValues.= "'".db_addslashes($this->Value["p_mk"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["p_mk"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_born"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("p_born").",";
			if (NeedQuotes(13))
				$insertValues.= "'".db_addslashes($this->Value["p_born"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["p_born"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_user"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("p_user").",";
			if (NeedQuotes(129))
				$insertValues.= "'".db_addslashes($this->Value["p_user"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["p_user"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_pwd"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("p_pwd").",";
			if (NeedQuotes(129))
				$insertValues.= "'".db_addslashes($this->Value["p_pwd"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["p_pwd"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_ip"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("p_ip").",";
			if (NeedQuotes(129))
				$insertValues.= "'".db_addslashes($this->Value["p_ip"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["p_ip"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_datetime"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("p_datetime").",";
			if (NeedQuotes(135))
				$insertValues.= "'".db_addslashes($this->Value["p_datetime"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["p_datetime"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_tscore"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("p_tscore").",";
			if (NeedQuotes(3))
				$insertValues.= "'".db_addslashes($this->Value["p_tscore"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["p_tscore"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_tkills"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("p_tkills").",";
			if (NeedQuotes(3))
				$insertValues.= "'".db_addslashes($this->Value["p_tkills"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["p_tkills"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_news"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("p_news").",";
			if (NeedQuotes(2))
				$insertValues.= "'".db_addslashes($this->Value["p_news"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["p_news"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_country"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("p_country").",";
			if (NeedQuotes(129))
				$insertValues.= "'".db_addslashes($this->Value["p_country"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["p_country"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_mobile"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("p_mobile").",";
			if (NeedQuotes(20))
				$insertValues.= "'".db_addslashes($this->Value["p_mobile"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["p_mobile"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_avatar"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("p_avatar").",";
			if (NeedQuotes(16))
				$insertValues.= "'".db_addslashes($this->Value["p_avatar"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["p_avatar"]) . ",";		

		}

		
	if ($insertFields!="" && $insertValues!="")		
	{
		$insertFields = substr($insertFields,0,-1);
		$insertValues = substr($insertValues,0,-1);
		$dalSQL = "insert into ".$this->m_GoodTableName." (".$insertFields.") values (".$insertValues.")";
		db_exec($dalSQL,$conn);
	}
	
        unset($this->Value["p_id"]);
        unset($this->Value["p_active"]);
        unset($this->Value["p_location"]);
        unset($this->Value["p_s_id"]);
        unset($this->Value["p_first"]);
        unset($this->Value["p_name"]);
        unset($this->Value["p_adr"]);
        unset($this->Value["p_zip"]);
        unset($this->Value["p_mail"]);
        unset($this->Value["p_newsaccept"]);
        unset($this->Value["p_score"]);
        unset($this->Value["p_scorehigh"]);
        unset($this->Value["p_games"]);
        unset($this->Value["p_time"]);
        unset($this->Value["p_win"]);
        unset($this->Value["p_mk"]);
        unset($this->Value["p_born"]);
        unset($this->Value["p_user"]);
        unset($this->Value["p_pwd"]);
        unset($this->Value["p_ip"]);
        unset($this->Value["p_datetime"]);
        unset($this->Value["p_tscore"]);
        unset($this->Value["p_tkills"]);
        unset($this->Value["p_news"]);
        unset($this->Value["p_country"]);
        unset($this->Value["p_mobile"]);
        unset($this->Value["p_avatar"]);
   // unset($this->m_ChangedFields);

	}
	
function Delete()
{
	global $conn;
	$deleteFields="";

		if ($this->p_id)
		{
			$this->Value["p_id"] = $this->p_id;
			$this->m_p_id = $this->p_id;
		}	
		if ($this->p_active)
		{
			$this->Value["p_active"] = $this->p_active;
			$this->m_p_active = $this->p_active;
		}	
		if ($this->p_location)
		{
			$this->Value["p_location"] = $this->p_location;
			$this->m_p_location = $this->p_location;
		}	
		if ($this->p_s_id)
		{
			$this->Value["p_s_id"] = $this->p_s_id;
			$this->m_p_s_id = $this->p_s_id;
		}	
		if ($this->p_first)
		{
			$this->Value["p_first"] = $this->p_first;
			$this->m_p_first = $this->p_first;
		}	
		if ($this->p_name)
		{
			$this->Value["p_name"] = $this->p_name;
			$this->m_p_name = $this->p_name;
		}	
		if ($this->p_adr)
		{
			$this->Value["p_adr"] = $this->p_adr;
			$this->m_p_adr = $this->p_adr;
		}	
		if ($this->p_zip)
		{
			$this->Value["p_zip"] = $this->p_zip;
			$this->m_p_zip = $this->p_zip;
		}	
		if ($this->p_mail)
		{
			$this->Value["p_mail"] = $this->p_mail;
			$this->m_p_mail = $this->p_mail;
		}	
		if ($this->p_newsaccept)
		{
			$this->Value["p_newsaccept"] = $this->p_newsaccept;
			$this->m_p_newsaccept = $this->p_newsaccept;
		}	
		if ($this->p_score)
		{
			$this->Value["p_score"] = $this->p_score;
			$this->m_p_score = $this->p_score;
		}	
		if ($this->p_scorehigh)
		{
			$this->Value["p_scorehigh"] = $this->p_scorehigh;
			$this->m_p_scorehigh = $this->p_scorehigh;
		}	
		if ($this->p_games)
		{
			$this->Value["p_games"] = $this->p_games;
			$this->m_p_games = $this->p_games;
		}	
		if ($this->p_time)
		{
			$this->Value["p_time"] = $this->p_time;
			$this->m_p_time = $this->p_time;
		}	
		if ($this->p_win)
		{
			$this->Value["p_win"] = $this->p_win;
			$this->m_p_win = $this->p_win;
		}	
		if ($this->p_mk)
		{
			$this->Value["p_mk"] = $this->p_mk;
			$this->m_p_mk = $this->p_mk;
		}	
		if ($this->p_born)
		{
			$this->Value["p_born"] = $this->p_born;
			$this->m_p_born = $this->p_born;
		}	
		if ($this->p_user)
		{
			$this->Value["p_user"] = $this->p_user;
			$this->m_p_user = $this->p_user;
		}	
		if ($this->p_pwd)
		{
			$this->Value["p_pwd"] = $this->p_pwd;
			$this->m_p_pwd = $this->p_pwd;
		}	
		if ($this->p_ip)
		{
			$this->Value["p_ip"] = $this->p_ip;
			$this->m_p_ip = $this->p_ip;
		}	
		if ($this->p_datetime)
		{
			$this->Value["p_datetime"] = $this->p_datetime;
			$this->m_p_datetime = $this->p_datetime;
		}	
		if ($this->p_tscore)
		{
			$this->Value["p_tscore"] = $this->p_tscore;
			$this->m_p_tscore = $this->p_tscore;
		}	
		if ($this->p_tkills)
		{
			$this->Value["p_tkills"] = $this->p_tkills;
			$this->m_p_tkills = $this->p_tkills;
		}	
		if ($this->p_news)
		{
			$this->Value["p_news"] = $this->p_news;
			$this->m_p_news = $this->p_news;
		}	
		if ($this->p_country)
		{
			$this->Value["p_country"] = $this->p_country;
			$this->m_p_country = $this->p_country;
		}	
		if ($this->p_mobile)
		{
			$this->Value["p_mobile"] = $this->p_mobile;
			$this->m_p_mobile = $this->p_mobile;
		}	
		if ($this->p_avatar)
		{
			$this->Value["p_avatar"] = $this->p_avatar;
			$this->m_p_avatar = $this->p_avatar;
		}	
	
		if ($this->Value["p_id"])
		    $this->m_p_id = $this->Value["p_id"];	
		if ($this->Value["p_active"])
		    $this->m_p_active = $this->Value["p_active"];	
		if ($this->Value["p_location"])
		    $this->m_p_location = $this->Value["p_location"];	
		if ($this->Value["p_s_id"])
		    $this->m_p_s_id = $this->Value["p_s_id"];	
		if ($this->Value["p_first"])
		    $this->m_p_first = $this->Value["p_first"];	
		if ($this->Value["p_name"])
		    $this->m_p_name = $this->Value["p_name"];	
		if ($this->Value["p_adr"])
		    $this->m_p_adr = $this->Value["p_adr"];	
		if ($this->Value["p_zip"])
		    $this->m_p_zip = $this->Value["p_zip"];	
		if ($this->Value["p_mail"])
		    $this->m_p_mail = $this->Value["p_mail"];	
		if ($this->Value["p_newsaccept"])
		    $this->m_p_newsaccept = $this->Value["p_newsaccept"];	
		if ($this->Value["p_score"])
		    $this->m_p_score = $this->Value["p_score"];	
		if ($this->Value["p_scorehigh"])
		    $this->m_p_scorehigh = $this->Value["p_scorehigh"];	
		if ($this->Value["p_games"])
		    $this->m_p_games = $this->Value["p_games"];	
		if ($this->Value["p_time"])
		    $this->m_p_time = $this->Value["p_time"];	
		if ($this->Value["p_win"])
		    $this->m_p_win = $this->Value["p_win"];	
		if ($this->Value["p_mk"])
		    $this->m_p_mk = $this->Value["p_mk"];	
		if ($this->Value["p_born"])
		    $this->m_p_born = $this->Value["p_born"];	
		if ($this->Value["p_user"])
		    $this->m_p_user = $this->Value["p_user"];	
		if ($this->Value["p_pwd"])
		    $this->m_p_pwd = $this->Value["p_pwd"];	
		if ($this->Value["p_ip"])
		    $this->m_p_ip = $this->Value["p_ip"];	
		if ($this->Value["p_datetime"])
		    $this->m_p_datetime = $this->Value["p_datetime"];	
		if ($this->Value["p_tscore"])
		    $this->m_p_tscore = $this->Value["p_tscore"];	
		if ($this->Value["p_tkills"])
		    $this->m_p_tkills = $this->Value["p_tkills"];	
		if ($this->Value["p_news"])
		    $this->m_p_news = $this->Value["p_news"];	
		if ($this->Value["p_country"])
		    $this->m_p_country = $this->Value["p_country"];	
		if ($this->Value["p_mobile"])
		    $this->m_p_mobile = $this->Value["p_mobile"];	
		if ($this->Value["p_avatar"])
		    $this->m_p_avatar = $this->Value["p_avatar"];	

		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_id"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("p_id").",";
			if (NeedQuotes(3))
				$deleteFields.= AddFieldWrappers("p_id")."='".db_addslashes($this->Value["p_id"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("p_id")."=".db_addslashes($this->Value["p_id"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_active"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("p_active").",";
			if (NeedQuotes(16))
				$deleteFields.= AddFieldWrappers("p_active")."='".db_addslashes($this->Value["p_active"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("p_active")."=".db_addslashes($this->Value["p_active"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_location"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("p_location").",";
			if (NeedQuotes(3))
				$deleteFields.= AddFieldWrappers("p_location")."='".db_addslashes($this->Value["p_location"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("p_location")."=".db_addslashes($this->Value["p_location"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_s_id"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("p_s_id").",";
			if (NeedQuotes(3))
				$deleteFields.= AddFieldWrappers("p_s_id")."='".db_addslashes($this->Value["p_s_id"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("p_s_id")."=".db_addslashes($this->Value["p_s_id"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_first"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("p_first").",";
			if (NeedQuotes(129))
				$deleteFields.= AddFieldWrappers("p_first")."='".db_addslashes($this->Value["p_first"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("p_first")."=".db_addslashes($this->Value["p_first"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_name"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("p_name").",";
			if (NeedQuotes(129))
				$deleteFields.= AddFieldWrappers("p_name")."='".db_addslashes($this->Value["p_name"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("p_name")."=".db_addslashes($this->Value["p_name"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_adr"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("p_adr").",";
			if (NeedQuotes(129))
				$deleteFields.= AddFieldWrappers("p_adr")."='".db_addslashes($this->Value["p_adr"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("p_adr")."=".db_addslashes($this->Value["p_adr"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_zip"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("p_zip").",";
			if (NeedQuotes(3))
				$deleteFields.= AddFieldWrappers("p_zip")."='".db_addslashes($this->Value["p_zip"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("p_zip")."=".db_addslashes($this->Value["p_zip"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_mail"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("p_mail").",";
			if (NeedQuotes(129))
				$deleteFields.= AddFieldWrappers("p_mail")."='".db_addslashes($this->Value["p_mail"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("p_mail")."=".db_addslashes($this->Value["p_mail"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_newsaccept"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("p_newsaccept").",";
			if (NeedQuotes(16))
				$deleteFields.= AddFieldWrappers("p_newsaccept")."='".db_addslashes($this->Value["p_newsaccept"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("p_newsaccept")."=".db_addslashes($this->Value["p_newsaccept"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_score"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("p_score").",";
			if (NeedQuotes(3))
				$deleteFields.= AddFieldWrappers("p_score")."='".db_addslashes($this->Value["p_score"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("p_score")."=".db_addslashes($this->Value["p_score"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_scorehigh"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("p_scorehigh").",";
			if (NeedQuotes(3))
				$deleteFields.= AddFieldWrappers("p_scorehigh")."='".db_addslashes($this->Value["p_scorehigh"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("p_scorehigh")."=".db_addslashes($this->Value["p_scorehigh"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_games"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("p_games").",";
			if (NeedQuotes(3))
				$deleteFields.= AddFieldWrappers("p_games")."='".db_addslashes($this->Value["p_games"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("p_games")."=".db_addslashes($this->Value["p_games"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_time"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("p_time").",";
			if (NeedQuotes(134))
				$deleteFields.= AddFieldWrappers("p_time")."='".db_addslashes($this->Value["p_time"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("p_time")."=".db_addslashes($this->Value["p_time"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_win"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("p_win").",";
			if (NeedQuotes(3))
				$deleteFields.= AddFieldWrappers("p_win")."='".db_addslashes($this->Value["p_win"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("p_win")."=".db_addslashes($this->Value["p_win"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_mk"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("p_mk").",";
			if (NeedQuotes(129))
				$deleteFields.= AddFieldWrappers("p_mk")."='".db_addslashes($this->Value["p_mk"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("p_mk")."=".db_addslashes($this->Value["p_mk"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_born"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("p_born").",";
			if (NeedQuotes(13))
				$deleteFields.= AddFieldWrappers("p_born")."='".db_addslashes($this->Value["p_born"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("p_born")."=".db_addslashes($this->Value["p_born"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_user"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("p_user").",";
			if (NeedQuotes(129))
				$deleteFields.= AddFieldWrappers("p_user")."='".db_addslashes($this->Value["p_user"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("p_user")."=".db_addslashes($this->Value["p_user"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_pwd"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("p_pwd").",";
			if (NeedQuotes(129))
				$deleteFields.= AddFieldWrappers("p_pwd")."='".db_addslashes($this->Value["p_pwd"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("p_pwd")."=".db_addslashes($this->Value["p_pwd"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_ip"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("p_ip").",";
			if (NeedQuotes(129))
				$deleteFields.= AddFieldWrappers("p_ip")."='".db_addslashes($this->Value["p_ip"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("p_ip")."=".db_addslashes($this->Value["p_ip"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_datetime"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("p_datetime").",";
			if (NeedQuotes(135))
				$deleteFields.= AddFieldWrappers("p_datetime")."='".db_addslashes($this->Value["p_datetime"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("p_datetime")."=".db_addslashes($this->Value["p_datetime"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_tscore"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("p_tscore").",";
			if (NeedQuotes(3))
				$deleteFields.= AddFieldWrappers("p_tscore")."='".db_addslashes($this->Value["p_tscore"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("p_tscore")."=".db_addslashes($this->Value["p_tscore"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_tkills"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("p_tkills").",";
			if (NeedQuotes(3))
				$deleteFields.= AddFieldWrappers("p_tkills")."='".db_addslashes($this->Value["p_tkills"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("p_tkills")."=".db_addslashes($this->Value["p_tkills"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_news"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("p_news").",";
			if (NeedQuotes(2))
				$deleteFields.= AddFieldWrappers("p_news")."='".db_addslashes($this->Value["p_news"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("p_news")."=".db_addslashes($this->Value["p_news"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_country"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("p_country").",";
			if (NeedQuotes(129))
				$deleteFields.= AddFieldWrappers("p_country")."='".db_addslashes($this->Value["p_country"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("p_country")."=".db_addslashes($this->Value["p_country"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_mobile"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("p_mobile").",";
			if (NeedQuotes(20))
				$deleteFields.= AddFieldWrappers("p_mobile")."='".db_addslashes($this->Value["p_mobile"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("p_mobile")."=".db_addslashes($this->Value["p_mobile"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_avatar"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("p_avatar").",";
			if (NeedQuotes(16))
				$deleteFields.= AddFieldWrappers("p_avatar")."='".db_addslashes($this->Value["p_avatar"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("p_avatar")."=".db_addslashes($this->Value["p_avatar"]) . " and ";		
		}

	if ($deleteFields)
	{
		$deleteFields = substr($deleteFields,0,-5);
		$dalSQL = "delete from ".$this->m_GoodTableName." where ".$deleteFields;
		db_exec($dalSQL,$conn);
	}
	
	    unset($this->Value["p_id"]);
	    unset($this->Value["p_active"]);
	    unset($this->Value["p_location"]);
	    unset($this->Value["p_s_id"]);
	    unset($this->Value["p_first"]);
	    unset($this->Value["p_name"]);
	    unset($this->Value["p_adr"]);
	    unset($this->Value["p_zip"]);
	    unset($this->Value["p_mail"]);
	    unset($this->Value["p_newsaccept"]);
	    unset($this->Value["p_score"]);
	    unset($this->Value["p_scorehigh"]);
	    unset($this->Value["p_games"]);
	    unset($this->Value["p_time"]);
	    unset($this->Value["p_win"]);
	    unset($this->Value["p_mk"]);
	    unset($this->Value["p_born"]);
	    unset($this->Value["p_user"]);
	    unset($this->Value["p_pwd"]);
	    unset($this->Value["p_ip"]);
	    unset($this->Value["p_datetime"]);
	    unset($this->Value["p_tscore"]);
	    unset($this->Value["p_tkills"]);
	    unset($this->Value["p_news"]);
	    unset($this->Value["p_country"]);
	    unset($this->Value["p_mobile"]);
	    unset($this->Value["p_avatar"]);
		
	//unset($this->Value);	
}

function Update()
{
	global $conn;
	
	$updateParam = "";
	$updateValue = "";
	
		if ($this->p_id)
		{
			if (1==1)
				$this->Param["p_id"] = $this->p_id;
			else
				$this->Value["p_id"] = $this->p_id;
			$this->m_p_id = $this->p_id;
		}	
		if ($this->p_active)
		{
			if (0==1)
				$this->Param["p_active"] = $this->p_active;
			else
				$this->Value["p_active"] = $this->p_active;
			$this->m_p_active = $this->p_active;
		}	
		if ($this->p_location)
		{
			if (0==1)
				$this->Param["p_location"] = $this->p_location;
			else
				$this->Value["p_location"] = $this->p_location;
			$this->m_p_location = $this->p_location;
		}	
		if ($this->p_s_id)
		{
			if (0==1)
				$this->Param["p_s_id"] = $this->p_s_id;
			else
				$this->Value["p_s_id"] = $this->p_s_id;
			$this->m_p_s_id = $this->p_s_id;
		}	
		if ($this->p_first)
		{
			if (0==1)
				$this->Param["p_first"] = $this->p_first;
			else
				$this->Value["p_first"] = $this->p_first;
			$this->m_p_first = $this->p_first;
		}	
		if ($this->p_name)
		{
			if (0==1)
				$this->Param["p_name"] = $this->p_name;
			else
				$this->Value["p_name"] = $this->p_name;
			$this->m_p_name = $this->p_name;
		}	
		if ($this->p_adr)
		{
			if (0==1)
				$this->Param["p_adr"] = $this->p_adr;
			else
				$this->Value["p_adr"] = $this->p_adr;
			$this->m_p_adr = $this->p_adr;
		}	
		if ($this->p_zip)
		{
			if (0==1)
				$this->Param["p_zip"] = $this->p_zip;
			else
				$this->Value["p_zip"] = $this->p_zip;
			$this->m_p_zip = $this->p_zip;
		}	
		if ($this->p_mail)
		{
			if (0==1)
				$this->Param["p_mail"] = $this->p_mail;
			else
				$this->Value["p_mail"] = $this->p_mail;
			$this->m_p_mail = $this->p_mail;
		}	
		if ($this->p_newsaccept)
		{
			if (0==1)
				$this->Param["p_newsaccept"] = $this->p_newsaccept;
			else
				$this->Value["p_newsaccept"] = $this->p_newsaccept;
			$this->m_p_newsaccept = $this->p_newsaccept;
		}	
		if ($this->p_score)
		{
			if (0==1)
				$this->Param["p_score"] = $this->p_score;
			else
				$this->Value["p_score"] = $this->p_score;
			$this->m_p_score = $this->p_score;
		}	
		if ($this->p_scorehigh)
		{
			if (0==1)
				$this->Param["p_scorehigh"] = $this->p_scorehigh;
			else
				$this->Value["p_scorehigh"] = $this->p_scorehigh;
			$this->m_p_scorehigh = $this->p_scorehigh;
		}	
		if ($this->p_games)
		{
			if (0==1)
				$this->Param["p_games"] = $this->p_games;
			else
				$this->Value["p_games"] = $this->p_games;
			$this->m_p_games = $this->p_games;
		}	
		if ($this->p_time)
		{
			if (0==1)
				$this->Param["p_time"] = $this->p_time;
			else
				$this->Value["p_time"] = $this->p_time;
			$this->m_p_time = $this->p_time;
		}	
		if ($this->p_win)
		{
			if (0==1)
				$this->Param["p_win"] = $this->p_win;
			else
				$this->Value["p_win"] = $this->p_win;
			$this->m_p_win = $this->p_win;
		}	
		if ($this->p_mk)
		{
			if (0==1)
				$this->Param["p_mk"] = $this->p_mk;
			else
				$this->Value["p_mk"] = $this->p_mk;
			$this->m_p_mk = $this->p_mk;
		}	
		if ($this->p_born)
		{
			if (0==1)
				$this->Param["p_born"] = $this->p_born;
			else
				$this->Value["p_born"] = $this->p_born;
			$this->m_p_born = $this->p_born;
		}	
		if ($this->p_user)
		{
			if (0==1)
				$this->Param["p_user"] = $this->p_user;
			else
				$this->Value["p_user"] = $this->p_user;
			$this->m_p_user = $this->p_user;
		}	
		if ($this->p_pwd)
		{
			if (0==1)
				$this->Param["p_pwd"] = $this->p_pwd;
			else
				$this->Value["p_pwd"] = $this->p_pwd;
			$this->m_p_pwd = $this->p_pwd;
		}	
		if ($this->p_ip)
		{
			if (0==1)
				$this->Param["p_ip"] = $this->p_ip;
			else
				$this->Value["p_ip"] = $this->p_ip;
			$this->m_p_ip = $this->p_ip;
		}	
		if ($this->p_datetime)
		{
			if (0==1)
				$this->Param["p_datetime"] = $this->p_datetime;
			else
				$this->Value["p_datetime"] = $this->p_datetime;
			$this->m_p_datetime = $this->p_datetime;
		}	
		if ($this->p_tscore)
		{
			if (0==1)
				$this->Param["p_tscore"] = $this->p_tscore;
			else
				$this->Value["p_tscore"] = $this->p_tscore;
			$this->m_p_tscore = $this->p_tscore;
		}	
		if ($this->p_tkills)
		{
			if (0==1)
				$this->Param["p_tkills"] = $this->p_tkills;
			else
				$this->Value["p_tkills"] = $this->p_tkills;
			$this->m_p_tkills = $this->p_tkills;
		}	
		if ($this->p_news)
		{
			if (0==1)
				$this->Param["p_news"] = $this->p_news;
			else
				$this->Value["p_news"] = $this->p_news;
			$this->m_p_news = $this->p_news;
		}	
		if ($this->p_country)
		{
			if (0==1)
				$this->Param["p_country"] = $this->p_country;
			else
				$this->Value["p_country"] = $this->p_country;
			$this->m_p_country = $this->p_country;
		}	
		if ($this->p_mobile)
		{
			if (0==1)
				$this->Param["p_mobile"] = $this->p_mobile;
			else
				$this->Value["p_mobile"] = $this->p_mobile;
			$this->m_p_mobile = $this->p_mobile;
		}	
		if ($this->p_avatar)
		{
			if (0==1)
				$this->Param["p_avatar"] = $this->p_avatar;
			else
				$this->Value["p_avatar"] = $this->p_avatar;
			$this->m_p_avatar = $this->p_avatar;
		}	
	
		if ($this->Value["p_id"])
		    $this->m_p_id = $this->Value["p_id"];		
		if ($this->Value["p_active"])
		    $this->m_p_active = $this->Value["p_active"];		
		if ($this->Value["p_location"])
		    $this->m_p_location = $this->Value["p_location"];		
		if ($this->Value["p_s_id"])
		    $this->m_p_s_id = $this->Value["p_s_id"];		
		if ($this->Value["p_first"])
		    $this->m_p_first = $this->Value["p_first"];		
		if ($this->Value["p_name"])
		    $this->m_p_name = $this->Value["p_name"];		
		if ($this->Value["p_adr"])
		    $this->m_p_adr = $this->Value["p_adr"];		
		if ($this->Value["p_zip"])
		    $this->m_p_zip = $this->Value["p_zip"];		
		if ($this->Value["p_mail"])
		    $this->m_p_mail = $this->Value["p_mail"];		
		if ($this->Value["p_newsaccept"])
		    $this->m_p_newsaccept = $this->Value["p_newsaccept"];		
		if ($this->Value["p_score"])
		    $this->m_p_score = $this->Value["p_score"];		
		if ($this->Value["p_scorehigh"])
		    $this->m_p_scorehigh = $this->Value["p_scorehigh"];		
		if ($this->Value["p_games"])
		    $this->m_p_games = $this->Value["p_games"];		
		if ($this->Value["p_time"])
		    $this->m_p_time = $this->Value["p_time"];		
		if ($this->Value["p_win"])
		    $this->m_p_win = $this->Value["p_win"];		
		if ($this->Value["p_mk"])
		    $this->m_p_mk = $this->Value["p_mk"];		
		if ($this->Value["p_born"])
		    $this->m_p_born = $this->Value["p_born"];		
		if ($this->Value["p_user"])
		    $this->m_p_user = $this->Value["p_user"];		
		if ($this->Value["p_pwd"])
		    $this->m_p_pwd = $this->Value["p_pwd"];		
		if ($this->Value["p_ip"])
		    $this->m_p_ip = $this->Value["p_ip"];		
		if ($this->Value["p_datetime"])
		    $this->m_p_datetime = $this->Value["p_datetime"];		
		if ($this->Value["p_tscore"])
		    $this->m_p_tscore = $this->Value["p_tscore"];		
		if ($this->Value["p_tkills"])
		    $this->m_p_tkills = $this->Value["p_tkills"];		
		if ($this->Value["p_news"])
		    $this->m_p_news = $this->Value["p_news"];		
		if ($this->Value["p_country"])
		    $this->m_p_country = $this->Value["p_country"];		
		if ($this->Value["p_mobile"])
		    $this->m_p_mobile = $this->Value["p_mobile"];		
		if ($this->Value["p_avatar"])
		    $this->m_p_avatar = $this->Value["p_avatar"];		
	
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_id"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateValue.= AddFieldWrappers("p_id")."='".$this->Value["p_id"]."', ";
			else
					$updateValue.= AddFieldWrappers("p_id")."=".$this->Value["p_id"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_id"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateParam.= AddFieldWrappers("p_id")."='".db_addslashes($this->Param["p_id"])."' and ";
			else
					$updateParam.= AddFieldWrappers("p_id")."=".db_addslashes($this->Param["p_id"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_active"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(16))
					$updateValue.= AddFieldWrappers("p_active")."='".$this->Value["p_active"]."', ";
			else
					$updateValue.= AddFieldWrappers("p_active")."=".$this->Value["p_active"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_active"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(16))
					$updateParam.= AddFieldWrappers("p_active")."='".db_addslashes($this->Param["p_active"])."' and ";
			else
					$updateParam.= AddFieldWrappers("p_active")."=".db_addslashes($this->Param["p_active"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_location"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateValue.= AddFieldWrappers("p_location")."='".$this->Value["p_location"]."', ";
			else
					$updateValue.= AddFieldWrappers("p_location")."=".$this->Value["p_location"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_location"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateParam.= AddFieldWrappers("p_location")."='".db_addslashes($this->Param["p_location"])."' and ";
			else
					$updateParam.= AddFieldWrappers("p_location")."=".db_addslashes($this->Param["p_location"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_s_id"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateValue.= AddFieldWrappers("p_s_id")."='".$this->Value["p_s_id"]."', ";
			else
					$updateValue.= AddFieldWrappers("p_s_id")."=".$this->Value["p_s_id"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_s_id"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateParam.= AddFieldWrappers("p_s_id")."='".db_addslashes($this->Param["p_s_id"])."' and ";
			else
					$updateParam.= AddFieldWrappers("p_s_id")."=".db_addslashes($this->Param["p_s_id"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_first"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(129))
					$updateValue.= AddFieldWrappers("p_first")."='".$this->Value["p_first"]."', ";
			else
					$updateValue.= AddFieldWrappers("p_first")."=".$this->Value["p_first"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_first"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(129))
					$updateParam.= AddFieldWrappers("p_first")."='".db_addslashes($this->Param["p_first"])."' and ";
			else
					$updateParam.= AddFieldWrappers("p_first")."=".db_addslashes($this->Param["p_first"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_name"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(129))
					$updateValue.= AddFieldWrappers("p_name")."='".$this->Value["p_name"]."', ";
			else
					$updateValue.= AddFieldWrappers("p_name")."=".$this->Value["p_name"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_name"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(129))
					$updateParam.= AddFieldWrappers("p_name")."='".db_addslashes($this->Param["p_name"])."' and ";
			else
					$updateParam.= AddFieldWrappers("p_name")."=".db_addslashes($this->Param["p_name"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_adr"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(129))
					$updateValue.= AddFieldWrappers("p_adr")."='".$this->Value["p_adr"]."', ";
			else
					$updateValue.= AddFieldWrappers("p_adr")."=".$this->Value["p_adr"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_adr"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(129))
					$updateParam.= AddFieldWrappers("p_adr")."='".db_addslashes($this->Param["p_adr"])."' and ";
			else
					$updateParam.= AddFieldWrappers("p_adr")."=".db_addslashes($this->Param["p_adr"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_zip"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateValue.= AddFieldWrappers("p_zip")."='".$this->Value["p_zip"]."', ";
			else
					$updateValue.= AddFieldWrappers("p_zip")."=".$this->Value["p_zip"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_zip"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateParam.= AddFieldWrappers("p_zip")."='".db_addslashes($this->Param["p_zip"])."' and ";
			else
					$updateParam.= AddFieldWrappers("p_zip")."=".db_addslashes($this->Param["p_zip"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_mail"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(129))
					$updateValue.= AddFieldWrappers("p_mail")."='".$this->Value["p_mail"]."', ";
			else
					$updateValue.= AddFieldWrappers("p_mail")."=".$this->Value["p_mail"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_mail"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(129))
					$updateParam.= AddFieldWrappers("p_mail")."='".db_addslashes($this->Param["p_mail"])."' and ";
			else
					$updateParam.= AddFieldWrappers("p_mail")."=".db_addslashes($this->Param["p_mail"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_newsaccept"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(16))
					$updateValue.= AddFieldWrappers("p_newsaccept")."='".$this->Value["p_newsaccept"]."', ";
			else
					$updateValue.= AddFieldWrappers("p_newsaccept")."=".$this->Value["p_newsaccept"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_newsaccept"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(16))
					$updateParam.= AddFieldWrappers("p_newsaccept")."='".db_addslashes($this->Param["p_newsaccept"])."' and ";
			else
					$updateParam.= AddFieldWrappers("p_newsaccept")."=".db_addslashes($this->Param["p_newsaccept"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_score"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateValue.= AddFieldWrappers("p_score")."='".$this->Value["p_score"]."', ";
			else
					$updateValue.= AddFieldWrappers("p_score")."=".$this->Value["p_score"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_score"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateParam.= AddFieldWrappers("p_score")."='".db_addslashes($this->Param["p_score"])."' and ";
			else
					$updateParam.= AddFieldWrappers("p_score")."=".db_addslashes($this->Param["p_score"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_scorehigh"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateValue.= AddFieldWrappers("p_scorehigh")."='".$this->Value["p_scorehigh"]."', ";
			else
					$updateValue.= AddFieldWrappers("p_scorehigh")."=".$this->Value["p_scorehigh"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_scorehigh"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateParam.= AddFieldWrappers("p_scorehigh")."='".db_addslashes($this->Param["p_scorehigh"])."' and ";
			else
					$updateParam.= AddFieldWrappers("p_scorehigh")."=".db_addslashes($this->Param["p_scorehigh"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_games"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateValue.= AddFieldWrappers("p_games")."='".$this->Value["p_games"]."', ";
			else
					$updateValue.= AddFieldWrappers("p_games")."=".$this->Value["p_games"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_games"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateParam.= AddFieldWrappers("p_games")."='".db_addslashes($this->Param["p_games"])."' and ";
			else
					$updateParam.= AddFieldWrappers("p_games")."=".db_addslashes($this->Param["p_games"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_time"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(134))
					$updateValue.= AddFieldWrappers("p_time")."='".$this->Value["p_time"]."', ";
			else
					$updateValue.= AddFieldWrappers("p_time")."=".$this->Value["p_time"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_time"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(134))
					$updateParam.= AddFieldWrappers("p_time")."='".db_addslashes($this->Param["p_time"])."' and ";
			else
					$updateParam.= AddFieldWrappers("p_time")."=".db_addslashes($this->Param["p_time"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_win"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateValue.= AddFieldWrappers("p_win")."='".$this->Value["p_win"]."', ";
			else
					$updateValue.= AddFieldWrappers("p_win")."=".$this->Value["p_win"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_win"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateParam.= AddFieldWrappers("p_win")."='".db_addslashes($this->Param["p_win"])."' and ";
			else
					$updateParam.= AddFieldWrappers("p_win")."=".db_addslashes($this->Param["p_win"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_mk"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(129))
					$updateValue.= AddFieldWrappers("p_mk")."='".$this->Value["p_mk"]."', ";
			else
					$updateValue.= AddFieldWrappers("p_mk")."=".$this->Value["p_mk"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_mk"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(129))
					$updateParam.= AddFieldWrappers("p_mk")."='".db_addslashes($this->Param["p_mk"])."' and ";
			else
					$updateParam.= AddFieldWrappers("p_mk")."=".db_addslashes($this->Param["p_mk"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_born"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(13))
					$updateValue.= AddFieldWrappers("p_born")."='".$this->Value["p_born"]."', ";
			else
					$updateValue.= AddFieldWrappers("p_born")."=".$this->Value["p_born"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_born"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(13))
					$updateParam.= AddFieldWrappers("p_born")."='".db_addslashes($this->Param["p_born"])."' and ";
			else
					$updateParam.= AddFieldWrappers("p_born")."=".db_addslashes($this->Param["p_born"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_user"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(129))
					$updateValue.= AddFieldWrappers("p_user")."='".$this->Value["p_user"]."', ";
			else
					$updateValue.= AddFieldWrappers("p_user")."=".$this->Value["p_user"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_user"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(129))
					$updateParam.= AddFieldWrappers("p_user")."='".db_addslashes($this->Param["p_user"])."' and ";
			else
					$updateParam.= AddFieldWrappers("p_user")."=".db_addslashes($this->Param["p_user"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_pwd"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(129))
					$updateValue.= AddFieldWrappers("p_pwd")."='".$this->Value["p_pwd"]."', ";
			else
					$updateValue.= AddFieldWrappers("p_pwd")."=".$this->Value["p_pwd"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_pwd"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(129))
					$updateParam.= AddFieldWrappers("p_pwd")."='".db_addslashes($this->Param["p_pwd"])."' and ";
			else
					$updateParam.= AddFieldWrappers("p_pwd")."=".db_addslashes($this->Param["p_pwd"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_ip"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(129))
					$updateValue.= AddFieldWrappers("p_ip")."='".$this->Value["p_ip"]."', ";
			else
					$updateValue.= AddFieldWrappers("p_ip")."=".$this->Value["p_ip"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_ip"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(129))
					$updateParam.= AddFieldWrappers("p_ip")."='".db_addslashes($this->Param["p_ip"])."' and ";
			else
					$updateParam.= AddFieldWrappers("p_ip")."=".db_addslashes($this->Param["p_ip"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_datetime"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(135))
					$updateValue.= AddFieldWrappers("p_datetime")."='".$this->Value["p_datetime"]."', ";
			else
					$updateValue.= AddFieldWrappers("p_datetime")."=".$this->Value["p_datetime"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_datetime"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(135))
					$updateParam.= AddFieldWrappers("p_datetime")."='".db_addslashes($this->Param["p_datetime"])."' and ";
			else
					$updateParam.= AddFieldWrappers("p_datetime")."=".db_addslashes($this->Param["p_datetime"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_tscore"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateValue.= AddFieldWrappers("p_tscore")."='".$this->Value["p_tscore"]."', ";
			else
					$updateValue.= AddFieldWrappers("p_tscore")."=".$this->Value["p_tscore"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_tscore"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateParam.= AddFieldWrappers("p_tscore")."='".db_addslashes($this->Param["p_tscore"])."' and ";
			else
					$updateParam.= AddFieldWrappers("p_tscore")."=".db_addslashes($this->Param["p_tscore"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_tkills"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateValue.= AddFieldWrappers("p_tkills")."='".$this->Value["p_tkills"]."', ";
			else
					$updateValue.= AddFieldWrappers("p_tkills")."=".$this->Value["p_tkills"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_tkills"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateParam.= AddFieldWrappers("p_tkills")."='".db_addslashes($this->Param["p_tkills"])."' and ";
			else
					$updateParam.= AddFieldWrappers("p_tkills")."=".db_addslashes($this->Param["p_tkills"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_news"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(2))
					$updateValue.= AddFieldWrappers("p_news")."='".$this->Value["p_news"]."', ";
			else
					$updateValue.= AddFieldWrappers("p_news")."=".$this->Value["p_news"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_news"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(2))
					$updateParam.= AddFieldWrappers("p_news")."='".db_addslashes($this->Param["p_news"])."' and ";
			else
					$updateParam.= AddFieldWrappers("p_news")."=".db_addslashes($this->Param["p_news"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_country"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(129))
					$updateValue.= AddFieldWrappers("p_country")."='".$this->Value["p_country"]."', ";
			else
					$updateValue.= AddFieldWrappers("p_country")."=".$this->Value["p_country"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_country"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(129))
					$updateParam.= AddFieldWrappers("p_country")."='".db_addslashes($this->Param["p_country"])."' and ";
			else
					$updateParam.= AddFieldWrappers("p_country")."=".db_addslashes($this->Param["p_country"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_mobile"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(20))
					$updateValue.= AddFieldWrappers("p_mobile")."='".$this->Value["p_mobile"]."', ";
			else
					$updateValue.= AddFieldWrappers("p_mobile")."=".$this->Value["p_mobile"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_mobile"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(20))
					$updateParam.= AddFieldWrappers("p_mobile")."='".db_addslashes($this->Param["p_mobile"])."' and ";
			else
					$updateParam.= AddFieldWrappers("p_mobile")."=".db_addslashes($this->Param["p_mobile"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("p_avatar"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(16))
					$updateValue.= AddFieldWrappers("p_avatar")."='".$this->Value["p_avatar"]."', ";
			else
					$updateValue.= AddFieldWrappers("p_avatar")."=".$this->Value["p_avatar"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_avatar"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(16))
					$updateParam.= AddFieldWrappers("p_avatar")."='".db_addslashes($this->Param["p_avatar"])."' and ";
			else
					$updateParam.= AddFieldWrappers("p_avatar")."=".db_addslashes($this->Param["p_avatar"])." and ";
		}
		
	
	if ($updateParam)
		$updateParam = substr($updateParam,0,-5);
	if ($updateValue)
		$updateValue = substr($updateValue,0,-2);
		
	if ($updateValue)
	{
		$dalSQL = "update ".$this->m_GoodTableName." set ".$updateValue." where ".$updateParam;
		db_exec($dalSQL,$conn);
	}
	
        unset($this->Value["p_id"]);
		unset($this->Param["p_id"]);
        unset($this->Value["p_active"]);
		unset($this->Param["p_active"]);
        unset($this->Value["p_location"]);
		unset($this->Param["p_location"]);
        unset($this->Value["p_s_id"]);
		unset($this->Param["p_s_id"]);
        unset($this->Value["p_first"]);
		unset($this->Param["p_first"]);
        unset($this->Value["p_name"]);
		unset($this->Param["p_name"]);
        unset($this->Value["p_adr"]);
		unset($this->Param["p_adr"]);
        unset($this->Value["p_zip"]);
		unset($this->Param["p_zip"]);
        unset($this->Value["p_mail"]);
		unset($this->Param["p_mail"]);
        unset($this->Value["p_newsaccept"]);
		unset($this->Param["p_newsaccept"]);
        unset($this->Value["p_score"]);
		unset($this->Param["p_score"]);
        unset($this->Value["p_scorehigh"]);
		unset($this->Param["p_scorehigh"]);
        unset($this->Value["p_games"]);
		unset($this->Param["p_games"]);
        unset($this->Value["p_time"]);
		unset($this->Param["p_time"]);
        unset($this->Value["p_win"]);
		unset($this->Param["p_win"]);
        unset($this->Value["p_mk"]);
		unset($this->Param["p_mk"]);
        unset($this->Value["p_born"]);
		unset($this->Param["p_born"]);
        unset($this->Value["p_user"]);
		unset($this->Param["p_user"]);
        unset($this->Value["p_pwd"]);
		unset($this->Param["p_pwd"]);
        unset($this->Value["p_ip"]);
		unset($this->Param["p_ip"]);
        unset($this->Value["p_datetime"]);
		unset($this->Param["p_datetime"]);
        unset($this->Value["p_tscore"]);
		unset($this->Param["p_tscore"]);
        unset($this->Value["p_tkills"]);
		unset($this->Param["p_tkills"]);
        unset($this->Value["p_news"]);
		unset($this->Param["p_news"]);
        unset($this->Value["p_country"]);
		unset($this->Param["p_country"]);
        unset($this->Value["p_mobile"]);
		unset($this->Param["p_mobile"]);
        unset($this->Value["p_avatar"]);
		unset($this->Param["p_avatar"]);
	
}

function QueryAll()
{
	global $conn;
	$dal_variables="";
		$dal_variables.= AddFieldWrappers("p_id").", ";
		$dal_variables.= AddFieldWrappers("p_active").", ";
		$dal_variables.= AddFieldWrappers("p_location").", ";
		$dal_variables.= AddFieldWrappers("p_s_id").", ";
		$dal_variables.= AddFieldWrappers("p_first").", ";
		$dal_variables.= AddFieldWrappers("p_name").", ";
		$dal_variables.= AddFieldWrappers("p_adr").", ";
		$dal_variables.= AddFieldWrappers("p_zip").", ";
		$dal_variables.= AddFieldWrappers("p_mail").", ";
		$dal_variables.= AddFieldWrappers("p_newsaccept").", ";
		$dal_variables.= AddFieldWrappers("p_score").", ";
		$dal_variables.= AddFieldWrappers("p_scorehigh").", ";
		$dal_variables.= AddFieldWrappers("p_games").", ";
		$dal_variables.= AddFieldWrappers("p_time").", ";
		$dal_variables.= AddFieldWrappers("p_win").", ";
		$dal_variables.= AddFieldWrappers("p_mk").", ";
		$dal_variables.= AddFieldWrappers("p_born").", ";
		$dal_variables.= AddFieldWrappers("p_user").", ";
		$dal_variables.= AddFieldWrappers("p_pwd").", ";
		$dal_variables.= AddFieldWrappers("p_ip").", ";
		$dal_variables.= AddFieldWrappers("p_datetime").", ";
		$dal_variables.= AddFieldWrappers("p_tscore").", ";
		$dal_variables.= AddFieldWrappers("p_tkills").", ";
		$dal_variables.= AddFieldWrappers("p_news").", ";
		$dal_variables.= AddFieldWrappers("p_country").", ";
		$dal_variables.= AddFieldWrappers("p_mobile").", ";
		$dal_variables.= AddFieldWrappers("p_avatar").", ";
	$dal_variables = substr($dal_variables,0,-2);
	$dalSQL = "select ".$dal_variables." from ".$this->m_GoodTableName;
	$rs = db_query($dalSQL,$conn);
	$data = db_fetch_array($rs);
	return $data;
}

function Query($swhere,$orderby)
{
	global $conn;
	$dal_variables="";
		$dal_variables.= AddFieldWrappers("p_id").", ";
		$dal_variables.= AddFieldWrappers("p_active").", ";
		$dal_variables.= AddFieldWrappers("p_location").", ";
		$dal_variables.= AddFieldWrappers("p_s_id").", ";
		$dal_variables.= AddFieldWrappers("p_first").", ";
		$dal_variables.= AddFieldWrappers("p_name").", ";
		$dal_variables.= AddFieldWrappers("p_adr").", ";
		$dal_variables.= AddFieldWrappers("p_zip").", ";
		$dal_variables.= AddFieldWrappers("p_mail").", ";
		$dal_variables.= AddFieldWrappers("p_newsaccept").", ";
		$dal_variables.= AddFieldWrappers("p_score").", ";
		$dal_variables.= AddFieldWrappers("p_scorehigh").", ";
		$dal_variables.= AddFieldWrappers("p_games").", ";
		$dal_variables.= AddFieldWrappers("p_time").", ";
		$dal_variables.= AddFieldWrappers("p_win").", ";
		$dal_variables.= AddFieldWrappers("p_mk").", ";
		$dal_variables.= AddFieldWrappers("p_born").", ";
		$dal_variables.= AddFieldWrappers("p_user").", ";
		$dal_variables.= AddFieldWrappers("p_pwd").", ";
		$dal_variables.= AddFieldWrappers("p_ip").", ";
		$dal_variables.= AddFieldWrappers("p_datetime").", ";
		$dal_variables.= AddFieldWrappers("p_tscore").", ";
		$dal_variables.= AddFieldWrappers("p_tkills").", ";
		$dal_variables.= AddFieldWrappers("p_news").", ";
		$dal_variables.= AddFieldWrappers("p_country").", ";
		$dal_variables.= AddFieldWrappers("p_mobile").", ";
		$dal_variables.= AddFieldWrappers("p_avatar").", ";
	$dal_variables = substr($dal_variables,0,-2);
	if ($swhere)
		$swhere = " where ".$swhere;
	if ($orderby)
		$orderby = " order by ".$orderby;
	
	$dalSQL = "select ".$dal_variables." from ".$this->m_GoodTableName.$swhere.$orderby;
	$rs = db_query($dalSQL,$conn);
	$data = db_fetch_array($rs);
	return $data; 	
}

function FetchByID()
{
	global $conn;
	$dal_variables="";
	$dal_where="";
        $dal_variables.= AddFieldWrappers("p_id").", ";
        $dal_variables.= AddFieldWrappers("p_active").", ";
        $dal_variables.= AddFieldWrappers("p_location").", ";
        $dal_variables.= AddFieldWrappers("p_s_id").", ";
        $dal_variables.= AddFieldWrappers("p_first").", ";
        $dal_variables.= AddFieldWrappers("p_name").", ";
        $dal_variables.= AddFieldWrappers("p_adr").", ";
        $dal_variables.= AddFieldWrappers("p_zip").", ";
        $dal_variables.= AddFieldWrappers("p_mail").", ";
        $dal_variables.= AddFieldWrappers("p_newsaccept").", ";
        $dal_variables.= AddFieldWrappers("p_score").", ";
        $dal_variables.= AddFieldWrappers("p_scorehigh").", ";
        $dal_variables.= AddFieldWrappers("p_games").", ";
        $dal_variables.= AddFieldWrappers("p_time").", ";
        $dal_variables.= AddFieldWrappers("p_win").", ";
        $dal_variables.= AddFieldWrappers("p_mk").", ";
        $dal_variables.= AddFieldWrappers("p_born").", ";
        $dal_variables.= AddFieldWrappers("p_user").", ";
        $dal_variables.= AddFieldWrappers("p_pwd").", ";
        $dal_variables.= AddFieldWrappers("p_ip").", ";
        $dal_variables.= AddFieldWrappers("p_datetime").", ";
        $dal_variables.= AddFieldWrappers("p_tscore").", ";
        $dal_variables.= AddFieldWrappers("p_tkills").", ";
        $dal_variables.= AddFieldWrappers("p_news").", ";
        $dal_variables.= AddFieldWrappers("p_country").", ";
        $dal_variables.= AddFieldWrappers("p_mobile").", ";
        $dal_variables.= AddFieldWrappers("p_avatar").", ";
	$dal_variables = substr($dal_variables,0,-2);
	
		if ($this->p_id)
			$this->Param["p_id"] = $this->p_id;	
		if ($this->p_active)
			$this->Param["p_active"] = $this->p_active;	
		if ($this->p_location)
			$this->Param["p_location"] = $this->p_location;	
		if ($this->p_s_id)
			$this->Param["p_s_id"] = $this->p_s_id;	
		if ($this->p_first)
			$this->Param["p_first"] = $this->p_first;	
		if ($this->p_name)
			$this->Param["p_name"] = $this->p_name;	
		if ($this->p_adr)
			$this->Param["p_adr"] = $this->p_adr;	
		if ($this->p_zip)
			$this->Param["p_zip"] = $this->p_zip;	
		if ($this->p_mail)
			$this->Param["p_mail"] = $this->p_mail;	
		if ($this->p_newsaccept)
			$this->Param["p_newsaccept"] = $this->p_newsaccept;	
		if ($this->p_score)
			$this->Param["p_score"] = $this->p_score;	
		if ($this->p_scorehigh)
			$this->Param["p_scorehigh"] = $this->p_scorehigh;	
		if ($this->p_games)
			$this->Param["p_games"] = $this->p_games;	
		if ($this->p_time)
			$this->Param["p_time"] = $this->p_time;	
		if ($this->p_win)
			$this->Param["p_win"] = $this->p_win;	
		if ($this->p_mk)
			$this->Param["p_mk"] = $this->p_mk;	
		if ($this->p_born)
			$this->Param["p_born"] = $this->p_born;	
		if ($this->p_user)
			$this->Param["p_user"] = $this->p_user;	
		if ($this->p_pwd)
			$this->Param["p_pwd"] = $this->p_pwd;	
		if ($this->p_ip)
			$this->Param["p_ip"] = $this->p_ip;	
		if ($this->p_datetime)
			$this->Param["p_datetime"] = $this->p_datetime;	
		if ($this->p_tscore)
			$this->Param["p_tscore"] = $this->p_tscore;	
		if ($this->p_tkills)
			$this->Param["p_tkills"] = $this->p_tkills;	
		if ($this->p_news)
			$this->Param["p_news"] = $this->p_news;	
		if ($this->p_country)
			$this->Param["p_country"] = $this->p_country;	
		if ($this->p_mobile)
			$this->Param["p_mobile"] = $this->p_mobile;	
		if ($this->p_avatar)
			$this->Param["p_avatar"] = $this->p_avatar;	
	
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_id") && 1==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(3))
				$dal_where.= AddFieldWrappers("p_id")."='".db_addslashes($this->Param["p_id"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("p_id")."=".db_addslashes($this->Param["p_id"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_active") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(16))
				$dal_where.= AddFieldWrappers("p_active")."='".db_addslashes($this->Param["p_active"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("p_active")."=".db_addslashes($this->Param["p_active"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_location") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(3))
				$dal_where.= AddFieldWrappers("p_location")."='".db_addslashes($this->Param["p_location"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("p_location")."=".db_addslashes($this->Param["p_location"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_s_id") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(3))
				$dal_where.= AddFieldWrappers("p_s_id")."='".db_addslashes($this->Param["p_s_id"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("p_s_id")."=".db_addslashes($this->Param["p_s_id"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_first") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(129))
				$dal_where.= AddFieldWrappers("p_first")."='".db_addslashes($this->Param["p_first"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("p_first")."=".db_addslashes($this->Param["p_first"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_name") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(129))
				$dal_where.= AddFieldWrappers("p_name")."='".db_addslashes($this->Param["p_name"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("p_name")."=".db_addslashes($this->Param["p_name"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_adr") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(129))
				$dal_where.= AddFieldWrappers("p_adr")."='".db_addslashes($this->Param["p_adr"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("p_adr")."=".db_addslashes($this->Param["p_adr"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_zip") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(3))
				$dal_where.= AddFieldWrappers("p_zip")."='".db_addslashes($this->Param["p_zip"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("p_zip")."=".db_addslashes($this->Param["p_zip"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_mail") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(129))
				$dal_where.= AddFieldWrappers("p_mail")."='".db_addslashes($this->Param["p_mail"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("p_mail")."=".db_addslashes($this->Param["p_mail"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_newsaccept") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(16))
				$dal_where.= AddFieldWrappers("p_newsaccept")."='".db_addslashes($this->Param["p_newsaccept"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("p_newsaccept")."=".db_addslashes($this->Param["p_newsaccept"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_score") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(3))
				$dal_where.= AddFieldWrappers("p_score")."='".db_addslashes($this->Param["p_score"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("p_score")."=".db_addslashes($this->Param["p_score"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_scorehigh") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(3))
				$dal_where.= AddFieldWrappers("p_scorehigh")."='".db_addslashes($this->Param["p_scorehigh"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("p_scorehigh")."=".db_addslashes($this->Param["p_scorehigh"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_games") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(3))
				$dal_where.= AddFieldWrappers("p_games")."='".db_addslashes($this->Param["p_games"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("p_games")."=".db_addslashes($this->Param["p_games"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_time") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(134))
				$dal_where.= AddFieldWrappers("p_time")."='".db_addslashes($this->Param["p_time"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("p_time")."=".db_addslashes($this->Param["p_time"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_win") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(3))
				$dal_where.= AddFieldWrappers("p_win")."='".db_addslashes($this->Param["p_win"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("p_win")."=".db_addslashes($this->Param["p_win"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_mk") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(129))
				$dal_where.= AddFieldWrappers("p_mk")."='".db_addslashes($this->Param["p_mk"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("p_mk")."=".db_addslashes($this->Param["p_mk"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_born") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(13))
				$dal_where.= AddFieldWrappers("p_born")."='".db_addslashes($this->Param["p_born"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("p_born")."=".db_addslashes($this->Param["p_born"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_user") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(129))
				$dal_where.= AddFieldWrappers("p_user")."='".db_addslashes($this->Param["p_user"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("p_user")."=".db_addslashes($this->Param["p_user"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_pwd") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(129))
				$dal_where.= AddFieldWrappers("p_pwd")."='".db_addslashes($this->Param["p_pwd"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("p_pwd")."=".db_addslashes($this->Param["p_pwd"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_ip") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(129))
				$dal_where.= AddFieldWrappers("p_ip")."='".db_addslashes($this->Param["p_ip"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("p_ip")."=".db_addslashes($this->Param["p_ip"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_datetime") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(135))
				$dal_where.= AddFieldWrappers("p_datetime")."='".db_addslashes($this->Param["p_datetime"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("p_datetime")."=".db_addslashes($this->Param["p_datetime"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_tscore") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(3))
				$dal_where.= AddFieldWrappers("p_tscore")."='".db_addslashes($this->Param["p_tscore"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("p_tscore")."=".db_addslashes($this->Param["p_tscore"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_tkills") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(3))
				$dal_where.= AddFieldWrappers("p_tkills")."='".db_addslashes($this->Param["p_tkills"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("p_tkills")."=".db_addslashes($this->Param["p_tkills"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_news") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(2))
				$dal_where.= AddFieldWrappers("p_news")."='".db_addslashes($this->Param["p_news"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("p_news")."=".db_addslashes($this->Param["p_news"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_country") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(129))
				$dal_where.= AddFieldWrappers("p_country")."='".db_addslashes($this->Param["p_country"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("p_country")."=".db_addslashes($this->Param["p_country"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_mobile") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(20))
				$dal_where.= AddFieldWrappers("p_mobile")."='".db_addslashes($this->Param["p_mobile"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("p_mobile")."=".db_addslashes($this->Param["p_mobile"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("p_avatar") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(16))
				$dal_where.= AddFieldWrappers("p_avatar")."='".db_addslashes($this->Param["p_avatar"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("p_avatar")."=".db_addslashes($this->Param["p_avatar"]) . " and ";
	
	if ($dal_where)
		$dal_where = " where ".substr($dal_where,0,-5);
	
	$dalSQL = "select ".$dal_variables." from ".$this->m_GoodTableName.$dal_where;
	$rs = db_query($dalSQL,$conn);
	$data = db_fetch_array($rs);
	return $data;
}
	
function Reset()
{
        unset($this->m_p_id);
		unset($this->Value["p_id"]);
		unset($this->Param["p_id"]);
        unset($this->m_p_active);
		unset($this->Value["p_active"]);
		unset($this->Param["p_active"]);
        unset($this->m_p_location);
		unset($this->Value["p_location"]);
		unset($this->Param["p_location"]);
        unset($this->m_p_s_id);
		unset($this->Value["p_s_id"]);
		unset($this->Param["p_s_id"]);
        unset($this->m_p_first);
		unset($this->Value["p_first"]);
		unset($this->Param["p_first"]);
        unset($this->m_p_name);
		unset($this->Value["p_name"]);
		unset($this->Param["p_name"]);
        unset($this->m_p_adr);
		unset($this->Value["p_adr"]);
		unset($this->Param["p_adr"]);
        unset($this->m_p_zip);
		unset($this->Value["p_zip"]);
		unset($this->Param["p_zip"]);
        unset($this->m_p_mail);
		unset($this->Value["p_mail"]);
		unset($this->Param["p_mail"]);
        unset($this->m_p_newsaccept);
		unset($this->Value["p_newsaccept"]);
		unset($this->Param["p_newsaccept"]);
        unset($this->m_p_score);
		unset($this->Value["p_score"]);
		unset($this->Param["p_score"]);
        unset($this->m_p_scorehigh);
		unset($this->Value["p_scorehigh"]);
		unset($this->Param["p_scorehigh"]);
        unset($this->m_p_games);
		unset($this->Value["p_games"]);
		unset($this->Param["p_games"]);
        unset($this->m_p_time);
		unset($this->Value["p_time"]);
		unset($this->Param["p_time"]);
        unset($this->m_p_win);
		unset($this->Value["p_win"]);
		unset($this->Param["p_win"]);
        unset($this->m_p_mk);
		unset($this->Value["p_mk"]);
		unset($this->Param["p_mk"]);
        unset($this->m_p_born);
		unset($this->Value["p_born"]);
		unset($this->Param["p_born"]);
        unset($this->m_p_user);
		unset($this->Value["p_user"]);
		unset($this->Param["p_user"]);
        unset($this->m_p_pwd);
		unset($this->Value["p_pwd"]);
		unset($this->Param["p_pwd"]);
        unset($this->m_p_ip);
		unset($this->Value["p_ip"]);
		unset($this->Param["p_ip"]);
        unset($this->m_p_datetime);
		unset($this->Value["p_datetime"]);
		unset($this->Param["p_datetime"]);
        unset($this->m_p_tscore);
		unset($this->Value["p_tscore"]);
		unset($this->Param["p_tscore"]);
        unset($this->m_p_tkills);
		unset($this->Value["p_tkills"]);
		unset($this->Param["p_tkills"]);
        unset($this->m_p_news);
		unset($this->Value["p_news"]);
		unset($this->Param["p_news"]);
        unset($this->m_p_country);
		unset($this->Value["p_country"]);
		unset($this->Param["p_country"]);
        unset($this->m_p_mobile);
		unset($this->Value["p_mobile"]);
		unset($this->Param["p_mobile"]);
        unset($this->m_p_avatar);
		unset($this->Value["p_avatar"]);
		unset($this->Param["p_avatar"]);
}	
	
}//end of class


$dal->player = new class_player();


class class_xcountries
{
	var $m_TableName;
	var $m_GoodTableName;
	var $m_xc_id;
	var $m_xc_name;
	var $m_xc_currency;
	var $m_xc_code;
	var $m_xc_desc;

	var $Param = array();
	var $Value = array();
	
	var $xc_id = array();
	var $xc_name = array();
	var $xc_currency = array();
	var $xc_code = array();
	var $xc_desc = array();
	
	var $m_ChangedFields;
	var $m_UpdateParam;
	var $m_UpdateValue;

	
	function class_xcountries()
	{
		$this->m_TableName = "xcountries";
		$this->m_GoodTableName = AddTableWrappers($this->m_TableName);	
	}

function TableName()
{
	$this->m_TableName = "xcountries";
	$this->m_GoodTableName = AddTableWrappers($this->m_TableName);
	return $this->m_GoodTableName;
} 

function Add() 
{
	global $conn;

	$insertFields="";
	$insertValues="";
		

		if ($this->xc_id)
		{
			$this->Value["xc_id"] = $this->xc_id;
		    $this->m_xc_id = $this->xc_id;
		}	

		if ($this->xc_name)
		{
			$this->Value["xc_name"] = $this->xc_name;
		    $this->m_xc_name = $this->xc_name;
		}	

		if ($this->xc_currency)
		{
			$this->Value["xc_currency"] = $this->xc_currency;
		    $this->m_xc_currency = $this->xc_currency;
		}	

		if ($this->xc_code)
		{
			$this->Value["xc_code"] = $this->xc_code;
		    $this->m_xc_code = $this->xc_code;
		}	

		if ($this->xc_desc)
		{
			$this->Value["xc_desc"] = $this->xc_desc;
		    $this->m_xc_desc = $this->xc_desc;
		}	
	
		if ($this->Value["xc_id"])
		    $this->m_xc_id = $this->Value["xc_id"];	
		if ($this->Value["xc_name"])
		    $this->m_xc_name = $this->Value["xc_name"];	
		if ($this->Value["xc_currency"])
		    $this->m_xc_currency = $this->Value["xc_currency"];	
		if ($this->Value["xc_code"])
		    $this->m_xc_code = $this->Value["xc_code"];	
		if ($this->Value["xc_desc"])
		    $this->m_xc_desc = $this->Value["xc_desc"];	

		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("xc_id"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("xc_id").",";
			if (NeedQuotes(129))
				$insertValues.= "'".db_addslashes($this->Value["xc_id"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["xc_id"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("xc_name"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("xc_name").",";
			if (NeedQuotes(201))
				$insertValues.= "'".db_addslashes($this->Value["xc_name"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["xc_name"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("xc_currency"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("xc_currency").",";
			if (NeedQuotes(129))
				$insertValues.= "'".db_addslashes($this->Value["xc_currency"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["xc_currency"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("xc_code"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("xc_code").",";
			if (NeedQuotes(200))
				$insertValues.= "'".db_addslashes($this->Value["xc_code"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["xc_code"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("xc_desc"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("xc_desc").",";
			if (NeedQuotes(201))
				$insertValues.= "'".db_addslashes($this->Value["xc_desc"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["xc_desc"]) . ",";		

		}

		
	if ($insertFields!="" && $insertValues!="")		
	{
		$insertFields = substr($insertFields,0,-1);
		$insertValues = substr($insertValues,0,-1);
		$dalSQL = "insert into ".$this->m_GoodTableName." (".$insertFields.") values (".$insertValues.")";
		db_exec($dalSQL,$conn);
	}
	
        unset($this->Value["xc_id"]);
        unset($this->Value["xc_name"]);
        unset($this->Value["xc_currency"]);
        unset($this->Value["xc_code"]);
        unset($this->Value["xc_desc"]);
   // unset($this->m_ChangedFields);

	}
	
function Delete()
{
	global $conn;
	$deleteFields="";

		if ($this->xc_id)
		{
			$this->Value["xc_id"] = $this->xc_id;
			$this->m_xc_id = $this->xc_id;
		}	
		if ($this->xc_name)
		{
			$this->Value["xc_name"] = $this->xc_name;
			$this->m_xc_name = $this->xc_name;
		}	
		if ($this->xc_currency)
		{
			$this->Value["xc_currency"] = $this->xc_currency;
			$this->m_xc_currency = $this->xc_currency;
		}	
		if ($this->xc_code)
		{
			$this->Value["xc_code"] = $this->xc_code;
			$this->m_xc_code = $this->xc_code;
		}	
		if ($this->xc_desc)
		{
			$this->Value["xc_desc"] = $this->xc_desc;
			$this->m_xc_desc = $this->xc_desc;
		}	
	
		if ($this->Value["xc_id"])
		    $this->m_xc_id = $this->Value["xc_id"];	
		if ($this->Value["xc_name"])
		    $this->m_xc_name = $this->Value["xc_name"];	
		if ($this->Value["xc_currency"])
		    $this->m_xc_currency = $this->Value["xc_currency"];	
		if ($this->Value["xc_code"])
		    $this->m_xc_code = $this->Value["xc_code"];	
		if ($this->Value["xc_desc"])
		    $this->m_xc_desc = $this->Value["xc_desc"];	

		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("xc_id"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("xc_id").",";
			if (NeedQuotes(129))
				$deleteFields.= AddFieldWrappers("xc_id")."='".db_addslashes($this->Value["xc_id"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("xc_id")."=".db_addslashes($this->Value["xc_id"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("xc_name"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("xc_name").",";
			if (NeedQuotes(201))
				$deleteFields.= AddFieldWrappers("xc_name")."='".db_addslashes($this->Value["xc_name"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("xc_name")."=".db_addslashes($this->Value["xc_name"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("xc_currency"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("xc_currency").",";
			if (NeedQuotes(129))
				$deleteFields.= AddFieldWrappers("xc_currency")."='".db_addslashes($this->Value["xc_currency"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("xc_currency")."=".db_addslashes($this->Value["xc_currency"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("xc_code"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("xc_code").",";
			if (NeedQuotes(200))
				$deleteFields.= AddFieldWrappers("xc_code")."='".db_addslashes($this->Value["xc_code"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("xc_code")."=".db_addslashes($this->Value["xc_code"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("xc_desc"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("xc_desc").",";
			if (NeedQuotes(201))
				$deleteFields.= AddFieldWrappers("xc_desc")."='".db_addslashes($this->Value["xc_desc"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("xc_desc")."=".db_addslashes($this->Value["xc_desc"]) . " and ";		
		}

	if ($deleteFields)
	{
		$deleteFields = substr($deleteFields,0,-5);
		$dalSQL = "delete from ".$this->m_GoodTableName." where ".$deleteFields;
		db_exec($dalSQL,$conn);
	}
	
	    unset($this->Value["xc_id"]);
	    unset($this->Value["xc_name"]);
	    unset($this->Value["xc_currency"]);
	    unset($this->Value["xc_code"]);
	    unset($this->Value["xc_desc"]);
		
	//unset($this->Value);	
}

function Update()
{
	global $conn;
	
	$updateParam = "";
	$updateValue = "";
	
		if ($this->xc_id)
		{
			if (1==1)
				$this->Param["xc_id"] = $this->xc_id;
			else
				$this->Value["xc_id"] = $this->xc_id;
			$this->m_xc_id = $this->xc_id;
		}	
		if ($this->xc_name)
		{
			if (0==1)
				$this->Param["xc_name"] = $this->xc_name;
			else
				$this->Value["xc_name"] = $this->xc_name;
			$this->m_xc_name = $this->xc_name;
		}	
		if ($this->xc_currency)
		{
			if (0==1)
				$this->Param["xc_currency"] = $this->xc_currency;
			else
				$this->Value["xc_currency"] = $this->xc_currency;
			$this->m_xc_currency = $this->xc_currency;
		}	
		if ($this->xc_code)
		{
			if (0==1)
				$this->Param["xc_code"] = $this->xc_code;
			else
				$this->Value["xc_code"] = $this->xc_code;
			$this->m_xc_code = $this->xc_code;
		}	
		if ($this->xc_desc)
		{
			if (0==1)
				$this->Param["xc_desc"] = $this->xc_desc;
			else
				$this->Value["xc_desc"] = $this->xc_desc;
			$this->m_xc_desc = $this->xc_desc;
		}	
	
		if ($this->Value["xc_id"])
		    $this->m_xc_id = $this->Value["xc_id"];		
		if ($this->Value["xc_name"])
		    $this->m_xc_name = $this->Value["xc_name"];		
		if ($this->Value["xc_currency"])
		    $this->m_xc_currency = $this->Value["xc_currency"];		
		if ($this->Value["xc_code"])
		    $this->m_xc_code = $this->Value["xc_code"];		
		if ($this->Value["xc_desc"])
		    $this->m_xc_desc = $this->Value["xc_desc"];		
	
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("xc_id"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(129))
					$updateValue.= AddFieldWrappers("xc_id")."='".$this->Value["xc_id"]."', ";
			else
					$updateValue.= AddFieldWrappers("xc_id")."=".$this->Value["xc_id"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("xc_id"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(129))
					$updateParam.= AddFieldWrappers("xc_id")."='".db_addslashes($this->Param["xc_id"])."' and ";
			else
					$updateParam.= AddFieldWrappers("xc_id")."=".db_addslashes($this->Param["xc_id"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("xc_name"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(201))
					$updateValue.= AddFieldWrappers("xc_name")."='".$this->Value["xc_name"]."', ";
			else
					$updateValue.= AddFieldWrappers("xc_name")."=".$this->Value["xc_name"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("xc_name"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(201))
					$updateParam.= AddFieldWrappers("xc_name")."='".db_addslashes($this->Param["xc_name"])."' and ";
			else
					$updateParam.= AddFieldWrappers("xc_name")."=".db_addslashes($this->Param["xc_name"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("xc_currency"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(129))
					$updateValue.= AddFieldWrappers("xc_currency")."='".$this->Value["xc_currency"]."', ";
			else
					$updateValue.= AddFieldWrappers("xc_currency")."=".$this->Value["xc_currency"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("xc_currency"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(129))
					$updateParam.= AddFieldWrappers("xc_currency")."='".db_addslashes($this->Param["xc_currency"])."' and ";
			else
					$updateParam.= AddFieldWrappers("xc_currency")."=".db_addslashes($this->Param["xc_currency"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("xc_code"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateValue.= AddFieldWrappers("xc_code")."='".$this->Value["xc_code"]."', ";
			else
					$updateValue.= AddFieldWrappers("xc_code")."=".$this->Value["xc_code"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("xc_code"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateParam.= AddFieldWrappers("xc_code")."='".db_addslashes($this->Param["xc_code"])."' and ";
			else
					$updateParam.= AddFieldWrappers("xc_code")."=".db_addslashes($this->Param["xc_code"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("xc_desc"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(201))
					$updateValue.= AddFieldWrappers("xc_desc")."='".$this->Value["xc_desc"]."', ";
			else
					$updateValue.= AddFieldWrappers("xc_desc")."=".$this->Value["xc_desc"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("xc_desc"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(201))
					$updateParam.= AddFieldWrappers("xc_desc")."='".db_addslashes($this->Param["xc_desc"])."' and ";
			else
					$updateParam.= AddFieldWrappers("xc_desc")."=".db_addslashes($this->Param["xc_desc"])." and ";
		}
		
	
	if ($updateParam)
		$updateParam = substr($updateParam,0,-5);
	if ($updateValue)
		$updateValue = substr($updateValue,0,-2);
		
	if ($updateValue)
	{
		$dalSQL = "update ".$this->m_GoodTableName." set ".$updateValue." where ".$updateParam;
		db_exec($dalSQL,$conn);
	}
	
        unset($this->Value["xc_id"]);
		unset($this->Param["xc_id"]);
        unset($this->Value["xc_name"]);
		unset($this->Param["xc_name"]);
        unset($this->Value["xc_currency"]);
		unset($this->Param["xc_currency"]);
        unset($this->Value["xc_code"]);
		unset($this->Param["xc_code"]);
        unset($this->Value["xc_desc"]);
		unset($this->Param["xc_desc"]);
	
}

function QueryAll()
{
	global $conn;
	$dal_variables="";
		$dal_variables.= AddFieldWrappers("xc_id").", ";
		$dal_variables.= AddFieldWrappers("xc_name").", ";
		$dal_variables.= AddFieldWrappers("xc_currency").", ";
		$dal_variables.= AddFieldWrappers("xc_code").", ";
		$dal_variables.= AddFieldWrappers("xc_desc").", ";
	$dal_variables = substr($dal_variables,0,-2);
	$dalSQL = "select ".$dal_variables." from ".$this->m_GoodTableName;
	$rs = db_query($dalSQL,$conn);
	$data = db_fetch_array($rs);
	return $data;
}

function Query($swhere,$orderby)
{
	global $conn;
	$dal_variables="";
		$dal_variables.= AddFieldWrappers("xc_id").", ";
		$dal_variables.= AddFieldWrappers("xc_name").", ";
		$dal_variables.= AddFieldWrappers("xc_currency").", ";
		$dal_variables.= AddFieldWrappers("xc_code").", ";
		$dal_variables.= AddFieldWrappers("xc_desc").", ";
	$dal_variables = substr($dal_variables,0,-2);
	if ($swhere)
		$swhere = " where ".$swhere;
	if ($orderby)
		$orderby = " order by ".$orderby;
	
	$dalSQL = "select ".$dal_variables." from ".$this->m_GoodTableName.$swhere.$orderby;
	$rs = db_query($dalSQL,$conn);
	$data = db_fetch_array($rs);
	return $data; 	
}

function FetchByID()
{
	global $conn;
	$dal_variables="";
	$dal_where="";
        $dal_variables.= AddFieldWrappers("xc_id").", ";
        $dal_variables.= AddFieldWrappers("xc_name").", ";
        $dal_variables.= AddFieldWrappers("xc_currency").", ";
        $dal_variables.= AddFieldWrappers("xc_code").", ";
        $dal_variables.= AddFieldWrappers("xc_desc").", ";
	$dal_variables = substr($dal_variables,0,-2);
	
		if ($this->xc_id)
			$this->Param["xc_id"] = $this->xc_id;	
		if ($this->xc_name)
			$this->Param["xc_name"] = $this->xc_name;	
		if ($this->xc_currency)
			$this->Param["xc_currency"] = $this->xc_currency;	
		if ($this->xc_code)
			$this->Param["xc_code"] = $this->xc_code;	
		if ($this->xc_desc)
			$this->Param["xc_desc"] = $this->xc_desc;	
	
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("xc_id") && 1==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(129))
				$dal_where.= AddFieldWrappers("xc_id")."='".db_addslashes($this->Param["xc_id"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("xc_id")."=".db_addslashes($this->Param["xc_id"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("xc_name") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(201))
				$dal_where.= AddFieldWrappers("xc_name")."='".db_addslashes($this->Param["xc_name"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("xc_name")."=".db_addslashes($this->Param["xc_name"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("xc_currency") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(129))
				$dal_where.= AddFieldWrappers("xc_currency")."='".db_addslashes($this->Param["xc_currency"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("xc_currency")."=".db_addslashes($this->Param["xc_currency"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("xc_code") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(200))
				$dal_where.= AddFieldWrappers("xc_code")."='".db_addslashes($this->Param["xc_code"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("xc_code")."=".db_addslashes($this->Param["xc_code"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("xc_desc") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(201))
				$dal_where.= AddFieldWrappers("xc_desc")."='".db_addslashes($this->Param["xc_desc"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("xc_desc")."=".db_addslashes($this->Param["xc_desc"]) . " and ";
	
	if ($dal_where)
		$dal_where = " where ".substr($dal_where,0,-5);
	
	$dalSQL = "select ".$dal_variables." from ".$this->m_GoodTableName.$dal_where;
	$rs = db_query($dalSQL,$conn);
	$data = db_fetch_array($rs);
	return $data;
}
	
function Reset()
{
        unset($this->m_xc_id);
		unset($this->Value["xc_id"]);
		unset($this->Param["xc_id"]);
        unset($this->m_xc_name);
		unset($this->Value["xc_name"]);
		unset($this->Param["xc_name"]);
        unset($this->m_xc_currency);
		unset($this->Value["xc_currency"]);
		unset($this->Param["xc_currency"]);
        unset($this->m_xc_code);
		unset($this->Value["xc_code"]);
		unset($this->Param["xc_code"]);
        unset($this->m_xc_desc);
		unset($this->Value["xc_desc"]);
		unset($this->Param["xc_desc"]);
}	
	
}//end of class


$dal->xcountries = new class_xcountries();

?>