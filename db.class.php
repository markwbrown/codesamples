<?php	
	class Db
	{
		var $dbHost = "localhost";
		var $dbUser = "username";
		var $dbPass = "password";
		var $dbName = "databasename";
		
		function Db($dbHost, $dbUser, $dbPass, $dbName)
		{
			$this->dbHost = $dbHost;
			$this->dbUser = $dbUser;
			$this->dbPass = $dbPass;
			$this->dbName = $dbName;
		}

		function dbConnect() 
		{
			$connection = mysql_connect($this->dbHost,$this->dbUser,$this->dbPass);
			if (!(mysql_select_db($this->dbName,$connection))) 
			{
				echo "Could not connect to the database";
			}
			
			return $connection;
		}
	};

?>