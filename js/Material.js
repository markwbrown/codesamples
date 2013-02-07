var Material = Class.create({
	initialize: function () {
		this.matURL = 'inc/material_data.inc.php';
		this.tableBody = $$("#dyn-rows tbody")[0];
		this.tableRows = $$("#dyn-rows tbody tr");
		this.formSelects = "#material-form select";
		
		this.params	= 	{
					mat_uid		: user_id,
					mat_nome 	: $('mat-nome').value
		};
		
		Ajax.Responders.register({
			onCreate: function(){ Element.show('spinner') },
			onComplete: function(){ Element.hide('spinner') }
		});
		
		Event.observe('salvar', 'click', this._onClickHandler.bind(this));
		
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
		
		var data = new Ajax.Request(this.matURL, options);
	},
	
	_onRecSuccess: function (transport) {
		// console.log(transport.responseText);
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
		
		var data = new Ajax.Request(this.matURL, options);
	},
	
	_onUpSuccess: function (transport) {
		
		//console.log(transport.responseText);
		
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
		alert("Não foi possível atualizar as informações.")
	},
	
	genlist: function () {
		var options = {
			method:'post',
			parameters: { "showlist": 1 },
			onSuccess: this._onGenSuccess.bind(this),
			onFailure: this._onGenFailure.bind(this)
		};
		
		var data = new Ajax.Request(this.matURL, options);
		
	},
	
	_onGenSuccess: function (transport) {

			//console.log(transport.responseText);
			
			var response = transport.responseText.evalJSON() || "no response text";
			
			var cols = new Array();
			
			if(response.length < 1) {
				
				var row = new Element("tr");

				cols[0] = new Element("td", { 'colspan':6 }).update("Nenhum material encontrado, tente novamente.");
				cols[0].setStyle({ 'text-align':'center', 'color':'red' });
				row.insert(cols[0]);
				this.tableBody.insert(row);
			} else {
				
				this._onGenCols(response);
				Effect.Pulsate('dyn-rows', { pulses: 1, duration: 2 });
			}
	
	},
	
	_onGenFailure: function () {
		alert("Não foi possível gerar a lista.")
	},
	
	_onGenCols: function (response) {
		var cols = new Array();

		var rmv = this._onRmv;
		var ky = this._onKeyHandler;
		
		obj = this;
		
		var i = 0;
		
		response.each(function (el) {
			
			i++;
			
			var row = new Element("tr", { "nbr": el["material_id"] });
			
			cols[0] = new Element("td", { 'class':'mat-id' }).update(i);
			var inp = new Element("input", { "type":"text", "value": el["material_nome"], "title":el["material_nome"], "maxlength":75 });
			cols[1] = new Element("td", { 'class':'mat-nome update-item' }).insert(inp);
			cols[2] = new Element("td").update('<a href="#rm" id="rm-'+el["material_id"]+'" class="rmv-item" onclick="rmv(' + el["material_id"] + ')"><img src="img/rmv_med.png" width="16" height="16" /></a>');
			
			cols.each(function (elem) { row.insert(elem); });
			
			$$("#dyn-rows tbody")[0].insert(row);
			
		});
		
		$$('.update-item').each( function (elem) {
			Event.observe(elem, 'keypress', ky);
		}); 
	},
	
	_onUpdateParams: function () {
		this.params.mat_nome = $('mat-nome').value;
		
		return this.params;
	},
	
	_onClearTable: function () {
		
		$$("#dyn-rows tbody tr").each(function (elem) {
			$$("#dyn-rows tbody")[0].removeChild(elem);
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
	
	_onRmv: function (a) { // Remove a row item

		var options = {
			method:'post',
			parameters: { "id": a, "remove": 1 },
			onSuccess: this._onRmvSuccess.bind(this),
			onFailure: this._onRmvFailure.bind(this)
		};
		
		var data = new Ajax.Request(this.matURL, options);
	},
	
	_onRmvSuccess: function (transport) {
		// console.log(transport.responseText);
		var response = transport.responseText.evalJSON();
		
		var rmv_msg = new Element('span', { "class":'callout orange rmv msg' }).update("Você acaba de deletar o material de nome " + response['material_nome'] + ".");
		$('box').insert(rmv_msg);
	},
	
	_onRmvFailure: function () {
		alert("Não foi possível remover as informações.")
	},
	
	_onKeyHandler: function (event) { // Handles the updates on the text boxes.
		
		if(event.keyCode == Event.KEY_RETURN) {

			if(this.hasClassName('mat-nome')) {
				var params = {
					tipo	: "material_nome",
					info	: this.down().value,
					item	: this.up().getAttribute('nbr')
				};
			}

			Event.stop(event);
		}
		
		obj.updateItem(params);
	}
	
});