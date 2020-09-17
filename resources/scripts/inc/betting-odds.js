(function($) {

    // Moment.JS
    $.getScript('https://cdn.jsdelivr.net/npm/moment@2.27.0/moment.min.js', function() {

        var $dateOdds = $('#dateOdds'),
            $moment   = moment(),
            $format   = 'dd, MMMM D';

        function showDate() {
            $dateOdds.html( $moment.format($format) );
        }

        function changeDate(key) {
            $moment.add(key, 'd');
        }

        function displayDate(key) {
            changeDate(key);
            showDate();
        }

        showDate();

        $('.odds-filter ._prevDay').on('click', function() {
            displayDate(-1);
        });

        $('.odds-filter ._nextDay').on('click', function() {
            displayDate(1);
        });

    });  

}) (jQuery);