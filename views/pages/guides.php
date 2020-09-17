<main id="main" class="main" role="main">
    <div class="uk-container uk-container-xlarge">
        <div class="uk-grid-small" uk-grid>

            <div class="uk-width-expand@l">
                <div class="uk-card uk-card-default uk-card-body" data-card="guides">
                    <h1 class="uk-card-title"><?php the_title(); ?></h1>

                    <?php $guides = ['post_type'=>'sports_guides','has_password'=>false,'posts_per_page'=>-1,'order'=>'desc'];
                    query_posts( $guides ); ?>
                    <div uk-grid class="uk-grid-match uk-child-width-1-2@s uk-child-width-1-3@xl" uk-height-match="target: > div > article > h3">
                        <?php while ( have_posts() ) : the_post(); ?>
                        <div class="article-guides">
                            <article class="uk-article">
                                <?php if ( has_post_thumbnail() ) {
                                    echo '<a href="'. get_permalink() .'">'. get_the_post_thumbnail() .'</a>';
                                } ?>                                
                                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            </article>
                        </div>
                        <?php endwhile; wp_reset_query(); ?>
                    </div>

                </div>
            </div>

            <div class="uk-width-1-1 uk-width-large@l">
            <!-- Start Content -->
            <div class="uk-card uk-card-default uk-card-body" data-card="Gtag">
                <?php (!function_exists('dynamic_sidebar')) || !dynamic_sidebar('guides_tag') ? null : null ; ?>
            </div>

            <?php 

                get_template_part( widget.'news' );

            ?>
            <!-- End Content -->                
            </div>

        </div>
    </div>
</main>