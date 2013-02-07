<?	/*   array of states in the system   - Cliente X 2009 */
	
	
	//Endereco - First, get the state that is currently in the database for that client, if the client exists
	$queryEndereco = "SELECT * FROM `endereco` WHERE `f_cliente_id`='" . $_GET['id'] . "'";
	$resultEndereco = mysql_query($queryEndereco) or die("Error:" . $queryEndereco .  mysql_error());
	$endereco = mysql_fetch_array($resultEndereco);	
	
	// the create the array of states		
	$estados = array(
				1 => "Acre",
				2 => "Alagoas",
				3 => "Amapá",
				4 => "Amazonas",
				5 => "Bahia",
				6 => "Ceará",
				7 => "Distrito Federal",
				8 => "Espírito Santo",
				9 => "Goiás",
				10 => "Maranhão",
				11 => "Mato Grosso",
				12 => "Mato Grosso do Sul",
				13 => "Minas Gerais",
				14 => "Pará",
				15 => "Paraíba",
				16 => "Paraná",
				17 => "Pernambuco",
				18 => "Piauí",
				19 => "Roraima",
				20 => "Rondonia",
				21 => "Rio de Janeiro",
				22 => "Rio Grande do Norte",
				23 => "Rio Grande do Sul",
				24 => "Santa Catarina",
				25 => "São Paulo",
				26 => "Sergipe",
				27 => "Tocantins"
				);
	
	// Then insert them into a string that can be printed out later		
	$stateList = '
	<select name="estado" id="estado" tabindex="9" class="inputControl">
		<option value="10" SELECTED>Maranh&atilde;o</option>
	';	
			
	foreach($estados as $e_index=>$e)
	{ 
		if($e_index==$endereco['endereco_estado'])
		{		
			$stateList .= "<option value='" . $e_index . "' SELECTED>" .  $e . "</option> ";
		}
		else
		{
			$stateList .= "<option value='" . $e_index . "'>" .  $e . "</option> ";
		}	
	} 
	
	$stateList .= "		
	</select>
	";
	
?>