<!-- Upravljanje folderima -->
<br><br><br>
<hr>
<h3>Upravljanje folderima</h3>
<br>
<?php
$dirs = '/usr/www/users/helixi/plugin-test/wp-content/plugins/upload-sort/move-files/folders/';
$scanned_dirs = array_diff(scandir($dirs), array('..', '.'));


function rrmdir($src) {
    $dir = opendir($src);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            $full = $src . '/' . $file;
            if ( is_dir($full) ) {
                rrmdir($full);
            }
            else {
                unlink($full);
            }
        }
    }
    closedir($dir);
    rmdir($src);
}


?>

<!-- Add new folder -->
<form method="post" enctype="multipart/form-data" name="newFolder">      
    <label>Dodaj novi folder</label>
    <input type="text" name="folderName">
    <label>Odaberi kategoriju</label>

    <!-- Select Folders subcategory -->
    <select name="folderCategory">
    <?php
        $cat_args = array( 'parent'  => 16, 'hide_empty' => 0 );
        $categories = get_categories($cat_args);
        foreach($categories as $category) {
            echo '<option value="' . $category->term_id . '">' . get_cat_name($category->term_id) . '</option>';
        }
    ?>
    </select>

    <input type="submit" value="Dodaj Folder" name="folderSubmit">
</form>

<!-- Creating folder BUTTON -->
<?php
if ( isset($_POST['folderSubmit']) ){
    mkdir('/usr/www/users/helixi/plugin-test/wp-content/plugins/upload-sort/move-files/folders/' . $_POST['folderName']);
    
    $wpdb->insert( 
        "{$wpdb->prefix}us_folders", 
        array( 
            'folder_name' => $_POST['folderName'], 
            'category_id' => $_POST['folderCategory']
        ), 
        array( 
            '%s', 
            '%s'
        ) 
    );
}
?>

<br>

<!-- Delete folder -->
<form method="post" enctype="multipart/form-data" name="delFolder">      
    <label>Obriši folder</label>
    <select name="dFldr">
        <?php
            foreach ($scanned_dirs as $dir) {
                echo '<option value="' . $dir . '">' . $dir . '</option>';
            }
        ?>
    </select>
    <input type="submit" value="Obriši Folder" name="delFolderSubmit">
</form>
<?php
if ( isset($_POST['delFolderSubmit']) ){
    rrmdir('/usr/www/users/helixi/plugin-test/wp-content/plugins/upload-sort/move-files/folders/' . $_POST['dFldr']);

    // Delete folder from database
    $wpdb->delete( 
        "{$wpdb->prefix}us_folders", 
        array( 
            'folder_name' => $_POST['dFldr']
        ), 
        array( 
            '%d'
        ) 
    );
}
?>

<br><br><br>

<!-- Lista foldera -->
<?php

$dirs2 = '/usr/www/users/helixi/plugin-test/wp-content/plugins/upload-sort/move-files/folders/';
$scanned_dirs2 = array_diff(scandir($dirs2), array('..', '.'));

foreach ($scanned_dirs2 as $dir) {
    echo '<div style="min-width:150px; float:left; margin-right:15px;">';
    echo '<strong>'.$dir.'</strong>';
    echo "<hr>";
    if (file_exists($dirs2 . $dir)) {
        $scanned_files = array_diff(scandir($dirs2 . $dir . '/'), array('..', '.'));
        foreach ($scanned_files as $file) {
            echo $file . '<br>';
        }
    }
    echo '</div>';
}
?>