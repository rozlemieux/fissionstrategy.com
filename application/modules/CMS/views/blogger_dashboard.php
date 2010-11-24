<?php 
echo '<div class="breadcrumbs">' . $page_title . '</div>';

if ($active_category == '') 
        $active_category = 0;

echo '<script type="text/javascript">var active_category_id = ' . $active_category . ";</script>";

echo '<form method="POST" action="/CMS/blogger/get_category">';
echo "<h2>Category:</h2>";
echo "<div style='float:left;padding-bottom:20px;'>$categories</div>";
echo "<div style='margin-top:20px;clear:both'>";
echo '<input type="submit" value="filter"></div>';
echo '</form>';

echo $js_grid;

if (isset($create_button)) {   ?>
<div class="create_button"><a href="<?php echo $create_button['url']; ?>"><?php echo $create_button['name']; ?></a></div>
<?php } ?>

<table id="Grid" style="display:none"></table>


