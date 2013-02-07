<?php
	@include "inc/pre_header_info.inc.php";
?>

<!DOCTYPE html>
<html>
<head>
	<? @include "inc/head.inc.php"; ?>

	<title>Grupo Cliente X - Visitas - Criar</title>
	
	<link href="datepicker.css" rel="stylesheet" type="text/css" />
	
	<script src="js/Visitas.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/datepicker.js" type="text/javascript" charset="utf-8"></script>
	
	<script type="text/javascript">
		document.observe("dom:loaded", function() {
			user_id = '<?php echo $_SESSION["us3rl00ge000121"] ?>';
			valid = new Validation('order-form', { onSubmit:false, stopOnFirst:true });
			visitas = new Visitas();
		});
		
		function rmv(el_nbr) {
			visitas._onRmv(el_nbr);
			
			var theRow = $('rm-'+el_nbr).up().up();
			new Effect.Fade(theRow, { duration:1});

		}

	</script>
</head>

<body>
	<div id="container">

		<? @include "inc/menu.inc.php"; ?>

		<div id="box" class="visitas">
			<form method="post" id="order-form" action="">
				<span class="right breadcrumb"><br/><br/><a href="listVisits.php">Adicionar Visitas >></a>&nbsp;&nbsp;</span>
							
				<h1>Criar Visitas</h1>
		
				<div class="content">
					<div class="topinfo">
						<div class="left data">
							<p>Data:</p>
							<fieldset>
						      	<input tabindex="1" type="text" class="format-d-m-y highlight-days-67 show-weeks search_param required validate-date-au" id="data" name="dp-normal-1" value="" maxlength="10" />
						    </fieldset>
						</div>
						<div class="left nome">
							<p>Nome:</p>
							<input class="required" type="text" id="nome" tabindex="2" id="nome" maxlength="75" />
						</div>
						<div class="left comprador">
							<p>Comprador:</p>
							<input class="required" tabindex="3" type="text" id="comprador" maxlength="75" />
						</div>
						<div class="wrapper clear">
							<div class="left telefone">
								<p>Telefone:</p>
								<textarea class="required" tabindex="4" type="text" id="telefone"></textarea>
							</div>
							<div class="left endereco">
								<p>Bairro:</p>
								<input tabindex="5" type="text" id="bairro" maxlength="75" />
							</div>
							<div class="left resultado">
								<p>Resultado:</p>
								<select class="validate-selection" tabindex="6" id="resultado">
									<option value="0">-- Selecione Um --</option>
									<option value="1">Proposta Entregue</option>
									<option value="2">Passarei Proposta</option>
									<option value="3">Visita Marcada</option>
									<option value="4">Marcar Visita</option>
									<option value="5">Deixei Contato</option>
									<option value="6">Nota de Compra Feita</option>
									<option value="7">Outro ...</option>
								</select>
							</div>
							<div class="left cliente-tipo">
								<p>Tipo de Cliente:</p>
								<select class="validate-selection" tabindex="6" id="cliente-tipo">
									<option value="0">-- Selecione Um --</option>
									<option value="1">Da Casa</option>
									<option value="2">Novo</option>
									<option value="3">Recuperando</option>
								</select>
							</div>
						</div>
						
						<div class="clear left obs">
							<p>Observações:</p>
							<textarea tabindex="7" id="obs" name="obs" maxlength="256"></textarea>
						</div>
					</div>
					
					<div class="save-button">
						<a href="#salvar" id="salvar" class="salvar button"><span>Salvar Visita</span></a>
					</div>
					
					<img class="spinner" id="spinner" src="img/spinner.gif" width="32" height="32" style="display:none" />
					
					<h3 class="center">Mostrando visitas de <b><?php echo date('d/m/Y', mktime(0,0,0,date('n'), date('d')-7, date('Y'))); ?></b> até <b><?php echo date('d/m/Y', mktime()); ?></b></h3>
					
					<div class="search-results">
						<div class="table-head">
							<div class="data left">Data</div>
							<div class="nome left">Nome</div>
							<div class="telefone left">Telefone</div>
							<div class="bairro left">Bairro</div>
							<div class="comprador left">Comprador</div>
							<div class="resultado left">Resultado</div>
							<div class="cliente-tipo left">Tipo</div>
							<div class="obs left">Observações</div>
						</div>
						
						<div class="callout list-wrapper">
							<table id="dyn-rows" cellspacing="0" cellpadding="0">
								<thead></thead>
								<tbody></tbody>
							</table>
						</div>
						
						<div class="arrows">
							<a href="#down" id="down"><img src="img/arrow_down.png" width="30" height="29" /></a>
							<a href="#up" id="up"><img src="img/arrow_up.png" width="30" height="29" /></a>
						</div>	
						
					</div>					
					
				</div> <!-- /.content-->
			</form>
		</div> <!-- /box -->

		<? @include "inc/footer_new.inc.php"; ?>

	</div> <!-- /container --> 
</body>
</html>