(function($) {

    //* Mobile Menu
    $(window).on('load', function() {
        $('.uk-nav-mobile').find('.menu-item-has-children').addClass('uk-parent');
    });


    function footerStack() {
        if ( $(window).width() <= 959 ) {
            $('.footer-directory .uk-accordion li').removeClass('uk-open');
            $('.footer-directory .uk-accordion li .uk-accordion-content').attr('hidden', '');
        }

        $(window).on('resize', function() {
            if ( $(window).width() >= 959 ) {
                $('.footer-directory .uk-accordion li').addClass('uk-open');
                $('.footer-directory .uk-accordion li .uk-accordion-content').removeAttr('hidden', '');
            } else if ( $(window).width() <= 959 ) {
                $('.footer-directory .uk-accordion li').removeClass('uk-open');
                $('.footer-directory .uk-accordion li .uk-accordion-content').attr('hidden', '');
            }
        }).resize();
    }
    footerStack();


    // Widget: News
    // Switch tabs/contents base on Leagues and/or Parent ID
    $(window).on('load', function() {

        var $body = $('body');
        // League: NFL
        if ( $body.hasClass('page-id-23') || $body.hasClass('parent-pageid-23') ) {
            UIkit.switcher('.widget-league-news').show(0);
            UIkit.tab('.widget-league-news-tab').show(0);
        } 

        // League: NBA
        else if ( $body.hasClass('page-id-25') || $body.hasClass('parent-pageid-25') ) {
            UIkit.switcher('.widget-league-news').show(1);
            UIkit.tab('.widget-league-news-tab').show(1);
        }

        // League: MLB
        else if ( $body.hasClass('page-id-27') || $body.hasClass('parent-pageid-27') ) {
            UIkit.switcher('.widget-league-news').show(2);
            UIkit.tab('.widget-league-news-tab').show(2);
        }

    });


    // Cookie Sessions
    // The basic check of site fully loaded
    if(document.readyState === 'complete') {
        $.getScript('https://cdnjs.cloudflare.com/ajax/libs/js-cookie/2.2.0/js.cookie.min.js', function(){

            // Session Cookie
            $kukie = Cookies.set('sgg-accept-cookies');
            if ( ! $kukie ) {
                $('.sgg-accept-cookies').removeAttr('hidden').attr('uk-scrollspy', 'cls: uk-animation-fast uk-animation-slide-bottom; delay: 2500');
                $('.sgg-accept-cookies').find('.uk-alert-accept').on('click', function() {
                    Cookies.set('sgg-accept-cookies', 'true', { expires: 7 });
                    UIkit.alert('.sgg-accept-cookies').close();
                });
            }

        });
    }

    // Polling for the sake of my Cookies
    var interval = setInterval(function() {
        if(document.readyState === 'complete') {
            clearInterval(interval);
            // done();
        }    
    }, 100);


    // Search News
    $('#searchNews').on('keyup', function() {

        var value = $(this).val().toLowerCase();
        $('.article-news').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1).addClass('uk-margin-remove');
        });

    });


}) (jQuery);

// Search Filter for Team
function searchTeam() {

    // Declare variables
    var input, filter, table, tr, td, i, txtValue;
    input  = document.getElementById("searchOdds");
    filter = input.value.toUpperCase();
    table  = document.getElementById("odds-list");
    tr     = table.getElementsByTagName("tr");

    // Loop through all table rows, and hide those who don't match the search query
    for ( i = 0; i < tr.length; i++ ) {

        td = tr[i].getElementsByTagName("td")[0];
        if ( td ) {
            txtValue = td.textContent || td.innerText;
            if ( txtValue.toUpperCase().indexOf(filter) > -1 ) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }

    } // end for

}