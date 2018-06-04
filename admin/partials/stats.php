<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
$( function() {
	$( "#datepicker" ).datepicker({
		dateFormat: "yy-mm-dd"
	});
} );
</script>


<h3>Statistike</h3>

<form method="post" name="statsForm"> 
	Datum: <input type="text" id="datepicker" name="date">
	Korisnik: <select name="user_opt">
		<option value="empty">Odaberi korisnika</option>
		<?php $get_users = $wpdb->get_results( "SELECT user_name, user_id FROM {$wpdb->prefix}us_users" );
		foreach ($get_users as $user) {
			echo '<option value="'.$user->user_name.'">'.$user->user_name.'</option>';
		} ?>
	</select>
	<input type="submit" value="Submit" name="stats_submit">
</form><br><br><br>

<?php

if( isset($_POST['stats_submit']) ) {

	if ( $_POST['user_opt'] == 'empty' ) { // Ako je samo datum unešen
		$dl_date = $_POST['date'];
		$table_results = $wpdb->get_results( "SELECT filename, username, date, time FROM {$wpdb->prefix}us_logs WHERE date='{$dl_date}'" );
		$num = 1;
		echo '<strong>'.$dl_date.'</strong>';
		echo '<table class="stats-table"><tr><th>Broj</th><th>Datoteka</th><th>Korisnik</th><th>Datum</th><th>Vrijeme</th></tr>';
		foreach ($table_results as $result) {
			echo '<tr><td>'.$num.'.</td><td>'.$result->filename.'</td><td>'.$result->username.'</td><td>'.$result->date.'</td><td>'.$result->time.'</td></tr>';
			$num++;
		}
		echo "</table>";

	} elseif ( empty($_POST['date']) ) { // Ako je samo korisnik unešen
		$dl_user = $_POST['user_opt'];
		$table_results = $wpdb->get_results( "SELECT filename, username, date, time FROM {$wpdb->prefix}us_logs WHERE username='{$dl_user}'" );
		$num = 1;
		echo '<strong>'.$dl_user.'</strong>';
		echo '<table class="stats-table"><tr><th>Broj</th><th>Datoteka</th><th>Korisnik</th><th>Datum</th><th>Vrijeme</th></tr>';
		foreach ($table_results as $result) {
			echo '<tr><td>'.$num.'.</td><td>'.$result->filename.'</td><td>'.$result->username.'</td><td>'.$result->date.'</td><td>'.$result->time.'</td></tr>';
			$num++;
		}
		echo "</table>";

	} elseif ( $_POST['user_opt'] == 'empty' && empty($_POST['date']) ) { // Ako ništa nije unešeno
		$table_results = $wpdb->get_results( "SELECT filename, username, date, time FROM {$wpdb->prefix}us_logs" );
		$num = 1;
		echo '<table class="stats-table"><tr><th>Broj</th><th>Datoteka</th><th>Korisnik</th><th>Datum</th><th>Vrijeme</th></tr>';
		foreach ($table_results as $result) {
			echo '<tr><td>'.$num.'.</td><td>'.$result->filename.'</td><td>'.$result->username.'</td><td>'.$result->date.'</td><td>'.$result->time.'</td></tr>';
			$num++;
		}
		echo "</table>";	

	} else { // Ako je sve unešeno (datum i korisnik)
		$dl_date = $_POST['date'];
		$dl_user = $_POST['user_opt'];
		$table_results = $wpdb->get_results( "SELECT filename, username, date, time FROM {$wpdb->prefix}us_logs WHERE username='{$dl_user}' AND date='{$dl_date}'" );
		$num = 1;
		echo '<strong>'.$dl_user.'</strong><br>';
		echo '<strong>'.$dl_date.'</strong>';
		echo '<table class="stats-table"><tr><th>Broj</th><th>Datoteka</th><th>Korisnik</th><th>Datum</th><th>Vrijeme</th></tr>';
		foreach ($table_results as $result) {
			echo '<tr><td>'.$num.'.</td><td>'.$result->filename.'</td><td>'.$result->username.'</td><td>'.$result->date.'</td><td>'.$result->time.'</td></tr>';
			$num++;
		}
		echo "</table>";	
	}

}

?>

<!-- Tko nije preuzeo -->

<br><br><br>
<h3>Tko nije preuzeo</h3>

<form method="post" name="tkonije">
	<input type="text" name="pdf_name" placeholder="Unesi točan naziv datoteke...">
	<input type="submit" value="Provjeri" name="tkonije_submit">
</form>

<?php

if ( isset($_POST['tkonije_submit']) ) {

	$dataname = $_POST['pdf_name'];
	// Select all users
	$allusers = $wpdb->get_results( "SELECT user_name FROM {$wpdb->prefix}us_users" );
	// Select all users who downloaded file
	$tkoje = $wpdb->get_results( "SELECT username FROM {$wpdb->prefix}us_logs WHERE filename='{$dataname}'" );

	// Select all files from all folders
	$foldersurl = dirname(dirname(dirname(__FILE__))) . '/move-files/folders/';
	function find_all_files($dir) 
	{ 
	    $root = scandir($dir); 
	    foreach($root as $value) 
	    { 
	        if($value === '.' || $value === '..') {continue;} 
	        if(is_file("$dir/$value")) {$result[]="$dir/$value";continue;} 
	        foreach(find_all_files("$dir/$value") as $value) 
	        { 
	            $result[]=$value; 
	        } 
	    } 
	    return $result; 
	}	

	// Check if file exists
	foreach ( find_all_files($foldersurl) as $file ) {
		if ( strpos($file, $dataname) === false ) {
			echo "ne postoji<br>";
		} else {
			echo "postoji<br>";
		}
	}

	if ( empty($tkoje) ) {
		echo '<br>Ne postoji datoteka <strong>'.$_POST['pdf_name'].'</strong> ili ju nitko nije preuzeo.';
	} else {

		foreach ($allusers as $user) {
			$allusers2[] = $user->user_name;
		}
		foreach ($tkoje as $tko) {
			$tkoje2[] = $tko->username;
		}

		$output = array_diff($allusers2, $tkoje2);

		echo '<br><strong>' . $dataname . '</strong> datoteku nije preuzeo:<br>';
		foreach ($output as $out) {
			echo $out.'<br>';
		}

	}
}



?>