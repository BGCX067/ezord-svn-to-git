var delay = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();

$(function() {
    $('ul.images input').hide();
    $('ul.images a').click(function() {
         var id = $(this).attr('id');
         updateOrderValue(id);
    });
    $('ul.images a').each(function() {
        var id = $(this).attr('id');
        var image = $(this).children('img');
        var vals = $('#order_items').val().split(',');
        var ul = $($(this).parent()).parent();
        for (var i=0; i<vals.length; i++) {
            if (vals[i] == id) {
                $('#cb_'+id).attr('checked', 'checked');
                $(image).addClass('active');
                $(ul).show();
                addTicky(id);
            }
        }
    });
    $('#reset_search').click(function(){
        $('#search').val('');
        window.location.reload();
    });
    $('#search_menu_items').click(function(){
        if($('#search').val().length<=0) {
            window.location.reload();
        }
        doSearch();
    });
    $('#search').keyup(function(){
        if ($(this).val().length>=3) {
            delay(function(){
                doSearch();
            }, 800 );
        } else if ($(this).val().length==0) {
            window.location.reload();
        }
    });
});

function addTicky (id) {
    var name = $('#name_'+id).html();
    var html_str = '<li id="ordered_id_'+id+'">'+name+'<span class="right"><input type="text" name="data[Order][quantity]['+id+']" value="1" /><img id="delete_'+id+'" src="'+BASE_URL+'/img/delete_s.png"></span></li>';
    $('#note_orders ul').append(html_str);
    $('#ordered_id_'+id).hide();
    $('#ordered_id_'+id).fadeIn(300);
    $('#delete_'+id).css('cursor', 'pointer');
    $('#delete_'+id).bind('click', function(){
        removeTicky (id);
        updateOrderValue(id);
    });
}

function removeTicky (id) {
    $('#ordered_id_'+id).remove();
}

function updateOrderValue (id) {
    var image = $('#'+id).children('img');
    var vals = $('#order_items').val().split(',');
    if (!$('#cb_'+id).attr('checked')) {
        $('#cb_'+id).attr('checked', 'checked');
        $(image).addClass('active');
        vals.push(id);
        addTicky(id);
    } else {
        $('#cb_'+id).attr('checked', false);
        $(image).removeClass('active');
        if (vals.length>0) {
            for(var i=0; i<vals.length; i++) {
                vals.splice(i, 1);
            }
        }
        removeTicky(id);
    }
    $('#order_items').val(vals.join(','));
}

function doSearch () {
    $('#list_items').fadeOut(300);
    $.get(BASE_URL+'/order/menu_items/search/'+pid+'/keyword:'+$('#search').val(), function(data) {
        $('#list_items').html(data);
        $('#list_items').fadeIn(300);
        $('ul.images input').hide();
        $('ul.images a').click(function() {
             var id = $(this).attr('id');
             updateOrderValue(id);
        });
        $('ul.images a').each(function() {
            var id = $(this).attr('id');
            var image = $(this).children('img');
            var vals = $('#order_items').val().split(',');
            var ul = $($(this).parent()).parent();
            for (var i=0; i<vals.length; i++) {
                if (vals[i] == id) {
                    $('#cb_'+id).attr('checked', 'checked');
                    $(image).addClass('active');
                    $(ul).show();
                }
            }
        });
    });
}
