// Global killswitch: only run if we are in a supported browser.
if (Drupal.jsEnabled) {
  // Set event handler. Listen for clicks and toggle between minimized and
  // maximized versions of discussions.
  $(document).ready(
    function(){
      $("#show-more-plus").click(function(){
        if ($('#popular-conversations ol').children().slice(19,20).css('display') == 'none') {
          // Remove styles to show. Can't use toggle or show as numbers in
          // ordered lists don't show if there's a "display: block" in the inline css.
          $('#popular-conversations ol').children().slice(10,20).removeAttr('style');
          $('#show-more-plus').html("-");
          $('#show-more-help-text').text('Hide');
          
        }
        else {
          $('#popular-conversations ol').children().slice(10,20).hide();
          $('#show-more-plus').html("+");
          $('#show-more-help-text').text('Show more popular conversations');
          
        }
        
        return false;
      });
    }
  );

$('#popular-conversations ol').children().slice(10,20).hide();

}