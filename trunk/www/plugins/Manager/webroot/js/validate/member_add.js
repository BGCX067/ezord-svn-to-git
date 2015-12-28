// Validate add member
$().ready(function() {
    $("#UserMemberForm").validate({
    	rules: {
    		'data[User][first_name]' : 'required',
    		'data[User][last_name]' : 'required',
    		'data[User][email]' : {
    			required: true,
    			email: true
    		},
    		'data[User][phone]': 'required'
    	},
    	messages: {
    		'data[User][first_name]' : 'Please enter your first name',
    		'data[User][last_name]' : 'Please enter your last name',
            'data[User][email]' : 'Please enter a valid email',
    		'data[User][phone]' : 'Please enter your phone number'
    	}
    });
});