<?php $guides = ['post_type'=>'sports_guides','has_password'=>false,'posts_per_page'=>5,'order'=>'desc'];
query_posts( $guides ); ?>
<div class="uk-card uk-card-default uk-card-body" data-card="guides">
    <h1 class="uk-card-title"><?php the_field('widget_title_guides', 'option'); ?></h1>

    <ul class="guides-lists">
       <?php while ( have_posts() ) : the_post();
        $widgetICON = get_field('guides_icon'); ?>
        <li class="uk-grid-collapse uk-flex-middle" uk-grid>
            <div class="uk-width-auto">
                <picture>
                    <a href="<?php the_permalink(); ?>">
                        <?php echo wp_get_attachment_image( $widgetICON['id'], 'guides-img' ); ?>
                    </a>
                </picture>
            </div>
            <div class="uk-width-expand">
                <div class="uk-panel">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </div>
            </div>
        </li>
        <?php endwhile; wp_reset_query(); ?>
        <li class="uk-margin-top uk-border-remove">
            <a href="<?php echo esc_url( site_url('gambling-guides') ); ?>" class="uk-button uk-button-primary uk-button-small">View All Guides</a>
        </li>        
    </ul>

</div>