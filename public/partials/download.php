<?php

require_once(rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/wp-load.php');

$name = $_GET['href'];
$fldr = substr($name, 0, 7);

$user_cat = $wpdb->get_results( "SELECT category_id FROM {$wpdb->prefix}us_users WHERE user_id=8" );
var_dump($user_cat);

/*header('Location: http://plugin-test.idemo.me/wp-content/plugins/upload-sort/move-files/folders/'.$fldr.'/'.$name);
exit;*/

?>