<?php
function get_team_markets( $futures ) {
  $markets = array();
  if ( isset( $futures[0]->BettingMarkets ) ) {
    foreach ( $futures[0]->BettingMarkets as $market ) {
      if ( $market->BettingMarketType === 'Team Future' ) {
        $markets[ $market->BettingBetType ] = $market->BettingMarketID;
      }
    }
  }
  
  return $markets;
}

function get_payouts( $league, $betting_market ) {
  $teams_data = get_teams( $league );
  $ids = array();
  $keys = array();
  foreach ( $teams_data as $team ) {
    $ids[ $team->TeamID ] = $team;
    $keys[ $team->Key ] = $team;
  }
  $outcomes = array();
  if ( isset ( $betting_market->BettingOutcomes ) ) {
    foreach ( $betting_market->BettingOutcomes as $outcome ) {
      if ( $outcome->TeamID !== null ) {
        $logo_url = $ids[ $outcome->TeamID ]->WikipediaLogoUrl;
        $team_name = $ids[ $outcome->TeamID ]->City . ' ' . $ids[ $outcome->TeamID ]->Name;
        $logo = '<img src="' . $logo_url . '" alt="' . $team_name . '" style="width: 36px;height: 24px; padding-right: 12px; -o-object-fit: contain; object-fit: contain;" />';
        $logo_name = $logo . ' '. $team_name;
  
        if ( ! isset( $outcomes[ $outcome->TeamID ] ) ) {
          $outcomes[ $outcome->TeamID ] = array(
            'team' => $logo_name,
            'name' => $team_name,
            'order' => $outcome->PayoutAmerican
          );
        }
        $outcomes[ $outcome->TeamID ][ $outcome->SportsBook->Name ] = ( $outcome->SportsbookUrl ) ? '<a href="' . $outcome->SportsbookUrl . '">' . $outcome->PayoutAmerican . '</a>' : $outcome->PayoutAmerican;
        if ( $outcome->PayoutAmerican  < $outcomes[ $outcome->TeamID ]['order'] ) {
          $outcomes[ $outcome->TeamID ]['order'] = $outcome->PayoutAmerican;
        }
      }
    }
  }
  
  return $outcomes;
}

function sort_teams_by_payout($a, $b) {
  if ( isset( $a['Consensus'] ) && ! isset( $b['Consensus'] ) ) {
    return -1;
  } elseif ( ! isset( $a['Consensus'] ) && isset( $b['Consensus'] ) ) {
    return 1;
  } elseif ( isset( $a['Consensus'] ) && isset( $b['Consensus'] ) ) {
    if ( $a['Consensus'] === $b['Consensus'] ) {
      if ( $a['order'] === $b['order'] ) {
        return ( $a['name'] < $b['name'] ) ? -1 : 1;
      }
      return ( intval( $a['order'] ) < intval( $b['order'] ) ) ? -1 : 1;
    }
    return ( intval( $a['Consensus'] ) < intval( $b['Consensus'] ) ) ? -1 : 1;
  } else {
    if ( $a['order'] === $b['order'] ) {
      return ( $a['name'] < $b['name'] ) ? -1 : 1;
    }
    return ( intval( $a['order'] ) < intval( $b['order'] ) ) ? -1 : 1;
  }
}

function get_market_from_futures( $futures, $market_id ) {
  if ( isset( $futures->BettingMarkets ) && is_array( $futures->BettingMarkets ) ) :
    foreach ( $futures->BettingMarkets as $market ) : 
      if ( $market_id === $market->BettingMarketID ) {
        return $market;
      }
    endforeach;
  endif;
}

function get_bet_type_from_futures( $futures, $type_id ) {
  if ( isset( $futures->BettingMarkets ) && is_array( $futures->BettingMarkets ) ) :
    $bet_type = array();
    foreach ( $futures->BettingMarkets as $market ) : 
      if ( intval( $type_id ) === intval( $market->BettingBetTypeID ) ) {
        $bet_type[] = $market;
      }
    endforeach;
    return $bet_type;
  endif;
}

