var LotForm = {
	config: {
		form: $('#lotForm'),
		'baseUrl': window.location.protocol + "//" + window.location.host + "/"
	},
	init: function() {
		this.onSelectBuyer();
		this.onSelectOrder();
	},
	onSelectBuyer: function() {
		/*var config = this.config;
		$('select#buyer').on('change', function (e) {
		    var buyerId = this.value,
		    	orderOption = $('select#order'),
		    	colorOption = $('select#color');

		    $.ajax({
			  method: "GET",
			  url: config.baseUrl + 'utility/orders/' +  buyerId,
			}).done(function( options ) {
			    orderOption.html(options);
			    colorOption.html('<option selected="selected" value="" hidden="hidden">Select a color</option>');
			}).fail(function( jqXHR, textStatus ) {
			  	console.log( "Request failed: " + textStatus );
			});
		});*/
		var config = this.config;
		$('select#order').on('change', function (e) {
		    var buyerId = this.value,
		    	purchaseOrderOption = $('select#purchaseOrder'),
		    	colorOption = $('select#color');

		    $.ajax({
			  method: "GET",
			  url: config.baseUrl + 'utility/purchase-orders/' +  buyerId,
			}).done(function( options ) {
			    purchaseOrderOption.html(options);
			    colorOption.html('<option selected="selected" value="" hidden="hidden">Select a color</option>');
			}).fail(function( jqXHR, textStatus ) {
			  	console.log( "Request failed: " + textStatus );
			});
		});
	},
	onSelectOrder: function() {
		var config = this.config;

		$('select#purchaseOrder').on('change', function (e) {
		    var orderId = this.value,
		    	params = {
		    		order_id: $('select#order').val(),
		        	buyer_id: $('select#buyer').val()
		    	},
			    colorOption = $('select#color');
			    
		    $.ajax({
			  method: "GET",
			  url: config.baseUrl + 'utility/colors?' + $.param(params),
			}).done(function( options ) {
			    colorOption.html(options);
			}).fail(function( jqXHR, textStatus ) {
			  	console.log( "Request failed: " + textStatus );
			});
		});
	}
};

LotForm.init();