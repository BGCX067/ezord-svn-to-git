// Validate register form
$().ready(function() {
    $("#ReserveAddForm").validate({
    	rules: {
    		'data[Reserve][time]' : 'required',
    	},
    	messages: {
    		'data[Reserve][time]' : 'Please select reserve time'
    	}
    });
});