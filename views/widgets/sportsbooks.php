<div class="uk-card uk-card-default uk-card-body" data-card="sportsbooks">
    <div class="uk-flex uk-flex-between">
        <h1 class="uk-card-title">Best Sportsbooks</h1>
        <div class="button-select-wrapper">
            <?php /*
            <select name="" id="" class="uk-select uk-width-medium">
                <option value="pennsylvania">Pennsylvania</option>
            </select>
            */ ?>
            
            <button type="button" class="uk-button uk-button-outline">Choose Betting Location</button>
            <div uk-dropdown="mode: click">
                <ul class="uk-nav uk-dropdown-nav">
                    <li><a rel="noopener">Pennsylvania</a></li>
                </ul>
            </div>
        </div>    
    </div>
    
    
    <div class="sportsbooks-lists">
    <?php $sportsbooks = ['post_type'=>'sportsbooks','has_password'=>false,'posts_per_page'=>-1,'order'=>'asc'];
    query_posts( $sportsbooks );

    while ( have_posts() ) : the_post();
        
        $image   = get_field('sb_image');
        $url     = get_field('sb_url');
        $promo   = get_field('sb_promotion');
        $details = get_field('sb_details');

        ?>
        <ul>
            <li class="sbl-sportsbook">
                <div class="sbl-item">
                    <div class="uk-background-cover uk-height-small" data-src="<?php echo $image['url']; ?>" uk-img>
                        <span hidden><?php the_title(); ?></span>
                    </div>
                    <a href="<?php echo $url; ?>" class="uk-position-cover"></a>
                </div>
            </li>
            <li class="sbl-offers">
                <div class="sbl-item">
                    <h4><?php echo $promo; ?></h4>
                </div>
            </li>
            <li class="sbl-details">
                <div class="sbl-item">
                    <?php echo $details; ?>
                </div>
            </li>
            <li class="sbl-link">
                <div class="sbl-item">
                    <a href="<?php echo $url; ?>" target="_blank" class="uk-button uk-button-primary uk-button-large">Bet Now</a>
                </div>
            </li>
        </ul>
        <?php  

    endwhile; 

    wp_reset_query(); ?>
    </div>

</div>