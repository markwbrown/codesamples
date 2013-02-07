/***** 
	Generates a pure list of calls. Used on the home page of the app.
****/


var CallList = Class.create({
	initialize: function () {
		this.callsURL = 'inc/calls_data.inc.php';
		this.tableBody = '#call-rows tbody';
		
		this.params	= 	{
					calls_uid			: user_id,
					calls_is_out		: $('outbound_call').value
		};
		
		Event.observe('up', 'click', this._onUpHandler.bind(this));
		Event.observe('down', 'click', this._onDownHandler.bind(this));
		
		this.genlist();	
	},
	
	genlist: function () {
		var options = {
			method:'post',
			parameters: { "showlist": 1, "calls_is_out": this.params.calls_is_out, "user_id":this.params.calls_uid },
			onSuccess: this._onGenSuccess.bind(this),
			onFailure: this._onGenFailure.bind(this)
		};
		
		var data = new Ajax.Request(this.callsURL, options);	
	},
	
	_onGenSuccess: function (transport) {
			//console.log(transport.responseText);
			
			var response = transport.responseText.evalJSON() || "no response text";
			var cols = new Array();
			
			if(response.length < 1) {
				
				var row = new Element("tr");

				cols[0] = new Element("td", { 'colspan':4 }).update("Nenhuma ligação encontrada para você nas ultimas duas semanas.");
				cols[0].setStyle({ 'text-align':'center', 'color':'red' });
				row.insert(cols[0]);
				$$(this.tableBody)[0].insert(row);
				
			} else {
				
				this._onGenCols(response);
			}
			Effect.Pulsate('call-rows', { pulses: 1, duration: 2 });
	},
	
	_onGenFailure: function () {
		alert("Não foi possível gerar a lista.")
	},
	
	_onGenCols: function (response) {
		var cols = new Array();
		
		response.each(function (el) {
			
			var row = new Element("tr");

			cols[0] = new Element("td").update(new Element("div", { 'class': 'data'}).update(el["calls_data"]));
			cols[1] = new Element("td").update(new Element("div", { 'class': 'nome'}).update(el["calls_cliente"]));
			cols[2] = new Element("td").update(new Element("div", { 'class': 'para'}).update(el["calls_para"]));
			cols[3] = new Element("td").update(new Element("div", { 'class': 'telefone'}).update(el["calls_telefone"]));
			cols[4] = new Element("td").update(new Element("div", { 'class': 'obs'}).update(el["calls_detalhes"]));	
			
			cols.each(function (elem) { row.insert(elem); });
			
			$$("#call-rows tbody")[0].insert(row);	
		});
	},
	
	_onDownHandler: function (e) {
		new Effect.Move($("call-rows"), { x: 0, y: -296, mode: 'relative' });
		Event.stop(e);
	},
	
	_onUpHandler: function (e) {
		var curTop = $("call-rows").getStyle('top');
		curTop = parseInt(curTop.substr(0, curTop.indexOf("px")));
		
		if(curTop != 0) {
			new Effect.Move($("call-rows"), { x: 0, y: 296, mode: 'relative' });
		}
		Event.stop(e);
	}
	
});