<?
	session_start(); //start a session	
	@include "inc/dbconnect.inc.php"; //All pages will need a database connection
	@include "inc/check.inc.php"; // Check to see if the user is logged in
?>