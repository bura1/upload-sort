<br><br><br>
<hr>
<h3>Sortiraj PDF</h3>
<form method="post">
	<input type="submit" name="submit" value="Sortiraj">
</form>
<br>

<?php

$uploads = '/usr/www/users/helixi/plugin-test/wp-content/plugins/upload-sort/move-files/uploads';
$files = array_diff(scandir($uploads), array('..', '.'));
$source = '/usr/www/users/helixi/plugin-test/wp-content/plugins/upload-sort/move-files/uploads/';
$backup = '/usr/www/users/helixi/plugin-test/wp-content/plugins/upload-sort/move-files/backup-uploads/';
$destination = '/usr/www/users/helixi/plugin-test/wp-content/plugins/upload-sort/move-files/folders/';
$nonexistent = array();
$sorted = array();
$unsorted = array();

// Sort and delete
if ( isset($_POST['submit']) ){
    
    foreach ($files as $file) {
      
      // Copy to backup-uploads folder
      if ( is_file($file) ) {
        copy($source.$file, $backup.$file);   
      }

      // Check if [DOC_X] folder doesn't exist
      if( !is_dir($destination . substr($file, 0, 7)) ) {
        $nonexistent[] = substr($file, 0, 7);
      } else {
      
        // Check if file does not exists
        if( !file_exists($destination . substr($file, 0, 7) . "/" . $file) ) { 
         
          // Copy to [DOC_X] folder if file doesn't exist
          if ( strpos($file, substr($file, 0, 7) ) !== false) {
            copy($source . $file, $destination . substr($file, 0, 7) . "/" . $file);
            $sorted[] = substr($file, 0, 7);
            echo $file . " sortiran<br>";

            // Check if file is already sorted
            $check_if_sorted = $wpdb->get_results( "SELECT filename FROM {$wpdb->prefix}us_sort WHERE filename='{$file}'" );

            if ( empty($check_if_sorted) ) {

              $wpdb->insert( // If file isn't sorted
                  "{$wpdb->prefix}us_sort", 
                  array( 
                      'filename' => $file,
                      'folder_name' => substr($file, 0, 7),
                      'sorted' => date("Y-m-d H:i:s")
                  )
              );

            } else {

              $wpdb->update( // If file is already sorted
                  "{$wpdb->prefix}us_sort", 
                  array( 
                      'sorted' => date("Y-m-d H:i:s")
                  ),
                  array(
                      'filename' => $file
                  )
              );

            }
          }

        } else {
          echo $file . " postoji<br>";
        }
      }
    }

    // echo file and folder status from $nonexistent array
    if ( !empty($nonexistent) ) {
      $nonexistent2 = array_unique($nonexistent);
      echo '<p>' . count($nonexistent) . ' datoteka nije sortirano!</p>';
      echo '<p>Ne postoje folderi:</p>';
      foreach ($nonexistent2 as $folder) {
        echo '<strong>' . $folder . '</strong><br>';
      }
    }
}

?>