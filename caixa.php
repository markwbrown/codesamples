<?php
	@include "inc/pre_header_info.inc.php";
	
	// Temporario
	if(!($_SESSION["us3rl00ge000121"] == 5 || $_SESSION["us3rl00ge000121"] == 13 || $_SESSION["us3rl00ge000121"] == 3)) {
		
		echo "	<script>
					alert('Você não tem privilégios suficientes para ver essa página.');
					document.location.href='index.php';
				</script>"; // redirecionar o usuario para a pagina inicial
	
	}
?>

<!DOCTYPE html>
<html>
<head>
	<? @include "inc/head.inc.php"; ?>

	<title>Grupo Cliente X - Caixa</title>
	
	<link href="datepicker.css" rel="stylesheet" type="text/css" />
	
	<script src="js/Caixa.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/datepicker.js" type="text/javascript" charset="utf-8"></script>
	
	<script type="text/javascript">
		document.observe("dom:loaded", function() {
			user_id = '<?php echo $_SESSION["us3rl00ge000121"] ?>';
			valid = new Validation('caixa-form', { onSubmit:false, stopOnFirst:true });
			t = new Caixa();
		});
		
		function rmv(el_nbr) {
			t._onRmv(el_nbr);
			
			var theRow = $('rm-'+el_nbr).up().up();
			new Effect.Fade(theRow, { duration:1});
		}
		

	</script>
</head>

<body class="caixa">
	<div id="container">

		<? @include "inc/menu.inc.php"; ?>

		<div id="box">
			<form method="post" id="caixa-form" action="">
				<span class="right breadcrumb"><br/><br/><a href="index.php">Página Inicial >></a>&nbsp;&nbsp;</span>
				<h1>Caixa</h1>
		
				<div class="content">
					<div class="topinfo">
						<div class="right total">
							<h5>R$ <span id="total">0,00</span></h5>
						</div>
						
						<div class="record">
							<a href="#gravar" title="Aperte para Gravar" id="salvar" class="right clear">
								<img src="img/ok_25x25.png" width="25" height="25" />
							</a>

							<table>
								<tr>
									<td>
										<fieldset>
									      	<input tabindex="1" type="text" class="format-d-m-y highlight-days-67 show-weeks search_param required validate-date-au" id="cx-data" name="dp-normal-1" value="" maxlength="10" />
									    </fieldset>
									</td>
									<td><input type="text" id="cx-receptor" class="required" maxlength="75" /></td>
									<td><input type="text" id="cx-finalidade" class="required" maxlength="75" /></td>
									<td>
										<select id="cx-material">
											<option value="0">-- nenhum material --</option>
										</select>
									</td>
									<td><input type="text" id="cx-nota" maxlength="25" /></td>
									<td><input type="text" id="cx-valor-in" class="required validate-currency-real" maxlength="10"/></td>
									<td><input type="text" id="cx-valor-out" class="required validate-currency-real" maxlength="10"/></td>
								</tr>
							</table>
						</div>
						
					</div>
					
					<img class="spinner" id="spinner" src="img/spinner.gif" width="32" height="32" style="display:none" />
					
					<div class="search-results">
						<div class="table-head">
							<div class="cx-data left">Data</div>
							<div class="cx-receptor left">Receptor</div>
							<div class="cx-finalidade left">Finalidade</div>
							<div class="cx-material left">Material</div>
							<div class="cx-nota left">Nota</div>
							<div class="cx-valor-in left">Entrada</div>
							<div class="cx-valor-out left">Saída</div>
							<div class="rmv left"></div>
						</div>
						
						<div class="callout list-wrapper">
							<table id="dyn-rows" cellspacing="0" cellpadding="0">
								<thead></thead>
								<tbody></tbody>
							</table>
						</div>	
						
					</div>					
					
				</div> <!-- /.content-->
			</form>
		</div> <!-- /box -->

		<? @include "inc/footer_new.inc.php"; ?>

	</div> <!-- /container --> 
</body>
</html>