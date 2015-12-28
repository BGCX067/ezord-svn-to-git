// Validate register form
$().ready(function() {
    $("#TableAddForm").validate({
    	rules: {
    		'data[Table][name]' : 'required',
    	},
    	messages: {
    		'data[Table][name]' : 'Please enter table name'
    	}
    });
});