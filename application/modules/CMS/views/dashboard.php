	<div id="masthead">
		
		<div class="content_pad">
			
			<h1 class="no_breadcrumbs"><?php 
echo '<div class="breadcrumbs">' . $page_title . '</div>';
?></h1>
			

			<div id="search">
<?php
echo $js_grid;

if (isset($create_button)) {   ?>
<button type="submit" class="btn btn-large"><a href="<?php echo $create_button['url']; ?>"><?php echo $create_button['name']; ?></a></button>
<?php } ?>

			</div> <!-- #search -->
			
		</div> <!-- .content_pad -->
		
	</div> <!-- #masthead -->	

<div id="content" class="xgrid">

			<div class="x12">			

<table class="data display datatable" id="Grid" style="display:none"></table>


<script>

$(document).ready(function() {

        // set it to full width
        $('.wrapper').width('98%');
    });

</script>
	</div> <!-- x12 -->
	</div> <!-- #content -->
