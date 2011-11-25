<?php 
echo '<div class="breadcrumbs">' . $page_title . '</div>';

if ($active_category == '') 
        $active_category = 0;

echo '<script type="text/javascript">var active_category_id = "' . $active_category . '";</script>';

//echo '<div id="fullpage">full page width</div>';
echo '<form method="POST" action="/CMS/blogger/get_category">';
echo "<div style='float:left'>Category:</div>";
echo "<div style='float:left;padding-left:40px;'>$categories</div>";
echo "<div style='float: left;padding-left:40px;'>";
echo '<input type="submit" value="filter by selected category(s)"></div>';
echo '</form>';
echo '<div style="clear:both"></div>';
echo $js_grid;

if (isset($create_button)) {   ?>
<div class="btn btn-orange"><a href="<?php echo $create_button['url']; ?>"><?php echo $create_button['name']; ?></a></div>
<?php } ?>

<table id="Grid" style="display:none"></table>

<script>

$(document).ready(function() {

        // just set it to full width
        $('.wrapper').width('98%');

        // not using button anymore 
        $('#fullpage').click(function() {
                if ($('.wrapper').width() < 1000)
                    $('.wrapper').width('98%');
                else 
                    $('.wrapper').width('988px');
                return false;
            });


    });

</script>
