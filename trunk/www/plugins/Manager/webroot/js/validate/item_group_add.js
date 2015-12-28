// Validate register form
$().ready(function() {
    $("#MenuItemGroupAddForm").validate({
    	rules: {
    		'data[MenuItemGroup][name]' : 'required',
    	},
    	messages: {
    		'data[MenuItemGroup][name]' : 'Please enter group name'
    	}
    });
});