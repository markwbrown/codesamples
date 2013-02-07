<?
// Session expire file
// Rafael Lucchese - July 2009, San Luis Obispo, California.
// The expiration time is set to the constant $time_expire

session_cache_expire(15); //time in minutes
session_start(); // NEVER FORGET TO START THE SESSION!!!

$inactive = 900;
if(isset($_SESSION['start']) ) {
	$session_life = time() - $_SESSION['start'];
	if($session_life > $inactive){
		header("Location: user_logout.php");
	}
}

$_SESSION['start'] = time();

if($_SESSION['valid_user'] != true)
{
	header('Location: ../index.php');
}

?>