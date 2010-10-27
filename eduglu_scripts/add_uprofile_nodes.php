#!/usr/bin/php -q
<?php

// auto creates uprofile nodes for each user without one and inserts an entry into the bio table

// bootstrap
// running Drupal's bootstrap on the command line causes a few notices, so surpress notices for a moment
#error_reporting(E_ERROR | E_PARSE);

// bootstrap Drupal
require_once('includes/bootstrap.inc');
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

// turn error reporting back to normal
#error_reporting(E_ALL);

// select list of uids who don't have an uprofile node from bio table
$uids = db_query('SELECT u.uid FROM {users} u WHERE u.uid NOT IN (SELECT uid FROM {bio})');

if (!$uids) print "no new users";
// create uprofile nodes with node_factory
while ($uid = db_fetch_object($uids)) {
  global $user;
  $user->uid = $uid->uid;
  if ($uid->uid == 0) continue;
  $edit = node_factory_create_node( 'uprofile');
  node_factory_set_value($edit, 'title', 'Profile Page');
  $nid = node_factory_save_node($edit);
  print "Making user profile node for uid ".$uid->uid."\n"; 
  // make inserts into bio table after each node is created
  db_query("INSERT INTO {bio} (nid, uid) VALUES (%d, %d)", $nid, $uid->uid);
}

?>
