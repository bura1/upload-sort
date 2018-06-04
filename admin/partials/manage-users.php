<!-- Upravljanje korisnicima -->
<h3>Upravljanje korisnicima</h3>
<br>

<?php

$args = array(
	'role'	=> 'doc'
);
$doc_users = get_users($args);

// $user->ID
// $user->user_login
$folders = $wpdb->get_results( "SELECT category_id, folder_name FROM {$wpdb->prefix}us_folders" );

echo '<div class="us-list">';
foreach ($doc_users as $user) {
	echo '<div class="user-box">';
	echo '<strong>'.$user->user_login.'</strong>';
	echo '<form method="post" name="user_roles">';
		echo '<input type="hidden" name="user_id" value="'.$user->ID.'">';
		echo '<input type="hidden" name="user_name" value="'.$user->user_login.'">';
		foreach ($folders as $folder) {
			echo '<input type="radio" name="category_id" value="'.$folder->category_id.'"> '.$folder->folder_name.'<br>';
		}
	echo '<input type="submit" value="Submit" name="user_role_submit">';
	echo '</form>';
	echo '</div>';
}
echo '</div>';

if ( isset($_POST['user_role_submit']) ) {

	if ( !empty($_POST['category_id']) ) {
		$check_if_exist = $wpdb->get_results( "SELECT user_id FROM {$wpdb->prefix}us_users WHERE user_id={$_POST['user_id']}" );
		
		if( empty($check_if_exist) ) {   // If user doesn't exist
		    $wpdb->insert( 
		        "{$wpdb->prefix}us_users", 
		        array( 
		        	'user_id'		  => $_POST['user_id'],
		            'category_id'	  => $_POST['category_id'],
		            'user_name'		  => $_POST['user_name']
		        )
		    );
		} else { // If user exist
		    $wpdb->update( 
		        "{$wpdb->prefix}us_users", 
		        array( 
		            'category_id'	  => $_POST['category_id']
		        ),
		        array(
					'user_id'		  => $_POST['user_id']
		        )
		    );
		}
	} else {
		echo '<p style="color:red;">Niste odabrali kategoriju!</p>';
	}
    
}

?>

<br><br>
<h3>Trenutna korisnička prava</h3>

<?php

foreach ($doc_users as $user) {
	$user_rights = $wpdb->get_results( "SELECT category_id FROM {$wpdb->prefix}us_users WHERE user_id={$user->ID}" );
	$folder_cat = $wpdb->get_results( "SELECT folder_name FROM {$wpdb->prefix}us_folders WHERE category_id={$user_rights['0']->category_id}" );

	echo $user->user_login . ' - ' . $folder_cat['0']->folder_name . '<br><br>';
}

?>