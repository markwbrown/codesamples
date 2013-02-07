<?
	/******* Debug Information File - Rafael Lucchese ********/
	// Colocado no arquivo footer.inc.php
	// A variavel SESSION["debug"] liga e desliga o seu funcionamento
	
	$_SESSION["debug"] = false; // Liga e desliga o funcionamento desse arquivo (caso ele cresca no futuro, seria melhor assim)
	$_SESSION['e_reporting'] = false;
	
	//Print the session array
	if($_SESSION["debug"]) {
		echo "<!--<pre>SESSION";
		print_r($_SESSION);
		echo "</pre>-->";
		
		echo "<!--<pre>POST";
		print_r($_POST);
		echo "</pre>-->";
		
		echo "<!--<pre>GET";
		print_r($_GET);
		echo "</pre>-->";
	}
	
	if($_SESSION['e_reporting']) {
		error_reporting(E_ALL);
	}
?>