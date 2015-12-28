// Validate auto add table form
$().ready(function() {
    $("#TableAutoAddForm").validate({
    	rules: {
    		'data[Table][number]' : {
    			required: true,
      			max: 30
    		},
    	},
    	messages: {
    		'data[Table][number]' : {
    			required: 'Please enter number of tables',
    			max: 'Please enter value less than 30'
    		}
    	}
    });
});