function get_markets_from_futures( $futures ) {
  $markets = array();
  if ( isset( $futures->BettingMarkets ) && is_array( $futures->BettingMarkets ) ) :
    foreach ( $futures->BettingMarkets as $market ) : 
      if ( 
        count( $market->AvailableSportsbooks ) === 1 && 
        $market->AvailableSportsbooks[0]->Name === 'Consensus' &&
        ! SPORTS_DATA_IO_ALLOW_CONSENSUS_ONLY 
      ) {
        // Do not include if no sportsbooks
      } else {
        $markets[ $market->BettingMarketID ] = $market;
      }
    endforeach;
  endif;
  return $markets;
}

function get_modified_title( $title ) {
  return  str_replace( ' Winner', '', $title );
}

function get_options( $markets ) {
  $options = array();
  $combined_ids = array( 11, 12, 13, 14, 15 );
  foreach ( $markets as $market ) {
    if ( in_array( $market->BettingBetTypeID, $combined_ids ) ) {
      $key = 'type-' . $market->BettingBetTypeID;
      if ( ! array_key_exists( $key, $options ) ) {
        $options[ $key ] = get_modified_title( $market->BettingBetType );
      }
    } else {
      $key = 'market-' . $market->BettingMarketID;
      if ( ! array_key_exists( $key, $options ) ) {
        $options[ $key ] = get_modified_title( $market->BettingBetType );
      }
    }
  }
  return $options;
}

// function get_market_options( $markets ) {
//   $options = array();
//   $combined_ids = array( 11, 12, 13, 14, 15 );
//   foreach ( $markets as $market ) {
//     if ( in_array( $market->BettingBetTypeID, $combined_ids ) ) {
//       if ( $market->TeamID !== null ) {
//         if ( ! isset( $options[ 'combined-' . $market->BettingBetTypeID ] ) ) {
//           $options[ 'combined-' . $market->BettingBetTypeID ] = array();
//         }
//         if ( ! isset( $combined[ $market->BettingBetTypeID ][ $market->TeamID ] ) ) {
//           $combined[ $market->BettingBetTypeID ][ $market->TeamID ] = array(
//             'title' => ''
//           );
//         }
//         $combined[ $market->TeamID ] = 
//       }
//     } else {
//       $title = str_replace( ' Winner', '', $market->BettingBetType );
//       $options[ 'standard-' . $market->BettingMarketID ] = $title;
//     }
//   }
//   return $options;
// }

function get_team_futures_by_market( $market ) {
  $teams = array();
  foreach ( $market->BettingOutcomes as $outcome ) {
    if ( $outcome->TeamID !== null ) {
      if ( ! isset( $teams[ $outcome->TeamID ] ) ) {
        $teams[ $outcome->TeamID ] = array();
      }
      if ( ! isset( $teams[ $outcome->TeamID ][ $outcome->SportsBook->Name ] ) )
      $teams[ $outcome->TeamID ][ $outcome->SportsBook->Name ][] = $outcome->PayoutAmerican;
    }
  }
}

function get_available_sportsbooks( $sportsbooks ) {
  $books = array();
  foreach ( $sportsbooks as $sportsbook ) {
    if ( $sportsbook->Name === 'Consensus' ) {
      array_unshift( $books, $sportsbook->Name );
    } else {
      $books[] = $sportsbook->Name;
    }
  }
  return $books;
}

function get_bet_team_title( $team ) {
  $logo = '<img src="' . $team['logo'] . '" alt="' . $team['title'] . '" style="width: 36px;height: 24px; padding-right: 12px; -o-object-fit: contain; object-fit: contain;" />';
  return $logo . ' '. $team['title'];
}

function get_bet_player_title( $player, $team ) {
  $player_name = $player->FirstName . ' ' . $player->LastName;
  if ( $team ) {
    $logo = '<img src="' . $team['logo'] . '" alt="' . $player_name . '" style="width: 36px;height: 24px; padding-right: 12px; -o-object-fit: contain; object-fit: contain;" />';
    return $logo . ' '. $player_name;
  } else {
    return $player_name;
  }
}

