var OrderForm = {
	config: {
        form: $('#orderForm'),
        orderBasicContainer: $('#orderBasic'),
        orderDetailContainer: $('#orderDetail'),
    },
	init: function() {
		this.addOrderDetail();
		this.removeOrderDetail();
	},
	addOrderDetail: function() {
		var container = this.config.orderDetailContainer;

		container.on('click', '.duplicate', function(event) {
			var dom = $(this).closest('.duplicate-me'),
				index = 1 + parseInt(dom.attr('index')),
				data = {};

			dom.find(':input').each(function(key, item) {
				if ($(item).attr('name')) {
					name = $(item).attr('name').replace( new RegExp(/\[\d+\]/, "gm"), "");
					$(item).attr('name' , (name + '[' + index + ']'));
					data[(name + '[' + index + ']')] = $(item).val();
				}
			});

			html = '<div class="form-group duplicate-me" index=' + index + '>' + dom.html() + '</div>';
			container.append(html);

			$(this).removeClass('duplicate');
			$(this).addClass('remove');
			$(this).html('<i class="glyphicon glyphicon-remove"></i>');
			dom.removeClass('duplicate-me');
			dom.addClass('remove-me');

			index = index - 1;
			dom.find(':input').each(function(key, item) {
				if ($(item).attr('name')) {
					name = $(item).attr('name').replace( new RegExp(/\[\d+\]/, "gm"), "");
					$(item).attr('name' , (name + '[' + index + ']'));
				}
			});

			// cloning data
			container.children().last().find(":input").each(function(key, item) {
				if ($(item).attr('name')) {
					$(item).val(data[$(item).attr('name')]);
					$('select[name="' + $(item).attr('name') + '"').val(data[$(item).attr('name')]);
				}
			});
		});
	},
	removeOrderDetail: function() {
		var container = this.config.orderDetailContainer;
		container.on('click', '.remove', function (event) {
			$(this).closest('.remove-me').remove();
		});
	}
};

OrderForm.init();
