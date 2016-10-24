<?php

/**
 * http://codex.wordpress.org/Theme_Customization_API
 *
 * How do I "output" custom theme modification settings? http://codex.wordpress.org/Function_Reference/get_theme_mod
 * echo get_theme_mod( 'copyright_info' );
 * or: echo get_theme_mod( 'copyright_info', 'Default (c) Copyright Info if nothing provided' );
 *
 * "sanitize_callback": http://codex.wordpress.org/Data_Validation
 */

/**
 * Implement Theme Customizer additions and adjustments.
 */

function themes_starter_customize( $wp_customize ) {

/*
 * Initialize sections
 */
	
	$wp_customize->add_section( 'theme_header_section', array(
		'title'          => 'Header',
		'priority'       => 1000,
	) );
	
/*
 * Section: Page Layout
 */
	// Header Logo
	$wp_customize->add_setting('header_logo', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	));
	$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'header_logo', array(
		'label'       => __('Upload Header Logo', 'my-theme'),
		'description' => __('Height: &gt;80px', 'my-theme'),
		'section'  => 'theme_header_section',
		'settings' => 'header_logo',
		'priority' => 1,
		'extensions' => array( 'jpg', 'jpeg', 'gif', 'png', 'svg' ),
	)));
	
	// Search?
	$wp_customize->add_setting( 'search_enabled', array(
		'default' => '1',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'search_enabled', array(
		'type'          => 'checkbox',
		'label'         => __( 'Show Searchfield?', 'my-theme' ),
		'section'       => 'theme_header_section',
		'settings'   => 'search_enabled',
		'priority'   => 2,
	) );
}
add_action( 'customize_register', 'themes_starter_customize' );


/**
 * Bind JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function themes_starter_customize_preview_js() {
	wp_enqueue_script( 'customizer', get_template_directory_uri() . '/inc/customizer.js', array( 'jquery' ), null, true );
}
add_action( 'customize_preview_init', 'themes_starter_customize_preview_js' );