function get_outcomes_from_bet_type( $league, $bet_type ) {
  $data = array();
  $teams = get_teams( $league );
  $team_ids = normalize_teams_by_id( $teams );
  
  foreach ( $bet_type as $option ) {
    $id = null;
    if ( $option->TeamID ) {
      $id = $option->TeamID;
      $title = get_bet_team_title( $team_ids[ $option->TeamID ] );
    } elseif ( $option->PlayerID ) {
      $id = $option->PlayerID;
      $team = null;
      $player_id = $option->PlayerID;
      $player = get_player( $league, $player_id );
      if ( $player->TeamID ) {
        $team = $team_ids[ $player->TeamID ];
      }
      $title = get_bet_player_title( $player, $team );
    } else {
      if ( isset ( $option->BettingOutcomes[0]->TeamID ) ) {
        $id = $option->BettingOutcomes[0]->TeamID;
        $title = get_bet_team_title( $team_ids[ $option->BettingOutcomes[0]->TeamID ] );
      } elseif ( isset ( $option->BettingOutcomes[0]->PlayerID ) ) {
        $id = $option->BettingOutcomes[0]->PlayerID;
        $team = null;
        $player_id = $option->BettingOutcomes[0]->PlayerID;
        $player = get_player( $league, $player_id );
        if ( $player->TeamID ) {
          $team = $team_ids[ $player->TeamID ];
        }
        $title = get_bet_player_title( $player, $team );
      }
    }

    if ( ! isset( $data[ $id ] ) ) {
      $data[ $id ] = array(
        'title' => $title,
        'outcomes' => array()
      );
    }

    if ( is_array( $option->BettingOutcomes )  ) {
      foreach ( $option->BettingOutcomes as $outcome ) {
        if ( ! isset( $data[ $id ]['outcomes'][ $outcome->BettingOutcomeType ] ) ) {
          $data[ $id ]['outcomes'][ $outcome->BettingOutcomeType ] = array();
        }

        if ( ! isset( $data[ $id ]['outcomes'][ $outcome->BettingOutcomeType ][ $outcome->SportsBook->Name ] ) ) {
          $data[ $id ]['outcomes'][ $outcome->BettingOutcomeType ][ $outcome->SportsBook->Name ] = $outcome->PayoutAmerican;
        }
      }
    }
  }

  return $data;
}

function get_outcomes_from_market( $market ) {
  $outcomes = array();

  foreach ( $market->BettingOutcomes as $outcome ) {
    $id = null;
    if ( $outcome->TeamID ) {
      $id = $outcome->TeamID;
    } elseif ( $outcome->PlayerID ) {
      $id = $outcome->PlayerID;
    } else {
      $id = $outcome->Participant;
    }

    if ( ! isset( $outcomes[ $id ] ) ) {
      $outcomes[ $id ] = array();
    }

    if ( $outcome->BettingOutcomeType ) {
      if ( ! isset( $outcomes[ $id ][ $outcome->BettingOutcomeType ] ) ) {
        $outcomes[ $id ][ $outcome->BettingOutcomeType ] = array();
      }

      if ( ! isset( $outcomes[ $id ][ $outcome->BettingOutcomeType ][ $outcome->SportsBook->Name ] ) ) {
        $outcomes[ $id ][ $outcome->BettingOutcomeType ][ $outcome->SportsBook->Name ] = $outcome;
      }
    } else {
      if ( ! isset( $outcomes[ $id ][ $outcome->SportsBook->Name ] ) ) {
        $outcomes[ $id ][ $outcome->SportsBook->Name ] = $outcome;
      }
    }
  }

  return $outcomes;
}

