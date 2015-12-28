$().ready(function() {
    $("#MemberAssignForm").validate({
    	rules: {
    		'data[UserMember][place_id]' : 'required'
    	},
    	messages: {
    		'data[UserMember][place_id]' : 'Please select a place'
    	}
    });
});