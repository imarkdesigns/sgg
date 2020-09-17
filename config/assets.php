<?php
//! ===
//! Do not edit anything in this file unless you know what you're doing
//! ===

//* Assets Resources
add_action('wp_enqueue_scripts', function() {

    global $post;

    # UIKit 3
    $v = '3.5.5'; // UIKit Version(s)
    wp_enqueue_style( 'uikit', 'https://cdn.jsdelivr.net/npm/uikit@'.$v.'/dist/css/uikit.min.css' );
    wp_enqueue_script( 'uikit', 'https://cdn.jsdelivr.net/npm/uikit@'.$v.'/dist/js/uikit.min.js', ['jquery'], null, true );
    wp_enqueue_script( 'uikit-icons', 'https://cdn.jsdelivr.net/npm/uikit@'.$v.'/dist/js/uikit-icons.min.js', null, null, true );

    wp_enqueue_script( 'main', _scripts.'main.min.js', null, null, true );
    // wp_enqueue_script( 'icon', _scripts.'icons.min.js', null, null, true );

    wp_enqueue_style( 'main', _styles.'main.min.css' );

    # Not Found
    if ( is_404() ) {

    }

    # Category
    elseif ( is_category([ 'nfl', 'nba', 'mlb' ]) ) {

        wp_enqueue_style( 'page', _styles.'league-news.min.css' );

    }

    # Single | Singular
    elseif ( is_single() ) {

        switch ( $post->post_type ) {

            case 'sports_guides' : $postName = 'guides'; break;

        }
        wp_enqueue_style( 'page', _styles.$postName.'.min.css' );

    }

    # Category | Tag
    elseif ( is_category() || is_archive() ) {

        switch ( get_query_var( 'taxonomy' ) ) {
            
            case 'guides_tag':
                $postName = 'guides';
                break;
            
        }
        wp_enqueue_style( 'page', _styles.$postName.'.min.css' );

    }

    # Pages
    elseif ( is_page() ) {

        switch ( $post->ID ) {

            // Pages
            case '10':   $pageName = 'about'; break;
            case '12':   $pageName = 'faqs'; break;
            case '14':   $pageName = 'contact'; break;
            case '21':   $pageName = 'sitemap'; break;

            case '552':  $pageName = 'guides'; break;

            // Legal
            case '3':
            case '17':
            case '19':   $pageName = 'legal'; break;

            case '292':
            case '294':
            case '296':  $pageName = 'league-news'; break;
            
            // Leagues Pages
            case '6':    $pageName = 'best-books'; break;
            case '8':    $pageName = 'league-odds'; break;

            // Leagues
            case '23':
            case '25':
            case '27':   $pageName = 'league-data'; break;

            // Leagues Odds Lines
            case '29':
            case '37':
            case '45':   $pageName = 'league-odds'; break;

            // Leagues Futures
            case '31':
            case '39':
            case '47':   $pageName = 'league-futures'; break;

            // Leagues ATS Standings
            case '33':
            case '41':
            case '49':   $pageName = 'league-ats'; break;

            // Leagues Injuries
            case '33':
            case '43':
            case '51':   $pageName = 'league-injuries'; break;

            default : $pageName = "home"; break;

        }
        wp_enqueue_style( 'page', _styles.$pageName.'.min.css' );

    }

}, 100);

// Disable WordPress Script & Function
function unnecessary_scripts() {
    // wp_dequeue_script('jquery');
    // wp_deregister_script('jquery');
    wp_dequeue_style('wp-block-library');
}
add_filter( 'wp_enqueue_scripts', 'unnecessary_scripts', PHP_INT_MAX );