<?php
	@include "inc/pre_header_info.inc.php";
?>

<!DOCTYPE html>
<html>
<head>
	<? @include "inc/head.inc.php"; ?>
	
	<title>Grupo Cliente X - Página Inicial</title>
	
	<link href="datepicker.css" rel="stylesheet" type="text/css" />
	
	<script src="js/CallList.js" type="text/javascript" charset="utf-8"></script>
	
	<script type="text/javascript">
		document.observe("dom:loaded", function() {
			user_id = '<?php echo $_SESSION["us3rl00ge000121"] ?>';
			
			var calls = new CallList(); // generates list of calls, first table
		});

	</script>	
</head>

<body>
	<div id="container">
		
		<? @include "inc/menu.inc.php"; ?>
		
		<div id="box" class="home">
			<h1>Painel de Entrada</h1>
	
			<div class="home-intro">
				<h3>Bem vindo(a), <?php echo $_SESSION["user"]; ?>!</h3>
				<div class="callout orange duvidas msg">	
					<p>Em caso de dúvidas, <a href="help_desk.php" class="tableLinks">envie uma mensagem</a>.</p>
				</div>
				
				<div class="painel callout">
					<h3>Quem me ligou</h3>
					
					<div class="callout orange rmv msg"><b>Ligações para <?php echo $_SESSION["user"]; ?></b> (de <? echo date('d/m/Y', mktime(0,0,0,date('n')-1, date('d'), date('Y'))); ?> à <? echo date('d/m/Y', mktime(0,0,0,date('n'), date('d'), date('Y'))); ?>)</div>
					
					<div class="search-results">
						<div class="table-head">
							<div class="data left">Data</div>
							<div class="nome left">Nome</div>
							<div class="para left">Para</div>
							<div class="telefone left">Telefone</div>
							<div class="obs left">Detalhes</div>
						</div>
						
						<div class="callout list-wrapper">
							<table id="call-rows" cellspacing="0" cellpadding="0">
								<thead></thead>
								<tbody></tbody>
							</table>
						</div>
						
						<div class="arrows">
							<a href="#" id="down"><img src="img/arrow_down.png" width="30" height="29" /></a>
							<a href="#" id="up"><img src="img/arrow_up.png" width="30" height="29" /></a>
						</div>	
						
						<input type="hidden" id="outbound_call" value="0" />
					</div>
				</div> <!-- /.painel -->
				
			</div> <!-- /.home-intro -->
		</div> <!-- /box -->

	<? @include "inc/footer_new.inc.php"; ?>

	</div> <!-- /container --> 
</body>
</html>