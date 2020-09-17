<div class="uk-card uk-card-default uk-card-body" data-card="league-article">
    <article class="uk-article uk-margin-bottom">
        <?php
            // Get all parameters
            $newsID = get_query_var( 'newsID' );
            $imgID  = get_query_var( 'imgID' );

            // Get category
            $leagueName = single_cat_title("", false);

            // Include API Keys
            include( locate_template( includes.'league-keys.php', false, true ) );

            switch ($leagueName) {
                case 'NFL':
                    $header_npk = $nfl_header_npk;
                    $header_dak = $nfl_header_dak;
                    break;
                
                case 'MLB':
                    $header_npk = $mlb_header_npk;
                    $header_dak = $mlb_header_dak;
                    break;

                case 'NBA':
                    $header_npk = $nba_header_npk;
                    $header_dak = $nba_header_dak;
                    break;
            }

            // Premium News
            $news_request = wp_remote_get( 'https://api.sportsdata.io/v3/'.strtolower($leagueName).'/news-rotoballer/json/RotoBallerPremiumNews', $header_npk );
            $news_body    = json_decode( wp_remote_retrieve_body( $news_request ) );

            // Trial - Score/Teams
            $team_request = wp_remote_get( 'https://api.sportsdata.io/v3/'.strtolower($leagueName).'/scores/json/teams', $header_dak );
            $team_body    = json_decode( wp_remote_retrieve_body( $team_request ) );

            // echo '<pre>';
            // print_r($news_body);
            // echo '</pre>';
            // die();

            if ( wp_remote_retrieve_response_code( $news_request ) == 200 ) :

                foreach ( $news_body as $news ) :

                    if ( $news->NewsID != $newsID )
                        continue;

                        $newsTeamID  = $news->TeamID;
                        $newsTitle   = $news->Title;
                        $newsContent = $news->Content;
                        $newsDate    = $news->Updated;

                    foreach ( $team_body as $team ) {

                        if ( $team->TeamID != $newsTeamID )
                            continue;

                        $teamName    = $team->Name;
                        $teamCity    = $team->City;
                        $teamFName   = $team->FullName;
                        $teamLogo    = $team->WikipediaLogoUrl;
                        $teamColor   = $team->PrimaryColor;

                    }

                endforeach; 
        ?>
        <figure>
            <?php echo wp_get_attachment_image( $imgID, 'full' ); ?>
            <figcaption style="background-color:<?php echo '#'.$teamColor; ?>;">
                <span>
                    <?php echo !empty($teamFName) ? $teamFName : $teamCity .' '. $teamName ; ?>
                </span>
            </figcaption>
        </figure>
        <h1><?php echo $newsTitle; ?></h1>
        <p><?php echo $newsContent; ?></p>
        <div class="uk-text-meta">
            <span>
                <?php echo $leagueName; ?>
            </span>
            <span>&#x25cf</span>
            <span>
                <?php  
                    $time = date_create($newsDate);
                    $time = date_format($time, 'D, F j, Y');

                    echo $time;
                ?>
            </span>
        </div>
        <?php endif; ?>

    </article>
</div>