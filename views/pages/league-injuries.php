<main id="main" class="main" role="main">
    <div class="uk-container uk-container-xlarge">
        <div class="uk-grid-small" uk-grid>

            <div class="uk-width-expand@l">
                <?php
                    // Get Post Name
                    $leagueName = get_the_title( $post->post_parent );

                    // Include API Keys
                    include( locate_template( includes.'league-keys.php', false, true ) );

                    switch ($leagueName) {
                        case 'NFL':
                            $header_npk = $nfl_header_npk;
                            break;
                        
                        case 'MLB':
                            $header_npk = $mlb_header_npk;
                            break;

                        case 'NBA':
                            $header_npk = $nba_header_npk;
                            break;
                    }

                    // Premium News
                    $news_request = wp_remote_get( 'https://api.sportsdata.io/v3/'.strtolower($leagueName).'/news-rotoballer/json/RotoBallerPremiumNews', $header_npk );
                    $news_body    = json_decode( wp_remote_retrieve_body( $news_request ) );

                    // Trial - Score/Players
                    $player_request = wp_remote_get( 'https://api.sportsdata.io/v3/'.strtolower($leagueName).'/scores/json/players', $header_atk );
                    $player_body    = json_decode( wp_remote_retrieve_body( $player_request ) );

                    // Widget Images
                    $images = get_field($widget, 'option');
                    $images = json_decode (json_encode ($images), FALSE);

                    // echo '<pre>';
                    // print_r($player_request);
                    // echo '</pre>';
                    // die();

                ?>
                <div class="uk-card uk-card-default uk-card-body" data-card="league-news">
                    <h1 class="uk-card-title"><?php echo $leagueName; ?> Injuries Report</h1>

                    <div uk-grid class="uk-grid-divider uk-child-width-1-1">
                    <?php foreach ( $news_body as $news ) : 

                        if ( preg_match('/Injuries/i', $news->Categories) ) :

                            foreach ( $player_body as $player ) {

                                if ( $news->PlayerID != $player->PlayerID )
                                    continue;

                                    $avatar = $player->PhotoUrl;
                                    $fname = $player->FirstName;
                                    $lname = $player->LastName;

                            } ?>
                            <article class="uk-comment">
                                <header class="uk-comment-header">
                                    <div class="uk-grid-medium uk-flex-middle" uk-grid>
                                        <div class="uk-width-expand">
                                            <h4 class="uk-comment-title uk-margin-remove"><?php echo $news->Title; ?></h4>
                                            <ul class="uk-comment-meta uk-subnav uk-subnav-divider uk-margin-remove-top">
                                                <li>
                                                <?php  
                                                    $time = date_create($news->Updated);
                                                    $time = date_format($time, 'D, F j, Y');

                                                    echo $time;
                                                ?>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </header>
                                <div class="uk-comment-body">
                                    <p><?php echo $news->Content; ?></p>
                                </div>
                            </article>  

                        <?php endif;
                    endforeach; ?>
                    </div>
                </div>


            </div>

            <div class="uk-width-1-1 uk-width-large@l">
            <!-- Start Content -->
            <?php 

                get_template_part( widget.'news' );
                get_template_part( widget.'guides' );

            ?>
            <!-- End Content -->
            </div>

        </div>
    </div>
</main>