<div class="uk-card uk-card-default uk-card-body" data-card="oddslines">
    
    <h1 class="uk-card-title"><?php the_field('widget_title_oddslines', 'option'); ?></h1>
    <?php the_field('widget_content_oddslines', 'option'); ?>
    

    <div class="oddslines-lists">
        <?php while ( have_rows('widget_repeater_oddslines', 'option') ) : the_row();
        $imgFeature = get_sub_field('league_photo', 'option');
        $imgLink    = get_field('attachment_link', $imgFeature['id']); ?>
        <figure>
            <a href="<?php echo $imgLink; ?>">
                <?php echo wp_get_attachment_image( $imgFeature['id'], [ 640, 360, true ] ); ?>
            </a>
        </figure>
        <?php endwhile; ?>
    </div>

</div>