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
$gameoddsbydate_request = wp_remote_get( 'https://api.sportsdata.io/v3/mlb/odds/json/GameOddsByDate/'.$currentDate.'', $mlb_header_opk );
$gameoddsbydate_body_json = wp_remote_retrieve_body( $gameoddsbydate_request );
$gameoddsbydate_body = json_decode($gameoddsbydate_body_json);

// Tier 1 - Score/Teams
$team_request = wp_remote_get( 'https://api.sportsdata.io/v3/mlb/scores/json/teams', $mlb_header_dak );
$teams_json = wp_remote_retrieve_body( $team_request );
$team_body = json_decode( $teams_json );

$sportsbooks = [ 
    [ 
        'id' => 'RiversCasinoPA',
        'display' => 'BetRivers',
        'link' => 'https://wlsugarhouseaffiliates.adsrv.eacdn.com/C.ashx?btag=a_3320b_380c_&affid=947&siteid=3320&adid=380&c=',
        'badge' => _uri . '/resources/images/sportsbooks/betrivers.jpg'
    ], 
    [ 
        'id' => 'UnibetNJ',
        'display' => 'Unibet',
        'link' => 'https://wlkindred.adsrv.eacdn.com/C.ashx?btag=a_783b_150c_&affid=195&siteid=783&adid=150&c=',
        'badge' => _uri . '/resources/images/sportsbooks/unibet.jpg'
    ],
    [ 'id' => 'DraftKings', 'display' => 'DraftKings' ], 
    [ 'id' => 'FanDuel', 'display' => 'FanDuel' ], 
    [ 'id' => 'ParxPA', 'display' => 'Parx' ] 
];

$available = [];

if ( isset( $gameoddsbydate_body ) && count( $gameoddsbydate_body ) > 0 ) {
    foreach ( $gameoddsbydate_body as $single ) {
        if ( isset( $single->PregameOdds ) && 
            is_array( $single->PregameOdds ) &&
            count( $single->PregameOdds ) > 0 )  {
            $odds = $single->PregameOdds;
            if ( is_array( $odds ) && count ( $odds ) > 0 ) {
                foreach ( $odds as $odd ) {
                    if ( ! in_array( $odd->Sportsbook, $available ) ) {
                        $available[] = $odd->Sportsbook;
                    }
                }
            }
        }
    }
}

function getSportsbookById( $needle, $haystack ) {
    for ( $i = 0; $i < count( $haystack ); $i++ ) {
        if ( $haystack[ $i ] === $needle ) {
            return $haystack[ $i ];
        }
    }
}

function bookline( $spread, $payout, $link, $sportsbook ) {
?>
<div class="odds-sb-bookline">
    <a<?php echo ($link !== '') ? ' href="' . $link . '"' : ''; ?>>
        <span class="sb-bookline-extlink">
            <span>
                <?php echo ($spread < 0) ? $spread : '+'.$spread; ?>
                <small class="uk-margin-small-left"><?php echo $payout ?></small>
            </span>
            <span class="sb-extlink-hover" hidden>
                <svg viewBox="0 0 24 24" width="15" height="15" xmlns="https://www.w3.org/2000/svg" class="" fill="#F7F8FD"><path d="M14,3V5H17.59L7.76,14.83L9.17,16.24L19,6.41V10H21V3M19,19H5V5H12V3H5C3.89,3 3,3.9 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19Z"></path></svg>
                <span>Bet Now</span>
            </span>
        </span>
    </a>
</div>
<?php } 

function naBookline() {
?>
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
<?php
}

function sportsbookPanel( $odds, $sportsbook ) { 
    $link = ( isset( $sportsbook['link'] ) ) ? $sportsbook['link'] : '';
?>
<td class="sportsbook-panel">
    <div class="uk-panel">
        <?php bookline( $odds->AwayPointSpread, $odds->AwayPointSpreadPayout, $link, $sportsbook ); ?>
        <?php bookline( $odds->HomePointSpread, $odds->HomePointSpreadPayout, $link, $sportsbook ); ?>
    </div>
</td>
<?php 
}

