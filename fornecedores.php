<?php
	@include "inc/pre_header_info.inc.php";
	@include "inc/states.inc.php"; // States - Drop down box
?>

<!DOCTYPE html>
<html>
<head>
	<? @include "inc/head.inc.php"; ?>
	
	<title>Grupo Cliente X - Fornecedor - Criar / Modificar</title>
	
	<script type="text/javascript" src="js/Fornecedor.js"></script>
	
	<script type="text/javascript">
		document.observe("dom:loaded", function() {
			
			user_id = '<?php echo $_SESSION["us3rl00ge000121"] ?>';
			$('razao_social').focus();
			
			f = new Fornecedor();
			
			// Really easy field validation
			valid = new Validation('fornecedor-form', { onSubmit:false, stopOnFirst:true, immediate : true });
			
		});
	</script>	
</head>

<body class="fornecedor">
	<div id="container">
		
		<? @include "inc/menu.inc.php"; ?>
		
		<div id="box">
			<span class="right breadcrumb"><br/><br/><a href="listFornecedores.php">buscar fornecedores >></a>&nbsp;&nbsp;</span>
			<h1>Adicione um Fornecedor</h1>
	
			<div class="content yellowBorder"> <!-- content div -->
				<form name="fornecedor-form" id="fornecedor-form" method="post">	
		
					<h2 class="push-up">Dados Básicos Sobre o Fornecedor</h2>
					<div class="infoInner">
						<div class="left inputControl">
							<input type="text" name="razao_social" id="razao_social" tabindex="1" class="userText required" />
							<br/><b>Razão Social</b>
						</div>	
			
						<div class="left inputControl">
							<input type="text" name="nome" id="nome" tabindex="2" class="userText" />
							<br/><b>Nome Fantasia</b>
						</div>	
							<div class="clear"></div>
					
						<div class="left inputControl cnpj-wrapper" style="display:block;">
							<input type="text" name="cnpj1" id="cnpj1" tabindex="3"  class="userText required" style="width:30px;" maxlength="2" />&nbsp;-&nbsp;
							<input type="text" name="cnpj2" id="cnpj2" tabindex="3"  class="userText required" style="width:60px;" maxlength="3" />&nbsp;-&nbsp;
							<input type="text" name="cnpj3" id="cnpj3" tabindex="3"  class="userText required" style="width:60px;" maxlength="3" />&nbsp;/&nbsp;
							<input type="text" name="cnpj4" id="cnpj4" tabindex="3"  class="userText required" style="width:60px;" maxlength="4" />&nbsp;-&nbsp;
							<input type="text" name="cnpj5" id="cnpj5" tabindex="3"  class="userText required" style="width:54px;" maxlength="2" />
							<br/><b>CNPJ</b>
						</div>
				
						<div class="left inputControl" style="display:none;">
							<input type="text" name="cpf" id="cpf" tabindex="3"  class="userText" style="width:30px;" />
							<br/><b>CPF</b>
						</div>	
				
						<div class="left inputControl">
							<input type="text" name="inscricao_estadual" id="inscricao_estadual" class="userText" tabindex="4"  class="userText" />
							<br/><b>Inscrição Estadual</b>
						</div>
						
						<div class="clear"></div>
						
						<div class="left inputControl">
							<input type="button" value="Adicionar" id="add-material" class="right" style="margin-left:20px;" />
							<select name="material" id="material" onchange="" class="input-material validate-selection" tabindex="5" style="width:293px">
								<option value="0">-- material fornecido --</option>
							</select>
							<br/><b>Tipo de Material Fornecido</b>
						</div>
						
						<div class="left inputControl" style="display:none" id="other-wrapper">
							<input type="text" name="other" id="other" tabindex="5"  class="userText" style="width:220px" />
							<input type="button" value="Adicionar Material" id="add-material-now" />
							<br/><b>Novo Material</b>
						</div>
						
						<img class="spinner" id="spinner" src="img/spinner.gif" width="32" height="32" style="display:none" />
						
						<div class="clear"></div>
					</div>	
		
					<h2>Endereço</h2>
					<div class="infoInner">
						<div class="left inputControl">
							<input type="text" name="rua" id="rua" tabindex="5" class="userText required" />
							<br/><b>Rua</b>
						</div>
			
						<div class="left inputControl">
							<input type="text" name="quadra" id="quadra" tabindex="6" class="userText" style="width:40px;" />
							<br/><b>Quadra</b>
						</div>	
			
						<div class="left inputControl">
							<input type="text" name="numero" id="numero" tabindex="6" class="userText" style="width:50px;" />
							<br/><b>Número</b>
						</div>	
				
						<div class="left inputControl">
							<input type="text" name="bairro" id="bairro" tabindex="7"  class="userText required" style="width:180px;" />
							<br/><b>Bairro</b>
						</div>
						<div class="clear"></div>
			
						<div class="left inputControl">
							<input type="text" name="cidade" id="cidade" tabindex="8" value="São Luís" class="userText required" />
							<br/><b>Cidade</b>
						</div>
				
						<div class="left inputControl">
							<? echo $stateList; ?> <!-- states.inc.php -->
							<br/><b>Estado</b>
						</div>
			
						<div class="left inputControl">
							<input type="text" name="cep" id="cep" tabindex="10" class="userText required" style="width:157px;" />
							<br/><b>Cep</b>
						</div>
		                         <div class="clear"></div>
                         
		                         <div class="left inputControlLast">
							<input type="text" name="website" id="website" tabindex="10" class="userText" />
							<br/><b>Website</b>
						</div>	
							<div class="clear"></div>
				
					</div>	
		
					<h2>Telefone e Fax</h2>
					<div class="infoInner">
						<div class="left inputControl">
							<input type="text" name="telefone" id="telefone" tabindex="11" class="userText required" />
							<br/><b>Telefone 1</b>
						</div>	
				
						<div class="left inputControl">
							<input type="text" name="fax" id="fax" tabindex="12" class="userText" />
							<br/><b>Fax</b>
						</div>
							<div class="clear"></div>
                         
		                <div class="left inputControl">
							<input type="text" name="telefone_2" id="telefone_2" tabindex="11" class="userText" />
							<br/><b>Telefone 2</b>
						</div>	
                         
		                <div class="left inputControlLast">
							<input type="text" name="telefone_3" id="telefone_3" tabindex="11" class="userText" />
							<br/><b>Telefone 3</b>
						</div>	
						<div class="clear"></div>
					
					</div>	
		
					<h2>Contatos</h2>

					<div id="contatos">
						
						<div class="contato contato-1 grid2col">
							<h3>Contato 1</h3>
							
							<div class="col">
								<dl>
									<dd><input type="text" name="contato-nome" id="contato-nome" tabindex="13" /></dd>
									<dt>Nome</dt>
									
									<dd class="clear"><input type="text" name="contato-celular" id="contato-celular" tabindex="13" /></dd>
				    				<dt>Celular</dt>
				
									<dd class="clear"><input type="text" name="contato-email" id="contato-email" tabindex="13" /></dd>
				    				<dt>Email</dt>
								</dl>
					    	</div>
							<div class="col last">
								<dl>
									<dd><input type="text" name="contato-tel" id="contato-tel" tabindex="13" /></dd>
					    			<dt>Telefone</dt>
  
									<dd class="clear"><input type="text" name="contato-cargo" id="contato-cargo" tabindex="13" /></dd>
									<dt>Cargo</dt>
								</dl>	
							</div>
						</div>
					</div>
					
					<a href="#add-contato" id="add-contato" class="add-contato">Adicionar outro contato</a>       
                   	
					<input type="hidden" value="1" name="go" />
		    
					<div class="save-button">
						<a href="#salvar" name="salvar" id="salvar" class="salvar button"><span>Salvar Fornecedor</span></a>
					</div>
				</form>	
			</div><!-- /.content -->
	
		</div> <!-- end of Box -->

		<? @include "inc/footer_new.inc.php"; ?>

	</div> <!-- /container --> 
</body>
</html>
