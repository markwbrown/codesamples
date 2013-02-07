<?php
	@include "inc/pre_header_info.inc.php";
	@include "inc/clientlist.inc.php"; // Clients - Drop down box
?>

<!DOCTYPE html>
<html>
<head>
	<? @include "inc/head.inc.php"; ?>

	<title>Grupo Cliente X - Ligações - Recebidas</title>
	
	<link href="datepicker.css" rel="stylesheet" type="text/css" />
	
	<script src="js/Calls.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/datepicker.js" type="text/javascript" charset="utf-8"></script>
	
	<script type="text/javascript">
		document.observe("dom:loaded", function() {
			user_id = '<?php echo $_SESSION["us3rl00ge000121"] ?>';
			valid = new Validation('order-form', { onSubmit:false, stopOnFirst:true });
			calls = new Calls();
		});
		
		function rmv(el_nbr) {
			calls._onRmv(el_nbr);
			
			var theRow = $('rm-'+el_nbr).up().up();
			new Effect.Fade(theRow, { duration:1});
		}

	</script>
</head>

<body class="calls in">
	<div id="container">

		<? @include "inc/menu.inc.php"; ?>

		<div id="box">
			<form method="post" id="order-form" action="">
				<span class="right breadcrumb"><br/><br/><a href="callsOut.php">Adicionar Ligações Discadas >></a>&nbsp;&nbsp;</span>
				<span class="right breadcrumb"><br/><br/><a href="listCalls.php"><< Buscar Ligações</a>&nbsp;&nbsp;|&nbsp;&nbsp;</span>
					
				<h1>Criar Ligações Recebidas</h1>
		
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
						
						<div class="wrapper clear">
							<div class="left telefone">
								<p>Telefone:</p>
								<input class="required" type="text" id="telefone" tabindex="3" id="nome" maxlength="13" />
							</div>

							<div class="left para">
								<p>Ligação Para:</p>
								<select id="para" class="required" tabindex="4">
									<option value="0"> -- Nome -- </option>
								</select>
							</div>
						</div>
						
						<div class="clear left obs">
							<p>Assunto:</p>
							<textarea tabindex="5" id="obs" name="obs" maxlength="256"></textarea>
						</div>
					</div>
					
					<div class="save-button">
						<a href="#salvar" id="salvar" class="salvar button"><span>Salvar Ligação</span></a>
					</div>
					
					<img class="spinner" id="spinner" src="img/spinner.gif" width="32" height="32" style="display:none" />
					
					<h3 class="center">Mostrando ligações de <b><?php echo date('d/m/Y', mktime(0,0,0,date('n')-1, date('d'), date('Y'))); ?></b> até <b><?php echo date('d/m/Y', mktime()); ?></b></h3>
					
					<div class="search-results">
						<div class="table-head">
							<div class="data left">Data</div>
							<div class="nome left">Nome</div>
							<div class="para left">Para</div>
							<div class="telefone left">Telefone</div>
							<div class="obs left">Detalhes</div>
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
					<input type="hidden" id="outbound_call" value=0 />
				</div> <!-- /.content-->
			</form>
		</div> <!-- /box -->

		<? @include "inc/footer_new.inc.php"; ?>

	</div> <!-- /container --> 
</body>
</html>