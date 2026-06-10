<?php
/**
 * Ulmap Night Sky functions and definitions
 */

function ulmap_night_sky_enqueue_styles() {
    wp_enqueue_style( 'ulmap-night-sky-style', get_stylesheet_uri() );
}
add_action( 'wp_enqueue_scripts', 'ulmap_night_sky_enqueue_styles' );

function ulmap_night_sky_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
}
add_action( 'after_setup_theme', 'ulmap_night_sky_setup' );

function ulmap_night_sky_widgets_init() {
    register_sidebar( array(
        'name'          => 'Status Badge Area',
        'id'            => 'status-badge-area',
        'before_widget' => '',
        'after_widget'  => '',
    ) );
}
add_action( 'widgets_init', 'ulmap_night_sky_widgets_init' );
