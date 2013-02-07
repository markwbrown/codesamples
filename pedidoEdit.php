<?php
	@include "inc/pre_header_info.inc.php";
	@include "inc/pedido_backend.inc.php";
?>

<!DOCTYPE html>
<html>
<head>
	<? @include "inc/head.inc.php"; ?>

	<title>Grupo Cliente X - Pedidos - Criar</title>
	
	<script src="js/pedido.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/PedidoMod.js" type="text/javascript" charset="utf-8"></script>
	
	<script type="text/javascript">
		
		document.observe("dom:loaded", function() {
			
			user_id = '<?php echo $_SESSION["us3rl00ge000121"] ?>';
			
			<? if($_GET['n'] || $_GET['n'] != 0) { ?>
				
					var pedido = new PedidoMod(<? echo $_GET['n']; ?>);
					
			<? } else { ?>
					location.href = "pedidoSplash.php";
			<?		
				}	?>

			valid = new Validation('order-form', { onSubmit:false, stopOnFirst:true });
		});

	</script>
	
</head>

<body>
	<div id="container">

		<? @include "inc/menu.inc.php"; ?>

		<div id="box" class="pedido">
			<form method="post" id="order-form" action="inc/pedido_backend.inc.php">
		
				<span class="right breadcrumb"><br/><br/><a href="pedidoSplash.php">Novo pedido >></a>&nbsp;&nbsp;</span>
				<span class="right breadcrumb"><br/><br/><a href="listOrders.php">&nbsp;&nbsp;<< Voltar para a busca de pedidos</a>&nbsp;&nbsp;|&nbsp;&nbsp;</span>
				
				<h1>Modificar Pedido</h1>
				
				<div class="statusSection grid3col">
					
					<div class="col first">
						<label for="status-p">Produção: </label>
						<input type="text" class="status-p" id="status_p" readonly="readonly" maxlength="32" />
					</div>
					
					<div class="col">
						<label for="status-f">Financeiro: </label>
						<input type="text" class="status-f" id="status_f" readonly="readonly" maxlength="32" />
					</div>
					
					<div class="col last">
						<label for="status-d">Diretoria: </label>
						<input type="text" class="status-d" id="status_d" readonly="readonly" maxlength="75" />
					</div>

				</div>
		
				<div class="right pedido-num"><span></span></div>
				
				<div class="right print-msg callout">
					<a class="print" id="print" href="pedidoPrint.php?n=<? echo $_GET['n']; ?>" target="_blank">
						<img class="left" src="img/icon_print.png" width="32" height="32" />
						<span>Imprimir</span>
					</a>
				</div>
				
				<div class="data-wrapper left">
					<label for="data" class="left">Data do pedido: </label>&nbsp;
					<span id="data" class="data" style=""></span>.
				</div>
				
				<div class="autor-wrapper left">
					<label for="autor" class="left">Autor: </label>&nbsp;
					<span id="autor" class="autor" style=""></span>.
				</div>
				
				<div class="ordem-wrapper left">
					<label for="ordem" class="left" style="padding-top:8px;">Ordem C.: </label>&nbsp;
					<input id="ordem" class="ordem" type="text" maxlength="20" />
				</div>
				
				<div class="pe-wrapper left">
					<label for="ordem" class="left" style="padding-top:8px;">P.Ent. </label>&nbsp;
					<input id="pe" class="pe" type="checkbox" />
				</div>
		
				<div class="grid4col clear">
					<div class="col first">
						<img src="img/grupo-Cliente X.png" width="100" height="65" />
					</div>
					<div class="col empresa">
						<h3>UNIÃO DE FARDAMENTOS COMERCIAL&nbsp;LTDA.</h3>
						<ul>
							<li>Insc. Estadual 12.126.954-0&nbsp;CNPJ:41.493.008/0001-14</li>
							<li>Rua Domingos Olímpio. Qda. S - Casa 01 - Conjunto Ipase. CEP: 65061-120.</li>
							<li>Fone: (98)3246-6800 - Fax: (98)3236-9086. Sao Luís, MA.</li>
						</ul>	
					</div>
					<div class="col empresa">
						<h3>LUCCHESE INDÚSTRIA E COMÉRCIO DE&nbsp;CONFECÇÕES&nbsp;LTDA.</h3>
						<ul>
							<li>Insc. Estadual 12.229.630-3&nbsp;CNPJ:08.227.276/0001-19</li>
							<li>Ave. Daniel de La Touche, N 01 - Quadra S - Lote 01 - Ipase. Sao Luís, MA.</li>
						</ul>
					</div>
					<div class="col last">
						<p>A EMPRESA NÃO SE RESPONSABILIZA PELO PAGAMENTO DE QUAISQUER VALORES AO VENDEDOR.</p>
						<p>VERIFIQUE O PEDIDO AO ASSINAR. NÃO TROCAMOS, NEM ACEITAMOS DEVOLUÇÃO DE MERCADORIAS.</p>
					</div>
				</div>
		
				<div class="content">
				   	<div class="topinfo">
						<div class="left razao"><p>Razão Social:</p><input type="text" id="razao" readonly /></div>
						<div class="left nome"><p>Nome:</p><input type="text" id="nome" readonly /></div>
			
						<div class="clear left endereco"><p>Endereço:</p><input tabindex="3" type="text" id="endereco" readonly /></div>
						<div class="left numero"><p>N°</p><input type="text" class="small" id="numero" readonly /></div>
						<div class="left bairro"><p>Bairro:</p><input type="text" id="bairro" readonly /></div>
			
						<div class="clear left cnpj"><p>CNPJ:</p><input type="text" id="cnpj" readonly /></div>
						<div class="left inscricao"><p>Insc. Estadual:</p><input type="text" id="inscricao" readonly /></div>
						<div class="left cidade"><p>Cidade:</p><input type="text" id="cidade" readonly /></div>
						<div class="left estado"><p>Estado:</p><input type="text" id="estado" readonly /></div>
						
						<div class="clear left fin"><p>Financeiro:</p><input tabindex="12" type="text" id="fin" maxlength="40" /></div>
						<div class="left fintel"><p>Tel.:</p><input tabindex="12" type="text" id="fintel" maxlength="45" /></div>
						<div class="left finemail"><p>Email.:</p><input tabindex="12" type="text" id="finemail" maxlength="55" /></div>
						
						<div class="clear left comprador"><p>Comprador:</p><input tabindex="13" type="text" id="comprador" maxlength="40" /></div>
						<div class="left tel"><p>Tel.:</p><input tabindex="13" type="text" id="tel" maxlength="45" /></div>
						<div class="left email"><p>Email.:</p><input tabindex="13" type="text" id="email"  maxlength="55" /></div>
						
						<div class="clear left entrega"><p>Loc. Entrega:</p><input tabindex="14" type="text" id="entrega" maxlength="38" /></div>
						<div class="left pagamento"><p>Cond. Pag.:</p><input tabindex="14" type="text" id="pagamento" maxlength="48" /></div>
						<div class="left prazo"><p>Prazo de Entrega:</p><input tabindex="14" type="text" id="prazo" maxlength="38" /></div>
					</div> <!-- /.topinfo-->
			
					<section id="dyn-rows"></section>
			
				</div> <!-- /.content-->
				
				<div id="save-button" class="save-button" style="display:none">
					<ul class="top-order-links left">
						<li><a id="order-duplicate" href="#duplicar">Duplicar esse Pedido</a></li>
						<li><a id="order-delete" href="#deletar">Deletar esse Pedido</a></li>
					</ul>
					<a href="#salvar" id="salvar" class="salvar button"><span>Salvar Modificações</span></a>
				</div>
				
			</form>
		</div> <!-- /box -->

		<? @include "inc/footer_new.inc.php"; ?>

	</div> <!-- /container --> 
</body>
</html>