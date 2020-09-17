<?php /**
 * Arranged & Compiled by: Mark Rivera, imarkdesigns@gmail.com
 * Github: https://github.com/imarkdesigns
 * @package acmx
**/

/*
* Yoast SEO Disable Automatic Redirects for
* Posts And Pages
* Credit: Yoast Development Team
* Last Tested: May 09 2017 using Yoast SEO Premium 4.7.1 on WordPress 4.7.4
*/

add_filter('wpseo_premium_post_redirect_slug_change', '__return_true' );

/*
* Yoast SEO Disable Automatic Redirects for
* Taxonomies (Category, Tags, Etc)
* Credit: Yoast Development Team
* Last Tested: May 09 2017 using Yoast SEO Premium 4.7.1 on WordPress 4.7.4
*/

add_filter('wpseo_premium_term_redirect_slug_change', '__return_true' );

/*
* Yoast SEO Disable Redirect Notifications for
* Posts or Pages: Moved to Trash
* Credit: Yoast Development Team
* Last Tested: May 09 2017 using Yoast SEO Premium 4.7.1 on WordPress 4.7.4
*/

add_filter('wpseo_enable_notification_post_trash', '__return_false');

/*
* Yoast SEO Disable Redirect Notifications for
* Posts and Pages: Change URL
* Credit: Yoast Development Team
* Last Tested: May 09 2017 using Yoast SEO Premium 4.7.1 on WordPress 4.7.4
*/

add_filter('wpseo_enable_notification_post_slug_change', '__return_false');

/*
* Yoast SEO Disable Redirect Notifications for
* Taxonomies: Moved to Trash
* Credit: Yoast Development Team
* Last Tested: May 09 2017 using Yoast SEO Premium 4.7.1 on WordPress 4.7.4
*/

add_filter('wpseo_enable_notification_term_delete','__return_false');

/*
* Yoast SEO Disable Redirect Notifications for
* Taxonomies: Change URL
* Credit: Yoast Development Team
* Last Tested: May 09 2017 using Yoast SEO Premium 4.7.1 on WordPress 4.7.4
*/

add_filter('wpseo_enable_notification_term_slug_change','__return_false');

/*
* Remove All Yoast HTML Comments for
* Inspect Element
* Credit: Paul Collett
*/

add_action('wp_head', function() { 
  ob_start( function($o) {
    return preg_replace('/^\n?<!--.*?[Y]oast.*?-->\n?$/mi','',$o);
  }); 
}, ~PHP_INT_MAX);

