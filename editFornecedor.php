<?php
	@include "inc/pre_header_info.inc.php";
	@include "inc/states.inc.php"; // States - Drop down box
	@include "inc/contato_fornec.inc.php"; // Contato - Drop down box
?>

<!DOCTYPE html>
<html>
<head>
	<? @include "inc/head.inc.php"; ?>
	
	<title>Grupo Cliente X - Fornecedores - Modificar</title>
	
	<script type="text/javascript" src="js/Fornecedor.js"></script>
	
	<script type="text/javascript">
		document.observe("dom:loaded", function() {
			user_id = '<?php echo $_SESSION["us3rl00ge000121"] ?>';
			$('razao_social').focus();
			
			f_id = '<?php echo $_GET["id"]; ?>'; 
			
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
			<span class="right breadcrumb"><br/><br/><a href="fornecedor_detail.php?id=<? echo $_GET['id']; ?>">&nbsp;&nbsp;<< Visualizar esse Fornecedor</a>&nbsp;&nbsp;|&nbsp;&nbsp;</span>
			
			<h1>Modifique um Fornecedor</h1> 
		
			<div class="content yellowBorder"> <!-- content div -->
				<form name="fornecedor-form" id="fornecedor-form" method="post">	
					<h2 class="push-up">Dados Básicos Sobre o Fornecedor</h2>
					<div class="infoInner">
						<div class="left inputControl">
							<input type="text" name="razao_social" id="razao_social" tabindex="1" class="userText required" value="" />
							
							<br/><b>Razão Social</b>
						</div>	
			
						<div class="left inputControl">
							<input type="text" name="nome" id="nome" tabindex="2" class="userText" value="" />
							<br/><b>Nome Fantasia</b>
						</div>	
							<div class="clear"></div>
				
						<div class="left inputControl">
							<input type="text" name="cnpj1" id="cnpj1" tabindex="3"  class="userText required" style="width:30px;" maxlength="2" value="" />&nbsp;-&nbsp;
							<input type="text" name="cnpj2" id="cnpj2" tabindex="3"  class="userText required" style="width:60px;" maxlength="3" value="" />&nbsp;-&nbsp;
							<input type="text" name="cnpj3" id="cnpj3" tabindex="3"  class="userText required" style="width:60px;" maxlength="3" value="" />&nbsp;/&nbsp;
							<input type="text" name="cnpj4" id="cnpj4" tabindex="3"  class="userText required" style="width:60px;" maxlength="4" value="" />&nbsp;-&nbsp;
							<input type="text" name="cnpj5" id="cnpj5" tabindex="3"  class="userText required" style="width:54px;" maxlength="2" value="" />
							
							<br/><b>CNPJ</b>
						</div>
			
						<div class="left inputControlLast">
							<input type="text" name="inscricao_estadual" id="inscricao_estadual" tabindex="4"  class="userText required" value="" />
							
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
							<input type="text" name="rua" id="rua" tabindex="5" class="userText required" value="" />
							
							<br/><b>Rua</b>
						</div>
			
						<div class="left inputControl">
							<input type="text" name="quadra" id="quadra" tabindex="6" class="userText" style="width:40px;" value="" />
							<br/><b>Quadra</b>
						</div>	
			
						<div class="left inputControl">
							<input type="text" name="numero" id="numero" tabindex="6" class="userText" style="width:50px;" value="" />
							<br/><b>N&uacute;mero</b>
						</div>	
				
						<div class="left inputControl">
							<input type="text" name="bairro" id="bairro" tabindex="7"  class="userText required" style="width:180px;" value="" />
							
							<br/><b>Bairro</b>
						</div>
						<div class="clear"></div>
			
						<div class="left inputControl">
							<input type="text" name="cidade" id="cidade" tabindex="8" value="São Luís" class="userText required" value="" />
							
							<br/><b>Cidade</b>
						</div>
				
						<div class="left inputControl">
							<? echo $stateList; ?> <!-- states.inc.php -->
							
							<br/><b>Estado</b>
						</div>
			
						<div class="left inputControl">
							<input type="text" name="cep" id="cep" tabindex="10" class="userText required" style="width:157px;" value="" />
							
							<br/><b>Cep</b>
						</div>
		                         <div class="clear"></div>
                         
		                         <div class="left inputControlLast">
							<input type="text" name="website" id="website" tabindex="10" class="userText" value="" />
							<br/><b>Website</b>
						</div>	
							<div class="clear"></div>	
					</div>	
		
					<h2>Telefone e Fax</h2>
					<div class="infoInner">
						<div class="left inputControl">
							<input type="text" name="telefone" id="telefone" tabindex="11" class="userText required" value="" />
							
							<br/><b>Telefone 1</b>
						</div>	
				
						<div class="left inputControl">
							<input type="text" name="fax" id="fax" tabindex="12" class="userText" value="" />
							<br/><b>Fax</b>
						</div>
							<div class="clear"></div>
                         
		                         <div class="left inputControl">
							<input type="text" name="telefone_2" id="telefone_2" tabindex="11" class="userText" value="" />
							
							<br/><b>Telefone 2</b>
						</div>	
                         
		                         <div class="left inputControlLast">
							<input type="text" name="telefone_3" id="telefone_3" tabindex="11" class="userText" value="" />
							<br/><b>Telefone 3</b>
						</div>	
						<div class="clear"></div>	
					</div>	
					
					<h2>Contatos</h2>

					<div id="contatos"></div>
					
					<a href="#add-contato" id="add-contato" class="add-contato">Adicionar outro contato</a>
					
					<input type="hidden" name="edC" id="edC" value="1" />
					<input type="hidden" id="client_id" value="<? echo $_GET['id']; ?>" /> 
					
					<div class="save-button">
						<a href="#modificar" name="modificar" id="modificar" class="salvar button"><span>Modificar Fornecedor</span></a>
					</div>
              
				</form>	
			</div><!-- end of content div-->
		</div> <!-- end of Box -->

		<? @include "inc/footer_new.inc.php"; ?>

	</div> <!-- /container --> 
</body>
</html>