// Global killswitch: only run if we are in a supported browser.
if (Drupal.jsEnabled) {
  // Set event handler. Listen for clicks and toggle between minimized and
  // maximized versions of discussions.
  $(document).ready(
    function(){
      $("#block-og-0 .item-list li:contains('Invite friend')").hide();
      
      $discussion = $("#block-og-0 .item-list li:contains('Create Discussion')").clone();
      $("#block-og-0 .item-list li:contains('Create Discussion')").remove();
      
      $("#block-og-0 .item-list li:contains('Create')").hide();
      
      $("#block-og-0 .item-list li").slice(0,1).before($discussion);
      
      $("#block-og-0 .item-list li:contains('Create Discussion') a").css('font-size', '125%');
      $("#block-og-0 .item-list li:contains('Create'):last").after("<p class='show-more-create'><span id='show-more-create-plus'>+</span> <span id='show-more-help-text'>More options</span></p><hr />");
      
      // Add click event.
      $("#show-more-create-plus").click(function(){
        //alert($("#block-og-0 .item-list li:contains('Create')").slice(0,1).css('display'));
   
        // Remove styles to show. Can't use toggle or show as numbers in
        // ordered lists don't show if there's a "display: block" in the inline css.
        $("#block-og-0 .item-list li:contains('Create')").removeAttr('style');
        $('.show-more-create').remove();
        $("#block-og-0 .item-list li:contains('Create'):last").after("<br />");
 
        
        return false;
      });
    }
  );

}