var Fornecedor = Class.create({
	initialize: function () {
		
		this.loadFieldsURL = 'inc/fornecedor_data.inc.php';
		
		if($('salvar') !== null) { // we are in fornecedor.php and not inside of editFornecedor.php
			
			this.fornecedorURL = 'inc/fornecedor_backend.inc.php';
			Event.observe('salvar', 'click', this._onClickHandler.bind(this));
		
		} else {
			
			this.fornecedorURL = 'inc/editFornecedor_backend.inc.php';
			Event.observe('modificar', 'click', this._onClickHandler.bind(this));
			
			// get info to load the fields
			new Ajax.Request(this.loadFieldsURL, { 
				method:'get',
				parameters: { 'id': f_id },
				onSuccess: this._onLoadFieldsSuccess.bind(this),
				onFailure: function() { alert('Ocorreu um probleminha...'); return false; }
			});
			
		}
			
		this.otherURL = 'inc/other_data.inc.php';
		this.materialSelect = "material";
		this.el = this;
		
		Ajax.Responders.register({
			onCreate: function(){ Element.show('spinner') },
			onComplete: function(){ Element.hide('spinner') }
		});
		
		Event.observe('add-material', 'click', function(e) { new Effect.Appear('other-wrapper' , { duration: 1.4 }); });
		Event.observe('add-material-now', 'click', this._onAddHandler.bind(this));
		Event.observe('add-contato', 'click', this._onAddContactHandler.bind(this));
		
		this._onFillSelect('material_nome', 'material');
		
	},
	
	getParams: function () {
		
		this.params	= 	{
					material_uid		: user_id,
					material_nome 		: $(this.materialSelect).options[$(this.materialSelect).options.selectedIndex].innerHTML,
					material_id 		: $(this.materialSelect).options[$(this.materialSelect).options.selectedIndex].value,
					forn_razao_social	: $("razao_social").value,
					forn_nome_fantasia	: $('nome').value,
					forn_cnpj_1			: $('cnpj1').value,
					forn_cnpj_2			: $('cnpj2').value,
					forn_cnpj_3			: $('cnpj3').value,
					forn_cnpj_4			: $('cnpj4').value,
					forn_cnpj_5			: $('cnpj5').value,
					forn_insc_estadual	: $('inscricao_estadual').value,
					endereco_rua		: $('rua').value,
					endereco_quadra		: $('quadra').value,
					endereco_numero		: $('numero').value,
					endereco_bairro		: $('bairro').value,
					endereco_cidade		: $('cidade').value,
					endereco_estado		: $('estado').options[$('estado').selectedIndex].value,
					endereco_cep		: $('cep').value,
					endereco_website	: $('website').value,
					telefone_numero_1	: $('telefone').value,
					telefone_numero_2	: $('telefone_2').value,
					telefone_numero_3	: $('telefone_3').value,
					fax					: $('fax').value
		};
		
		if($('client_id') !== null) { // hidden value inside of editFornecedor.php
			this.params.client_id = $('client_id').value;
		}
		
		this.params.contato = [];
		
		for (var i=1; i <= $$('.contato').length; i++) {
			
			this.params.contato[i] = {};
			
			this.params.contato[i].nome = $$('.contato-'+i+' input')[0].value;
			this.params.contato[i].celular = $$('.contato-'+i+' input')[1].value;
			this.params.contato[i].email = $$('.contato-'+i+' input')[2].value;
			this.params.contato[i].telefone = $$('.contato-'+i+' input')[3].value;
			this.params.contato[i].cargo = $$('.contato-'+i+' input')[4].value;
			
			if($$('.contato-'+i)[0].hasAttribute('c_id')) {
				this.params.contato[i].c_id = $$('.contato-'+i)[0].readAttribute('c_id');
			}
		
		};
		
		return this.params;
		
	},
	
	_onLoadFieldsSuccess: function(transport) { 
		
		var response = transport.responseText.evalJSON();
		//console.log(response);
		
		$("razao_social").value = response[0].fornecedor_razao_social;
		$('nome').value = response[0]['fornecedor_nome'];
		$('cnpj1').value = response[0]['fornecedor_cnpj1'];
		$('cnpj2').value = response[0]['fornecedor_cnpj2'];
		$('cnpj3').value = response[0]['fornecedor_cnpj3'];
		$('cnpj4').value = response[0]['fornecedor_cnpj4'];
		$('cnpj5').value = response[0]['fornecedor_cnpj5'];
		$('inscricao_estadual').value = response[0]['fornecedor_inscricao_estadual'];
		
		for (var i=0; i < $(this.materialSelect).options.length; i++) {
			if($(this.materialSelect).options[i].value == response[0]['material_id']) {
				$(this.materialSelect).options[i].selected = "selected";
			}
		}
		
		$('rua').value = response[0]['endereco_rua'];
		$('quadra').value = response[0]['endereco_quadra'];
		$('numero').value = response[0]['endereco_numero'];
		$('bairro').value = response[0]['endereco_bairro'];
		$('cidade').value = response[0]['endereco_cidade'];
		
		for (var i=0; i < $('estado').options.length; i++) {
			if($('estado').options[i].value == response[0]['endereco_estado']) {
				$('estado').options[i].selected = "selected";
			}
		}
		
		$('cep').value = response[0]['endereco_cep'];
		$('website').value = response[0]['endereco_website'];
		$('telefone').value = response[0]['telefone_numero'];
		$('telefone_2').value = response[0]['telefone_numero_2'];
		$('telefone_3').value = response[0]['telefone_numero_3'];
		$('fax').value = response[0]['fax_numero'];
		
		var a_contact = [];
		
		for (var i=1; i <= response.length; i++) {
			
			a_contact[i] = this._onAddContactHandler(); 
			
			$$(".contato-"+i+ " input.nome")[0].value = response[i-1]["comprador_nome"];
			$$(".contato-"+i+ " input.tel")[0].value = response[i-1]["comprador_telefone"];
			$$(".contato-"+i+ " input.cel")[0].value = response[i-1]["comprador_celular"];
			$$(".contato-"+i+ " input.email")[0].value = response[i-1]["comprador_email"];
			$$(".contato-"+i+ " input.cargo")[0].value = response[i-1]["comprador_cargo"];
			$("contato-"+i).setAttribute("c_id", response[i-1]['comprador_id']);
			
		};
		
	},
	
	fillSelect: function (type, id) { // Valid types: dates, pedido_entrega, cliente_razao, autor_nome
		var data = new Ajax.Request('inc/list_gen_select.inc.php', { 
			method:'get',
			parameters: { 'type': type },
			onSuccess: function(transport) {
				var response = transport.responseText.evalJSON() || "no response text";
				
				$H(response).each(function (pair) {
					var newLabel = "", 
					i = 0;
					
					$H(pair.value).each(function (thispair) {
						if(i>0) { 
							newLabel += " ";
						}
						
						newLabel += thispair.value;
						i++;
					});
					
					
					$(id).insert(new Element('option', {value: pair.key}).update(newLabel));
						
				});
				
			},
			onFailure: function() { alert('Ocorreu um probleminha...'); return false; }
		});
	},
	
	clearSelect: function (type, id) { // Valid types: dates, pedido_entrega, cliente_razao, autor_nome
		
		$(this.materialSelect).options.length = 1;
	},
	
	_onClickHandler: function (event) { // Handles the updates on the text boxes.
		
		if(valid.validate()) { // Validate the fields first
		
			var stringfied  = Object.toJSON(this.getParams());
		
			var elem = this.el;
			var matSel = this.materialSelect;
		
			var data = new Ajax.Request(this.fornecedorURL, { 
				
				method:'get',
				parameters: { 'query_params': stringfied },
			
				onSuccess: function(transport) {
				
					var response = transport.responseText.evalJSON() || "no response text";
					//console.log(response);
				
				
					if(response["error"] !== null && response["error"]  === "cnpj_repetido") {
						
						for (var i=0; i < $$('span.callout.orange.rmv.msg').length; i++) {
							
							$$('span.callout.orange.rmv.msg')[i].remove();
						
						}
						
						var rmv_msg = new Element('span', { "class":'callout orange rmv msg' }).update(response["msg"]);
						$('box').insert(rmv_msg);
				
					} else { // info will actually be recorded (or updated)
						
						if($('client_id') !== null) { // if we are inside of editFornecedor.php
							
							// Clear fields
							for (var i=0; i < $$('form input').length; i++) {

								if($$('form input')[i].readAttribute("type") === "text") {

									$$('form input')[i].value="";
								}

							} // /for

							//$('material').selectedIndex = 0;

							$("razao_social").value = response[0].fornecedor_razao_social;
							$('nome').value = response[0]['fornecedor_nome'];
							$('cnpj1').value = response[0]['fornecedor_cnpj1'];
							$('cnpj2').value = response[0]['fornecedor_cnpj2'];
							$('cnpj3').value = response[0]['fornecedor_cnpj3'];
							$('cnpj4').value = response[0]['fornecedor_cnpj4'];
							$('cnpj5').value = response[0]['fornecedor_cnpj5'];
							$('inscricao_estadual').value = response[0]['fornecedor_inscricao_estadual'];

							for (var i=0; i < $(matSel).options.length; i++) {
								if($(matSel).options[i].value == response[0]['material_id']) {
									$(matSel).options[i].selected = "selected";
								}
							}

							$('rua').value = response[0]['endereco_rua'];
							$('quadra').value = response[0]['endereco_quadra'];
							$('numero').value = response[0]['endereco_numero'];
							$('bairro').value = response[0]['endereco_bairro'];
							$('cidade').value = response[0]['endereco_cidade'];

							for (var i=0; i < $('estado').options.length; i++) {
								if($('estado').options[i].value == response[0]['endereco_estado']) {
									$('estado').options[i].selected = "selected";
								}
							}

							$('cep').value = response[0]['endereco_cep'];
							$('website').value = response[0]['endereco_website'];
							$('telefone').value = response[0]['telefone_numero'];
							$('telefone_2').value = response[0]['telefone_numero_2'];
							$('telefone_3').value = response[0]['telefone_numero_3'];
							$('fax').value = response[0]['fax_numero'];
							

							for (var i=1; i <= response.length; i++) {

								$$(".contato-"+i+ " input.nome")[0].value = response[i-1]["comprador_nome"];
								$$(".contato-"+i+ " input.tel")[0].value = response[i-1]["comprador_telefone"];
								$$(".contato-"+i+ " input.email")[0].value = response[i-1]["comprador_email"];
								$$(".contato-"+i+ " input.cargo")[0].value = response[i-1]["comprador_cargo"];
								$$(".contato-"+i+ " input.cel")[0].value = response[i-1]["comprador_celular"];
								$("contato-"+i).setAttribute("c_id", response[i-1]['comprador_id']);

							};
							
							for (var i=0; i < $$('span.callout.orange.rmv.msg').length; i++) {

								$$('span.callout.orange.rmv.msg')[i].remove();

							}
							
							// Message user that data has been updated	
							var rmv_msg = new Element('span', { "class":'callout orange rmv msg' });

							var link1 = new Element('a', { 'href':'listFornecedores.php' }).update("Busca");
							var link2 = new Element('a', { 'href':'fornecedor_detail.php?id='+f_id }).update("Vizualizar Fornecedor");
							var bold = new Element('b').update(response[0]['fornecedor_razao_social'] + ". ");
							var para = new Element("p").update("Dados atualizados: ");

							para.insert(bold);
							para.insert(link1);
							para.insert("&nbsp;|&nbsp;"); 
							para.insert(link2);

							rmv_msg.insert(para);

							$('box').insert(rmv_msg);
							
						} else { // we are inside of fornecedor.php
							
							for (var i=0; i < $$('span.callout.orange.rmv.msg').length; i++) {

								$$('span.callout.orange.rmv.msg')[i].remove();

							}

							var rmv_msg = new Element('span', { "class":'callout orange rmv msg' });

							var link1 = new Element('a', { 'href':'listFornecedores.php' }).update("Busca");
							var link2 = new Element('a', { 'href':'editFornecedor.php?id='+ response[0]['fornecedor_id'] }).update("Editar Fornecedor");
							var bold = new Element('b').update(response[0]['fornecedor_razao_social'] + ". ");
							var para = new Element("p").update("Fornecedor adicionado: ");

							para.insert(bold);
							para.insert(link1);
							para.insert("&nbsp;|&nbsp;"); 
							para.insert(link2);

							rmv_msg.insert(para);

							$('box').insert(rmv_msg);

							// Clear fields
							for (var i=0; i < $$('form input').length; i++) {

								if($$('form input')[i].readAttribute("type") === "text") {

									$$('form input')[i].value = "";
								}
								
							} // /for

							$('material').selectedIndex = 0;
						
						}
					} // /else	
				},
			
				onFailure: function() { alert('Ocorreu um probleminha...'); return false; }
			});
			
		} else { // /validate if
			
			for (var i=0; i < $$('span.callout.orange.rmv.msg').length; i++) {
				$$('span.callout.orange.rmv.msg')[i].remove();
			};
			
			var rmv_msg = new Element('span', { "class":'callout orange rmv msg' }).update("Um ou mais campos obrigatórios ainda precisam ser preenchidos.");
			$('box').insert(rmv_msg);
			
		}
	},
	
	_onAddHandler: function (event) {
		
		var new_material = $('other').value;
		
		this._onCreateSelectItem("novo_material", new_material);
		this._onClearSelect('material_nome', 'material');
		this._onFillSelect('material_nome', 'material');
		
		$('other').value = "";
		new Effect.Fade('other-wrapper' , { duration: 2.0 });

	},
	
	_onAddContactHandler: function (event) {
		
		var kount = $$('.contato').length + 1;
		
		var colgrid = new Element("div", { "class":"grid2col contato contato-" + kount, "id":"contato-" + kount }).setStyle({ "display":"none" });
		colgrid.insert(new Element('h3').update("Contato " + kount));
		
		var col1 = new Element("div", { "class":"col" });
		var col2 = new Element("div", { "class":"col last" });
		
		var dl1 = new Element("dl");
		var dl2 = new Element("dl");
		
		dl1.insert(new Element("dd").insert(new Element('input', { "class": "nome", "tabindex":13, "type":"text" })));
		dl1.insert(new Element("dt").insert(new Element('b').update("Nome")));
		
		dl2.insert(new Element("dd").insert(new Element('input', { "class": "tel", "tabindex":13, "type":"text"  })));
		dl2.insert(new Element("dt").insert(new Element('b').update("Telefone")));
		
		dl1.insert(new Element("dd", { "class": "clear" }).insert(new Element('input', { "class": "cel", "tabindex":13, "type":"text" })));
		dl1.insert(new Element("dt").insert(new Element('b').update("Celular")));
		
		dl2.insert(new Element("dd", { "class": "clear" }).insert(new Element('input', { "class": "cargo", "tabindex":13, "type":"text"  })));
		dl2.insert(new Element("dt").insert(new Element('b').update("Cargo")));
		
		dl1.insert(new Element("dd", { "class": "clear" }).insert(new Element('input', { "class": "email", "tabindex":13, "type":"text" })));
		dl1.insert(new Element("dt").insert(new Element('b').update("Email")));
		
		col1.insert(dl1);
		col2.insert(dl2);
		
		colgrid.insert(col1);
		colgrid.insert(col2);
		
		$("contatos").insert(colgrid);
		
		new Effect.Appear('contato-'+kount , { duration: 1.4 });
	},
	
	_onCreateSelectItem: function (type, value) {
		
		var params = {"type": type, "novo_material": value};
		var stringfied  = Object.toJSON(params);
		
		var options = {
			method:'post',
			parameters: { "query_params": stringfied },
			onSuccess: function(transport) {
				// console.log(transport.responseText);
			},
			onFailure: function() {
				alert("Não foi possível criar o novo material.");
			}
		};
		
		new Ajax.Request(this.otherURL, options);
	},
	
	_onFillSelect: function (id, type) {
		
		this.fillSelect(id, type);
	},
	
	_onClearSelect: function (id, type) {
		
		this.clearSelect(id, type);
	},
	
	_onCouldNotRecord: function () {
		
		
	}
	
});