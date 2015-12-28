// Validate register form
$().ready(function() {
    $("#MenuItemAddForm").validate({
    	rules: {
    		'data[MenuItem][name]' : 'required',
            'data[MenuItem][menu_item_group_id]' : 'required',
            'data[MenuItem][price]' : {
                'required' : true,
                'number' : true,
            }
    	},
    	messages: {
    		'data[MenuItem][name]' : 'Please enter item name',
            'data[MenuItem][menu_item_group_id]' : 'Please select a group',
            'data[MenuItem][price]' : {
                'required': 'Please enter item price',
                'number': 'Please enter a valid price'
            }
    	}
    });
});