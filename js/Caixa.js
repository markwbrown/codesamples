var Caixa = Class.create({
	
	initialize: function () {
		
		this.dataURL = 'inc/caixa_data.inc.php';
		this.tableBody = $$("#dyn-rows tbody")[0];
		this.tableRows = $$("#dyn-rows tbody tr");
		this.formInputs = "#caixa-form input";
		this.formSelects = "#caixa-form select";
		
		this.params	= 	{
					caixa_uid			: user_id,
					caixa_material		: $('cx-material').options[$('cx-material').options.selectedIndex].value,
					caixa_data			: $('cx-data').value,
					caixa_receptor 		: $('cx-receptor').value,
					caixa_finalidade	: $('cx-finalidade').value,
					caixa_nota 			: $('cx-nota').value,
					caixa_valor_in 		: $('cx-valor-in').value,
					caixa_valor_out 	: $('cx-valor-out').value
		};
		
		Ajax.Responders.register({
			onCreate: function(){ Element.show('spinner') },
			onComplete: function(){ Element.hide('spinner') }
		});
		
		Event.observe('salvar', 'click', this._onClickHandler.bind(this));
		
		this._onFillSelect('material_nome', 'cx-material');
		
		this._onClearTable();
		
		this.genlist(35);
		
	},
	
	record: function (params) {
		
		var stringfied  = Object.toJSON(params);
		
		var options = {
			method:'post',
			parameters: { "query_params": stringfied, "record": 1 },
			onSuccess: this._onRecSuccess.bind(this),
			onFailure: this._onRecFailure.bind(this)
		};
		
		var data = new Ajax.Request(this.dataURL, options);
		
	},
	
	_onRecSuccess: function (transport) {
		
		//console.log(transport.responseText);
		this._onClearTable();
		this.genlist(35);
		
	},
	
	_onRecFailure: function () {
		
		alert("Não foi possível escrever as informações.");
		
	},
	
	updateItem: function (params) {
		
		var stringfied  = Object.toJSON(params);
		
		var options = {
			method:'post',
			parameters: { "query_params": stringfied, "update": 1 },
			onSuccess: this._onUpSuccess.bind(this),
			onFailure: this._onUpFailure.bind(this)
		};
		
		var data = new Ajax.Request(this.dataURL, options);
		
	},
	
	_onUpSuccess: function (transport) {
		
		var response = transport.responseText.evalJSON();
		
		$$('tr').each(function (el) {
			if(el.getAttribute('nbr') == response['item']) {
				el.setStyle({ backgroundColor:"#babaca" });
				Effect.Pulsate(el, { pulses: 1, duration: 2 });
			}
		});
		
		var rmv_msg = new Element('span', { "class":'callout orange rmv msg', "id":"aviso" }).update("Você acaba de modificar o campo \"" + response['tipo'] + ".\"");
		
		if(!$('aviso')) {
			$('box').insert(rmv_msg);
		} else {
			$('aviso').update("Você acaba de modificar o campo \"" + response['tipo'] + ".\"");
		}
		
	},
	
	_onUpFailure: function () {
		
		alert("Não foi possível atualizar as informações.");
		
	},
	
	genlist: function (n) {
				
		var options = {
			
			method:'post',
			parameters: { "showlist": 1 },
			onSuccess: this._onGenSuccess.bind(this),
			onFailure: this._onGenFailure.bind(this)
			
		};
		
		if(n !== null) {
			
			options.parameters.limit = n;
			
		}
		
		var data = new Ajax.Request(this.dataURL, options);
		
	},
	
	_onGenSuccess: function (transport) {

		//console.log(transport.responseText);
		
		var response = transport.responseText.evalJSON() || "no response text";
		
		if($$(".callout.orange.rmv.msg")[0] !== undefined) {
			new Effect.Fade($('aviso'), { duration:1});
		}	
		
		
		var cols = new Array();
		
		if(response.length < 1) {
			
			var row = new Element("tr");

			cols[0] = new Element("td", { 'colspan':6 }).update("Nada encontrado, tente novamente.");
			cols[0].setStyle({ 'text-align':'center', 'color':'red' });
			row.insert(cols[0]);
			this.tableBody.insert(row);
			
		} else {
			
			this._onGenCols(response);
			
			if(response[0]['caixa_grand_total']) {
				
				$('total').innerHTML = response[0]['caixa_grand_total'];
			
			}
			
			Effect.Pulsate('dyn-rows', { pulses: 1, duration: 2 });
			
		}
		
		this._onClearForm();
		
	},
	
	_onGenFailure: function () {
		
		alert("Não foi possível gerar a lista.");
		
	},
	
	_onGenCols: function (response) {
		
		var cols = new Array();
		
		var fr = this.formatResult;
		var rmv = this._onRmv;
		var ky = this._onKeyHandler;
		var fc = this.formatCurr;
		obj = this;
		
		response.each(function (el) {
			
			var row = new Element("tr", { "nbr": el["caixa_id"] });
			
			cols[0] = new Element("td", { 'id':'cx-data', 'class':'cx-data update-item', "title":el["caixa_data"] }).update(el["caixa_data"]);
			cols[1] = new Element("td", { 'id':'cx-receptor', 'class':'cx-receptor update-item', "title":el["caixa_receptor"] }).update(el["caixa_receptor"]);
			cols[2] = new Element("td", { 'id':'cx-finalidade', 'class':'cx-finalidade update-item', "title":el["caixa_finalidade"] }).update(el["caixa_finalidade"]);
			cols[3] = new Element("td", { 'id':'cx-material', 'class':'cx-material update-item', "title":el["material_nome"] }).update(el["material_nome"]);
			cols[4] = new Element("td", { 'id':'cx-nota', 'class':'cx-nota update-item', "title":el["caixa_nota"] }).update(el["caixa_nota"]);
			cols[5] = new Element("td", { 'id':'cx-valor-in', 'class':'cx-valor-in update-item', "title":el["caixa_valor_in"] }).update(el["caixa_valor_in"]);
			cols[6] = new Element("td", { 'id':'cx-valor-out', 'class':'cx-valor-out update-item', "title":el["caixa_valor_out"] }).update(el["caixa_valor_out"]);
			cols[7] = new Element("td").update('<a href="#rm" id="rm-'+el["caixa_id"]+'" class="rmv-item" onclick="rmv(' + el["caixa_id"] + ')"><img src="img/rmv_med.png" width="16" height="16" /></a>');
			
			cols.each(function (elem) { row.insert(elem); });
			
			$$("#dyn-rows tbody")[0].insert(row);	
			
		});
		
		$$('.update-item').each( function (elem) {
			Event.observe(elem, 'keypress', ky);
		});
		
	},
	
	_onUpdateParams: function () {
		
		this.params.caixa_material = $('cx-material').options[$('cx-material').options.selectedIndex].value;
		this.params.caixa_data = $('cx-data').value;
		this.params.caixa_receptor = $('cx-receptor').value;
		this.params.caixa_finalidade = $('cx-finalidade').value;
		this.params.caixa_nota = $('cx-nota').value;
		this.params.caixa_valor_in = $('cx-valor-in').value;
		this.params.caixa_valor_out = $('cx-valor-out').value;
		
		return this.params;
		
	},
	
	_onClearTable: function () {
		
		$$("#dyn-rows tbody tr").each(function (elem) {
			
			$$("#dyn-rows tbody")[0].removeChild(elem);
			
		});
		
	},
	
	_onClearForm: function () {
		/*
		$$(this.formInputs).each(function (elem) {
			
			if(elem.type == "text") {
				elem.value = "";
			}
			
		});
		
		$$(this.formSelects).each(function (elem) {
		
			elem.options.selectedIndex = 0; 
			
		});*/
	},
	
	_onValidate: function () {
		return valid.validate();
	},
	
	_onClickHandler: function (event) {
		
		if(this._onValidate() != false) {
				
			this._onUpdateParams(); 
			this.record(this._onUpdateParams());
		
		}
		
	},
	
	_onRmv: function (a) { // Remove a row item

		var options = {
			method:'post',
			parameters: { "id": a, "remove": 1 },
			onSuccess: this._onRmvSuccess.bind(this),
			onFailure: this._onRmvFailure.bind(this)
		};
		
		var data = new Ajax.Request(this.dataURL, options);
	},
	
	_onRmvSuccess: function (transport) {
		var response = transport.responseText.evalJSON();
		//console.log(transport.responseText.evalJSON());
		
		var rmv_msg = new Element('span', { "class":'callout orange rmv msg', "id":'aviso' }).update("Deletado: Data " + response[0]['caixa_data_deleted'] + " com valor de entrada R$ " + response[0]['caixa_valor_in_deleted'] + " e saida R$ " +  response[0]['caixa_valor_out_deleted'] + ".");
		
		if(!$('aviso')) {
			$('box').insert(rmv_msg);
		} else {
			$('aviso').update("Deletado: Data " + response[0]['caixa_data_deleted'] + " com valor de entrada R$ " + response[0]['caixa_valor_in_deleted'] + " e saida R$ " +  response[0]['caixa_valor_out_deleted'] + ".");
			new Effect.Appear($('aviso'), { duration:1 });
		}
		
		if(response[0]['caixa_grand_total']) {
			
			$('total').innerHTML = response[0]['caixa_grand_total'];
		
		}
		
	},
	
	_onRmvFailure: function () {
		alert("Não foi possível remover as informações.")
	},
	
	_onFillSelect: function (type, id) { // Valid types: dates, pedido_entrega, cliente_razao, autor_nome
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
	
	_onClickCellHandler: function (event) { // Handles the updates on the text boxes.
		
		// Take the contents of the cell
		// Place in a textbox
		// Place the textbox in the cell
		// Place the event handler on it where we can press enter
		
	},	
	
	_onKeyHandler: function (event) { // Handles the updates on the text boxes.
		
		if(event.keyCode == Event.KEY_RETURN) {

			if(this.hasClassName('cx-data')) {
				var params = {
					tipo	: "cx-data",
					info	: this.down().value,
					item	: this.up().getAttribute('nbr')
				};
			}

			if(this.hasClassName('cx-receptor')) {
				var params = {
					tipo	: "cx-receptor",
					info	: this.down().value,
					item	: this.up().getAttribute('nbr')
				};
			}

			if(this.hasClassName('cx-finalidade')) {
				var params = {
					tipo	: "cx-finalidade",
					info	: this.down().value,
					item	: this.up().getAttribute('nbr')
				};
			}
			
			if(this.hasClassName('cx-material')) {
				var params = {
					tipo	: "cx-material",
					info	: this.down().value,
					item	: this.up().getAttribute('nbr')
				};
			}

			if(this.hasClassName('cx-nota')) {
				var params = {
					tipo	: "cx-nota",
					info	: this.down().value,
					item	: this.up().getAttribute('nbr')
				};
			}
			
			if(this.hasClassName('cx-valor-in')) {
				var params = {
					tipo	: "cx-valor-in",
					info	: this.down().value,
					item	: this.up().getAttribute('nbr')
				};
			}
			
			if(this.hasClassName('cx-valor-out')) {
				var params = {
					tipo	: "cx-valor-out",
					info	: this.down().value,
					item	: this.up().getAttribute('nbr')
				};
			}
			
			if(this.hasClassName('cx-type-switch')) {
				var params = {
					tipo	: "cx-type-switch",
					info	: this.down().value,
					item	: this.up().getAttribute('nbr')
				};
			}

			Event.stop(event);
			
			obj.updateItem(params);
		}
		
	}
	
});