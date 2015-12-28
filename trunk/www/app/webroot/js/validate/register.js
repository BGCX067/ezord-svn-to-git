// Validate register form
$().ready(function() {
    $("#UserRegisterForm").validate({
    	rules: {
    		'data[User][first_name]' : 'required',
    		'data[User][last_name]' : 'required',
    		'data[User][password]' : {
    			required: true,
    			minlength: 5
    		},
    		'data[User][confirm-password]' : {
    			required: true,
    			minlength: 5,
    			equalTo: '#password'
    		},
    		'data[User][email]' : {
    			required: true,
    			email: true
    		},
    		'data[User][phone]': 'required',
            'data[User][captcha]': 'required'
    	},
    	messages: {
    		'data[User][first_name]' : 'Please enter your first name',
    		'data[User][last_name]' : 'Please enter your last name',
    		'data[User][password]' : {
    			required: 'Please provide a password',
    			minlength: 'Your password must be at least 5 characters long'
    		},
    		'data[User][confirm-password]' : {
    			required: 'Please provide a password',
    			minlength: 'Your password must be at least 5 characters long',
    			equalTo: 'Please enter the same password as above'
    		},
            'data[User][email]' : 'Please enter a valid email',
    		'data[User][phone]' : 'Please enter your phone number',
            'data[User][captcha]' : 'Please enter the code above'
    	}
    });
    $('#captcha_image').css('cursor', 'pointer');
    $('#captcha_image').click(function(){
        $(this).attr('src', $(this).attr('src')+'-x-');
    })
});