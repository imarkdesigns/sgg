<main id="main" class="main" role="main">
    <div class="uk-container uk-container-xlarge">
        <div class="uk-grid-small" uk-grid>

           <div class="uk-width-expand@l">    
                <div class="uk-card uk-card-default uk-card-body" data-card="future-odds">
                    <div class="uk-flex uk-flex-between uk-flex-middle">
                        <h1 class="uk-card-title"><?php echo get_the_title( $post->post_parent ) . ' Futures'; ?></h1>
                        <?php 
                        switch ( $post->post_parent ) { 
                            case '23':
                                do_action( 'nfl_market_options' );
                                break;
                            case '25':
                                do_action( 'nba_market_options' );
                                break;
                            case '27':
                                do_action( 'mlb_market_options' );
                                break;
                            default:
                                break;
                        }
                        ?>
                    </div>
                    <div class="uk-position-relative">
                        <div class="uk-overflow-auto">
                            <?php 
                            switch ( $post->post_parent ) { 
                                case '23':
                                    do_action( 'nfl_futures' );
                                    break;
                                case '25':
                                    do_action( 'nba_futures' );
                                    break;
                                case '27':
                                    do_action( 'mlb_futures' );
                                    break;
                                default:
                                    break;
                            }
                            ?>
                        </div>    
                    </div>
                </div>
            </div>

            <div class="uk-width-1-1 uk-width-large@l">
            <?php

                get_template_part( widget.'news' );
                get_template_part( widget.'guides' );
            
            ?>
            </div>

    </div>
</main>