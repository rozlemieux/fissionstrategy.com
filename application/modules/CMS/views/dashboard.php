<?php 
echo '<div class="breadcrumbs">' . $page_title . '</div>';

echo $js_grid;

if (isset($create_button)) {   ?>
<div class="create_button"><a href="<?php echo $create_button['url']; ?>"><?php echo $create_button['name']; ?></a></div>
<?php } ?>

<div id="edit_popup" style="display: none;" >
  <form action="edit_field">
    <input id="edit_id" name="edit_id" type="hidden" value="" />
    <input id="field_name" name="field_name" type="hidden" value="" />

    <div><div class="popup_label">Editing:</div><div id="popup_who"></div></div>
    <h3><span id="popup_title"></span>: </h3>
    <input type="text" name="value" id="popup_value" />
    <div id="popup_extra"></div>
    <div class="popup_buttons">
      <div id="save" style="float: right" class="popup_button">Save</div>
      <div id="cancel" class="popup_button">Cancel</div>
    </div>
  </form>
</div>

<table id="Grid" style="display:none"></table>


