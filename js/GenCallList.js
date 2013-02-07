var GenCallList = Class.create(List, {
	listURL		: 	'inc/list_calls_gen_table.inc.php',
	visitsURL	: 	'inc/calls_data.inc.php',
	tableBody	: 	'#dyn-rows tbody',
	tableRows	: 	'#dyn-rows tbody tr',
	
	initialize: function () {
		Ajax.Responders.register({
			onCreate: function(){ Element.show('spinner') },
			onComplete: function(){ Element.hide('spinner') }
		});
		
		//Event.observe('gen_list', 'keypress', this._onKeyHandler.bind(this));
		Event.observe('gen_list', 'click', this._onClickHandler.bind(this));
		Event.observe('print', 'click', this._onPrintHandler.bind(this));
		
		this.fillSelect("autor_nome", "para"); // nome de todos os usuarios
		
		$('gen_list').focus();
	},
	
	getSearchParams: function ($super) {
		var query_params = {};
		
		if($$('.search_param').size() > 0) {
			
			if(($('radio_feita').checked || $('radio_recebida').checked)) {
				
				if($('radio_feita').checked) {
					var call_type = $('radio_feita').value;

				} else { // default
					var call_type = $('radio_recebida').value;

				}
				
				if($$('input.search_param')[0].hasAttribute('disabled') || $$('input.search_param')[1].hasAttribute('disabled')) { // remove dates from the parameters
				
					query_params["dates"] = {
						"call_type": call_type, "para":$('para').value
					};
				
				} else if($('para').hasAttribute('disabled')) { // remove callee from the parameters
				
					query_params["dates"] = {
						"param_value": $$('input.search_param')[0].value, "param_value2": $$('input.search_param')[1].value, "call_type": call_type
					};
				
				} else { // all parameters are present
				
					query_params["dates"] = {
						"param_value": $$('input.search_param')[0].value, "param_value2": $$('input.search_param')[1].value, "call_type": call_type, "para":$('para').value
					};
				
				}
			}
		}
		
		return Object.toJSON(query_params);
	},
	
	genlist: function ($super, params) { // Overriding from parent
		$$(this.tableRows).each(function (elem) {
			$$('#dyn-rows tbody')[0].removeChild(elem);
		});
		
		var options = {
			method:'post',
			parameters: { "query_params": params },
			onSuccess: this._onMonitorSucess.bind(this)
		};
		
		new Ajax.Request(this.listURL, options);
	},
	
	_onMonitorSucess: function(transport) {
		console.log(transport.responseText);
		
		var response = transport.responseText.evalJSON() || "no response text";
		
		this._onCreateTable(response);
		
	},
	
	_onCreateTable: function(response) {
		var cols = new Array();
		
		//console.log("Response: " + response);
		var fr = this.formatResult;
		var rmv = this._onRmv;
		
		if(response.length < 1) {
			var row = new Element("tr");

			cols[0] = new Element("td", { 'colspan':7 }).update("Nenhuma ligação encontrada, tente novamente.");
			cols[0].setStyle({ 'text-align':'center', 'color':'red' });
			row.insert(cols[0]);
			
			$$('#dyn-rows tbody')[0].insert(row);
			
		} else {
			
			var ky = this._onUpdateHandler;
			obj = this;
			
			response.each(function (el) {
			
				var row = new Element("tr", { "nbr": el["calls_id"] });

				cols[0] = new Element("td", { 'class':'data update-item' }).update(new Element("input", { "value": el["calls_data"], "title":el["calls_data"] }));
				cols[1] = new Element("td", { 'class':'nome update-item' }).update(new Element("input", { "value": el["calls_cliente"], "title":el["calls_cliente"] }));
				cols[2] = new Element("td", { 'class':'telefone update-item' }).update(new Element("input", { "value": el["calls_telefone"], "title":el["calls_telefone"] }));
				
				if(typeof(!el["calls_tempo"] || el["calls_mins"] === "undefined"))
					el["calls_tempo"] = "n/a";
				
				cols[3] = new Element("td", { 'class':'para update-item' }).update(new Element("input", { "value": el["calls_para"], "title":el["calls_para"] }));	
				cols[4] = new Element("td", { 'class':'tempo update-item' }).update(new Element("input", { "value": el["calls_mins"], "title":el["calls_tempo"] }));
				cols[5] = new Element("td", { 'class':'obs update-item' }).update(new Element("textarea", {"title":el["calls_detalhes"]}).update("** Atendente: " + el["f_users_firstname"] + " **. " + el["calls_detalhes"]));
			
				cols.each(function (elem) { row.insert(elem); });
			
				$$("#dyn-rows tbody")[0].insert(row);
			});
			
			$$('.update-item').each( function (elem) {
				Event.observe(elem, 'keypress', ky);
			});
		}
	},
	
	updateItem: function (params) {
		var stringfied  = Object.toJSON(params);
		
		var options = {
			method:'post',
			parameters: { "query_params": stringfied, "update": 1 },
			onSuccess: this._onUpSuccess.bind(this),
			onFailure: this._onUpFailure.bind(this)
		};
		
		var data = new Ajax.Request(this.visitsURL, options);
	},
	
	_onUpSuccess: function (transport) {
		// console.log("Hello there"); 
		var response = transport.responseText.evalJSON();
		// this._onClearTable();
		// this.genlist();
		
		$$('tr').each(function (el) {
			if(el.getAttribute('nbr') == response['item']) {
				el.setStyle({ backgroundColor:"#babaca" });
				Effect.Pulsate(el, { pulses: 1, duration: 2 });
			}
		});
		
		var rmv_msg = new Element('span', { "class":'callout orange rmv add msg', "id":"aviso" }).update("Você acaba de modificar o campo \"" + response['tipo'] + ".\"");
		if(!$('aviso')) {
			$('box').insert(rmv_msg);
		} else {
			$('aviso').update("Você acaba de modificar o campo \"" + response['tipo'] + ".\"");
		}
		
	},
	
	_onUpFailure: function () {
		alert("Não foi possível atualizar as informações.")
	},
	
	_onKeyHandler: function (event) {
		if(event.keyCode == Event.KEY_RETURN) {
			this.genlist(this.getSearchParams());
			Event.stop(event);
		}
	},
	
	_onClickHandler: function (event) {
		this.genlist(this.getSearchParams());
		$('gen_list').focus();
	},
	
	_onPrintHandler: function (event) {
		window.print();
	},
	
	_onUpdateHandler: function (event) { // Handles the updates on the text boxes.
		if(event.keyCode == Event.KEY_RETURN) {
			//this.genlist(this.getSearchParams());
			if(this.hasClassName('data')) {
				var params = {
					tipo	: "data",
					info	: this.down().value,
					item	: this.up().getAttribute('nbr')
				};
			}

			if(this.hasClassName('nome')) {
				var params = {
					tipo	: "nome",
					info	: this.down().value,
					item	: this.up().getAttribute('nbr')
				};
			}
			
			if(this.hasClassName('para')) {
				var params = {
					tipo	: "para",
					info	: this.down().value,
					item	: this.up().getAttribute('nbr')
				};
			}

			if(this.hasClassName('telefone')) {
				var params = {
					tipo	: "telefone",
					info	: this.down().value,
					item	: this.up().getAttribute('nbr')
				};
			}

			if(this.hasClassName('tempo')) {
				var params = {
					tipo	: "tempo",
					info	: this.down().value,
					item	: this.up().getAttribute('nbr')
				};
			}

			if(this.hasClassName('obs')) {
				var params = {
					tipo	: "obs",
					info	: this.down().value,
					item	: this.up().getAttribute('nbr')
				};
			}

			//console.log(this.down().value);
			//console.log(this.up().getAttribute('nbr'));
			Event.stop(event);

			//console.log(this.up().up().up().up().up());
		}
		
		obj.updateItem(params);
	},
	
	toggleRead: function () {
		// When a user clicks, will be marked as read 
	},
	
	toggleFavorite: function () {
		// When a user clicks, will be marked as favorite
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
				$(id).insert(new Element('option', {value: 1}).update("outro..."));
			},
			onFailure: function() { alert('Ocorreu um probleminha...'); return false; }
		});
	}
	
});