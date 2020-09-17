<?php

// Get Parent Name
$leagueName = get_the_title( $post->post_parent );

// Include API Keys
include( locate_template( includes.'league-keys.php', false, true ) );

$season_request = wp_remote_get( 'https://api.sportsdata.io/v3/nba/scores/json/CurrentSeason', $nba_header_dak );
$season_body    = json_decode( wp_remote_retrieve_body( $season_request ) );

// Premium Odds
$bettingfutures_request = wp_remote_get( 'https://api.sportsdata.io/v3/nba/odds/json/BettingFuturesBySeason/'.$season_body->Season.'', $nba_header_opk );
$bettingfutures_body    = json_decode( wp_remote_retrieve_body( $bettingfutures_request ) );

foreach ( $bettingfutures_body as $bettingfutures ) {

    if ( $bettingfutures->BettingEventID == '172' ) {
        $futuresIDEast   = $bettingfutures->BettingEventID;
    }

}

$bettingfuturesEast_request = wp_remote_get( 'https://api.sportsdata.io/v3/nba/odds/json/BettingMarkets/'.$futuresIDEast.'', $nba_header_opk );
$bettingmarketsEast_body = json_decode( wp_remote_retrieve_body( $bettingfuturesEast_request ), true );
$bettingmarketsEast_body2 = json_decode( wp_remote_retrieve_body( $bettingfuturesEast_request ) );

$team_request = wp_remote_get( 'https://api.sportsdata.io/v3/nba/scores/json/teams', $nba_header_dak );
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
                <td>Consensus</td>
                <td>DraftKings</td>
                <td>FanDuel</td>
                <td>PointsBet</td>
            </tr>
        </thead>
        <tbody>

        </tbody>

    </table>
</div>

