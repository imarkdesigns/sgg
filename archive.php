<?php get_header();

    $current_category = single_cat_title("", false);

    get_template_part( _article, null );

get_footer();