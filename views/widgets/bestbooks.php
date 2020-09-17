<div class="uk-card uk-card-default uk-card-body" data-card="bestbooks">
    <h1 class="uk-card-title"><?php the_field('widget_title_bestbooks', 'option'); ?></h1>
    
    <figure class="uk-text-center">
        <?php the_field('widget_content_bestbooks', 'option'); ?>
        <a href="<?php the_field('widget_button_link_bestbooks', 'option'); ?>" class="uk-button uk-button-primary uk-button-large"> <?php the_field('widget_button_label_bestbooks', 'option'); ?> </a>
    </figure>

</div>