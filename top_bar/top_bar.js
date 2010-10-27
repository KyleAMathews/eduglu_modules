if (Drupal.jsEnabled) {
  $(document).ready(function() {
    var toggleSession = function(toggle) {
      if (toggle) {
        $(".profile-dropdown").toggle();
        $("#session").toggleClass('active');
      }
      else {
        $(".profile-dropdown").hide();
        $("#session").removeClass('active');
      }
    }
    var toggleYourGroups = function(toggle) {
      if (toggle) {
        // Set max height for your-groups-dropdown.
        var newHeight = $(window).height() - 150;
        $('.your-groups-container').css('max-height', newHeight);

        $("#your-groups-dropdown").toggle();
        $("#your-groups").toggleClass('active');
      }
      else {
        $("#your-groups-dropdown").hide();
        $("#your-groups").removeClass('active');
      }
    }
    $("#session").bind('click', function(event) {
      toggleYourGroups(false);
      toggleSession(true);
    });
    $("#your-groups").bind('click', function(event) {
      toggleSession(false);
      toggleYourGroups(true);
    });
    $("#group-settings").bind('click', function(event) {
      $("#group-settings-dropdown").toggle();
      $(this).toggleClass('active');
    });
  });
}
