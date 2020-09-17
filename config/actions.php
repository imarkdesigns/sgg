<?php
//! ===
//! Do not edit anything in this file unless you know what you're doing
//! ===

//* Schema.Org
function schema() {
    if ( is_single() ) :
        $type = 'Article';

    elseif ( is_author() ) :
        $type = 'ProfilePage';

    elseif ( is_search() ) :
        $type = 'SearchResultsPage';

    else :
        $type = 'WebPage';
    endif;

    $schema = 'http://schema.org/';
    echo 'itemscope itemtype="'.$schema.$type.'"';
}

//* Remove Posts & Comments (WP Navigation)
add_action('admin_menu', function() {
    // remove_menu_page( 'edit.php' ); // Remove Posts
    remove_menu_page( 'edit-comments.php' ); // Remove Comments
});

add_action( 'wp_before_admin_bar_render', 'my_admin_bar_render' );
function my_admin_bar_render() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('comments');
}

//* Remove Emoji & Admin-Bar
add_filter('emoji_svg_url', '__return_false');
// add_filter('show_admin_bar', '__return_false');

//* Removes or edits the 'Protected:' part from posts titles
function remove_protected_text() {
  return __('%s');
}
add_filter( 'protected_title_format', 'remove_protected_text' );

//* Allow Unfiltered Uploads & Edit themes/plugins
define('ALLOW_UNFILTERED_UPLOADS', true);
define('DISALLOW_FILE_EDIT', true);
define('WP_DEBUG', false);

//* Replace <p> to <figure> wrapping image tag
function img_caption_shortcode_filter($val, $attr, $content = null) {
  extract(shortcode_atts(array(
    'id'      => '',
    'align'   => 'aligncenter',
    'width'   => '',
    'caption' => ''
  ), $attr));

  // No caption, no dice... But why width?
  if ( 1 > (int) $width || empty($caption) )
    return $val;

  if ( $id )
    $id = esc_attr( $id );

  // Add itemprop="contentURL" to image - Ugly hack
  $content = str_replace('<img', '<img itemprop="contentURL"', $content);
  return '<figure id="' . $id . '" aria-describedby="figcaption_' . $id . '" class="wp-caption ' . esc_attr($align) . '" itemscope itemtype="http://schema.org/ImageObject">' . do_shortcode( $content ) . '<figcaption id="figcaption_'. $id . '" class="wp-caption-text uk-text-small" itemprop="description">' . $caption . '</figcaption></figure>';
}
add_filter( 'img_caption_shortcode', 'img_caption_shortcode_filter', 10, 3 );

//* Format textarea for display
$filters = array('term_description');
foreach ( $filters as $filter ) {
  add_filter( $filter, 'wptexturize' );
  add_filter( $filter, 'convert_chars' );
  remove_filter( $filter, 'wpautop' );
}

//* Remove empty <p> tags
function remove_empty_p( $content ) {
  $content = force_balance_tags( $content );
  $content = preg_replace( '#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $content );
  $content = preg_replace( '~\s?<p>(\s|&nbsp;)+</p>\s?~', '', $content );
  return $content;
}
add_filter('the_content', 'remove_empty_p', 20, 1);

//* Allow VCard Uploading
function vcard_upload($mimes) {
  $mimes['vcf'] = 'text/x-vcard';
  return $mimes;
}
add_filter( 'upload_mimes', 'vcard_upload' );

//* Allow SVG Uploading
function svg_upload($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', 'svg_upload', 99);

//* Create sub-navigation to main menu
class subMenuWrap extends Walker_Nav_Menu {
    function start_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<div class=\"uk-navbar-dropdown\" uk-dropdown=\"offset: 0\"><ul class=\"uk-nav uk-navbar-dropdown-nav\">\n";
    }
    function end_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul></div>\n";
    }
}

//* Create sub-navigation to mobile menu
class mobileMenuWrap extends Walker_Nav_Menu {
    function start_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"uk-nav-sub\">\n";
    }
    function end_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }
}

//* Display Popular Post
function observePostViews($postID) {
  $count_key = 'post_views_count';
  $count = get_post_meta($postID, $count_key, true);
  if($count=='') {
    $count = 0;
    delete_post_meta($postID, $count_key);
    add_post_meta($postID, $count_key, '0');
  } else {
    $count++;
    update_post_meta($postID, $count_key, $count);
  }
}

function fetchPostViews($postID) {
  $count_key = 'post_views_count';
  $count = get_post_meta($postID, $count_key, true);
  if($count=='') {
    delete_post_meta($postID, $count_key);
    add_post_meta($postID, $count_key, '0');
  return "0 View";
  }
  return $count.' Views';
}

//* Add Sticky Post to FAQ
