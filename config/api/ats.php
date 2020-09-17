<?php
function get_team_trends_scope( $trends, $scope ) {
  $ats = '';
  $ou = '';
  foreach ( $trends->TeamGameTrends as $trend ) {
    if ( $trend->Scope === $scope ) {
      $ats = $trend->WinsAgainstTheSpread . '-' . $trend->LossesAgainstTheSpread;
      if ( $trend->PushesAgainstTheSpread !== 0 ) {
          $ats .= '-' . $trend->PushesAgainstTheSpread;
      } 
      $ou = $trend->Overs . '-' . $trend->Unders;
      if ( $trend->OverUnderPushes !== 0 ) {
          $ou .= '-' . $trend->OverUnderPushes;
      }
      break;
    }
  }
  return [
    'against_the_spread' => $ats,
    'over_under' => $ou
  ];
}

function get_team_data( $league, $standing, $logo, $team_abbr ) {
  $team_trends = get_team_trends( $league, $team_abbr );
  $last_ten_home = get_team_trends_scope( $team_trends, 'Last 10 Home Games' );
  $last_ten_away = get_team_trends_scope( $team_trends, 'Last 10 Away Games' );

  $logo_alt = ( $league === 'nfl' ) ? $standing->Name : $standing->City . ' ' . $standing->Name;
  $logo = '<img src="' . $logo . '" alt="' . $logo_alt . '" />';
  $logo_name = $logo . ' '. $standing->Name;

  $team = array(
    'wins' => $standing->Wins,
    'losses' => $standing->Losses,
    'name' => $standing->Name,
    'team' => $logo_name,
    'overall' => $standing->Wins . '-' . $standing->Losses,
    'ats_home' => $last_ten_home['against_the_spread'],
    'ats_away' => $last_ten_away['against_the_spread'],
    'ou_home' => $last_ten_home['over_under'],
    'ou_away' => $last_ten_away['over_under']
  );

  if ( $league !== 'nfl' ) {
    $team['home'] = $standing->HomeWins . '-' . $standing->HomeLosses;
    $team['away'] = $standing->AwayWins . '-' . $standing->AwayLosses;
  }

  return $team;
}

function sort_teams_by_record($a, $b) {
  if ( $a['wins'] === $b['wins'] ) {
    if ( $a['losses'] === $b['losses'] ) {
      return ( $a['name'] < $b['name'] ) ? -1 : 1;
    } else {
      return ( $a['losses'] < $b['losses'] ) ? -1 : 1;
    } 
  } else {
    return ( $a['wins'] > $b['wins'] ) ? -1 : 1;
  }
}

function against_the_spread_table( $league, $teams ) {
  ?>
  <table class="uk-table uk-table-divider">
    <thead>
      <tr>
        <th class="team-label">Team</th>
        <th>Overall</th>
        <?php if ( $league !== 'nfl' ) : ?>
        <th>Home</th>
        <th>Away</th>
        <?php endif; ?>
        <th><small>Last 10 Games</small> ATS Home</th>
        <th><small>Last 10 Games</small> ATS Away</th>
        <th><small>Last 10 Games</small> OV/UN Home</th>
        <th><small>Last 10 Games</small> OV/UN Away</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ( $teams as $team ) : ?>
      <tr>
        <td class="team-panel"><?php echo $team['team']; ?></td>
        <td><?php echo $team['overall']; ?></td>
        <?php if ( $league !== 'nfl' ) : ?>
        <td><?php echo $team['home']; ?></td>
        <td><?php echo $team['away']; ?></td>
        <?php endif; ?>
        <td><?php echo $team['ats_home']; ?></td>
        <td><?php echo $team['ats_away']; ?></td>
        <td><?php echo $team['ou_home']; ?></td>
        <td><?php echo $team['ou_away']; ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php
}

function against_the_spread( $league ) {
  $logos = get_logos( $league );
  $season = get_current_season( $league );
  $standings = get_standings( $league, $season );
  $teams = array();
  foreach ( $standings as $standing ) {
    $team_abbr = ( $league === 'nfl' ) ? $standing->Team : $standing->Key;
    $teams[] = get_team_data( $league, $standing, $logos[ $team_abbr ], $team_abbr );
  }
  usort( $teams, 'sort_teams_by_record' );
  against_the_spread_table( $league, $teams );
}

function nba_against_the_spread() {
  against_the_spread( 'nba' );
}
add_action( 'nba_against_the_spread', 'nba_against_the_spread' );

function nfl_against_the_spread() {
  against_the_spread( 'nfl' );
}
add_action( 'nfl_against_the_spread', 'nfl_against_the_spread' );

function mlb_against_the_spread() {
  against_the_spread( 'mlb' );
}
add_action( 'mlb_against_the_spread', 'mlb_against_the_spread' );