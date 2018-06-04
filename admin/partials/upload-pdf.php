<?php
// $current_user = wp_get_current_user();
// https://codex.wordpress.org/Function_Reference/wp_get_current_user
?>

<h3>Upload PDF</h3>
<form method="post" enctype="multipart/form-data" name="formUploadFile">      
    <label>Odaberi datoteke za upload:</label>
    <input type="file" name="fileToUpload[]" id="fileToUpload" multiple >
    <input type="submit" value="Upload File" name="pdfSubmit">
</form>

<?php
$target_dir = "/usr/www/users/helixi/plugin-test/wp-content/plugins/upload-sort/move-files/uploads/";
$exists_info1 = array();
$exists_info2 = array();

if ( isset($_POST['pdfSubmit']) ){
    $count=0;
    foreach ($_FILES['fileToUpload']['name'] as $filename) 
    {
        // Ako datoteka već postoji
        if (file_exists( $target_dir . $filename )) {
            $exists_info1[] = $filename;
        } else { /* Ako datoteka ne postoji */
            $temp=$target_dir;
            $tmp=$_FILES['fileToUpload']['tmp_name'][$count];
            $count=$count + 1;
            $temp=$temp.basename($filename);
            move_uploaded_file($tmp,$temp);

            // Insert to table - uploads
            $wpdb->insert( 
                "{$wpdb->prefix}us_uploads", 
                array( 
                    'name' => $filename, 
                    'uploaded' => date("Y-m-d h:i:s")
                ), 
                array( 
                    '%s', 
                    '%s'
                ) 
            );

            $temp='';
            $tmp='';
            $exists_info2[] = $filename;
        }
    }
    foreach ($exists_info1 as $filename) {
        echo '<strong>' . $filename . '</strong> - <span style="color:red;">datoteka već postoji</span><br>';
    }
    foreach ($exists_info2 as $filename) {
        echo '<strong>' . $filename . '</strong> - <span style="color:green;">datoteka uploadana</span><br>';
    }
}
?>