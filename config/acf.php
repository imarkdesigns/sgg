<?php
//! ===
//! Do not edit anything in this file unless you know what you're doing
//! ===

//* ACF Hooks
add_action('acf/init', function() {

});

//* ACF Override UI
add_action('acf/input/admin_head', function() {

  echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">';
  echo '<link rel="stylesheet" href="'._uri.'/resources/styles/wp-acf-editor.min.css">'."\n";

});

//* Speed up ACF backend loading time
add_filter('acf/settings/remove_wp_meta_box', '__return_true');

//* ACF Options
if ( function_exists( 'acf_add_options_page' ) ) :

    acf_add_options_page([
        'menu_slug'   => 'theme-fragments',
        'menu_title'  => 'Fragments',
        'page_title'  => 'Theme Fragments',
        'capability'  => 'edit_posts',
        'icon_url'    => 'dashicons-layout',
        'position'    => '20',
        'redirect'    => true
    ]);

        acf_add_options_sub_page([
            'menu_title'  => 'Not Found',
            'page_title'  => 'Not Found',
            'parent_slug' => 'theme-fragments'
        ]);

        acf_add_options_sub_page([
            'menu_title'  => 'Footer Page',
            'page_title'  => 'Footer Page',
            'parent_slug' => 'theme-fragments'
        ]);  

    acf_add_options_page([
        'menu_slug'   => 'theme-widgets',
        'menu_title'  => 'Widgets',
        'page_title'  => 'Theme Widgets',
        'capability'  => 'edit_posts',
        'icon_url'    => 'dashicons-welcome-widgets-menus',
        'position'    => '20',
        'redirect'    => true
    ]);

        acf_add_options_sub_page([
            'menu_title'  => 'Odds Lines',
            'page_title'  => 'Real Time Odds Lines by League',
            'parent_slug' => 'theme-widgets'
        ]);


        acf_add_options_sub_page([
            'menu_title'  => 'Best Books',
            'page_title'  => 'Where Online Sports Betting is Legal',
            'parent_slug' => 'theme-widgets'
        ]);

        acf_add_options_sub_page([
            'menu_title'  => 'Gambling Guides',
            'page_title'  => 'Sports Gambling Guides',
            'parent_slug' => 'theme-widgets'
        ]);

        acf_add_options_sub_page([
            'menu_title'  => 'Gambling News',
            'page_title'  => 'Sports Gambling News',
            'parent_slug' => 'theme-widgets'
        ]);

endif;
