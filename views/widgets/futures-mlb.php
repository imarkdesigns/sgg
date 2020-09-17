<?php

// Get Parent Name
$leagueName = get_the_title( $post->post_parent );

// Include API Keys
include( locate_template( includes.'league-keys.php', false, true ) );

$season_request = wp_remote_get( 'https://api.sportsdata.io/v3/mlb/scores/json/CurrentSeason', $mlb_header_dak );
$season_body    = json_decode( wp_remote_retrieve_body( $season_request ) );

// Premium Odds
$bettingfutures_request = wp_remote_get( 'https://api.sportsdata.io/v3/mlb/odds/json/BettingFuturesBySeason/'.$season_body->Season.'', $mlb_header_opk );
$bettingfutures_body    = json_decode( wp_remote_retrieve_body( $bettingfutures_request ) );

foreach ( $bettingfutures_body as $bettingfutures ) {

    if ( $bettingfutures->BettingEventID == '43' ) {
        $futuresID   = $bettingfutures->BettingEventID;
    }

}

$bettingfutures_request = wp_remote_get( 'https://api.sportsdata.io/v3/mlb/odds/json/BettingMarkets/'.$futuresID.'', $mlb_header_opk );
$bettingmarkets_body = json_decode( wp_remote_retrieve_body( $bettingfutures_request ), true );
$bettingmarkets_body2 = json_decode( wp_remote_retrieve_body( $bettingfutures_request ) );

$team_request = wp_remote_get( 'https://api.sportsdata.io/v3/mlb/scores/json/teams', $mlb_header_dak );
$team_body    = json_decode( wp_remote_retrieve_body( $team_request ) );

// echo '<pre>';
// print_r($bettingmarketsEast_body[0]['AvailableSportsbooks']);
// echo '</pre>';

?>
<div class="uk-flex uk-flex-between uk-flex-middle">
    <h1 class="uk-card-title"><?php echo $leagueName .' '. get_the_title(); ?></h1>

    <select class="uk-select" name="futuresConference" style="max-width: 250px;">
        <option selected disabled>Choose Conference</option>
        <option value="championship-conference"> <?php echo $season_body->Description .' '. $leagueName; ?> Championship </option>
        <option value="eastern-conference"> <?php echo $season_body->Description .' '. $leagueName; ?> Eastern Conference </option>
        <option value="western-conference"> <?php echo $season_body->Description .' '. $leagueName; ?> Western Conference </option>
        <option value="mvp-conference"> <?php echo $season_body->Description .' '. $leagueName; ?> MVP </option>
    </select>
</div>

<div class="uk-position-relative">
    <table class="uk-table uk-table-responsive uk-table-divider">
        <thead>
            <tr>
                <td>Futures</td>
                <?php
                    $sportsbooks = $bettingmarkets_body[0]['AvailableSportsbooks'];

                    usort($sportsbooks, function($a, $b) {
                        return $a['Name'] <=> $b['Name'];
                    });

                    for ( $n = 0; $n <= count($sportsbooks); $n++ ) :
                        echo '<td>'. $sportsbooks[$n]['Name'] .'</td>';
                    endfor;
                ?>
            </tr>
        </thead>
        <tbody>
        <?php
            foreach ( $bettingmarkets_body2[0]->BettingOutcomes as $outcomeEast ) : 

            if ( $outcomeEast->TeamID != null ) : 

            foreach ( $team_body as $team ) {

                if ( $outcomeEast->TeamID != $team->TeamID )
                    continue;

                    $teamName = $team->Name;
                    $teamLogo = $team->WikipediaLogoUrl;

            }

            ?>
            <tr>
                <td>
                    <img src="<?php echo esc_url($teamLogo); ?>" class="uk-margin-small-right" width="36" height="24" alt="<?php echo $teamName; ?>">
                    <?php echo $teamName; ?>
                        
                </td>
                <td>
                <?php 
                    if ( $outcomeEast->SportsBook->Name == 'DraftKings' ) {
                        echo $outcomeEast->PayoutAmerican;
                    }
                ?>
                </td>
                <td>
                <?php
                    if ( $outcomeEast->SportsBook->Name == 'FanDuel' ) {
                        echo $outcomeEast->PayoutAmerican;
                    }
                ?>
                </td>
                <td>
                <?php 
                    if ( $outcomeEast->SportsBook->Name == 'Consensus' ) {
                        echo $outcomeEast->PayoutAmerican;
                    }
                ?>
                </td>
                <td>
                <?php 
                    if ( $outcomeEast->SportsBook->Name == 'PointsBet' ) {
                        echo $outcomeEast->PayoutAmerican;
                    }

                    
                ?>
                </td>
            </tr>
            <?php 
            endif;

        endforeach; ?>
        </tbody>

    </table>
</div>

