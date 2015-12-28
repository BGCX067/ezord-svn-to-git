// Validate register form
$().ready(function() {
    $("#MenuItemPriceForm").validate({
    	rules: {
    		'data[MenuItemPrice][price]' : 'required',
            'data[MenuItemPrice][time_from]' : 'required',
            'data[MenuItemPrice][time_to]' : 'required'
    	},
    	messages: {
    		'data[MenuItemPrice][price]' : 'Please enter price',
            'data[MenuItemPrice][time_from]' : 'Please select time from',
            'data[MenuItemPrice][time_to]' : 'Please select time to'
    	}
    });
});