<?php

// Get Parent Name
$leagueName = get_the_title( $post->post_parent );

// Include API Keys
include( locate_template( includes.'league-keys.php', false, true ) );

// Set Date
$currentDate  = date('Y-m-d');
$scheduleDate = '2020-07-30';

if ( strtotime($currentDate) > strtotime($scheduleDate) ) {
    $currentDate = date('Y-m-d');
} else {
    $currentDate = $scheduleDate;
}

// Premium Odds
$gameoddsbydate_request = wp_remote_get( 'https://api.sportsdata.io/v3/nba/odds/json/GameOddsByDate/'.$currentDate.'', $nba_header_opk );
$gameoddsbydate_body_json = wp_remote_retrieve_body( $gameoddsbydate_request );
$gameoddsbydate_body = json_decode($gameoddsbydate_body_json);

//$gameoddsbydate_body    = json_decode( wp_remote_retrieve_body( $gameoddsbydate_request ) );

// Tier 1 - Score/Teams
$team_request = wp_remote_get( 'https://api.sportsdata.io/v3/nba/scores/json/teams', $nba_header_dak );
$teams_json = wp_remote_retrieve_body( $team_request );
$team_body = json_decode( $teams_json );

$sportsbooks = [ 'DraftKings', 'FanDuel', 'Parx', 'BetRivers', 'Unibet' ];

?>

<script type="text/javascript">
// variables for saving the state of the page
var responseGameOdds = <?php echo $gameoddsbydate_body_json; ?>;
var teamsObj =  <?php echo $teams_json; ?>;
var teamsHashByID = {};
var currentDate = new Date("<?php echo $currentDate; ?>");
var oddsType = "Spread";

// League specific valiables
// change Url for each League
var apiLeagueUrl = "https://api.sportsdata.io/v3/nba/odds/json/GameOddsByDate/";
var headerValue = "14ab1b17eede492d8996908963d2ebbd";
</script>

<?php

    // include odds Ajax Scripts
    include "oddsAjaxInc.php";  

?>

<div uk-grid class="uk-flex-between uk-flex-middle uk-margin-bottom odds-locations">
    <div class="uk-width-expand@m">
        <ul class="uk-subnav uk-subnav-pill uk-subnav-divider odds-localnav" uk-margin>
            <li><a href="<?php echo esc_url( site_url('nfl/odds-betting-lines') ); ?>">NFL</a></li>
            <li class="uk-active"><a href="<?php echo esc_url( site_url('nba/odds-betting-lines') ); ?>">NBA</a></li>
            <li><a href="<?php echo esc_url( site_url('mlb/odds-betting-lines') ); ?>">MLB</a></li>
        </ul>
    </div>
    <div class="uk-width-auto@m">
        <?php /*
        <select class="uk-select" name="oddsLocation">
            <option selected disabled>Choose Betting Location</option>
            <option value="Pennsylvania">Pennsylvania</option>
        </select>
        */ ?>
        <div class="button-select-wrapper">
            <button type="button" class="uk-button uk-button-outline">Choose Betting Location</button>
            <div uk-dropdown="mode: click">
                <ul class="uk-nav uk-dropdown-nav">
                    <li><a rel="noopener">Pennsylvania</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="odds-filter">
    <div uk-grid class="uk-grid-small uk-child-width-1-1 uk-child-width-expand@m uk-light">
        <div class="odds-search">
          <div class="uk-search uk-search-default uk-width-1-1">
              <span uk-search-icon></span>
              <input type="search" id="searchOdds" class="uk-search-input" placeholder="Search..." onkeyup="searchTeam()">
          </div>  
        </div>
        <div class="odds-schedule">
            <div>
                <button type="button" onclick="updateDateChange(false);" class="uk-icon-link _prevDay" uk-icon="triangle-left"></button>
                <span id="dateOdds"></span>
                <button type="button" onclick="updateDateChange(true);" class="uk-icon-link _nextDay" uk-icon="triangle-right"></button>
            </div>
        </div>
        <div class="odds-type">      
            <select id="typeOdds" class="uk-select" name="typeOdds" onchange="updateOddsType(this);">
                <option selected disabled>Choose Odds Type</option>
                <option value="Spread">Spread</option>
                <option value="Total">Total</option>
                <option value="Moneyline">Moneyline</option>
            </select>
        </div>
    </div>
