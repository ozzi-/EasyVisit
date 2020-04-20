<?php 
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */				
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	
	if ($mysqli->connect_error) {
		handleError("connection failed: ".$mysqli->connect_error);
		exit();
	}	
	
	if (!$mysqli->set_charset("utf8")) {
		printf("Error loading character set utf8: %s\n", $mysqli->error);
		exit();
	}

        function multiquery($commands){
          global $mysqli;
          $res = $mysqli->multi_query($commands);
          if (!$res) {
            return "Query failed: (" . $mysqli->errno . ") " . $mysqli->error;
          }
          return true;
        }

	function s_query($statement,$a_param_type, $a_bind_params,$rowCheck=false){
		global $mysqli;
		$result= doStatement($statement,$a_param_type, $a_bind_params,$rowCheck,"query");
		return $result;
	}

	function del_query($statement,$a_param_type, $a_bind_params,$rowCheck=false){
		global $mysqli;
		$result = doStatement($statement,$a_param_type, $a_bind_params,$rowCheck,"delete");
		return $result;
	}
	
	function s_i_query($statement,$a_param_type, $a_bind_params,$rowCheck=false){
		global $mysqli;
		$result= doStatement($statement,$a_param_type, $a_bind_params,$rowCheck,"insert");
		return 	$result;
	}
	
	function s_c_query($statement,$a_param_type, $a_bind_params,$rowCheck=false){
		global $mysqli;
		$result= doStatement($statement,$a_param_type, $a_bind_params,$rowCheck,"query");
		return sizeof($result);
	}
	
	function handleError($error){
		$msg="";
		if(!DB_SHOW_ERRORS){
			$msg.="Sorry, an unexpected SQL error has occurred. Please try again.";
		}else{
			$msg.="SQL ERROR: ".$error;
		}
		$msg.='<br><br><input type="button" value="Back" onclick="window.history.back()" />';
		die($msg);
	}
	
	function doStatement($statement,$a_param_type, $a_bind_params,$rowCheck=false,$type){
		global $mysqli;
		
		if ($stmt = $mysqli->prepare ($statement)) {	 	

			$a_params = array();			
			$param_type = '';			
			$n = (count($a_param_type)==null)?0:count($a_param_type);
			for($i = 0; $i < $n; $i++) {
				$param_type .= $a_param_type[$i];
			}
			$a_params[] = & $param_type;
			for($i = 0; $i < $n; $i++) {
				$a_params[] = & $a_bind_params[$i];
			}
			if($n!=0){ // only if there are parameters to bind
				call_user_func_array(array($stmt, 'bind_param'), $a_params);				
			}

			$resultExecute = $stmt->execute ();
			if (!$resultExecute ) {
				handleError($mysqli->error);
			} else {
				if($type=="query"){
					$result = get_result( $stmt );	
					if( ($rowCheck && sizeof($result) === 0)){
						if($mysqli->error==""){
							$error = "Query didn't return any results";
						}else{
							$error = $mysqli->error;
						}
						handleError($error);
					}
					$stmt -> close();
					return $result; 
				}elseif($type=="insert"){
					return $stmt->insert_id;
				}elseif($type=="delete"){
					return $mysqli->affected_rows;
				}else{
					die("UNKNOWN STATEMENT MODE");
				}
			}
		} else {
			handleError($mysqli->error);
		}
	}
	
	function get_result( $Statement ) {
		$RESULT = array();
		$Statement->store_result();
		for ( $i = 0; $i < $Statement->num_rows; $i++ ) {
			$Metadata = $Statement->result_metadata();
			$PARAMS = array();
			while ( $Field = $Metadata->fetch_field() ) {
				$PARAMS[] = &$RESULT[ $i ][ $Field->name ];
			}
			call_user_func_array( array( $Statement, 'bind_result' ), $PARAMS );
			$Statement->fetch();
		}
		return $RESULT;
	}	
?>