function consensusOdds( $spread ) {
?>
    <div class="odds-consensus">
        <?php echo ( $spread > 0 ) ? '+' . $spread : $spread; ?>
    </div>
<?php
}

function consensusPanel( $single ) {
?>
<div class="uk-panel">
    <?php consensusOdds( $single->AwayPointSpread ); ?>
    <?php consensusOdds( $single->HomePointSpread ); ?>
</div>
<?php
}
?>

<script type="text/javascript">
// variables for saving the state of the page
var responseGameOdds = <?php echo $gameoddsbydate_body_json; ?>;
var teamsObj = <?php echo $teams_json; ?>;
var teamsHashByID = {};
var currentDate = new Date("<?php echo $currentDate; ?>");
var oddsType = "Spread";

// League specific valiables
// change Url for each League
var apiLeagueUrl = "https://api.sportsdata.io/v3/mlb/odds/json/GameOddsByDate/";
var headerValue = "b426343c15c843c3ab56930d2a919e2c";
</script>

<?php

    // include odds Ajax Scripts
    include "oddsAjaxInc.php";  

?>

<div uk-grid class="uk-flex-between uk-flex-middle uk-margin-bottom odds-locations">
    <div class="uk-width-expand@m">
        <ul class="uk-subnav uk-subnav-pill uk-subnav-divider odds-localnav" uk-margin>
            <li><a href="<?php echo esc_url( site_url('nfl/odds-betting-lines') ); ?>">NFL</a></li>
            <li><a href="<?php echo esc_url( site_url('nba/odds-betting-lines') ); ?>">NBA</a></li>
            <li class="uk-active"><a href="<?php echo esc_url( site_url('mlb/odds-betting-lines') ); ?>">MLB</a></li>
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
                    <?php foreach ( $sportsbooks as $sportsbookHeading ) : 
                        // if ( in_array( $sportsbookHeading['id'], $available ) ) : ?>
                        <th width="120">
                            <span>
                                <?php if ( isset( $sportsbookHeading['link'] ) ) : ?>
                                    <a href="<?php echo $sportsbookHeading['link']; ?>" target="_blank">
                                <?php endif; ?>
                                    
                                    <?php if ( isset( $sportsbookHeading['badge'] ) ) : ?>
                                        <img src="<?php echo $sportsbookHeading['badge']; ?>" width="120" height="40" alt="<?php echo $sportsbookHeading['display'] ?>">
                                    <?php else : ?>
                                        <?php echo $sportsbookHeading['display']; ?>
                                    <?php endif; ?>

                                <?php if ( isset( $sportsbookHeading['link'] ) ) : ?>
                                    </a>
                                <?php endif; ?>
                            </span>
                        </th>
                        <?php // endif;
                    endforeach; ?>
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
                        <?php if ( ! empty( $gameodd->PregameOdds ) ) : 
                            foreach ( $gameodd->PregameOdds as $single ) :
                                if ( $single->Sportsbook === 'Consensus' ) :
                                    consensusPanel( $single );
                                    break;
                                endif;
                            endforeach;
                        endif; ?>
                    </td>

                    <?php 
                    if ( ! empty( $gameodd->PregameOdds ) ) : 
                        foreach ( $sportsbooks as $sportbookItem ) :
                            $found = false;
                            foreach ( $gameodd->PregameOdds as $odds) :
                                if ( $odds->Sportsbook === $sportsbookItem['id'] ) :
                                    $found = true;
                                    sportsbookPanel( $odds, $sportsbookItem );
                                endif;
                            endforeach;
                            if ( ! $found ) {
                                naBookline();
                            }
                        endforeach;
                    
                    else : 
                        foreach ( $sportsbooks as $sportsbookTemp ) : 
                            if ( in_array( $sportsbookTemp['id'], $available ) ) {
                                naBookline();
                            }
                        endforeach;
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