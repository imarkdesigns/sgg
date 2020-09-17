<?php $mblMenu = [

  'theme_location'  => 'Mobile Menu',
  'menu_class'      => 'uk-nav-default uk-nav-parent-icon uk-nav-mobile',
  'container'       => '',
  'items_wrap'      => '<ul class="%2$s" uk-nav>%3$s</ul>',
  'depth'           => 2,
  'walker'          => new mobileMenuWrap()

]; ?>

<div id="mobile" uk-offcanvas="overlay: true">
    <div class="uk-offcanvas-bar">
        <button role="button" type="button" class="uk-offcanvas-close" uk-close></button>
        
        <?php echo '<img src="'. _uri.'/resources/images/site-SGG-logo-alt.png' .'" alt="'. get_bloginfo() .'">'; ?>
        <?php wp_nav_menu( $mblMenu ); ?>

    </div>
</div>