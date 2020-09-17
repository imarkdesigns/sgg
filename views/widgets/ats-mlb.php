<?php

// Get Parent Name
$leagueName = get_the_title( $post->post_parent );

// Include API Keys
include( locate_template( includes.'league-keys.php', false, true ) ); 

// Dev API Unlimited
$season_request = wp_remote_get( 'https://api.sportsdata.io/v3/mlb/scores/json/CurrentSeason', $mlb_header_dak );
$season_body    = json_decode( wp_remote_retrieve_body( $season_request ) );

$standing_request = wp_remote_get( 'https://api.sportsdata.io/v3/mlb/scores/json/Standings/'.$season_body->Season.'',  $mlb_header_dak );
$standing_body    = json_decode( wp_remote_retrieve_body( $standing_request ) );

$team_request = wp_remote_get( 'https://api.sportsdata.io/v3/mlb/scores/json/teams', $mlb_header_dak );
$team_body    = json_decode( wp_remote_retrieve_body( $team_request ) );

// echo '<pre>';
// print_r($team_body);
// echo '</pre>';

?>

<div class="uk-flex uk-flex-between uk-flex-middle">
    <h1 class="uk-card-title"><?php echo $leagueName .' Against the Spread'; ?></h1>
</div>

<div class="uk-position-relative">
    <div class="uk-overflow-auto">
        <!-- Start Table -->

        <table class="uk-table uk-table-divider">
            <thead>
                <tr>
                    <th class="team-label">Team</th>
                    <th>Overall</th>
                    <th>Home</th>
                    <th>Away</th>
                    <th><small>Last 10 Games</small> ATS Home</th>
                    <th><small>Last 10 Games</small> ATS Away</th>
                    <th><small>Last 10 Games</small> OV/UN Home</th>
                    <th><small>Last 10 Games</small> OV/UN Away</th>
                </tr>
            </thead>
            <tbody>
            <?php if ( $team_body ) :

                foreach ( $team_body as $team ) : 
                    
                    // Standings
                    foreach ( $standing_body as $standing ) {

                        if ( $standing->TeamID != $team->TeamID )
                            continue;

                            $totalWins      = $standing->Wins;
                            $totalLosses    = $standing->Losses;
                            $homeWins       = $standing->HomeWins;
                            $homeLosses     = $standing->HomeLosses;
                            $awayWins       = $standing->AwayWins;
                            $awayLosses     = $standing->AwayLosses;

                    } ?>
                    <tr>
                        <td class="team-panel">
                        <?php 
                            echo '<img src="'.$team->WikipediaLogoUrl.'" alt="'.$team->City.' '.$team->Name .'">';
                            echo $team->Name; 
                        ?>
                        </td>
                        <td><?php echo $totalWins.'-'.$totalLosses; ?></td>
                        <td><?php echo $homeWins.'-'.$homeLosses; ?></td>
                        <td><?php echo $awayWins.'-'.$awayLosses; ?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <?php 

                endforeach; 

            endif; ?>
            </tbody>

        </table>
        
        <!-- End Table -->
    </div>    
</div>

