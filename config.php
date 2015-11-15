<?php
	$host     ="localhost";
	$username ="root";
	$password ="";
	$database ="corez_aq3";


	mysql_connect($host, $username, $password);
	mysql_select_db($database);

  	function change($x){
		if($x==0){
			$d = '-';
		}else{
			$d = $x;
		}
		return $d;
	}

?>