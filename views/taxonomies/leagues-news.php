<main id="main" class="main" role="main">
    <div class="uk-container uk-container-xlarge">
        <div uk-grid class="uk-grid-small">

            <div class="uk-width-expand@l">
            <!-- Start Content -->
            <?php 
                // views/taxonomies
                get_template_part( widget.'news-article' );
                
                // viewws/widgets
                get_template_part( widget.'sportsbooks' ); 

            ?>
            <!-- End Content -->
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