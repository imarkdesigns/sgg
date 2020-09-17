<main id="main" class="main" role="main">
    <div class="uk-container uk-container-xlarge">
        <div class="uk-container uk-container-xlarge">
            <div class="uk-card uk-card-default uk-card-body" data-card="odds-ats">
                <div class="uk-flex uk-flex-between uk-flex-middle">
                    <h1 class="uk-card-title"><?php echo get_the_title( $post->post_parent ) . ' Against the Spread'; ?></h1>
                </div>
                <div class="uk-position-relative">
                    <div class="uk-overflow-auto">
                        <?php 
                        switch ( $post->post_parent ) { 
                            case '23':
                                do_action( 'nfl_against_the_spread' );
                                break;
                            case '25':
                                do_action( 'nba_against_the_spread' );
                                break;
                            case '27':
                                do_action( 'mlb_against_the_spread' );
                                break;
                            default:
                                break;
                        }
                        ?>
                    </div>    
                </div>
            </div>
        </div>
    </div>
</main>