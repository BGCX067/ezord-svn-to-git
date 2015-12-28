// Validate register form
$().ready(function() {
    $("#UserForm").validate({
    	rules: {
    		'data[User][current-password]' : {
    			required: true,
    			minlength: 5
    		},
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
    		'data[User][current-password]' : {
    			required: 'Please provide your current password',
    			minlength: 'Your password must be at least 5 characters long'
    		},
    		'data[User][password]' : {
    			required: 'Please provide new password',
    			minlength: 'Your password must be at least 5 characters long'
    		},
    		'data[User][confirm-password]' : {
    			required: 'Please confirm new password',
    			minlength: 'Your password must be at least 5 characters long',
    			equalTo: 'Please enter the same password as above'
    		}
    	}
    });
});