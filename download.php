<?php

require_once(rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/wp-load.php');
$name = $_GET['href'];
$fldr = substr($name, 0, 7);
$user = wp_get_current_user();

$current_dl_count = $wpdb->get_results( "SELECT dl_count FROM {$wpdb->prefix}us_sort WHERE filename='{$name}'" );

// wp_us_sort, Download counter
$wpdb->update( 
    "{$wpdb->prefix}us_sort", 
    array( 
        'dl_count'	=> $current_dl_count['0']->dl_count + 1
    ),
    array(
		'filename'	=> $name
    )
);

// wp_us_logs, Logs
$wpdb->insert( 
    "{$wpdb->prefix}us_logs", 
    array( 
    	'filename'	=> $name,
        'date'	=> date("Y-m-d"),
        'time'  => date("H:i:s"),
        'user_id'	=> $user->ID,
        'username'	=> $user->user_login
    )
);

header('Location: http://plugin-test.idemo.me/wp-content/plugins/upload-sort/move-files/folders/'.$fldr.'/'.$name);
exit;

?>