function sort_by_type( $a, $b ) {
  $a1 = array_key_first( $a );
  $b1 = array_key_first( $b );
  $a2 = array_key_first( $a[ $a1 ] );
  $b2 = array_key_first( $b[ $b1 ] );
  if ( isset( $a[ $a1 ][ $a2 ]['Consenus'] ) && isset( $b[ $b1 ][ $b2 ]['Consenus'] ) ) {
    return ( $a[ $a1 ][ $a2 ]['Consenus'] < $b[ $b1 ][ $b2 ]['Consenus']) ? -1 : 1;
  } else {
    $a3 = array_key_first( $a[ $a1 ][ $a2 ] );
    $b3 = array_key_first( $b[ $b1 ][ $b2 ] );
    return ( $a[ $a1 ][ $a2 ][ $a3 ] < $b[ $b1 ][ $b2 ][ $b3 ]) ? -1 : 1;
  }
}

function sort_by_consensus_payout( $a, $b ) {
  if ( isset( $a['data']['Consensus'] ) && isset( $b['data']['Consensus'] ) ) {
    if ( $a['data']['Consensus'] === $b['data']['Consensus'] ) {
      return 0;
    } else {
      return ( $a['data']['Consensus'] < $b['data']['Consensus'] ) ? -1 : 1;
    }
  } elseif ( isset( $a['data']['Consensus'] ) && ! isset( $b['data']['Consensus'] ) ) {
    return -1;
  } elseif ( ! isset( $a['data']['Consensus'] ) && isset( $b['data']['Consensus'] ) ) {
    return 1;
  }
}

function normalize_odds( $outcome ) {
  $odds = array();
  foreach ( $outcome as $sportsbook => $data ) {
    $payout = 'N/A';
    if ( isset( $data->PayoutAmerican ) ) {
      $payout = '';
      if ( $data->PayoutAmerican > 0 ) {
        $payout = '+';
      }
      $payout += $data->PayoutAmerican;
    }
    $odds[ $sportsbook ] = $payout; 
  }
  return $odds;
}

function normalize_teams_by_id( $teams ) {
  $ids = array();
  foreach ( $teams as $team ) {
    $ids[ $team->TeamID ] = array(
      'title' => $team->City . ' ' . $team->Name,
      'city' => $team->City,
      'name' => $team->Name,
      'logo' => $team->WikipediaLogoUrl
    );
  }
  return $ids;
}

function normalize_title( $team = null, $player = null ) {
  if ( $player ) {
    $player_name = $player->FirstName . ' ' . $player->LastName;
    if ( $team ) {
      $logo = '<img src="' . $team['logo'] . '" alt="' . $player_name . '" style="width: 36px;height: 24px; padding-right: 12px; -o-object-fit: contain; object-fit: contain;" />';
      return $logo . ' '. $player_name;
    } else {
      return $player_name;
    }
  } else {
    $logo = '<img src="' . $team['logo'] . '" alt="' . $team['title'] . '" style="width: 36px;height: 24px; padding-right: 12px; -o-object-fit: contain; object-fit: contain;" />';
    return $logo . ' '. $team['title'];
  }
}

function normalize_futures_outcomes( $league, $outcomes, $sportsbooks ) {
  $data = array();
  $multiple_outcomes = false;
  $output_options = array();
  $teams = get_teams( $league );
  $team_ids = normalize_teams_by_id( $teams );
  
  foreach ( $outcomes as $team_outcome ) {
    foreach ( $team_outcome as $key => $value ) {
      if ( ! in_array( $key, $sportsbooks ) ) {
        $multiple_outcomes = true;
        if ( ! in_array( $output_options ) ) {
          $output_options[] = $key;
        }
      }
    }
  }

  if ( $multiple_outcomes ) {
    $data_options = array();
    

    foreach ( $outcomes as $team_key => $multi_outcomes ) {
      
      foreach ( $multi_outcomes as $option => $option_data ) {
        $first_key = array_key_first( $option_data );
        $single = $option_data[ $first_key ];
        $data_options[ $option ] = normalize_odds($option_data);
        $title = $single->Participant;
        if ( $single->PlayerID ) {
          $player = get_player( $league, $single->PlayerID);
          if ( $player->TeamID ) {
            $title = normalize_title( $team_ids[ $player->TeamID ], $player );
          } else {
            $title = normalize_title( null, $player );
          }
        }
        $temp_data = array(
          'title' => $title,
          'multiple' => true,
          'data' => $data_options
        );
        $data[] = $temp_data;
      }
    }
  } else {
    foreach ( $outcomes as $option => $option_data ) {
      $first_key = array_key_first( $option_data );
      $single = $option_data[ $first_key ];
      $title = $single->Participant;
      if ( $single->PlayerID ) {
        $player = get_player( $league, $single->PlayerID);
        if ( $player->TeamID ) {
          $title = normalize_title( $team_ids[ $player->TeamID ], $player );
        } else {
          $title = normalize_title( null, $player );
        }
      } elseif ( $single->TeamID ) {
        $title = normalize_title( $team_ids[ $single->TeamID ] );
      }
      $temp_data = array(
        'title' => $title,
        'multiple' => false,
        'data' => normalize_odds( $option_data )
      );
      $data[] = $temp_data;
    }
    
  }
  return $data;
}

