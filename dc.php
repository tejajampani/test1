<?php
//  Db connection
    /* 
    define('DB_SERVER','localhost');
	define('DB_USER','pradeepv_drant3');
	define('DB_PASSWD','PaeLabs@6969');
	define('DB_NAME','pradeepv_drant3');
	  */
	  
 	/*define('DB_SERVER','localhost');
	 define('DB_USER','pradeepv_drant2');
	 define('DB_PASSWD','pass@123');
	 define('DB_NAME','pradeepv_drant-new'); */
	
 	define('DB_SERVER','localhost');
	define('DB_USER','root');
	define('DB_PASSWD','');
	define('DB_NAME','proddy');
	
	class DatabaseClass 
	{
		var $_dbhostName;
		var $_dbName;
		var $_dbUser;
		var $_dbPwd;
		var $_dbCon;

		function DatabaseClass()
		{
			$this->_dbhostName = DB_SERVER;
			$this->_dbName = DB_NAME;
			$this->_dbUser = DB_USER;
			$this->_dbPwd = DB_PASSWD;
		}
		function dbOpen()
		{
			
			$val= $this->_dbCon=mysql_connect($this->_dbhostName,$this->_dbUser,$this->_dbPwd) or die ("Could not connect DB");
			mysql_select_db($this->_dbName,$this->_dbCon) or die ("Could not select DatabaseClass");
			
		}
		function dbClose()
		{
			mysql_close($this->_dbCon);	
		}
		function getConn()
		{
			return $this->_dbCon;
		}
	}//DatabaseClass

	class SqlClass
	{
		var $_dbSql;
		var $_dbTotalRecord;
		var $_dbFields=array();	
		var $_objDatabaseClass;
		var $_dbNewID;		// This field will be used to get newly inserted id
		var $_dbAffectedRows;
		var $_dbErrMsg;
		var $_dbErr;	// Boolean
		var $_dbShowAdvanceError;	// Boolean

		function SqlClass()
		{
			$this->_dbSql="";
			$this->_dbTotalRecord=NULL;
			$this->_dbNewID=NULL;
			$this->_dbAffectedRows=NULL;
			$this->_dbErr=false;
			$this->_dbShowAdvanceError=false;
			$this->_dbErrMsg="";
		}
		function executeQuery($sql)
		{
			$this->_objDatabaseClass=new DatabaseClass();
			$this->_objDatabaseClass->dbOpen();
			$result="";
			 $result = mysql_query($sql);
			return $result;
		}

		function executeSql($sql,$doubtedFields=0)
		{
			if(strtoupper(substr($sql,0,6))=="SELECT")
			{
				$Mode="Read";
			}
			else{
				$Mode="Write";
			}
			$this->_objDatabaseClass=new DatabaseClass();
			$this->_objDatabaseClass->dbOpen();
			# To check Weather Doubted Fields are present or Not
			if(is_array($doubtedFields) && $doubtedFields!=0)
			{
				for($i=0; $i<count($doubtedFields); $i++)
				{
					if(strpos($sql,'?')!==false)
					{
						$start=substr($sql,0,strpos($sql,'?'));			
						$end=substr($sql,strpos($sql,'?')+1);
						$sql=$start.$this->sql_quote($doubtedFields[$i]).$end;
					}
				}
			}//end Doubted Field Check
			switch($Mode)
			{
				case "Read":
				//echo $sql;
				$row=array();
				
				if($record=mysql_query($sql,$this->_objDatabaseClass->getConn()))
				{
					$this->_dbTotalRecord=mysql_num_rows($record);
					if($this->_dbTotalRecord>0)
					{
						$fieldsname="";
						for($i=0; $i<mysql_num_fields($record); $i++)
						{
							$fieldsname .= mysql_field_name($record,$i).",";
						}
						$rawData=array();			
						while($row=mysql_fetch_array($record))
						{			
							foreach ($row as $fieldName => $fieldValue) 
							{
								$rawData["$fieldName"]=stripslashes($fieldValue);
							} 
							$this->_dbFields[]=$rawData;
						}
                		 mysql_free_result($record);
	
						#Reversing The Array Coz I need to Fetch Record below function Using POP built in function Function
						$this->_dbFields = array_reverse($this->_dbFields, true);	
						return $this->_dbFields;
					}
					else{
						$this->_dbErrMsg="<li>No Record Found</li>";
						$this->_dbErr=true;
						$this->_objDatabaseClass->dbClose();	// DisConnecting From DB
						return 'no rows';				
					}
				}
				else{
					if($this->_dbShowAdvanceError)
					{
						$this->_dbErrMsg='<br><b style="color:#FF0000">Your Query is</b> <br> '.$sql.' <br><b style="color:#FF0000"> Mysql Says</b><br>'.mysql_error();
					}
					else{
						$this->_dbErrMsg="<li> A Problem Occur While Executing the Your Query</li>";
					}
					$this->_dbErr=true;
					$this->_objDatabaseClass->dbClose();	// DisConnecting From DB
					return false;
				}
			break;

			case "Write":
				if(mysql_query($sql,$this->_objDatabaseClass->getConn()))
				{ 
					$this->_dbNewID=mysql_insert_id($this->_objDatabaseClass->getConn());
					$this->_dbAffectedRows=mysql_affected_rows($this->_objDatabaseClass->getConn());
					$this->_dbErrMsg="Record Sucessfully Inserted";
					$this->_dbErr=true;
					$this->_objDatabaseClass->dbClose();	// DisConnecting From DB
					return true;
				}
				else{

					if($this->_dbShowAdvanceError)
					{
						$this->_dbErrMsg='<br><b style="color:#FF0000">Your Query is</b> <br> '.$sql.' <br><b style="color:#FF0000"> Mysql Says</b><br>'.mysql_error();
					}
					else{
						$this->_dbErrMsg="<li> A Problem Occur While Executing the Your Query</li>";
					}
					$this->_dbErr=true;
					$this->_objDatabaseClass->dbClose();	// DisConnecting From DB		
					return false;
				}
			break;
			}
		}
		function getLstInserted($query)
		{
			$this->_objDatabaseClass=new DatabaseClass();
			$this->_objDatabaseClass->dbOpen();
			$result="";
			$result = mysql_query($query);
			//echo $result;
			$lastInst = mysql_insert_id();
			return $lastInst;
		}
	//this function is customised for sports module to use with unions.......
		function executeSql_union($sql,$doubtedFields=0)
		{
		     
			if(strtoupper(substr($sql,1,6))=="SELECT")
			{
				$Mode="Read";
				
			}
			else{
			
				$Mode="Write";
			}
			$this->_objDatabaseClass=new DatabaseClass();
			$this->_objDatabaseClass->dbOpen();
			# To check Weather Doubted Fields are present or Not
			if(is_array($doubtedFields) && $doubtedFields!=0)
			{
				for($i=0; $i<count($doubtedFields); $i++)
				{
					if(strpos($sql,'?')!==false)
					{
						$start=substr($sql,0,strpos($sql,'?'));			
						$end=substr($sql,strpos($sql,'?')+1);
						$sql=$start.$this->sql_quote($doubtedFields[$i]).$end;
					}
				}
			}//end Doubted Field Check
		
			switch($Mode)
			{
				case "Read":
		
				$row=array();
				
				if($record=mysql_query($sql,$this->_objDatabaseClass->getConn()))
				{
					$this->_dbTotalRecord=mysql_num_rows($record);
					if($this->_dbTotalRecord>0)
					{
						$fieldsname="";
						for($i=0; $i<mysql_num_fields($record); $i++)
						{
							$fieldsname .= mysql_field_name($record,$i).",";
						}
						$rawData=array();			
						while($row=mysql_fetch_array($record))
						{			
							foreach ($row as $fieldName => $fieldValue) 
							{
								$rawData["$fieldName"]=stripslashes($fieldValue);
							} 
							$this->_dbFields[]=$rawData;
						}
                		 mysql_free_result($record);
	
						#Reversing The Array Coz I need to Fetch Record below function Using POP built in function Function
						$this->_dbFields = array_reverse($this->_dbFields, true);	
						return $this->_dbFields;
					}
					else{
						$this->_dbErrMsg="<li>No Record Found</li>";
						$this->_dbErr=true;
						$this->_objDatabaseClass->dbClose();	// DisConnecting From DB
						return true;				
					}
				}
				else{
					if($this->_dbShowAdvanceError)
					{
						$this->_dbErrMsg='<br><b style="color:#FF0000">Your Query is</b> <br> '.$sql.' <br><b style="color:#FF0000"> Mysql Says</b><br>'.mysql_error();
					}
					else{
						$this->_dbErrMsg="<li> A Problem Occur While Executing the Your Query</li>";
					}
					$this->_dbErr=true;
					$this->_objDatabaseClass->dbClose();	// DisConnecting From DB
					return false;
				}
			break;

			case "Write":
			 
				if(mysql_query($sql,$this->_objDatabaseClass->getConn()))
				{
					$this->_dbNewID=mysql_insert_id($this->_objDatabaseClass->getConn());
					$this->_dbAffectedRows=mysql_affected_rows($this->_objDatabaseClass->getConn());
					$this->_dbErrMsg="Record Sucessfully Inserted";
					$this->_dbErr=true;
					$this->_objDatabaseClass->dbClose();	// DisConnecting From DB
					return true;
				}
				else{
					if($this->_dbShowAdvanceError)
					{
						$this->_dbErrMsg='<br><b style="color:#FF0000">Your Query is</b> <br> '.$sql.' <br><b style="color:#FF0000"> Mysql Says</b><br>'.mysql_error();
					}
					else{
						$this->_dbErrMsg="<li> A Problem Occur While Executing the Your Query</li>";
					}
					$this->_dbErr=true;
					$this->_objDatabaseClass->dbClose();	// DisConnecting From DB		
					return false;
				}
			break;
			}
		}

		function fetchRow($dbRows)
		{
			if(is_array($dbRows))
			{
				$returnValue = array_pop($dbRows);
				if(!is_null($returnValue))
				{					
					return $returnValue;
				}
				else{
					return false;
				}
			}
			else{
				$this->_dbErrMsg="0 Rows Found";
				$this->_dbErr=true;
				return false;
			}
		}
		function isError()
		{
			 return $this->_dbErr;
		}
		function getErrMsg()

		{
			 return $this->_dbErrMsg;
		}
		function getNumRecord()
		{
			 return $this->_dbTotalRecord;
		}	
		function setAdvanceErr($value)
		{
			 $this->_dbShowAdvanceError=$value;
		}			
		function getNewID()
		{	
					
			return $this->_dbNewID;
		}	
		function getAffectedRows()
		{
			return $this->_dbAffectedRows;
		}
		function sql_quote($value)
		{ 
			if( get_magic_quotes_gpc() )
			{ 
			  $value = stripslashes($value); 
			} 
			$link = mysql_connect(DB_SERVER, DB_USER, DB_PASSWD);
			//check if this function exists 
			if( function_exists( "mysql_real_escape_string" ) )
			{ 
				  $value = mysql_real_escape_string($value); 
			} 
			//for PHP version < 4.3.0 use addslashes 
			else { 
			  $value = addslashes($value); 
			} 
			return $value; 
		} 
		
	}// SqlClass
	
	class Queries
	{
		/***************/
		/* makeinsertquery($table,$tab)
			$table = tablename;
			$tab is an array contains field names and those corsponding values
			Ex -> $tab = array("sid = 1","sname = name");
			
		*/
		/***************/
		
		function makeinsertquery($table,$tab,$colName)
		{
			$query="insert into $table (";
			$ck=count($tab);
			for($i=0;$i<$ck;$i++)
			{
				$field=explode("=","'".$colName[$i]."'");
				$field=str_replace("'","`",$field);
				$query.=$field[0].",";
			}
			$query=substr($query,0,strlen($query)-1);
			$query.=') values (';
			for($j=0;$j<$ck;$j++)
			{
				$value=explode("=",$tab[$j]);
				$query.="'".trim($value[0])."'".",";
			}
			$query=substr($query,0,strlen($query)-1);
			$query.=")";
			
			//echo $query;
			return $query;
		}
		/***************/
		/* makeupdatequery($table,$tab,$where)
			$table = tablename;
			$tab is an array contains field names and those corsponding values
			Ex -> $tab = array("sid = 1","sname = name");
			where Condition
			Ex -> $where =  "sno = 1";
			
		*/
		/***************/
		function makeupdatequery($table,$list,$colList,$where)
		{
			$query="update $table set ";
			$ck=count($colList);
			for($i=0;$i<$ck;$i++)
			{
				//$attributes=explode("=",$tab[$i]);
				$query.=$colList[$i]."="."'".trim($list[$i])."'".", ";
			}
			$query=substr($query,0,strlen($query)-2);
			if($where!="")
			{
				$query.=" where ";
				if(is_array($where))
				{
					$ck=count($where);
					for($i=0;$i<$ck;$i++)
					{
						$attributes=explode("=",$where[$i]);
						$query.=$attributes[0]."="."'".trim($attributes[1])."'";
						if($i+1!=$ck)
						{
							$query.=" and ";
						}					
					}					           
				}   
				else
				{	
					$arr=explode("=",$where);
					$query.=$arr[0]."=";
					$query.="'".trim($arr[1])."'";
				}       
			}
			//echo $query;
			$objSql = new SqlClass();
			$res = $objSql->executeSql($query);
			return $res;
		}
		/***************/
		/* makedeletequery($table,$field,$value)
			$table = tablename;
			$field Table filed name
			Ex -> $field = 'sid';
			$value value
			Ex -> $value = 1;
			
		*/
		/***************/
		function makedeletequery($table,$field,$value)
		{
			$query="DELETE from ".$table." where ".$field."='".trim($value)."'";
			$objSql = new SqlClass();
			$res = $objSql->executeSql($query);
			return $res;
		}
		/***************/
		/* makeselectquery($table,$column,$field,$value)
			$table = tablename;
			$column Required field name
			Ex -> $column = 'firstname';
			$field Condation field name
			$field = "sid";
			$value value
			Ex -> $value = 1;
			
		*/
		/***************/
		function makeselectquery($table,$column,$field,$value)
		{
			$query="select ".$column." from ".$table." where ".$field."='".trim($value)."'";
			$objSql = new SqlClass();
			$res = $objSql->executeSql($query);
			$row = $objSql->fetchRow($res);
			$val = $row['0'];
			return $val;
		}
		/***************/
		/* makeselectallquery($table,$field,$value)
			$table = tablename;
			$field Condation field name
			$field = "sid";
			$value value
			Ex -> $value = 1;
			
		*/
		/***************/
		function makeselectallquery($table,$field,$value)
		{
			$query="select * from ".$table." where ".$field."='".trim($value)."'";
			$objSql = new SqlClass();
			$res = $objSql->executeSql($query);
			$row = $objSql->fetchRow($res);
			return $row;
		}
		// function to dispaly slected fileds in a table
		function selectreqfields($filds,$table,$field,$value)
		{
			$query="select ".$filds." from ".$table." where ".$field."='".trim($value)."'";
			$objSql = new SqlClass();
			$res = $objSql->executeSql($query);
			//$row = $objSql->fetchRow($res);
			return $res;
		}
		function makeselectallvalues($table,$where='')
		{
			$query="select * from ".$table;
			if($where!="")
			{
				$query.=" where ";
				if(is_array($where))
				{
					$ck=count($where);
					for($i=0;$i<$ck;$i++)
					{
						$attributes=explode("=",$where[$i]);
		
						$query.=$attributes[0]."="."'".trim($attributes[1])."'";
						if($i+1!=$ck)
						{
							$query.=" and ";
						}					
					}					           
				}   
				else
				{	
					$arr=explode("=",$where);
					$query.=$arr[0]."=";
					$query.="'".trim($arr[1])."'";
				}       
			}
			$objSql = new SqlClass();
			$res = $objSql->executeSql($query);
			while($row = $objSql->fetchRow($res)){
			$rows[]=$row;
			}
			return $rows;
		}
		//anusha db modification
		
		//function used to get required fields order by
		function selectreqfieldsquery($selval,$table,$fieldname,$fieldval,$order)
		{
			 $query="select $selval from $table where $fieldname=$fieldval order by $order";
			$objSql = new SqlClass();
			$res = $objSql->executeSql($query);
			return $res;
		}
			
		//selecting multiple fields depending on multiple conditions
		function selectmultifields($selval,$where,$table)
		{
		$query="select $selval from $table";
		
			if($where!="")
			{
				$query.=" where ";
				if(is_array($where))
				{
					$ck=count($where);
					for($i=0;$i<$ck;$i++)
					{
						$attributes=explode("=",$where[$i]);
		
						$query.=$attributes[0]."="."'".trim($attributes[1])."'";
						if($i+1!=$ck)
						{	
							$query.=" and ";
							
						}				
					} 			           
				}   
				else
				{	
					$arr=explode("=",$where);
					$query.=$arr[0]."=";
					$query.="'".trim($arr[1])."'";
				}  
			}     
			//echo $query;
			$objSql = new SqlClass();
			$res = $objSql->executeSql($query);
			return $res;

		}
		//sellecting multiple fields
		/*function selectreqfields($selval,$table,$fieldname,$fieldval)
		{
			  $query="select $selval from $table where $fieldname=$fieldval";
			$objSql = new SqlClass();
			$res = $objSql->executeSql($query);
			
			return $res;
		}*/
 	//function to select one field  multiple rows
		function makemultiselectquery($table,$column,$field,$value)
		{
			$query="select ".$column." from ".$table." where ".$field."='".trim($value)."'";
			$objSql = new SqlClass();
			$res = $objSql->executeSql($query);
			return $res;
		}
		//function to select all fields multiple rows
		function makemultiselectallquery($table,$field,$value)
		{
			$query="select * from ".$table." where ".$field."='".trim($value)."'";
			$objSql = new SqlClass();
			$res = $objSql->executeSql($query);
			return $res;
		}
		function makeselectall($table)
		{
		 $query="select * from ".$table;
			$objSql = new SqlClass();
			$res = $objSql->executeSql($query);
			while($row = $objSql->fetchRow($res)){
			$rows[]=$row;
			}
			return $rows;
		}
		function makeselectallfields($table,$field,$value)
		{   
		    $rows=array();
			$query="select * from ".$table." where ".$field."='".trim($value)."'";
			//echo $query;
			$objSql = new SqlClass();
			$res = $objSql->executeSql($query);
			while($row = $objSql->fetchRow($res)){
			$rows[]=$row;
			}
			return $rows;
		}
		function makeselectallfield($table,$field,$value)
		{   
		    $rows=array();
			$query="select * from ".$table." where ".$field."='".trim($value)."'";
			//echo $query;
			$objSql = new SqlClass();
			$res = $objSql->executeSql($query);
			while($row = $objSql->fetchRow($res)){
			$rows[]=$row;
			}
			return $rows;
		}
		function selectfieldquery($table,$where,$selval)
		{
			$query="select ".$selval." from ".$table;
			//$query=substr($query,0,strlen($query)-2);
			if($where!="")
			{
				$query.=" where ";
				if(is_array($where))
				{
					$ck=count($where);
					for($i=0;$i<$ck;$i++)
					{
						$attributes=explode("=",$where[$i]);
		
						$query.=$attributes[0]."="."'".trim($attributes[1])."'";
						if($i+1!=$ck)
						{	
							$query.=" and ";
							
						}					
					}					           
				}   
				else
				{	
					$arr=explode("=",$where);
					$query.=$arr[0]."=";
					$query.="'".trim($arr[1])."'";
				}       
			}
			
			$objSql = new SqlClass();
			$res = $objSql->executeSql($query);
			return $res;
		}
		function makeselectmulticolquery($table,$column,$field,$value)
		{
			$query="select ".$column." from ".$table." where ".$field."='".trim($value)."'";
			$objSql = new SqlClass();
			$res = $objSql->executeSql($query);
			$row = $objSql->fetchRow($res);
			return $row;
		}

	}
  
?> 