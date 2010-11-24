<?php 
echo '<div class="breadcrumbs">' . $page_title . '</div>';

echo $js_grid;

if (isset($create_button)) {   ?>
<div class="create_button"><a href="<?php echo $create_button['url']; ?>"><?php echo $create_button['name']; ?></a></div>
<?php } ?>

<table id="Grid" style="display:none"></table>


