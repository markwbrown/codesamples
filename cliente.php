<?php
	@include "inc/pre_header_info.inc.php";
	@include "inc/cliente_backend.inc.php";
?>

<!DOCTYPE html>
<html>
<head>
	<? @include "inc/head.inc.php"; ?>
	
	<title>Grupo Cliente X - Cliente - Modificar</title>
	
	<script type="text/javascript" src="js/effects.js"></script>
	<script type="text/javascript" src="js/tabs.js"></script>
	
	<script type="text/javascript">
		document.observe("dom:loaded", function() {
			new Tab({id: "tabs1", rounded: 1, height: 1}); // Creates tabs on the fly
			document.cliente.razao_social.focus();
		});
	</script>	
</head>

<body>
	<div id="container">
		
		<? @include "inc/menu.inc.php"; ?>
		
		<div id="box">
			<span class="error right">("*" = campos obrigatórios)<br/><br/><a href="listClients.php">voltar para a busca de clientes >></a>&nbsp;&nbsp;</span>
			<h1>Adicione um Cliente</h1>
	
			<div class="content yellowBorder"> <!-- content div -->
				<form name="cliente" method="post">	
		
					<h2 class="push-up">Dados Básicos Sobre o Cliente</h2>
					<div class="infoInner">
						<div class="left inputControl">
							<input type="text" name="razao_social" tabindex="1" class="userText" />
							<span class="error">*</span>
							<br/><b>Razão Social</b>
						</div>	
			
						<div class="left inputControl">
							<input type="text" name="nome" tabindex="2" class="userText" />
							<br/><b>Nome Fantasia</b>
						</div>	
							<div class="clear"></div>
					
						<div class="left inputControl" style="display:block;">
							<input type="text" name="cnpj1" tabindex="3"  class="userText" style="width:30px;" maxlength="2" />&nbsp;-&nbsp;
							<input type="text" name="cnpj2" tabindex="3"  class="userText" style="width:60px;" maxlength="3" />&nbsp;-&nbsp;
							<input type="text" name="cnpj3" tabindex="3"  class="userText" style="width:60px;" maxlength="3" />&nbsp;/&nbsp;
							<input type="text" name="cnpj4" tabindex="3"  class="userText" style="width:60px;" maxlength="4" />&nbsp;-&nbsp;
							<input type="text" name="cnpj5" tabindex="3"  class="userText" style="width:54px;" maxlength="2" />
							<span class="error">*</span>
							<br/><b>CNPJ</b>
						</div>
				
						<div class="left inputControl" style="display:none;">
							<input type="text" name="cpf" tabindex="3"  class="userText" style="width:30px;" />
							<span class="error">*</span>
							<br/><b>CPF</b>
						</div>	
				
						<div class="left inputControl">
							<input type="text" name="inscricao_estadual" tabindex="4"  class="userText" />
							<span class="error">*</span>
							<br/><b>Inscrição Estadual</b>
						</div>

						<div class="clear"></div>
					</div>	
		
					<h2>Endereço</h2>
					<div class="infoInner">
						<div class="left inputControl">
							<input type="text" name="rua" tabindex="5" class="userText" />
							<span class="error">*</span>
							<br/><b>Rua</b>
						</div>
			
						<div class="left inputControl">
							<input type="text" name="quadra" tabindex="6" class="userText" style="width:40px;" />
							<br/><b>Quadra</b>
						</div>	
			
						<div class="left inputControl">
							<input type="text" name="numero" tabindex="6" class="userText" style="width:50px;" />
							<br/><b>Número</b>
						</div>	
				
						<div class="left inputControl">
							<input type="text" name="bairro" tabindex="7"  class="userText" style="width:180px;" />
							<span class="error">*</span>
							<br/><b>Bairro</b>
						</div>
						<div class="clear"></div>
			
						<div class="left inputControl">
							<input type="text" name="cidade" tabindex="8" value="São Luís" class="userText" />
							<span class="error">*</span>
							<br/><b>Cidade</b>
						</div>
				
						<div class="left inputControl">
							<? echo $stateList; ?> <!-- states.inc.php -->
							<span class="error">*</span>
							<br/><b>Estado</b>
						</div>
			
						<div class="left inputControl">
							<input type="text" name="cep" tabindex="10" class="userText" style="width:157px;" />
							<span class="error">*</span>
							<br/><b>Cep</b>
						</div>
		                         <div class="clear"></div>
                         
		                         <div class="left inputControlLast">
							<input type="text" name="website" tabindex="10" class="userText" />
							<br/><b>Website</b>
						</div>	
							<div class="clear"></div>
				
					</div>	
		
					<h2>Telefone e Fax</h2>
					<div class="infoInner">
						<div class="left inputControl">
							<input type="text" name="telefone" tabindex="11" class="userText" />
							<span class="error">*</span>
							<br/><b>Telefone 1</b>
						</div>	
				
						<div class="left inputControl">
							<input type="text" name="fax" onKeyDown="Ulib.faxVal()" onKeyUp="Ulib.faxVal()" tabindex="12" class="userText" />
							<br/><b>Fax</b>
						</div>
							<div class="clear"></div>
                         
		                         <div class="left inputControl">
							<input type="text" name="telefone_2" tabindex="11" class="userText" />
							<span class="error" style="color:#fff;">*</span>
							<br/><b>Telefone 2</b>
						</div>	
                         
		                         <div class="left inputControlLast">
							<input type="text" name="telefone_3" tabindex="11" class="userText" />
							<br/><b>Telefone 3</b>
						</div>	
						<div class="clear"></div>
					
					</div>	
		
					<h2>Contatos</h2>
		               <div id="tabs1" class="tabset">
		                   <ul class="tabset_tabs">
		                       <li class="active"><a href="#tab-one">Contato 1</a></li>
		                       <li><a href="#tab-two">Contato 2</a></li>
		                       <li><a href="#tab-three">Contato 3</a></li>
		                   </ul>
		                   <div class="tabset_content_container">
		                       <div id="tab-one" class="tabset_content">
		                           <!-- information to be entered - Contact #1 -->	
		                               <div class="infoInner">
		                                   <div class="left inputControl">
		                                       <input type="text" name="comprador" tabindex="13" class="userTextSmaller" />
		                                       <span class="error">*</span>
		                                       <br/><b>Nome</b>
		                                   </div>
                                   
		                                   <div class="left inputControl">
		                                       <input type="text" name="telefone_do_comprador" tabindex="13" class="userTextSmaller" />
		                                       <br/><b>Telefone</b>
		                                   </div>	
		                                   <div class="clear"></div>
                                   
		                                   <div class="left inputControl">
		                                       <input type="text" name="email_do_comprador" tabindex="13" class="userTextSmaller" />
		                                       <span class="error" style="color:#fff;">*</span>
		                                       <br/><b>Email</b>
		                                   </div>
                                   
		                                   <div class="left inputControl">
		                                       <? echo $contato1; ?> <!-- contato.inc.php -->
		                                       <span class="error">*</span>
		                                       <br/><b>Tipo de Contato</b>
		                                   </div>
		                                   <div class="clear"></div>
                                   
		                               </div>	
		                           <!-- end of information to be entered - Contact #1 -->
		                       </div> <!-- /.tabset_content -->
		                       <div id="tab-two" class="tabset_content">
		                           <!-- information to be entered - Contact #2 -->	
		                               <div class="infoInner">
		                                   <div class="left inputControl">
		                                       <input type="text" name="comprador_2" tabindex="13" class="userTextSmaller" />
		                                       <span class="error">*</span>
		                                       <br/><b>Nome</b>
		                                   </div>
                                   
		                                   <div class="left inputControl">
		                                       <input type="text" name="telefone_do_comprador_2" tabindex="13" class="userTextSmaller" />
		                                       <br/><b>Telefone</b>
		                                   </div>	
		                                   <div class="clear"></div>
                                   
		                                   <div class="left inputControl">
		                                       <input type="text" name="email_do_comprador_2" tabindex="13" class="userTextSmaller" />
		                                       <span class="error" style="color:#fff;">*</span>
		                                       <br/><b>Email</b>
		                                   </div>
                                   
		                                   <div class="left inputControl">
		                                       <? echo $contato2; ?> <!-- contato.inc.php -->
		                                       <span class="error">*</span>
		                                       <br/><b>Tipo de Contato</b>
		                                   </div>
		                                   <div class="clear"></div>
                                   
		                               </div>	
		                           <!-- end of information to be entered - Contact #2-->
		                       </div> <!-- /.tabset_content -->
		                       <div id="tab-three" class="tabset_content">
		                           <!-- information to be entered - Contact #3 -->	
		                               <div class="infoInner">
		                                   <div class="left inputControl">
		                                       <input type="text" name="comprador_3" tabindex="13" class="userTextSmaller" />
		                                       <span class="error">*</span>
		                                       <br/><b>Nome</b>
		                                   </div>
                                   
		                                   <div class="left inputControl">
		                                       <input type="text" name="telefone_do_comprador_3" tabindex="13" class="userTextSmaller" />
		                                       <br/><b>Telefone</b>
		                                   </div>	
		                                   <div class="clear"></div>
                                   
		                                   <div class="left inputControl">
		                                       <input type="text" name="email_do_comprador_3" tabindex="13" class="userTextSmaller" />
		                                       <span class="error" style="color:#fff;">*</span>
		                                       <br/><b>Email</b>
		                                   </div>
                                   
		                                   <div class="left inputControl">
		                                       <? echo $contato3; ?> <!-- contato.inc.php -->
		                                       <span class="error">*</span>
		                                       <br/><b>Tipo de Contato</b>
		                                   </div>
		                                   <div class="clear"></div>
                                   
		                               </div>	
		                           <!-- end of information to be entered - Contact #3 -->
		                       </div> <!-- /.tabset_content -->
		                   </div> <!-- /.tabset_content_container -->
		               </div> <!-- /.tabset -->
                   	
					<input type="hidden" value="1" name="go" />
		            <div style="padding-top:40px; padding-bottom:20px; padding-left:390px;">
						<input type="image" src="img/adicionar-single.gif" alt="Adicionar Cliente" tabindex="16" onclick="return Ulib.verifyCliente()" />
					</div>
				</form>	
			</div><!-- /.content -->
	
		</div> <!-- end of Box -->

		<? @include "inc/footer_new.inc.php"; ?>

	</div> <!-- /container --> 
</body>
</html>
