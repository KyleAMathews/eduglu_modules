// How often do people click on the your-groups drop down?
Drupal.behaviors.click_your_groups = function(context) {
  $("#your-groups").bind('click', function(event) {
    mpq.push(["track", "click-your-groups"]);
  });
}
// How often do people click on the my-groups drop down?
Drupal.behaviors.click_session_dropdown = function(context) {
  $("#session").bind('click', function(event) {
    mpq.push(["track", "click-session-dropdown"]);
  });
}    
// How often do people click on the "more" link?
Drupal.behaviors.click_expand_this_post = function(context) {
  $(".expand-post").bind('click', function (event) {
    mpq.push(["track", "click-expand-this-post"]);
    return false;
  });
}
// How often do people click on the group settings tab?
Drupal.behaviors.click_group_settings = function(context) {
  $("#group-settings").bind('click', function(event) {
    mpq.push(["track", "click-group-settings"]);
}

