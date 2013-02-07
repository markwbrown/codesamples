<?php
	
	/*	mailer.class.php - Uses some security, then sends an email. 
		Designed for PHP5
	*/
	
	class Mailer
	{

		var $address = "";
		var $subject = "";
		var $message = "";
		var $headerFrom = "";
		var $senderName = "";
		var $headerFull = "";

		function Mailer($thisAddress, $thisSubject, $thisMessage, $thisFromAddr, $thisSenderName)
		{
			$this->address = $thisAddress;
			$this->subject = $thisSubject;
			$this->message = $thisMessage;
			$this->headerFromAddr = $thisFromAddr;
			$this->senderName = $thisSenderName;
		}

		function setHeader()
		{
			$this->headerFull = "MIME-Version: 1.0\n";
			$this->headerFull .= "Content-type: text/html; charset=iso-8859-1\n";
			$this->headerFull .= "From: {$this->senderName} <{$this->headerFromAddr}>\n";
			$this->headerFull .= "X-Sender: <{$this->senderName}>\n";
			$this->headerFull .= "X-Mailer: PHP\n"; // mailer
			$this->headerFull .= "X-Priority: 1\n"; // Urgent message!
			$this->headerFull .= "Return-Path: <{$this->headerFromAddr}>\n";  // Return path for errors
		}

		function getHeader()
		{
			return $this->headerFull;
		}

		function secureIt()
		{
			$temp_array = $_POST;

			for ($i=0;$i < count($temp_array);$i++) 
			{
				$temp_key = key($temp_array);
				$temp_current = current($temp_array);

				if (stristr($temp_current,"MIME") || stristr($temp_current,"content-type") || stristr($temp_current,"bcc") || stristr($temp_current,"content-transfer") || stristr($temp_current,"charset")) 
				{
					exit;
				}

				next($temp_array);
			}

			$temp_array = $_GET;

			for ($i=0;$i < count($temp_array);$i++) 
			{
				$temp_key = key($temp_array);
				$temp_current = current($temp_array);

				if (stristr($temp_current,"MIME") || stristr($temp_current,"content-type") || stristr($temp_current,"bcc") || stristr($temp_current,"content-transfer") || stristr($temp_current,"charset"))
				{
					exit;
				}

				next($temp_array);
			}
		} // end of public method secureIt

		function mailIt()
		{
			mail($this->address, $this->subject, $this->message, $this->headerFull);
		}

	}; // end of class
?>