function futures_type_table( $outcomes, $sportsbooks ) {
  ?>
  <table class="uk-table uk-table-responsive uk-table-divider">
    <thead>
      <tr>
        <td class="team-label">Futures</td>
        <?php foreach ( $sportsbooks as $sportsbook ) : ?>
        <td><?php echo $sportsbook; ?></td>
        <?php endforeach; ?>
      </tr>
    </thead>
    <tbody>
      <?php foreach ( $outcomes as $outcome ) : ?>
      <tr>
        <td class="team-panel"><?php echo $outcome['title']; ?></td>
        <?php foreach ( $sportsbooks as $sportsbook ) : ?>
        <td>
          <?php 
          foreach ( $outcome['outcomes'] as $option_name => $option ) {
            echo $option_name . ' ';
            if ( isset( $option[ $sportsbook ] ) ) {
              if ( $option[ $sportsbook ] > 0 ) {
                echo '+';
              }
              echo number_format( $option[ $sportsbook ] );
            } else {
              echo 'N/A';
            }
            echo "<br />";
          }
          ?>
        </td>
        <?php endforeach; ?>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php
}

function futures_table( $outcomes, $sportsbooks ) {
  ?>
  <table class="uk-table uk-table-responsive uk-table-divider">
    <thead>
      <tr>
        <td class="team-label">Futures</td>
        <?php foreach ( $sportsbooks as $sportsbook ) : ?>
        <td><?php echo $sportsbook; ?></td>
        <?php endforeach; ?>
      </tr>
    </thead>
    <tbody>
      <?php foreach ( $outcomes as $outcome ) : ?>
      <tr>
        <td class="team-panel"><?php echo $outcome['title']; ?></td>
        <?php foreach ( $sportsbooks as $sportsbook ) : ?>
        <td>
          <?php 
          if ( $outcome['multiple'] === true ) {
            foreach ( $outcome['data'] as $option_name => $option ) {
              echo $option_name . ' ';
              if ( isset( $option[ $sportsbook ] ) ) {
                if ( $option[ $sportsbook ] > 0 ) {
                  echo '+';
                }
                echo number_format( $option[ $sportsbook ], 0, '.', ',' );
              } else {
                echo 'N/A';
              }
              echo "<br />";
            }
          } else {
            if ( isset( $outcome['data'][ $sportsbook ] ) ) {
              if ( $outcome['data'][ $sportsbook ] > 0 ) {
                echo '+';
              }
              echo number_format( $outcome['data'][ $sportsbook ], 0, '.', ',' );
            } else {
              echo 'N/A';
            }
          }
          ?>
        </td>
        <?php endforeach; ?>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php
}

function get_selected_option( $league ) {
  if ( isset( $_GET['type_id'] ) ) {
    return 'type-' . $_GET['type_id'];
  }

  if ( isset( $_GET['market_id'] ) ) {
    return 'market-' . $_GET['market_id'];
  }

  $id = '';

  switch ( $league ) {
    case 'nba': $id = SPORTS_DATA_IO_NBA_DEFAULT_MARKET_ID; break;
    case 'nfl': $id = SPORTS_DATA_IO_NFL_DEFAULT_MARKET_ID; break;
    case 'mlb': $id = SPORTS_DATA_IO_MLB_DEFAULT_MARKET_ID; break;
    default: break;
  }

  return 'market-' . $id;
}

