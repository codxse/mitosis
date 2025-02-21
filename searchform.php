<?php
/**
 * Template for displaying search forms. 
 */

?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ) ?>">
	<label>
		<span class="screen-reader-text"><?php _x( 'Search this site:', 'label' )?></span>
		<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search this site &hellip;', 'placeholder' ) ?>" value="<?php echo get_search_query() ?>" name="s" />
	</label>
	<button type="submit" class="search-submit">
		<i class="icon icon-magnifying-glass"></i>	
	<span class="screen-reader-text"><?php echo _x( 'Search button', 'submit button'); ?></span>
	</button>
</form>