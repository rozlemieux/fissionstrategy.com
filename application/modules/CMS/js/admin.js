

$(document).ready(function() {

        $('#caldate').hide();

        $('#blogdateinput').focus(function() {
                $('#caldate').show();
            });

        $('.focusable').focus(function() {
                if (this.value == this.defaultValue){
                    this.value = '';
                }
                if(this.value != this.defaultValue) {
                    this.select();
                }
            });
        $('.focusable').blur(function() {
                if (this.value == '') {
                    this.value = (this.defaultValue ? this.defaultValue : '');
                }
            });

    }); 

// this is to set up only relevent column displays - some categories only use a few columns of real data
//
function fs_toggle_cols() {


    return;

    if (typeof active_category_id == 'undefined')
            return;

    var flexigrid;
    // find the grid object
    $('#Grid').each( function() {
            if (this.grid && this.p.url) {
                flexigrid = this;
            }
        });

    if (active_category_id == 42) {
        for (i = 0; i < 23; i++) {
            if ((i > 3) && (i != 21)) {
                $(flexigrid).flexToggleCol(i,0);
            }
        }
    }

    // Twitter
    if (active_category_id == 43) {
        for (i = 0; i < 23; i++) {
            if ((i > 2) && (i != 21) && (i != 20) && (i != 15)) {
                $(flexigrid).flexToggleCol(i,0);
            }
        }
    }

    return;
}

function grid_functions(com, grid) {
    if (com=='Select All') {
        $('.bDiv tbody tr',grid).addClass('trSelected');
    }
    
    if (com=='DeSelect All') {
        $('.bDiv tbody tr',grid).removeClass('trSelected');
    }
    
    if (com=='Export') {

        var flexigrid;

        // find the grid object
        $('#Grid').each( function() {
                if (this.grid && this.p.url) {
                    flexigrid = this;
                }
            });

        var params = '';
        params += 'export=1';  
        params += '&page=1';  
        params += '&rp=9999'; 
        params += '&sortname=' + flexigrid.p.sortname;
        params += '&sortorder=' + flexigrid.p.sortorder;
        params += '&query=' + $('input[name=q]',grid.sDiv).val();
        params += '&qtype=' + $('select[name=qtype]',grid.sDiv).val();;

        $.ajax({
            type: "POST",
                    url: flexigrid.p.url,
                    data: params,
                    success: function(data){
                          window.open(data, "Export", "toolbar=yes, scrollbars=yes, resizeable=yes");
                        //$('#Grid').flexReload();
                }
            });
    }
    
    if (com=='Delete')     {

        if ($('.trSelected',grid).length > 0) {
            if (confirm('Delete ' + $('.trSelected',grid).length + ' items?')) {
                var items = $('.trSelected',grid);
                var itemlist ='';
                for(i=0;i<items.length;i++){
                    itemlist+= items[i].id.substr(3)+",";
                }
                $.ajax({
                    type: "POST",
                            url: location.href + "/delete",
                            data: "items="+itemlist,
                            success: function(data){
                            $('#Grid').flexReload();
                        }
                    });
            }
        } else {
            return false;
        } 
    }          
} 