function get_selected_market_id( $league ) {
  $id = null;
  if ( isset( $_GET['market_id'] ) ) {
    $id = $_GET['market_id'];
  } else {
    switch ( $league ) {
      case 'nba': $id = SPORTS_DATA_IO_NBA_DEFAULT_MARKET_ID; break;
      case 'nfl': $id = SPORTS_DATA_IO_NFL_DEFAULT_MARKET_ID; break;
      case 'mlb': $id = SPORTS_DATA_IO_MLB_DEFAULT_MARKET_ID; break;
      default: break;
    }
  }
  return $id;
}

function futures( $league ) {
  $season = get_current_season( $league );
  $futures_data = get_betting_futures( $league, $season );
  $futures = $futures_data[0];
  $selected_key = get_selected_option( $league );
  
  if ( substr( $selected_key, 0, 4 ) ===  'type' ) {
    $bet_type = get_bet_type_from_futures( $futures, $_GET['type_id'] );
    $outcomes = get_outcomes_from_bet_type( $league, $bet_type );
    usort( $outcomes, 'sort_by_type' );
    $sportsbooks = get_available_sportsbooks( $bet_type[0]->AvailableSportsbooks );
    futures_type_table( $outcomes, $sportsbooks );
  } else {
    $market_id = intval( substr( $selected_key, 7 ) ); // everything after 'market-'
    $market = get_market_from_futures( $futures, $market_id );
    $sportsbooks = get_available_sportsbooks( $market->AvailableSportsbooks );
    $outcomes = get_outcomes_from_market( $market );
    $normalized = normalize_futures_outcomes( $league, $outcomes, $sportsbooks );
    usort( $normalized, 'sort_by_consensus_payout' );
    futures_table( $normalized, $sportsbooks );
  }
}

function market_options( $league ) {
  $season = get_current_season( $league );
  $futures = get_betting_futures( $league, $season );
  $markets = get_markets_from_futures( $futures[0] );
  $options = get_options( $markets );
  $selected_key = get_selected_option( $league );
  ?>
  <form action="<?php the_permalink(); ?>" method="get">
    <select name="markettype" id="markettype" class="uk-select" style="max-width: 250px;">
      <?php foreach ( $options as $key => $title ) : ?>
      <option value="<?php echo $key; ?>" <?php echo ( $key === $selected_key ) ? 'selected="selected"' : ''; ?>><?php echo $title; ?></option>
      <?php endforeach; ?>
    </select>
  </form>
  <?php
}

function nba_futures() { futures( 'nba' ); }
add_action( 'nba_futures', 'nba_futures' );

function nfl_futures() { futures( 'nfl' ); }
add_action( 'nfl_futures', 'nfl_futures' );

function mlb_futures() { futures( 'mlb' ); }
add_action( 'mlb_futures', 'mlb_futures' );

function nba_market_options() { market_options( 'nba' ); }
add_action( 'nba_market_options', 'nba_market_options' );

function nfl_market_options() { market_options( 'nfl' ); }
add_action( 'nfl_market_options', 'nfl_market_options' );

function mlb_market_options() { market_options( 'mlb' ); }
add_action( 'mlb_market_options', 'mlb_market_options' );

function add_query_vars_filter( $vars ){
  $vars[] = "market_id";
  $vars[] = "type_id";
  return $vars;
}
add_filter( 'query_vars', 'add_query_vars_filter' );

function enqueue_market_js() {
  wp_enqueue_script('markettype', get_template_directory_uri() . '/resources/scripts/inc/markettype.js', array( 'jquery' ) );
  wp_localize_script( 'markettype', 'SGG', array(
    'permalink' => get_permalink(),
  ) );
}
add_action( 'wp_enqueue_scripts', 'enqueue_market_js' );
