
// set up all event once everything is loaded
//
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

        $('#edit_popup #save').click(function() {
                save_popup_edits();
                return false;
            });

        $('#edit_popup #cancel').click(function() {
                hide_lightbox();
                currently_editing = null;
                return false;
            });

	$('.calendar .day_num').click(function() {
                day_num = $(this).find('.day_num').html();
                day_data = prompt('Enter Stuff', $(this).find('.content').html());
                if (day_data != null) {
                    $.ajax({
                        url: window.location,
                                type: 'POST',
                                data: {
                            day: day_num,
                                    data: day_data
                                    },
                                success: function(msg) {
                                location.reload();
                            }						
                        });
                }
            });
    }); 

// respond to editable cell save button
//
function save_popup_edits() {
    dataString = $('#edit_popup form').serialize();
    $.ajax({
        type: "POST",
                url: location.href + "/update_field",
                data: dataString,
                success: function(res) {
                if ((res.indexOf('Error:') != -1) || (res.indexOf('<!DOC') != -1))
                    alert(res);
                else {
                    $(currently_editing).text(res);
                    hide_lightbox();
                    currently_editing = null;
                }
            }
        });
}

// show a dim lightbox - just to enforce modal operation
function show_lightbox() {
    if ($("#lightbox").width() < 1100) 
        $("#lightbox").width('1100px');
    $('html, body').animate({scrollTop:0}, 'fast');
    $("#lightbox").show();
}

// hide the lightbox and close the editable area
function hide_lightbox() {
    $("#lightbox").hide();
    $("#edit_popup").slideUp ( 200 );
    $(currently_editing).parent().removeClass('currently_editing');
}

var currently_editing = null;

// a cell has been click, set up to edit the contents
//
function edit_cell(to_edit) {
    
    // parent is flexigrid wrapper div, parent of div is td
    var cell = $(to_edit).parent().parent(); 
    var row = $(cell).parent();

    if (!($(row).hasClass('trSelected')))
        $(row).addClass('trSelected');

    // set up for modal operations
    show_lightbox();

    currently_editing = to_edit;

    // id is always in column 0
    var edit_id = parseInt($('td:eq(0)', row).text());
    // currently, the name is always in column 2 - may have to pass that as a parameter
    var who = $('td:eq(2)', row).text();

    // sitems (in flexigrid) holds table header info
    var cellIndex = $(cell).index();
    show_edit_field(to_edit, edit_id, sitems[cellIndex].name, who, sitems[cellIndex].display, $(to_edit).text());
    
    // slide the editable area down
    $("#edit_popup").slideDown( 200 );

    return false;
}

// set up the editable area to edit this cell
//
function show_edit_field(to_edit, edit_id, field_name, who, title, value) {

    //get the position of the current cell
    var pos = $(to_edit).offset();  
    var page_right = $(document).width();

    // fill in the id, field name
    $("#edit_popup #edit_id").val(edit_id);
    $("#edit_popup #field_name").val(field_name);

    // fill in who or what is being edited
    var t = ' title="Open a full editor (edit all fields)" ';
    $("#edit_popup #popup_who").html('<a ' + t + 'href="' + location.href + '/edit/' + edit_id + '">' + who + '</a>');
    $("#edit_popup #popup_title").text(title);

    // clear the extra form fields
    $("#edit_popup #popup_extra").html('');

    // if extra form fields, append them to the form
    var parent = $(to_edit).parent();
    if ($('.editable_extra', parent).html()) {
        $('.editable_extra select', parent).clone().appendTo($("#edit_popup #popup_extra"));
        $("#edit_popup #popup_extra").show();
        $("#popup_value").hide();
    }
    // else just set the value and show that
    else {
        $("#popup_value").val(value);
        $("#popup_value").show();
        $("#popup_value").focus();
    }

    // now set the editable area to be immediately under this cell
    //    move enough to the left to show if it is too close to the right edge
    var width = ($(to_edit).width() < 350) ? 350 : $(to_edit).width();
    var popup_left = ((pos.left + width) > page_right) ? (page_right - (width + 20)) : (pos.left - 5);

    $("#edit_popup").width(width);
    $(to_edit).parent().addClass('currently_editing');
    $("#edit_popup").css( { "left": popup_left + "px", "top": (pos.top + 20) + "px" } );
}

// called when grid load is complete (via ajax)
function fs_toggle_cols() {

    // set up click handler for editable cells
    $('.editable_cell').click(function () {
            return edit_cell(this);
        });

    // hovering over editable cells displays the 'edit' png
    $('.editable_cell').hover(
        function () {
            $(this).parent().append($('<div class="edit-row"></div>'));
        }, 
        function () {
            $(this).parent().find("div:last").remove();
        }

    );

    return;

    // not used currently

    // this is to set up only relevent column displays - some categories only use a few columns of real data
    //
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

// these are handlers for the buttons in the grid toolbar (top)
//
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


