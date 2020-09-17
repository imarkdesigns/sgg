<main id="main" class="main" role="main">
    <div class="uk-container uk-container-xlarge">
        <div class="uk-grid-small" uk-grid>

            <div class="uk-width-expand@l">
                
                <div class="uk-card uk-card-default uk-card-body" data-card="legal-states" hidden>
                    <h1 class="uk-card-title">Where Sports Betting is Legal Now</h1>
                    <p>We’ve compiled a comprehensive look at the status of sports betting in every in all 50 states (plus Washington D.C.)</p>

                    <table class="uk-table uk-table-divider uk-table-small uk-table-responsive">
                        <thead>
                            <tr>
                                <th>State</th>
                                <th>Legal Sports Betting</th>
                                <th>Online Sports Betting</th>
                                <th>Recent Legislation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="uk-active">
                                <td>Colorado</td>
                                <td>Yes</td>
                                <td>Yes</td>
                                <td>Yes</td>
                            </tr>
                            <tr class="uk-active">
                                <td>Indiana</td>
                                <td>Yes</td>
                                <td>Yes</td>
                                <td>Yes</td>
                            </tr>
                            <tr class="uk-active">
                                <td>New Jersey</td>
                                <td>Yes</td>
                                <td>Yes</td>
                                <td>Yes</td>
                            </tr>
                            <tr class="uk-active">
                                <td>Pennsylvania</td>
                                <td>Yes</td>
                                <td>Yes</td>
                                <td>Yes</td>
                            </tr>
                            <tr class="uk-active">
                                <td>West Virginia</td>
                                <td>Yes</td>
                                <td>Yes</td>
                                <td>Yes</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <?php get_template_part( widget.'sportsbooks' ); ?>

                <div class="uk-card uk-card-default uk-card-body" data-card="content">
                    <?php the_content(); ?>
                </div>
                
            </div>

            <div class="uk-width-1-1 uk-width-large@l">

                <div class="uk-card uk-card-default uk-card-body" data-card="other-legal-states" hidden>
                    <h1 class="uk-card-title">Other States With Legal Sports Betting</h1>
                    <p>All of the US states with legal sports betting or active legislation.</p>

                    <div uk-grid class="uk-child-width-auto@s">
                        <div>
                            <h4>Live (No Bonus Offers)</h4>
                            <ul>
                                <li>Iowa</li>
                                <li>Mississippi</li>
                                <li>Nevada</li>
                                <li>New Hampshire</li>
                                <li>Oregon</li>
                                <li>Rhode Island</li>
                            </ul>
                        </div>
                        <div>
                            <h4>Coming Soon</h4>
                            <ul>
                                <li>District of Columbia</li>
                                <li>Tennessee</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <?php 

                    get_template_part( widget.'guides' );
                    get_template_part( widget.'news' );

                ?>
            </div>

        </div>
    </div>
</main>