<?php
	@include "inc/pre_header_info.inc.php";	
	@include "inc/changepass_backend.inc.php"

?>

<!DOCTYPE html>
<html>
<head>
	<? @include "inc/head.inc.php"; ?>
	
	<title>Grupo Cliente X - Aplicativos - Mudar Senha</title>
	
	<script type="text/javascript">
	
		user_id = '<?php echo $_SESSION["us3rl00ge000121"] ?>';
	
		document.observe("dom:loaded", function() {
			document.cPass.o_password.focus();
			
			Event.observe('salvar', 'click', function(e) {
				document.cPass.submit();
			});
		});
	</script>
</head>

<body>
	<div id="container">
		
		<? @include "inc/menu.inc.php"; ?>
		
		<div id="box" class="pass">
			<h1>Mudar Senha</h1>
	
			<div class="painel callout">
				<form name="cPass" method="post">
					<div class="infoInner">
			        	<div class="left inputControl">
							<input type="text" name="nome" value="<? echo $_SESSION['user']; ?>" readonly class="userText readOnly" />
							<span class="error">&nbsp;*</span>
						</div>
						<div class="right inputTitle">
							<b>Nome</b>
						</div>	
						<div class="clear"></div>
						<div class="left inputControl">
							<input type="password" name="o_password"  value="" class="userText" />
							<span class="error">&nbsp;*</span>
						</div>
						<div class="right inputTitle">
							<b>Senha Antiga</b>
						</div>	
						<div class="clear"></div>
						<div class="left inputControl">
							<input type="password" name="n_password"  value="" class="userText" />
							<span class="error">&nbsp;*</span>
						</div>
						<div class="right inputTitle">
							<b>Nova Senha </b>
						</div>	
						<div class="clear"></div>
						<div class="left inputControl">
							<input type="password" name="n_password2"  value="" class="userText" />
							<span class="error">&nbsp;*</span>
						</div>
						<div class="right inputTitle">
							<b>Nova senha (digite novamente)</b>
						</div>	
						<div class="clear"></div>
						
						<input type="hidden" value=1 name="change" />
						<div class="save-button">
							<a href="#salvar" name="changepass" id="salvar" class="salvar button"><span>Mudar Senha</span></a>
						</div>
					</div>			
				</form>
			</div>
			
		</div> <!-- /box -->

		<? @include "inc/footer_new.inc.php"; ?>

	</div> <!-- /container --> 
</body>
</html>

