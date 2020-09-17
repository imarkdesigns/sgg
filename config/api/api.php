<?php
define( 'SPORTS_DATA_IO_KEY', 'aebf9d3f48774f94a9e31e228c57a15c' );
define( 'SPORTS_DATA_IO_ODDS_NBA_KEY', '14ab1b17eede492d8996908963d2ebbd' );
define( 'SPORTS_DATA_IO_ODDS_NFL_KEY', '2a2e46fcc4504134aadced092416ba1e' );
define( 'SPORTS_DATA_IO_ODDS_MLB_KEY', 'b426343c15c843c3ab56930d2a919e2c' );
define( 'SPORTS_DATA_IO_ALLOW_CONSENSUS_ONLY', false );
define( 'SPORTS_DATA_IO_NBA_DEFAULT_MARKET_ID', 2358 );
define( 'SPORTS_DATA_IO_NFL_DEFAULT_MARKET_ID', 447 );
define( 'SPORTS_DATA_IO_MLB_DEFAULT_MARKET_ID', 70 );

function get_sports_data_io_headers() {
  return array( 'headers' => array( 'Ocp-Apim-Subscription-Key' => SPORTS_DATA_IO_KEY ) );
}

function get_sports_data_io_odds_nba_headers() {
  return array( 'headers' => array( 'Ocp-Apim-Subscription-Key' => SPORTS_DATA_IO_ODDS_NBA_KEY ) );
}

function get_sports_data_io_odds_nfl_headers() {
  return array( 'headers' => array( 'Ocp-Apim-Subscription-Key' => SPORTS_DATA_IO_ODDS_NFL_KEY ) );
}

function get_sports_data_io_odds_mlb_headers() {
  return array( 'headers' => array( 'Ocp-Apim-Subscription-Key' => SPORTS_DATA_IO_ODDS_MLB_KEY ) );
}

function get_current_season( $league ) {
  $season = json_decode( 
    wp_remote_retrieve_body( 
      wp_remote_get( 
        'https://api.sportsdata.io/v3/' . $league . '/scores/json/CurrentSeason', 
        get_sports_data_io_headers()
      ) 
    ) 
  );
  if ( gettype($season) === 'integer' ) {
    return $season;
  } else {
    return  $season->ApiSeason;
  }
}

function get_standings( $league, $season ) {
  return json_decode( 
    wp_remote_retrieve_body( 
      wp_remote_get(
        'https://api.sportsdata.io/v3/' . $league . '/scores/json/Standings/' . $season, 
        get_sports_data_io_headers()
      )
    ) 
  );
}

function get_betting_futures( $league, $season ) {
  $headers = array();
  switch ( $league ) {
    case 'nba': $headers = get_sports_data_io_odds_nba_headers(); break;
    case 'nfl': $headers = get_sports_data_io_odds_nfl_headers(); break;
    case 'mlb': $headers = get_sports_data_io_odds_mlb_headers(); break;
  }

  return json_decode( 
    wp_remote_retrieve_body(
      wp_remote_get( 
        'https://api.sportsdata.io/v3/' . $league . '/odds/json/BettingFuturesBySeason/' . $season,
        $headers
      )
    )
  );
}

function get_betting_market( $league, $market_id ) {
  $headers = array();
  switch ( $league ) {
    case 'nba': $headers = get_sports_data_io_odds_nba_headers(); break;
    case 'nfl': $headers = get_sports_data_io_odds_nfl_headers(); break;
    case 'mlb': $headers = get_sports_data_io_odds_mlb_headers(); break;
  }
  return json_decode( 
    wp_remote_retrieve_body(
      wp_remote_get( 
        'https://api.sportsdata.io/v3/' . $league . '/odds/json/BettingMarket/' . $market_id,
        $headers
      )
    )
  );
}

function get_teams( $league ) {
  return json_decode( 
    wp_remote_retrieve_body( 
      wp_remote_get(
        'https://api.sportsdata.io/v3/' . $league . '/scores/json/teams', 
        get_sports_data_io_headers()
      )
    )
  );
}

function get_player( $league, $player ) {
  return json_decode( 
    wp_remote_retrieve_body( 
      wp_remote_get(
        'https://api.sportsdata.io/v3/' . $league . '/scores/json/Player/' . $player, 
        get_sports_data_io_headers()
      )
    )
  );
}

function get_logos( $league ) {
  $teams = get_teams( $league );
  $logos = array();
  foreach ( $teams as $team ) {
    $logos[ $team->Key ] = $team->WikipediaLogoUrl;
  }
  return $logos;
}

function get_team_trends( $league, $team ) {
  return json_decode( 
    wp_remote_retrieve_body( 
      wp_remote_get(
        'https://api.sportsdata.io/v3/' . $league . '/odds/json/TeamTrends/' . $team,
        get_sports_data_io_headers()
      )
    )
  );
}

require_once( get_template_directory() . '/config/api/ats.php' );
require_once( get_template_directory() . '/config/api/futures.php' );