<main id="main" class="main" role="main">
    <div class="uk-container uk-container-xlarge">
        <div class="uk-grid-small" uk-grid>

            <div class="uk-width-expand@l">
            <!-- Start Content -->
                
                <div class="uk-card uk-card-default uk-card-body" data-card="content">
                    <article class="uk-article uk-margin-bottom">
                    <?php

                        // Display Featured Image
                        if ( has_post_thumbnail() ) {
                            the_post_thumbnail();
                        }

                        the_title('<h2 class="uk-article-title">','</h2>');
                        the_content();

                    ?>
                    </article>
                </div>

            <!-- End Content -->
            </div>

            <div class="uk-width-1-1 uk-width-large@l">
                <div class="uk-card uk-card-default uk-card-body" data-card="Gtag">
                    <?php (!function_exists('dynamic_sidebar')) || !dynamic_sidebar('guides_tag') ? null : null ; ?>
                </div>

                <?php 

                    get_sidebar();

                ?>
            </div>

        </div>
    </div>
</main>