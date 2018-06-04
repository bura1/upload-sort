<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.helix.hr/
 * @since      1.0.0
 *
 * @package    Upload_Sort
 * @subpackage Upload_Sort/admin/partials
 */
?>

<?php global $wpdb; ?>

<div class="tab">
  <button class="tablinks" id="Btnupload" onclick="openEDO(event, 'upload')">Upload / Sort</button>
  <button class="tablinks" id="Btnfolders" onclick="openEDO(event, 'folders')">Upravljanje folderima</button>
  <button class="tablinks" id="Btnusers" onclick="openEDO(event, 'users')">Upravljanje korisnicima</button>
  <button class="tablinks" id="Btnstats" onclick="openEDO(event, 'stats')">Statistike</button>
</div>

<div id="upload" class="tabcontent">
	<?php
	include 'upload-pdf.php';
	include 'sort-pdf.php';
	?>
</div>

<div id="folders" class="tabcontent">
	<?php include 'manage-folders.php'; ?>
</div>

<div id="users" class="tabcontent">
<?php include 'manage-users.php'; ?>
</div>

<div id="stats" class="tabcontent">
<?php include 'stats.php'; ?>
</div>

<script>
// TAB
var seltab = sessionStorage.getItem('sel_tab');
if (seltab) {
  document.getElementById("Btn" + seltab).click();
} else {
  document.getElementById("BtnHome").click();
}

function openEDO(event, plannerName) {
  var i, tabcontent, tablinks;
  sessionStorage.setItem('sel_tab', plannerName);

  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(plannerName).style.display = "block";
  event.currentTarget.className += " active";
}
</script>