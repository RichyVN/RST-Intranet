jQuery(function($){

  // WP < 3.5: Hide the senseless gallery setting fields
  jQuery('table#basic tr:eq(0), table#basic tr:eq(3)').hide();

  // WP >= 3.5
  $('div.gallery-settings').livequery(function(){
    $('.gallery-settings select.link-to > option[value=post]').remove();
    $('.gallery-settings select.columns').parents('label').remove();
  });

});
