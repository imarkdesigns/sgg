<?php 
// Enable this if needed
// This function was conflict with javascript DOM (wp-video-popup)
// $hdrMenu = [

//   'theme_location'  => 'Main Menu',
//   'menu_class'      => 'uk-navbar-nav uk-visible@l',
//   'container'       => '',
//   'items_wrap'      => '<ul class="%2$s">%3$s</ul>',
//   'depth'           => 2,
//   'walker'          => new subMenuWrap()

// ];

// WP Custom Logo
$customLogoID = get_theme_mod( 'custom_logo' );
$logo         = wp_get_attachment_image_src( $customLogoID, 'full' ); ?>

<a href="#main" class="skip-to-content-link">Skip to Content</a>
<div uk-sticky="sel-target: .uk-navbar-container; cls-active: uk-navbar-sticky; offset: -400; animation: uk-animation-slide-top" class="uk-position-z-index" data-globals="menu">
    <div class="uk-container uk-container-xlarge">

        <nav uk-navbar class="uk-navbar-container uk-navbar-transparent">
            <div class="uk-navbar-left">
                <a href="<?php echo esc_url( home_url() ); ?>" class="uk-logo">
                    <?php echo '<img src="'. $logo[0] .'" alt="'. get_bloginfo() .'">'; ?>
                </a>
            </div>
            <div class="uk-navbar-right">
                <?php // wp_nav_menu( $hdrMenu ); ?>
                <ul class="uk-navbar-nav uk-visible@l">
                    <li><a href="<?php echo esc_url( home_url() ); ?>">Home</a></li>
                    <li><a href="<?php echo esc_url( site_url('best-books') ); ?>">Best Books</a></li>
                    <li class="uk-parent">
                        <a href="#">Live Odds</a>
                        <div class="uk-navbar-dropdown uk-dropdown">
                            <ul class="uk-nav uk-navbar-dropdown-nav">
                                <li><a href="<?php echo esc_url( site_url('nfl/odds-betting-lines') ); ?>">NFL Odds &amp; Betting Lines</a></li>
                                <li><a href="<?php echo esc_url( site_url('nba/odds-betting-lines') ); ?>">NBA Odds &amp; Betting Lines</a></li>
                                <li><a href="<?php echo esc_url( site_url('mlb/odds-betting-lines') ); ?>">MLB Odds &amp; Betting Lines</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="uk-parent">
                        <a href="#">NFL</a>
                        <div class="uk-navbar-dropdown uk-dropdown">
                            <ul class="uk-nav uk-navbar-dropdown-nav">
                                <li><a href="<?php echo esc_url( site_url('nfl') ); ?>">Sports Gambling Data</a></li>
                                <li><a href="<?php echo esc_url( site_url('nfl/odds-betting-lines') ); ?>">Odds &amp; Betting Lines</a></li>
                                <?php /*
                                <li><a href="<?php echo esc_url( site_url('nfl/futures') ); ?>">Futures</a></li>
                                <li><a href="<?php echo esc_url( site_url('nfl/ats-standings') ); ?>">ATS Standings</a></li>
                                */ ?>
                                <li><a href="<?php echo esc_url( site_url('nfl/injuries') ); ?>">Injuries</a></li>
                                <li><a href="<?php echo esc_url( site_url('nfl/news') ); ?>">News</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="uk-parent">
                        <a href="#">NBA</a>
                        <div class="uk-navbar-dropdown uk-dropdown">
                            <ul class="uk-nav uk-navbar-dropdown-nav">
                                <li><a href="<?php echo esc_url( site_url('nba') ); ?>">Sports Gambling Data</a></li>
                                <li><a href="<?php echo esc_url( site_url('nba/odds-betting-lines') ); ?>">Odds &amp; Betting Lines</a></li>
                                <?php /*
                                <li><a href="<?php echo esc_url( site_url('nba/futures') ); ?>">Futures</a></li>
                                <li><a href="<?php echo esc_url( site_url('nba/ats-standings') ); ?>">ATS Standings</a></li>
                                */ ?>
                                <li><a href="<?php echo esc_url( site_url('nba/injuries') ); ?>">Injuries</a></li>
                                <li><a href="<?php echo esc_url( site_url('nba/news') ); ?>">News</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="uk-parent">
                        <a href="#">MLB</a>
                        <div class="uk-navbar-dropdown uk-dropdown">
                            <ul class="uk-nav uk-navbar-dropdown-nav">
                                <li><a href="<?php echo esc_url( site_url('mlb') ); ?>">Sports Gambling Data</a></li>
                                <li><a href="<?php echo esc_url( site_url('mlb/odds-betting-lines') ); ?>">Odds &amp; Betting Lines</a></li>
                                <?php /*
                                <li><a href="<?php echo esc_url( site_url('mlb/futures') ); ?>">Futures</a></li>
                                <li><a href="<?php echo esc_url( site_url('mlb/ats-standings') ); ?>">ATS Standings</a></li>
                                */ ?>
                                <li><a href="<?php echo esc_url( site_url('mlb/injuries') ); ?>">Injuries</a></li>
                                <li><a href="<?php echo esc_url( site_url('mlb/news') ); ?>">News</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="uk-parent">
                        <a href="#">Media</a>
                        <div class="uk-navbar-dropdown uk-dropdown">
                            <ul class="uk-nav uk-navbar-dropdown-nav">
                                <li><a href="#" class="wp-video-popup">Watch Video</a></li>
                            </ul>
                        </div>
                    </li>
                    <li><a href="<?php echo esc_url( site_url('contact') ); ?>">Contact</a></li>
                </ul>
                
                <a href="#newsletter" role="button" type="button" class="uk-button uk-button-primary newsletter uk-visible@l" uk-scroll> <i uk-icon="icon: mail"></i> Get On Our List </a>
                <button role="button" class="uk-navbar-toggle uk-hidden@l" uk-navbar-toggle-icon uk-toggle="target: #mobile"></button>
            </div>
        </nav>

    </div>

    <?php if ( is_page([ 23, 25, 27 ]) || $post->post_parent > 0 ) : 
    $post_parent = strtolower(get_the_title( $post->post_parent )); ?>
    <nav class="uk-background-primary uk-light">
        <div class="uk-container uk-container-xlarge">
        <?php
        if ( $post->post_parent ) {
            $children = wp_list_pages( array(
                'exclude'  => '31, 33, 39, 41, 47, 49',
                'title_li' => '',
                'child_of' => $post->post_parent,
                'echo'     => 0,
            ) );
        } else {
            $children = wp_list_pages( array(
                'exclude'  => '31, 33, 39, 41, 47, 49',
                'title_li' => '',
                'child_of' => $post->ID,
                'echo'     => 0
            ) );
        }
         
        if ( $children ) : ?>
            <ul class="uk-subnav uk-flex uk-flex-right" uk-margin>
                <?php echo $children; ?>
            </ul>
        <?php endif; ?>

        </div>
    </nav>
    <?php endif; ?>

</div>

<?php
// WP Video Popup Pro
$ytClip = 'https://www.youtube.com/watch?v=_HWm_2ok8gw';
echo do_shortcode( '[wp-video-popup hide-related="1" video="'.$ytClip.'"]' );
