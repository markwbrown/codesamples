var Calls = Class.create({
	initialize: function () {
		this.callsURL = 'inc/calls_data.inc.php';
		this.tableBody = $$("#dyn-rows tbody")[0];
		this.tableRows = $$("#dyn-rows tbody tr");
		this.formInputs	= $$('#order-form input');
		
		this.params	= 	{
					calls_uid			: user_id,
					calls_data 			: $('data').value,
					calls_nome 			: $('nome').value,
					calls_telefone	 	: $('telefone').value,
					calls_obs			: $('obs').value,
					calls_is_out		: $('outbound_call').value
		};
		
		if($('outbound_call').value==0) {
			this.params.calls_para			= $('para').options[$('para').options.selectedIndex].innerHTML;
			this.params.calls_para_uid		= $('para').value;
		} else {
			this.params.calls_mins			= $('mins').value;
		}
		
		Ajax.Responders.register({
			onCreate: function(){ Element.show('spinner') },
			onComplete: function(){ Element.hide('spinner') }
		});
		
		Event.observe('salvar', 'click', this._onClickHandler.bind(this));
		Event.observe('up', 'click', this._onUpHandler.bind(this));
		Event.observe('down', 'click', this._onDownHandler.bind(this));
		
		this.fillSelect("autor_nome", "para"); // autor nome: nome de todos os usuarios
		
		this._onClearTable();
		this.genlist();
		
	},
	
	record: function (params) {
		var stringfied  = Object.toJSON(params);
		
		var options = {
			method:'post',
			parameters: { "query_params": stringfied, "record": 1 },
			onSuccess: this._onRecSuccess.bind(this),
			onFailure: this._onRecFailure.bind(this)
		};
		
		var data = new Ajax.Request(this.callsURL, options);
	},
	
	_onRecSuccess: function (transport) {
		//console.log(transport.responseText);
		this._onClearTable();
		this.genlist();
	},
	
	_onRecFailure: function () {
		alert("Não foi possível escrever as informações.")
	},
	
	updateItem: function (params) {
		var stringfied  = Object.toJSON(params);
		
		var options = {
			method:'post',
			parameters: { "query_params": stringfied, "update": 1 },
			onSuccess: this._onUpSuccess.bind(this),
			onFailure: this._onUpFailure.bind(this)
		};
		
		var data = new Ajax.Request(this.callsURL, options);
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
		
		var rmv_msg = new Element('span', { "class":'callout orange rmv msg', "id":"aviso" }).update("Você acaba de modificar o campo \"" + response['tipo'] + ".\"");
		if(!$('aviso')) {
			$('box').insert(rmv_msg);
		} else {
			$('aviso').update("Você acaba de modificar o campo \"" + response['tipo'] + ".\"");
		}
		
	},
	
	_onUpFailure: function () {
		alert("Não foi possível atualizar as informações.")
	},
	
	genlist: function () {
		var options = {
			method:'post',
			parameters: { "showlist": 1, "calls_is_out": this.params.calls_is_out },
			onSuccess: this._onGenSuccess.bind(this),
			onFailure: this._onGenFailure.bind(this)
		};
		
		var data = new Ajax.Request(this.callsURL, options);
		
	},
	
	_onGenSuccess: function (transport) {
			this._onClearForm();
			
			//console.log(transport.responseText);
			
			var response = transport.responseText.evalJSON() || "no response text";
			
			var cols = new Array();
			
			if(response.length < 1) {
				
				var row = new Element("tr");

				cols[0] = new Element("td", { 'colspan':4 }).update("Nenhuma ligação encontrada, tente novamente.");
				cols[0].setStyle({ 'text-align':'center', 'color':'red' });
				row.insert(cols[0]);
				this.tableBody.insert(row);
			} else {
				
				this._onGenCols(response);
				this._onClearForm();
				Effect.Pulsate('dyn-rows', { pulses: 1, duration: 2 });
			}
	},
	
	_onGenFailure: function () {
		alert("Não foi possível gerar a lista.")
	},
	
	_onGenCols: function (response) {
		var cols = new Array();
		
		var fr = this.formatResult;
		var rmv = this._onRmv;
		var ky = this._onKeyHandler;
		obj = this;
		
		response.each(function (el) {
			
			var row = new Element("tr", { "nbr": el["calls_id"] });

			cols[0] = new Element("td", { 'class':'data update-item' }).update(new Element("input", { "value": el["calls_data"], "title":el["calls_data"], "tabindex":6 }));
			cols[1] = new Element("td", { 'class':'nome update-item' }).update(new Element("input", { "value": el["calls_cliente"], "title":el["calls_cliente"], "tabindex":6 }));
			
			if($('outbound_call').value==1) {
				cols[2] = new Element("td", { 'class':'mins update-item' }).update(new Element("input", { "value": el["calls_mins"], "title":el["calls_mins"], "tabindex":6 }));
				cols[3] = new Element("td", { 'class':'para update-item' }).update(new Element("input", { "value": el["calls_para"], "title":el["calls_para"], "tabindex":6 }));
				cols[4] = new Element("td", { 'class':'telefone update-item' }).update(new Element("input", { "value": el["calls_telefone"], "title":el["calls_telefone"], "tabindex":6 }));
				cols[5] = new Element("td", { 'class':'obs update-item' }).update(new Element("textarea", {"title":el["calls_detalhes"], "tabindex":6}).update("** Atendente: " + el["f_users_firstname"] + " **. " + el["calls_detalhes"]));
				cols[6] = new Element("td").update('<a href="#rm" id="rm-'+el["calls_id"]+'" class="rmv-item" onclick="rmv(' + el["calls_id"] + ')"><img src="img/rmv_med.png" width="16" height="16" /></a>');
			} else {	
				cols[2] = new Element("td", { 'class':'para update-item' }).update(new Element("input", { "value": el["calls_para"], "title":el["calls_para"], "tabindex":6 }));
				cols[3] = new Element("td", { 'class':'telefone update-item' }).update(new Element("input", { "value": el["calls_telefone"], "title":el["calls_telefone"], "tabindex":6 }));
				cols[4] = new Element("td", { 'class':'obs update-item' }).update(new Element("textarea", {"title":el["calls_detalhes"], "tabindex":6}).update("** Atendente: " + el["f_users_firstname"] + " **. " + el["calls_detalhes"]));
				cols[5] = new Element("td").update('<a href="#rm" id="rm-'+el["calls_id"]+'" class="rmv-item" onclick="rmv(' + el["calls_id"] + ')"><img src="img/rmv_med.png" width="16" height="16" /></a>');	
			}	
			
			cols.each(function (elem) { row.insert(elem); });
			
			$$("#dyn-rows tbody")[0].insert(row);	
			
		});
		
		$$('.update-item').each( function (elem) {
			Event.observe(elem, 'keypress', ky);
		});
	},
	
	_onUpdateParams: function () {
		this.params.calls_data = $('data').value;
		this.params.calls_nome = $('nome').value;
		
		if($('mins')) {
			this.params.calls_mins = $('mins').value;
		}
		
		if($('para')) {
			this.params.calls_para = $('para').options[$('para').options.selectedIndex].innerHTML;
			this.params.calls_para_uid	= $('para').value;
		}
		
		this.params.calls_telefone = $('telefone').value;
		this.params.calls_obs = $('obs').value;
		
		return this.params;
	},
	
	_onClearTable: function () {
		$$("#dyn-rows tbody tr").each(function (elem) {
			$$("#dyn-rows tbody")[0].removeChild(elem);
		});
	},
	
	_onClearForm: function () {
		this.formInputs.each(function (elem) {
			if(elem.type == "text") {
				elem.value = "";
			}
			$('obs').value = "";
		});
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
	
	_onDownHandler: function (event) {
		new Effect.Move($("dyn-rows"), { x: 0, y: -296, mode: 'relative' });
		Event.stop(event);
	},
	
	_onUpHandler: function (event) {
		var curTop = $("dyn-rows").getStyle('top');
		curTop = parseInt(curTop.substr(0, curTop.indexOf("px")));
		
		if(curTop != 0) {
			new Effect.Move($("dyn-rows"), { x: 0, y: 296, mode: 'relative' });
		}
		Event.stop(event);
	},
	
	_onRmv: function (a) { // Remove a row item
		//console.log(a);
		
		var options = {
			method:'post',
			parameters: { "id": a, "remove": 1 },
			onSuccess: this._onRmvSuccess.bind(this),
			onFailure: this._onRmvFailure.bind(this)
		};
		
		var data = new Ajax.Request(this.callsURL, options);
	},
	
	_onRmvSuccess: function (transport) {
		var response = transport.responseText.evalJSON();
		//console.log(transport.responseText.evalJSON());
		
		var rmv_msg = new Element('span', { "class":'callout orange rmv msg' }).update("Você acaba de deletar a visita para o cliente " + response['calls_cliente'] + " na data " + response['calls_data'] + ".");
		$('box').insert(rmv_msg);
	},
	
	_onRmvFailure: function () {
		alert("Não foi possível remover as informações.")
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
	},
	
	_onKeyHandler: function (event) { // Handles the updates on the text boxes.
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
			
			if(this.hasClassName('mins')) {
				var params = {
					tipo	: "mins",
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
	}
	
});