<main id="main" class="main" role="main">
    <div class="uk-container uk-container-xlarge">
        <div class="uk-grid-small" uk-grid>

            <div class="uk-width-expand@l">
                <div class="uk-card uk-card-default uk-card-body" data-card="content">
                    <h1 class="uk-card-title"><?php the_title(); ?></h1>

                    <article class="uk-article">
                    <?php if ( is_page([ 3, 17, 19 ]) ) :
                        the_content();
                    endif; ?>
                    </article>
                </div>
            </div>

            <div class="uk-width-1-1 uk-width-large@l">
            <?php

                get_template_part( widget.'news' );
                get_template_part( widget.'guides' );
            
            ?>                
            </div>

        </div>
    </div>
</main>