// Validate register form
$().ready(function() {
    $("#PlaceAddForm").validate({
    	rules: {
    		'data[Place][name]' : 'required',
    		'data[Place][slogan]' : 'required'
    	},
    	messages: {
    		'data[Place][name]' : 'Please enter your coffee place name',
    		'data[Place][slogan]' : 'Please enter your coffee place slogan'
    	}
    });
});