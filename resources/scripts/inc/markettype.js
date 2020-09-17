jQuery(document).ready(function($) {
  $('#markettype').change(function() {
    SGG.permalink;
    let selected = $(this).val();
    if (selected.substr(0, 5) === 'type-') {
      const param = 'type_id=' + selected.substr(5);
      window.location = SGG.permalink + '?' + param;
    } else {
      const param = 'market_id=' + selected.substr(7);
      window.location = SGG.permalink + '?' + param;
    }
  });
});