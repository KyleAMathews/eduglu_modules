<div id="top-stuff">
  <div id="top-bar-outer">
    <div id="top-bar-bg"></div>
      <div id="top-bar">
        <div class="top-bar-inside">
          <div class="static-links">
            <div id="logo">
              <a class="logo" title="<?php print $site_name; ?><?php if ($site_slogan != '') print ' &ndash; '. $site_slogan; ?>" href="<?php print base_path() ?>">
                <img id="logo-image" src= "<?php if (isset($logo)) {print $logo;} ?>" />
              </a>
            </div>
              <?php print drupal_get_form('search_theme_form'); ?>
            <div id="global-nav">
              <?php print $secondary_menu; ?> 
            </div>
          </div>
          <div class="active-links">
            <?php if ($user->uid !=0) { ?>
            <div id="session">
              <a href="#" class="profile-links">
                <span id="profile-image"><?php print $user_image ?></span>
              </a>
              <span id="screen-name">
                <?php print $user->name ?> 
              </span>
              <span class="down-arrow">
                &#9660; 
              </span>
              <ul class="profile-dropdown" style="display: none;">
                <li><?php print $profile ?></li>
                <li><?php print $account_settings ?></li>
                <li><?php print $logout ?></li>
              </ul>
            </div>
            <div id="your-groups">
              <span id="your-groups-label">
                your groups
              </span>
              <span class="down-arrow">
                &#9660; 
              </span>
              <?php print $your_groups ?>
            </div>
            <?php } else { print l(t("login"), "user", array('attributes' => array('id' => 'login-top-bar'))); } ?>
            <span class="vr"></span>
          </div>
      </div>
    </div>
  </div>
</div>
