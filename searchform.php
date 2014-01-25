<form role="search" method="GET" action="<?php echo home_url(); ?>" id="searchform" class="boxsized">
	<input type="search" id="search2" class="search-field" name="s" placeholder="search..." value="<?php the_search_query(); ?>" />
	<input type="submit" value="" class="btn search-btn" /><span class="fa search-icon"></span>
</form>
