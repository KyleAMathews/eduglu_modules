<?php

############################################################
###  This file boostraps Drupal so it can be run
###  from a command-line-driven script.  Include this file
###  at the top of your scrip.
###
###  Written by Conan Albrecht with input from various web
###  site tutorials.   March 2009.


# set up the drupal directory -- very important 
$DRUPAL_DIR = '/home/kyle/workspace/www/edully';

# set some server variables so Drupal doesn't freak out
$_SERVER['SCRIPT_NAME'] = '/script.php';
$_SERVER['SCRIPT_FILENAME'] = '/script.php';
$_SERVER['HTTP_HOST'] = 'example.com';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['REQUEST_METHOD'] = 'POST';

# act as the first user
global $user;
$user->uid = 1;
 error_reporting(E_ALL);
# gain access to drupal
chdir($DRUPAL_DIR);  # drupal assumes this is the root dir
//error_reporting(E_ERROR | E_PARSE); # drupal throws a few warnings, so suppress these
require_once('./includes/bootstrap.inc');
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
dd("hello world");
# restore error reporting to its normal setting
error_reporting(E_ALL);

# Copy all group alises and insert into the purl table.
$results = db_query("SELECT nid FROM {node} WHERE type = 'group'");
$nids = array();
while ($data = db_fetch_array($results)) {
  $nids[] = $data['nid'];
}

// Reset on each run.
db_query('truncate spaces');
db_query('truncate spaces_settings');
db_query('truncate spaces_features');
db_query('truncate purl');
db_query('truncate spaces_presets');

// Load our space presets.
$type = 'og';
$value = 'a:5:{s:8:"features";a:3:{s:11:"discussions";s:1:"2";s:15:"group_dashboard";s:1:"2";s:11:"atrium_book";s:1:"0";}s:8:"settings";a:1:{s:4:"home";s:9:"dashboard";}s:6:"locked";a:2:{s:8:"features";a:3:{s:11:"discussions";i:1;s:15:"group_dashboard";i:1;s:11:"atrium_book";i:0;}s:8:"settings";a:1:{s:4:"home";i:0;}}s:7:"weights";a:3:{s:11:"discussions";s:3:"-10";s:15:"group_dashboard";s:2:"-9";s:11:"atrium_book";s:2:"-8";}s:2:"og";a:4:{s:12:"og_selective";s:1:"0";s:11:"og_register";i:0;s:12:"og_directory";i:1;s:10:"og_private";i:0;}}';

$space_preset_class_group = array(
  'type' => $type,
  'value' => $value,
  'name' => 'Class Group',
  'description' => 'For public classes',
  'id' => 'class_group',
);

$space_preset_interest_community = array(
  'type' => $type,
  'value' => $value,
  'name' => 'Interest Community',
  'description' => 'For interest communities',
  'id' => 'interest_community',
);

$space_preset_major_group = array(
  'type' => $type,
  'value' => $value,
  'name' => 'Major group',
  'description' => 'For majors',
  'id' => 'major_group',
);

$space_preset_location_group = array(
  'type' => $type,
  'value' => $value,
  'name' => 'Location Groups',
  'description' => 'For location groups',
  'id' => 'location_group',
);

drupal_write_record('spaces_presets', $space_preset_class_group);
drupal_write_record('spaces_presets', $space_preset_interest_community);
drupal_write_record('spaces_presets', $space_preset_major_group);
drupal_write_record('spaces_presets', $space_preset_location_group);

# For each group, load node, match it to a space preset, and convert
foreach ($nids as $nid) {
  $groupnode = node_load(array('nid' => $nid));
  // Is it an interest group?
  if (in_array(19, array_keys($groupnode->taxonomy))) {
    // This type of group can only be an Interest group preset unless it was
    // created as a private group before.
    
    // If private, save as a private preset.
    if ($groupnode->og_private) {
      save_og_space($groupnode, 'private');
    }
    // Else, it's a normal interest group
    else {
      save_og_space($groupnode, 'interest_community');
    }
  }
  // Is it a class group?
  else if (in_array(322, array_keys($groupnode->taxonomy))) {
    save_og_space($groupnode, 'class_group');
  }
  // Is it a geographic group?
  if (in_array(20, array_keys($groupnode->taxonomy))) {
    save_og_space($groupnode, 'location_group');
  }
  // Is this a major group?
  if (in_array(790, array_keys($groupnode->taxonomy))) {
    save_og_space($groupnode, 'major_group');
  }
}

# Remove group and content aliases. Content aliases because they're too long.
# Group because they'll be replaced by PURL aliases.



function real_prefix($node) {
  // For og.
  if ($node->og_description) {
    $paths = explode('/', $node->path);
    return $paths[1];
  }
}

function save_og_space($groupnode, $space_type) {
  $prefix = real_prefix($groupnode);
  $spaces = array(
    'sid' => $groupnode->nid,
    'type' => 'og',
    'preset' => $space_type,
    'customizer' => 'b:0;',
  );
  
  $purl = array(
    'value' => $prefix,
    'provider' => 'spaces_og',
    'id' => $groupnode->nid,
  );
  
  $spaces_features_dashboard = array(
    'sid' => $groupnode->nid,
    'type' => 'og',
    'id' => 'group_dashboard',
    'value' => 2,
  );

  $spaces_features_book = array(
    'sid' => $groupnode->nid,
    'type' => 'og',
    'id' => 'atrium_book',
    'value' => 0,
  );
  
  $spaces_settings = array(
    'sid' => $groupnode->nid,
    'type' => 'og',
    'id' => 'home',
    'value' => 's:9:"dashboard";',
  );
  
  # TODO need to set spaces_features and spaces_settings
  drupal_write_record('spaces', $spaces);
  drupal_write_record('purl', $purl);
  drupal_write_record('spaces_features', $spaces_features_dashboard);
  drupal_write_record('spaces_features', $spaces_features_book);
  drupal_write_record('spaces_settings', $spaces_settings);
  echo "success! created space for " . $groupnode->title . "!\n";
  return "";
}