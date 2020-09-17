<div class="uk-card uk-card-default uk-card-body" data-card="news">
    <h1 class="uk-card-title"><?php the_field('widget_title_news', 'option'); ?></h1>
    
    <ul uk-tab="animation: uk-animation-fade">
        <li><a href="#">NFL</a></li>
        <li><a href="#">NBA</a></li>
        <li><a href="#">MLB</a></li>
    </ul>

    <div class="uk-switcher uk-margin">
        <?php
            // Get category
            $league = ['NFL', 'NBA', 'MLB'];

            // Include API Keys
            include( locate_template( includes.'league-keys.php', false, true ) );

            for ( $cat = 0; $cat <= 2; $cat++ ) :

            switch ($league[$cat]) {
                case 'NFL':
                    $header_npk = $nfl_header_npk;
                    $header_dak = $nfl_header_dak;
                    $widget     = 'widget_gallery_nflnews';
                    break;
                
                case 'MLB':
                    $header_npk = $mlb_header_npk;
                    $header_dak = $mlb_header_dak;
                    $widget     = 'widget_gallery_mlbnews';
                    break;

                case 'NBA':
                    $header_npk = $nba_header_npk;
                    $header_dak = $nba_header_dak;
                    $widget     = 'widget_gallery_nbanews';
                    break;
            }

            // Premium News
            $news_request = wp_remote_get( 'https://api.sportsdata.io/v3/'.strtolower($league[$cat]).'/news-rotoballer/json/RotoBallerPremiumNews', $header_npk );
            $news_body    = json_decode( wp_remote_retrieve_body( $news_request ) );

            // Tier 1 - Score/Teams
            $team_request = wp_remote_get( 'https://api.sportsdata.io/v3/'.strtolower($league[$cat]).'/scores/json/teams', $header_dak );
            $team_body    = json_decode( wp_remote_retrieve_body( $team_request ) );

            // Widget Images
            $images = get_field($widget, 'option');
            $images = json_decode (json_encode ($images), FALSE);

            // echo '<pre>';
            // print_r($team_body);
            // echo '</pre>';
            // die();

        ?>
        <div>
            <ul class="news-lists">
                <?php foreach ( array_slice($news_body, 0, 5) as $news ) : 

                    foreach ( $team_body as $team ) {

                        if ( $team->TeamID != $news->TeamID )
                            continue;

                            $teamName  = $team->Name;
                            $teamCity  = $team->City;
                            $teamFName = $team->FullName;
                            $teamLogo  = $team->WikipediaLogoUrl;
                            $teamColor = $team->PrimaryColor;

                    }

                    foreach ( $images as $image ) {

                        if ( in_array( $teamName, explode(", ", $image->caption) ) ) {
                            $imgID = $image->id;
                        } 

                        if ( $teamName == $image->title ) {
                            $imgID = $image->id;
                        }

                } ?>
                <li class="uk-grid-collapse uk-flex-middle" uk-grid>
                    <div class="uk-width-auto">
                        <div style="border-bottom:5px solid #<?php echo $teamColor; ?>">
                            <a href="<?php echo esc_html( site_url('/article/'.strtolower($league[$cat]).'?newsID=') . $news->NewsID .'&imgID=' . $imgID ); ?>" title="<?php echo $news->Title; ?>">
                                <?php echo wp_get_attachment_image( $imgID, [ 60, 60, true ] ); ?>
                            </a>
                        </div>
                    </div>
                    <div class="uk-width-expand">
                        <div class="uk-panel">
                            <?php  
                                $time = date_create($news->Updated);
                                $time = date_format($time, 'D, F j, Y');
                            ?>                            
                            <small><?php echo $league[$cat] .' <span>&#x25cf</span> '. $time; ?></small>
                            <h1><?php echo $teamCity.' '.$teamName; ?></h1>
                            <h4><a href="<?php echo esc_html( site_url('/article/'.strtolower($league[$cat]).'?newsID=') . $news->NewsID .'&imgID=' . $imgID ); ?>"><?php echo $news->Title; ?></a></h4>
                        </div>
                    </div>
                </li>
                <?php endforeach; ?>
                <li class="uk-margin-top uk-border-remove">
                    <a href="<?php echo esc_url( site_url( strtolower($league[$cat]).'/news' ) ); ?>" class="uk-button uk-button-primary uk-button-small">View All <?php echo $league[$cat]; ?> News</a>
                </li>
            </ul>

        </div>
        <?php endfor; ?>
    </div>
</div>