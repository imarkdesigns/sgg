<main id="main" class="main" role="main">
    <div class="uk-container uk-container-xlarge">
        <div class="uk-grid-small" uk-grid>

            <div class="uk-width-expand@l">
                <div class="uk-card uk-card-default uk-card-body" data-card="content">
                    <h1 class="uk-card-title"><?php the_title(); ?></h1>

                    <h4>Sports Gambling Guides</h4>
                    <?php $page = ['post_type'=>'page','posts_per_page'=>-1,'post_parent'=>0,'post__not_in'=>[10,12],'order'=>'asc','orderby'=>'menu_order']; 
                    query_posts( $page ); ?>
                    <ul class="uk-list uk-flex uk-flex-wrap uk-margin-large-bottom">
                        <?php while ( have_posts() ) : the_post();
                            echo '<li class="uk-width-1-2@s uk-width-1-4@m uk-margin-remove">';
                            echo '<a href="'.get_permalink().'">'.get_the_title().'</a>';
                            echo '</li>';
                        endwhile; ?>
                    </ul>

                    <h4>Sports League</h4>
                    <div uk-grid class="uk-child-width-1-3@m uk-margin-large-bottom">
                        <?php $nfl = wp_list_pages([
                            'exclude'  => '31, 33, 39, 41, 47, 49',
                            'title_li' => 'NLF Sports Gambling Data',
                            'child_of' => 23,
                            'echo'     => 0,
                        ]); ?>
                        <ul>
                            <?php echo $nfl; ?>
                        </ul>

                        <?php $nba = wp_list_pages([
                            'exclude'  => '31, 33, 39, 41, 47, 49',
                            'title_li' => 'NBA Sports Gambling Data',
                            'child_of' => 25,
                            'echo'     => 0,
                        ]); ?>
                        <ul>
                            <?php echo $nba; ?>
                        </ul>

                        <?php $mlb = wp_list_pages([
                            'exclude'  => '31, 33, 39, 41, 47, 49',
                            'title_li' => 'MLB Sports Gambling Data',
                            'child_of' => 25,
                            'echo'     => 0,
                        ]); ?>
                        <ul>
                            <?php echo $mlb; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="uk-width-1-1 uk-width-large@l">
                
            </div>

        </div>
    </div>
</main>