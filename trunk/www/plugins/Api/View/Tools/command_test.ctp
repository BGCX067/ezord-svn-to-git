<div id="content">
    <form id="api-test" action="#" method="post"><!-- Form -->
        <fieldset>
    		<div class="input_field">
    			<label for="a">Api command</label>
    			<input type="text" name="a" class="input bigfield" id="command" value="/api/users/getLogin" />
    		</div>
            <div class="input_field">
    			<label for="b">Get Params</label>
    			<input type="text" name="b" class="input bigfield" id="get_vals" />
    		</div>
            <div class="input_field">
    			<label for="c">Post Params</label>
    			<input type="text" name="c" class="input bigfield" id="post_vals" value="email=caohoangson@gmail.com&password=123456" />
    		</div>
            <br class="clear" />
    		<p class="input_field">
                <label for="d">&nbsp;</label>
                <input type="button" value="Submit" class="submit" id="button" />
            </p>
    	</fieldset>
        
        <pre>
            <textarea cols="96" rows="30" readonly="readonly" id="result"></textarea>
        </pre>
    </form>
</div>
<script type="text/javascript">
    $(function() {
        var BASE_URL = '<?php echo $this->base; ?>'
        $('#button').click(function(){
             var command = $('#command').val();
             if (command != '') {
                var post_vals = $('#post_vals').val();
                var get_vals = $('#get_vals').val();
                if (get_vals != '')
                    get_vals = '?'+get_vals;
                var url = BASE_URL+command+get_vals;
                $.post(url, post_vals, function (data) {
                    $('#result').html(FormatJSON(data, "")); 
                }, 'json')
                .error(function(data) { $('#result').html('Api command loaded fail.') });
             } else
                alert ('Please enter command');
        });
    });
    
    function FormatJSON(oData, sIndent) {
        if (arguments.length < 2) {
            var sIndent = "";
        }
        var sIndentStyle = "    ";
        var sDataType = RealTypeOf(oData);
    
        // open object
        if (sDataType == "array") {
            if (oData.length == 0) {
                return "[]";
            }
            var sHTML = "[";
        } else {
            var iCount = 0;
            $.each(oData, function() {
                iCount++;
                return;
            });
            if (iCount == 0) { // object is empty
                return "{}";
            }
            var sHTML = "{";
        }
    
        // loop through items
        var iCount = 0;
        $.each(oData, function(sKey, vValue) {
            if (iCount > 0) {
                sHTML += ",";
            }
            if (sDataType == "array") {
                sHTML += ("\n" + sIndent + sIndentStyle);
            } else {
                sHTML += ("\n" + sIndent + sIndentStyle + "\"" + sKey + "\"" + ": ");
            }
    
            // display relevant data type
            switch (RealTypeOf(vValue)) {
                case "array":
                case "object":
                    sHTML += FormatJSON(vValue, (sIndent + sIndentStyle));
                    break;
                case "boolean":
                case "number":
                    sHTML += vValue.toString();
                    break;
                case "null":
                    sHTML += "null";
                    break;
                case "string":
                    sHTML += ("\"" + vValue + "\"");
                    break;
                default:
                    sHTML += ("TYPEOF: " + typeof(vValue));
            }
    
            // loop
            iCount++;
        });
    
        // close object
        if (sDataType == "array") {
            sHTML += ("\n" + sIndent + "]");
        } else {
            sHTML += ("\n" + sIndent + "}");
        }
    
        // return
        return sHTML;
    }
    
    function RealTypeOf(v) {
      if (typeof(v) == "object") {
        if (v === null) return "null";
        if (v.constructor == (new Array).constructor) return "array";
        if (v.constructor == (new Date).constructor) return "date";
        if (v.constructor == (new RegExp).constructor) return "regex";
        return "object";
      }
      return typeof(v);
    }
</script>