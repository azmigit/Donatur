<?php
class CObject {
	 private $conn;

	function __construct(){
		global $conn,$log;
		$this->conn = $conn;
		$this->log = $log;
	}

	function Object_Create(){
		$sqlc = "insert into ".$this->conn->databaseName."_".$_SESSION['db'].".setting_object (i, n, o) values('".$this->i."', '".$this->n."', '".json_encode($this->o)."')";
		$this->log->Log_Create($mod,$file,$sqlc);
		$rc_sql = $this->conn->Execute($sqlc) or die(mysql_error());	
		if ($rc_sql){
			echo json_encode(array('success'=>true));
		} else {
			echo json_encode(array('msg'=>'Some errors occured.'));
		}	
	}

	function Object_Read(){
		$page = isset($this->page) ? intval($this->page) : 1;
		$rows = isset($this->rows) ? intval($this->rows) : 10;
		$offset = abs($page-1)*$rows;
		$result['rows'] = array();

		/*echo*/
		if ($this->keySearch){
    		$where .=  " AND (a.n like '%".addslashes($this->keySearch)."%' or a.o like '%".addslashes($this->keySearch)."%')";    
    	}
		
		$sqlc = "select count(*) total from ".$this->conn->databaseName."_".$_SESSION['db'].".setting_object a where 1 $where";
		$rc_sqlc = $this->conn->Execute($sqlc) or die(mysql_error());	
		$result["total"] = $rc_sqlc->fields['total'];

		$sqlr ="select 
					a.*
				from 
					".$this->conn->databaseName."_".$_SESSION['db'].".setting_object a 
				where 1 $where
				order by a.n asc
				limit $offset,$rows";
		$r_sql = $this->conn->Execute($sqlr) or die(mysql_error());	
		
		$i=0;
		foreach($r_sql as $fields) {
			$result['rows'][$i]['i'] = $fields['i'];			
			$result['rows'][$i]['n'] = $fields['n'];	

			$object = preg_replace('/"/', '', $fields['o'],1);
			$object = preg_replace("/\"$/","",$object);
			$object = json_decode($object,true);
			
			$result['rows'][$i]['o'] = $object;
			$result['rows'][$i]['t'] = count($object);
			$i++;
		}
		echo json_encode($result);
	}

	function Object_Update(){
		$sqlu = "replace into ".$this->conn->databaseName."_".$_SESSION['db'].".setting_object (i, n, o) values('".$this->i."', '".$this->n."', '".json_encode($this->o)."')";
		$this->log->Log_Create($mod,$file,$sqlu);
		$ru_sql = $this->conn->Execute($sqlu) or die(mysql_error());	
		if ($ru_sql){
			echo json_encode(array('success'=>true));
		} else {
			echo json_encode(array('msg'=>'Some errors occured.'));
		}
		
	}

	function Object_Delete(){
		$sqld = "delete from ".$this->conn->databaseName."_".$_SESSION['db'].".setting_object where i='".$this->i."'";
		$this->log->Log_Create($mod,$file,$sqld);
		$rd_sql = $this->conn->Execute($sqld) or die(mysql_error());	
		if ($rd_sql){
			echo json_encode(array('success'=>true));
		} else {
			echo json_encode(array('msg'=>'Some errors occured.'));
		}
		
	}

	function Object_Options(){
		$x = isset($x)?$x:0;
		$sqlr = "select o from ".$this->conn->databaseName."_".$_SESSION['db'].".setting_object WHERE i='".$this->i."'";
		$r_sql = $this->conn->Execute($sqlr) or die(mysql_error());	
		
		$object = $r_sql->fields['o'];
		if ($object){
			$object = preg_replace('/"/', '', $object,1);
			$object = preg_replace("/\"$/","",$object);
			$object = json_decode($object,true);
		}
		else 
			$object = array();
		if (isset($this->x)) $x = explode(",",$this->x);

		$i=0;		
		foreach($object as $fields) {
			if ($fields['a']=='y'){
				$result[$i]['i'] = $fields['i'];
				$result[$i]['o'] = $fields['o'];
				
				if (is_array($x)){
					if (in_array($fields['i'],$x))
						$result[$i]['ck'] = "1";
					else
						$result[$i]['ck'] = "0";				
				}
				
				$i++;
			}
		}

		echo json_encode($result);
	}

	function Object_Array(){
		$sqlr = mysql_query("select o from setting_object WHERE i='".$this->i."'");
		$r_sql = mysql_fetch_array($sqlr);
		
		$object = $r_sql['o'];
		if ($object){
			$object = preg_replace('/"/', '', $object,1);
			$object = preg_replace("/\"$/","",$object);
			$object = json_decode($object,true);
		}
		else 
			$object = array();

		foreach($object as $fields) {
			if ($fields['a']=='y'){
				$result[$fields['i']] = $fields['o'];
			}
		}
		return $result;
	}
	
}
?>