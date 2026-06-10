<?php
/**
 * Consultant Pro functions and definitions
 */

if ( ! function_exists( 'consultant_pro_setup' ) ) :
    function consultant_pro_setup() {
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
        
        register_nav_menus( array(
            'primary' => esc_html__( 'Primary Menu', 'consultant-pro' ),
            'footer'  => esc_html__( 'Footer Menu', 'consultant-pro' ),
        ) );
    }
endif;
add_action( 'after_setup_theme', 'consultant_pro_setup' );

function consultant_pro_scripts() {
    wp_enqueue_style( 'consultant-pro-style', get_stylesheet_uri(), array(), '1.0' );
    wp_enqueue_script( 'consultant-pro-scripts', get_template_directory_uri() . '/assets/js/main.js', array(), '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'consultant_pro_scripts' );

/**
 * Custom Post Types (Projects)
 */
function consultant_pro_register_cpt() {
    register_post_type( 'project', array(
        'labels' => array(
            'name' => __( 'Projects' ),
            'singular_name' => __( 'Project' )
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'menu_icon' => 'dashicons-portfolio',
        'rewrite' => array('slug' => 'projects'),
    ));
}
add_action( 'init', 'consultant_pro_register_cpt' );
