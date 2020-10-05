<?php  
// include file with Javascript, Ajax and Template code
?>

<script type="text/javascript">

// Odds Type dropdown onchange event handler
function updateOddsType(oType) {
	
	// initialize teams Hash
	buildTeamsHash(teamsObj);
	
	oddsType = oType.value;
	
	showNewOddsTable();
	
}

// create a teams Hash for lookup
function buildTeamsHash(teams) {
	
	$.each( teams, function( index, team ) {
	  teamsHashByID[team.TeamID] = team;
	});
}

// return Spread, Moneyline or Total value for each game
function getCorrectOddsTypeValue(bookObj, oType, away, payout) {
	switch(oType) {
	  case "Moneyline": {
		  if(payout) {
			  return "ML";
		  } else {
			  if(away) {
				  return bookObj.AwayMoneyLine;
			  } else {
				  return bookObj.HomeMoneyLine;
			  }
		  }
		}
		break;
	  case "Total": {
		  if(payout) {
			  if(away) {
				  return bookObj.OverPayout;
			  } else {
				  return bookObj.UnderPayout;
			  }
		  } else {
			  return bookObj.OverUnder;
		  }
		}
		break;
	  default: { // Spread case
		  if(payout) {
			  if(away) {
				  return bookObj.AwayPointSpreadPayout;
			  } else {
				  return bookObj.HomePointSpreadPayout;
			  }
		  } else {
			  if(away) {
				  return bookObj.AwayPointSpread;
			  } else {
				  return bookObj.HomePointSpread;
			  }
		  }
	  }
	} 
}

// Date Change event handler
function updateDateChange(next) {
	
	// initialize teams Hash
	buildTeamsHash(teamsObj);
	
	if(next) {
		currentDate.setDate(currentDate.getDate() + 1);
	}
	else {
		currentDate.setDate(currentDate.getDate() - 1);
	}
	
	makeNewOddsRequest(apiLeagueUrl + formatDate(currentDate));
}

// make the Ajax API call
function makeNewOddsRequest(url) {
	
	$.ajax({
    type: 'GET',
    url: url,
    dataType: 'json',
	headers: {
        "Ocp-Apim-Subscription-Key" : headerValue
    },
    success: oddsByDateCallback 
  });
	
}

// callback for the AJAX call
var oddsByDateCallback = function(data) {
    
	// save the data for this date
	responseGameOdds = data;
	
	console.log(data);
	
	showNewOddsTable();
	
}

function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) 
        month = '0' + month;
    if (day.length < 2) 
        day = '0' + day;

    return [year, month, day].join('-');
}
	
