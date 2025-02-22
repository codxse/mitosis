<?php

function mitosis_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Left Sidebar', 'mitosis' ),
		'id'            => 'sidebar-left',
		'description'   => __( 'Widgets in this area will be shown on the left sidebar.', 'mitosis' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Right Sidebar', 'mitosis' ),
		'id'            => 'sidebar-right',
		'description'   => __( 'Widgets in this area will be shown on the right sidebar.', 'mitosis' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	
	register_sidebar( array(
		'name'          => __( 'Below Post Widget Area', 'mitosis' ),
		'id'            => 'below-post-widget-area',
		'description'   => __( 'Widgets in this area will be shown below the post content on single post pages.', 'mitosis' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s below-post-widget">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}

add_action( 'widgets_init', 'mitosis_widgets_init' );