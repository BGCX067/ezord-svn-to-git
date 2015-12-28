// Validate register form
$().ready(function() {
    $("#UserMemberForm").validate({
    	rules: {
    		'data[User][password]' : {
    			required: true,
    			minlength: 5
    		},
    		'data[User][confirm-password]' : {
    			required: true,
    			minlength: 5,
    			equalTo: '#password'
    		}
    	},
    	messages: {
    		'data[User][password]' : {
    			required: 'Please provide a password',
    			minlength: 'Your password must be at least 5 characters long'
    		},
    		'data[User][confirm-password]' : {
    			required: 'Please provide a password',
    			minlength: 'Your password must be at least 5 characters long',
    			equalTo: 'Please enter the same password as above'
    		}
    	}
    });
});