</div>

<div class="uk-position-relative">
    <div class="uk-overflow-auto">
        <!-- Start Table -->
        <table id="odds-list" class="uk-table uk-table-divider">
            
            <thead>
                <tr>
                    <th>
                        <div class="team-label">
                            <?php echo $leagueName; ?>
                        </div>
                    </th>
                    <th width="120"><span>Consensus</span></th>
                    <?php for ( $sb = 0; $sb < count($sportsbooks); $sb++ ) {

                        if ( $sportsbooks[$sb] == 'BetRivers' ) {
                            $url = 'https://wlsugarhouseaffiliates.adsrv.eacdn.com/C.ashx?btag=a_3320b_380c_&affid=947&siteid=3320&adid=380&c=';
                            $img = '<a href="'.$url.'" target="_blank"><img src="'._uri.'/resources/images/sportsbooks/betrivers.jpg" width="120" height="40" alt="BetRivers"></a>';
                            $sb1 = '<th width="120"><span>'.$img.'</span></th>';

                            echo $sb1;

                        } elseif ( $sportsbooks[$sb] == 'Unibet' ) {
                            $url = 'https://pa.unibet.com/sportsbook-welcome';
                            $img = '<a href="'.$url.'" target="_blank"><img src="'._uri.'/resources/images/sportsbooks/unibet.jpg" width="120" height="40" alt="UnibetNJ"></a>';
                            $sb2 = '<th width="120"><span>'.$img.'</span></th>';

                            echo $sb2; 

                        } else {
                            echo '<th width="120"><span>'. $sportsbooks[$sb] .'</span></th>';
                        }

                    } ?>
                </tr>
            </thead>

            <tbody id="odds-list-body">
                <?php foreach ( $gameoddsbydate_body as $gameodd ) : ?>
                <tr>
                    <td>
                        <div class="team-panel">
                        <?php foreach ( $team_body as $team ) {

                            if ( $gameodd->AwayTeamId != $team->TeamID )
                                continue;

                                $AwayTeamName = $team->Name;
                                $AwayTeamLogo = $team->WikipediaLogoUrl;

                            }

                            foreach ( $team_body as $team ) {

                                if ( $gameodd->HomeTeamId != $team->TeamID )
                                    continue;

                                    $HomeTeamName = $team->Name;
                                    $HomeTeamLogo = $team->WikipediaLogoUrl;

                            } ?>
                            <div class="uk-panel">
                                <div class="odds-away">
                                    <div class="odds-away-team">
                                        <img src="<?php echo esc_url($AwayTeamLogo); ?>" height="24" alt="<?php echo $AwayTeamName; ?>">
                                        <?php echo $AwayTeamName; ?>
                                    </div>
                                    <div class="odds-away-score"><?php echo ( $gameodd->AwayTeamScore ) ? $gameodd->AwayTeamScore : '' ; ?></div>
                                </div>
                                <div class="odds-home">
                                    <div class="odds-home-team">
                                        <img src="<?php echo esc_url($HomeTeamLogo); ?>" height="24" alt="<?php echo $HomeTeamName; ?>">
                                        <?php echo $HomeTeamName; ?>
                                    </div>
                                    <div class="odds-home-score"><?php echo ( $gameodd->HomeTeamScore ) ? $gameodd->HomeTeamScore : '' ; ?></div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="consensus-panel">
                    <?php foreach ( $gameodd->PregameOdds as $consensus ) {

                        if ( $consensus->Sportsbook != 'Consensus' )
                            continue;

                        $AwayPointSpread = $consensus->AwayPointSpread;
                        $HomePointSpread = $consensus->HomePointSpread;

                        } ?>
                        <div class="uk-panel">
                            <div class="odds-consensus">
                            <?php
                                if ( !empty($gameodd->PregameOdds) ) {
                                    if ( $AwayPointSpread < 0 ) {
                                        echo $AwayPointSpread;
                                    } else {
                                        echo '+'.$AwayPointSpread;
                                    }
                                }
                            ?>
                            </div>
                            <div class="odds-consensus">
                            <?php
                                if ( !empty($gameodd->PregameOdds) ) {
                                    if ( $HomePointSpread < 0 ) {
                                        echo $HomePointSpread;
                                    } else {
                                        echo '+'.$HomePointSpread;
                                    }
                                }
                            ?>
                            </div>
                        </div>
                    </td>
                    <?php if ( !empty($gameodd->PregameOdds) ) : 
                    foreach ( $gameodd->PregameOdds as $pregameodds ) :

                        if ( $pregameodds->Sportsbook == 'RiversCasinoPA' ) : ?>

                            <td class="sportsbook-panel">
                                <div class="uk-panel">
                                    <div class="odds-sb-bookline">
                                        <a href="https://wlsugarhouseaffiliates.adsrv.eacdn.com/C.ashx?btag=a_3320b_415c_&affid=947&siteid=3320&adid=415&c=" target="_blank">
                                            <span class="sb-bookline-extlink">
                                                <span>
                                                    <?php echo ($pregameodds->AwayPointSpread < 0) ? $pregameodds->AwayPointSpread : '+'.$pregameodds->AwayPointSpread; ?>
                                                    <small class="uk-margin-small-left"><?php echo $pregameodds->AwayPointSpreadPayout ?></small>
                                                </span>
                                                <span class="sb-extlink-hover">
                                                    <svg viewBox="0 0 24 24" width="15" height="15" xmlns="https://www.w3.org/2000/svg" class="" fill="#F7F8FD"><path d="M14,3V5H17.59L7.76,14.83L9.17,16.24L19,6.41V10H21V3M19,19H5V5H12V3H5C3.89,3 3,3.9 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19Z"></path></svg>
                                                    <span>Bet Now</span>
                                                </span>
                                            </span>
                                        </a>
                                    </div>
                                    <div class="odds-sb-bookline">
                                        <a href="https://wlsugarhouseaffiliates.adsrv.eacdn.com/C.ashx?btag=a_3320b_415c_&affid=947&siteid=3320&adid=415&c=" target="_blank">
                                            <span class="sb-bookline-extlink">
                                                <span>
                                                    <?php echo ($pregameodds->HomePointSpread < 0) ? $pregameodds->HomePointSpread : '+'.$pregameodds->HomePointSpread; ?>
                                                    <small class="uk-margin-small-left"><?php echo $pregameodds->HomePointSpreadPayout ?></small>
                                                </span>
                                                <span class="sb-extlink-hover">
                                                    <svg viewBox="0 0 24 24" width="15" height="15" xmlns="https://www.w3.org/2000/svg" class="" fill="#F7F8FD"><path d="M14,3V5H17.59L7.76,14.83L9.17,16.24L19,6.41V10H21V3M19,19H5V5H12V3H5C3.89,3 3,3.9 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19Z"></path></svg>
                                                    <span>Bet Now</span>
                                                </span>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </td>

                        <?php elseif ( $pregameodds->Sportsbook == 'UnibetNJ' ) : ?>

                            <td class="sportsbook-panel">
                                <div class="uk-panel">
                                    <div class="odds-sb-bookline">
                                        <a href="https://wlkindred.adsrv.eacdn.com/C.ashx?btag=a_783b_150c_&affid=195&siteid=783&adid=150&c=" target="_blank">
                                            <span class="sb-bookline-extlink">
                                                <span>
                                                    <?php echo ($pregameodds->AwayPointSpread < 0) ? $pregameodds->AwayPointSpread : '+'.$pregameodds->AwayPointSpread; ?>
                                                    <small class="uk-margin-small-left"><?php echo $pregameodds->AwayPointSpreadPayout ?></small>
                                                </span>
                                                <span class="sb-extlink-hover">
                                                    <svg viewBox="0 0 24 24" width="15" height="15" xmlns="https://www.w3.org/2000/svg" class="" fill="#F7F8FD"><path d="M14,3V5H17.59L7.76,14.83L9.17,16.24L19,6.41V10H21V3M19,19H5V5H12V3H5C3.89,3 3,3.9 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19Z"></path></svg>
                                                    <span>Bet Now</span>
                                                </span>
                                            </span>
                                        </a>
                                    </div>
                                    <div class="odds-sb-bookline">
                                        <a href="https://wlkindred.adsrv.eacdn.com/C.ashx?btag=a_783b_150c_&affid=195&siteid=783&adid=150&c=" target="_blank">
                                            <span class="sb-bookline-extlink">
                                                <span>
                                                    <?php echo ($pregameodds->HomePointSpread < 0) ? $pregameodds->HomePointSpread : '+'.$pregameodds->HomePointSpread; ?>
                                                    <small class="uk-margin-small-left"><?php echo $pregameodds->HomePointSpreadPayout ?></small>
                                                </span>
                                                <span class="sb-extlink-hover">
                                                    <svg viewBox="0 0 24 24" width="15" height="15" xmlns="https://www.w3.org/2000/svg" class="" fill="#F7F8FD"><path d="M14,3V5H17.59L7.76,14.83L9.17,16.24L19,6.41V10H21V3M19,19H5V5H12V3H5C3.89,3 3,3.9 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19Z"></path></svg>
                                                    <span>Bet Now</span>
                                                </span>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </td>

                        <?php else :

                        preg_match( '/888|PointsBet|Consensus|SugarHouse|MGM/', $pregameodds->Sportsbook, $matches );
                        if ( empty($matches) ) : ?>
                        <td class="sportsbook-panel">
                            <div class="uk-panel">
                                <div class="odds-sb-bookline">
                                    <a>
                                        <span class="sb-bookline-extlink">
                                            <span>
                                                <?php echo ($pregameodds->AwayPointSpread < 0) ? $pregameodds->AwayPointSpread : '+'.$pregameodds->AwayPointSpread; ?>
                                                <small class="uk-margin-small-left"><?php echo $pregameodds->AwayPointSpreadPayout ?></small>
                                            </span>
                                            <span class="sb-extlink-hover" hidden>
                                                <svg viewBox="0 0 24 24" width="15" height="15" xmlns="https://www.w3.org/2000/svg" class="" fill="#F7F8FD"><path d="M14,3V5H17.59L7.76,14.83L9.17,16.24L19,6.41V10H21V3M19,19H5V5H12V3H5C3.89,3 3,3.9 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19Z"></path></svg>
                                                <span>Bet Now</span>
                                            </span>
                                        </span>
                                    </a>
                                </div>
                                <div class="odds-sb-bookline">
                                    <a>
                                        <span class="sb-bookline-extlink">
                                            <span>
                                                <?php echo ($pregameodds->HomePointSpread < 0) ? $pregameodds->HomePointSpread : '+'.$pregameodds->HomePointSpread; ?>
                                                <small class="uk-margin-small-left"><?php echo $pregameodds->HomePointSpreadPayout ?></small>
                                            </span>
                                            <span class="sb-extlink-hover" hidden>
                                                <svg viewBox="0 0 24 24" width="15" height="15" xmlns="https://www.w3.org/2000/svg" class="" fill="#F7F8FD"><path d="M14,3V5H17.59L7.76,14.83L9.17,16.24L19,6.41V10H21V3M19,19H5V5H12V3H5C3.89,3 3,3.9 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19Z"></path></svg>
                                                <span>Bet Now</span>
                                            </span>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </td>                      
                        <?php endif; // End preg_match();
                            
                        endif; // End if BetRivers
                            
                    endforeach; // End foreach();

                    else : 
                        for ( $sb = 0; $sb < count($sportsbooks); $sb++ ) :?>
                        <td class="sportsbook-panel">
                            <div class="uk-panel">
                                <div class="odds-sb-bookline">
                                    <div class="uk-background-muted sb-bookline-extlink">
                                        <span class="uk-text-muted uk-text-small">N/A</span>
                                    </div>
                                </div>
                                <div class="odds-sb-bookline">
                                    <div class="uk-background-muted sb-bookline-extlink">
                                        <span class="uk-text-muted uk-text-small">N/A</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <?php endfor;
                    endif; ?>
                </tr>
                <tr class="schedule-row">
                    <td colspan="1" class="schedule-panel">
                    <?php
                        $date_set = strtotime($gameodd->DateTime);
                        $date_set = date('D n/d, g:i A', $date_set);

                        echo '<div>'. $date_set .'| Status: '. $gameodd->Status .'</div>';
                    ?>
                    </td>
                    <td colspan="6">
                        <div>&nbsp;</div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
        <!-- End Table -->
    </div>
</div>