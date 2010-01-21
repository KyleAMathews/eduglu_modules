<?php
# set up the drupal directory -- very important 
$DRUPAL_DIR = '/var/www/island_prod/';
# set some server variables so Drupal doesn't freak out
$_SERVER['SCRIPT_NAME'] = '/script.php';
$_SERVER['SCRIPT_FILENAME'] = '/script.php';
$_SERVER['HTTP_HOST'] = 'example.com';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['REQUEST_METHOD'] = 'POST';
 
# act as the first user
global $user;
$user->uid = 1;
# gain access to drupal
chdir($DRUPAL_DIR);  # drupal assumes this is the root dir
error_reporting(E_ERROR | E_PARSE); # drupal throws a few warnings, so suppress these
require_once('./includes/bootstrap.inc');
echo drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
# restore error reporting to its normal setting
error_reporting(E_ALL);

# Set some variables
$origin_group = 251;
$dest_group = 223;
$origin_name = db_result(db_query("SELECT title FROM {node} WHERE nid = %d", $origin_group));
$dest_name = db_result(db_query("SELECT title FROM {node} WHERE nid = %d", $dest_group));

// Get list of admins -- don't remove them from the origin group.
$admins = array();
$results = db_query("SELECT uid FROM {og_uid} WHERE is_admin = 1 AND nid = %d", $origin_group);
while ($data = db_fetch_array($results)) {
  $admins[] = $data['uid'];
}

$test = true; // Don't actually move anything if true.

if ($test) {
  echo "----TESTING----\n\n\n";
}

// Get list of uids of members in origin group.
$results = db_query("SELECT uid FROM og_uid WHERE nid = %d", $origin_group);
$origin_uids = array();
while ($data = db_fetch_array($results)) {
  $origin_uids[] = $data['uid'];
}

echo "Moving uids to new group\n";

foreach ($origin_uids as $uid) {
  if (!$test) {
    og_save_subscription($dest_group, $uid, array("is_active" => 1));
  }
  
  echo "Added uid " . $uid . " to group: " . $dest_name . " from group: " . $origin_name . "\n";
}

foreach ($origin_uids as $uid) {
  if (!$test && !in_array($uid, $admins)) {
    og_delete_subscription($origin_group, $uid);
  }
  
  echo "Deleted uid " . $uid . " from group: " . $origin_name . "\n"; 
}

// Inform Group memebers of the Move.
$subject = "Moved your group subscription from " . $origin_name . " to " . $dest_name;
$body = "This message is to inform you that your group subscription in the " . $orgin_name . " group has been moved to the " . $dest_name . ". \n\n You may visit your new group at " . l($dest_name, "node/" . $dest_group);

echo $subject . "\n";
echo $body;

if (!$test) {
  foreach ($origin_uids as $uid) {
    notifications_lite_send($uid, $subject, $body);    
  }
}
