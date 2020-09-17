<main id="main" class="main" role="main">
    <div class="uk-container uk-container-xlarge">
        <div class="uk-grid-small" uk-grid>

            <div class="uk-width-expand@l">
            <!-- Start Content -->
                
                <div class="uk-card uk-card-default uk-card-body" data-card="contact">
                    <h3 class="uk-card-title"><?php the_title(); ?></h3>
                    
                    <?php echo do_shortcode( get_field('wpforms_shortcode') ); ?>
                </div>
            
            <!-- End Content -->
            </div>

            <div class="uk-width-1-1 uk-width-large@l">
            <?php 

                if ( get_query_var( 'success' ) == 'true' && ! empty( get_query_var( 'success' ) ) ) :
                    ?>
                    <div class="uk-card uk-card-primary uk-card-body" data-card="content">
                        
                        <?php the_field('wpforms_response'); ?>

                    </div>
                    <?php
                endif;

            ?>
            </div>

        </div>
    </div>
</main>