</script>

			<script id="booksOddsCellTemp" type="text/x-jQuery-tmpl">
			
			{{if odds_book.length > 0 }}
			
			{{each odds_book}}
			
				{{if !(away_odds == null) && !(home_odds == null) }}
			
						<td class="sportsbook-panel">
							<div class="uk-panel">
								<div class="odds-sb-bookline">
                                    {{if sportsbook == "RiversCasinoPA"}}
                                    <a href="https://wlsugarhouseaffiliates.adsrv.eacdn.com/C.ashx?btag=a_3320b_415c_&affid=947&siteid=3320&adid=415&c=" target="_blank">
                                    {{else sportsbook == "UnibetNJ"}}
                                    <a href="https://wlkindred.adsrv.eacdn.com/C.ashx?btag=a_783b_150c_&affid=195&siteid=783&adid=150&c=" target="_blank">                                        
                                    {{else}}
                                    <a>
                                    {{/if}}
										<span class="sb-bookline-extlink">
											<span>
											   {{= away_odds }} <small class="uk-margin-small-left">{{= away_payout }}</small>
											</span>
											<span class="sb-extlink-hover" {{if (sportsbook != 'RiversCasinoPA' && sportsbook != 'UnibetNJ') }}hidden{{/if}}>
												<svg viewBox="0 0 24 24" width="15" height="15" xmlns="https://www.w3.org/2000/svg" class="" fill="#F7F8FD"><path d="M14,3V5H17.59L7.76,14.83L9.17,16.24L19,6.41V10H21V3M19,19H5V5H12V3H5C3.89,3 3,3.9 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19Z"></path></svg>
												<span>Bet Now</span>
											</span>
										</span>
									</a>
								</div>
								<div class="odds-sb-bookline">
                                    {{if sportsbook == "RiversCasinoPA"}}
                                    <a href="https://wlsugarhouseaffiliates.adsrv.eacdn.com/C.ashx?btag=a_3320b_415c_&affid=947&siteid=3320&adid=415&c=" target="_blank">
                                    {{else sportsbook == "UnibetNJ"}}
                                    <a href="https://wlkindred.adsrv.eacdn.com/C.ashx?btag=a_783b_150c_&affid=195&siteid=783&adid=150&c=" target="_blank">                                           
                                    {{else}}
                                    <a>
                                    {{/if}}
										<span class="sb-bookline-extlink">
											<span>
												{{= home_odds }} <small class="uk-margin-small-left">{{= home_payout }}</small>
											</span>
											<span class="sb-extlink-hover" {{if (sportsbook != 'RiversCasinoPA' && sportsbook != 'UnibetNJ') }}hidden{{/if}}>
												<svg viewBox="0 0 24 24" width="15" height="15" xmlns="https://www.w3.org/2000/svg" class="" fill="#F7F8FD"><path d="M14,3V5H17.59L7.76,14.83L9.17,16.24L19,6.41V10H21V3M19,19H5V5H12V3H5C3.89,3 3,3.9 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19Z"></path></svg>
												<span>Bet Now</span>
											</span>
										</span>
									</a>
								</div>
							</div>
						</td> 

				{{else}}
				
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
				
				{{/if}}
						
			{{/each}}
			
			{{else}}
			  
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
			  
			{{/if}}
			
			</script>
			
			<script id="oddsRowEmptyTmpl" type="text/x-jQuery-tmpl">
			
				<tr>
					<td colspan="8" style="width:100%; text-align:center; font-weight:bold;">
						<br /><br />
						No games available. Try another sport or day.
						<br /><br />
					</td>
				</tr>
			
			</script>
			
			<script id="oddsRowTemplate" type="text/x-jQuery-tmpl">
			
			 <tr>
                <td>
                    <div class="team-panel">
                        <div class="uk-panel">
                            <div class="odds-away">
                                <div class="odds-away-team">
                                    <img class="odds-away-team-img" src="data:image/svg+xml,%3Csvg%20xmlns=&#039;http://www.w3.org/2000/svg&#039;%20viewBox=&#039;0%200%200%2024&#039;%3E%3C/svg%3E" height="24" alt="${away_name}" class="perfmatters-lazy" data-src="${away_url}" loading="lazy" /><noscript><img src="${away_url}" height="24" alt="${away_name}"></noscript>
                                    <span>${away_name}</span>                            </div>
                                <div class="odds-away-score">${away_score}</div>
                            </div>
                            <div class="odds-home">
                                <div class="odds-home-team">
                                    <img class="odds-home-team-img" src="data:image/svg+xml,%3Csvg%20xmlns=&#039;http://www.w3.org/2000/svg&#039;%20viewBox=&#039;0%200%200%2024&#039;%3E%3C/svg%3E" height="24" alt="${home_name}" class="perfmatters-lazy" data-src="${home_url}" loading="lazy" /><noscript><img src="${home_url}" height="24" alt="${home_name}"></noscript>
                                    <span>${home_name}</span>                                
                                </div>
                                <div class="odds-home-score">${home_score}</div>
                            </div>
                        </div>
                    </div>
                </td>
                <td class="consensus-panel">
                    <div class="uk-panel">
                        <div class="odds-consensus">
                            ${away_consensus}
                        </div>
                        <div class="odds-consensus">
                            ${home_consensus}
                        </div>
                    </div>
                </td>
				
				{{tmpl($data) "#booksOddsCellTemp"}}
				
                 </tr>
				 
				 <tr class="schedule-row">
					<td colspan="1" class="schedule-panel">
					   <div>${date_time} | Status: ${status}</div>
                    </td>
					<td colspan="6">
                        <div>&nbsp;</div>
                    </td>
				</tr>
			
			</script>
			
			<script type="text/javascript">
			
			function formatDateForTable(value) {
				
				var date = new Date(value);

				var options = {
				  day: 'numeric',
				  month: 'numeric',
				  weekday: 'short',
				  hour: 'numeric',
				  minute: 'numeric'
				};
				
				return date.toLocaleString('en-US', options);
			}
			
			function showNewOddsTable() {
				
				// clear old data
				$("#odds-list-body").empty();
				
				// show "no game.." message when needed
				if(responseGameOdds.length == 0) {
				  // Render the odds table row using the template
				  $("#oddsRowEmptyTmpl").tmpl().appendTo("#odds-list-body");
				}
				
				$.each(responseGameOdds, function( index, gameData ) {
				  
				  var oddsData = {};
				  
				  // set urls
					oddsData.away_url = teamsHashByID[gameData.AwayTeamId].WikipediaLogoUrl;
					oddsData.home_url = teamsHashByID[gameData.HomeTeamId].WikipediaLogoUrl;
					oddsData.away_name = teamsHashByID[gameData.AwayTeamId].Name;
					oddsData.home_name = teamsHashByID[gameData.HomeTeamId].Name;
					oddsData.away_score = gameData.AwayTeamScore;
					oddsData.home_score = gameData.HomeTeamScore;
					
					oddsData.date_time = formatDateForTable(gameData.DateTime);
					oddsData.status = gameData.Status;
					
					oddsData.odds_book = [];
				  
				  // check that the game has odds array
				  if(gameData.PregameOdds && gameData.PregameOdds.length > 0) {
								
						// loop through Pregame odds
						let available = [];

						for(var i = 0; i < gameData.PregameOdds.length; i++) {
							available.push(gameData.PregameOdds[i].Sportsbook);
							if(gameData.PregameOdds[i].Sportsbook == "Consensus") {
								oddsData.away_consensus = getCorrectOddsTypeValue(gameData.PregameOdds[i], oddsType, true, false);
								oddsData.home_consensus = getCorrectOddsTypeValue(gameData.PregameOdds[i], oddsType, false, false);
							} 
						}

						var sportsbooks = [ 'RiversCasinoPA', 'UnibetNJ', 'DraftKings', 'FanDuel', 'ParxPA' ];
						sportsbooks.forEach(sportsbook => {
							var found = false;
							for(var i = 0; i < gameData.PregameOdds.length; i++) {
								if (gameData.PregameOdds[i].Sportsbook == sportsbook) {
									var oddsBookItem = {};
									oddsBookItem.sportsbook = sportsbook;
									oddsBookItem.away_odds = formatOddsVal(getCorrectOddsTypeValue(gameData.PregameOdds[i], oddsType, true, false));
									oddsBookItem.home_odds = formatOddsVal(getCorrectOddsTypeValue(gameData.PregameOdds[i], oddsType, false, false));
									oddsBookItem.away_payout = formatOddsVal(getCorrectOddsTypeValue(gameData.PregameOdds[i], oddsType, true, true));
									oddsBookItem.home_payout = formatOddsVal(getCorrectOddsTypeValue(gameData.PregameOdds[i], oddsType, false, true));
									
									oddsData.odds_book.push(oddsBookItem);
									found = true;
								}
							}
							if (!found) {
								var oddsBookItem = {};
								oddsBookItem.sportsbook = sportsbook;
								oddsBookItem.away_odds = null;
								oddsBookItem.home_odds = null;
								oddsBookItem.away_payout = null;
								oddsBookItem.home_payout = null;

								oddsData.odds_book.push(oddsBookItem);
							}
						});
					
				  } // end odds array check
				  
				  // Render the odds table row using the template
				  $("#oddsRowTemplate").tmpl(oddsData).appendTo("#odds-list-body");
				  
				});
				
			} // close showNewOddsTable
			
			// add a + to the numver if needed
			function formatOddsVal(val) {
				
				if(val > 0 && !isNaN(val)) {
					return "+" + val;
				} else {
					return val;
				}
			}
			
			</script>
			
<?php
	// register template js file
	 wp_enqueue_script( 'script', get_template_directory_uri() . '/views/widgets/jquery.tmpl.js', array ( 'jquery' ), 1.1, true);
?>