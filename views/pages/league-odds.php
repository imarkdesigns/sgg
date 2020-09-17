<?php $content = ['post_type'=>'page','page_id'=>8];
query_posts( $content ); ?>
<main id="main" class="main" role="main">
    <div class="uk-container uk-container-xlarge">
        
        <div class="uk-card uk-card-default uk-card-body" data-card="betting-odds">
            <h1 class="uk-card-title"><?php the_title(); ?></h1>
                        
            <?php
                switch ( $post->post_parent ) {

                    case '23':
                        $odds = 'odds-nfl';
                        break;

                    case '25':
                        $odds = 'odds-nba';
                        break;

                    case '27':
                        $odds = 'odds-mlb';
                        break;
                    
                    default:
                        $odds = 'odds';
                        break;
                
                }

                get_template_part( widget.$odds ); 
            ?>
        </div>

        
        <div class="uk-card uk-card-default uk-card-body" data-card="content">
        <?php 
            while ( have_posts() ) : the_post();
                the_content(); 
            endwhile; wp_reset_query();
        ?>
        </div>


    </div>
</main>