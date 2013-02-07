<?php
	$doc_root = getenv("DOCUMENT_ROOT");
	@include_once "dbconnect.inc.php";
	
	$item_id = $_GET['item_id'];
	
	if($item_id !== null) { // Client has to exist as per the front end, but still let's use a fail-check mechanism
			
			$queryItem = " SELECT * FROM peca_armazenada WHERE peca_armazenada_id = {$item_id}";
		  
		  	$resultItem = mysql_query($queryItem) or die($queryItem . "  Result: " . mysql_error());
			
			$row = mysql_fetch_array($resultItem, MYSQL_ASSOC);
		
		// Return here with the echo statement so we can show a message and give the undo option
		echo json_encode($row);
		
	} else {
		
		echo "Cliente ainda não selecionado.";
	}

?>