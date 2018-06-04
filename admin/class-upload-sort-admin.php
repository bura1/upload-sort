<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.helix.hr/
 * @since      1.0.0
 *
 * @package    Upload_Sort
 * @subpackage Upload_Sort/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Upload_Sort
 * @subpackage Upload_Sort/admin
 * @author     Helix <tomislav.buric@helix.hr>
 */
class Upload_Sort_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Upload_Sort_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Upload_Sort_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/upload-sort-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Upload_Sort_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Upload_Sort_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/upload-sort-admin.js', array( 'jquery' ), $this->version, false );

	}


	/* Admin menu */

	public function display_admin_page() {
		add_menu_page(
			'Upload and Sort',
			'Upload and Sort',
			'manage_options',
			'upload-sort',
			array($this, 'showPage'),
			'dashicons-upload',
			3
		);
	}
	public function showPage() {
		include plugin_dir_path(dirname(__FILE__)).'admin/partials/upload-sort-admin-display.php';
	}

}

// SHORTCODE [display-files]
function wp_upload_sort_shortcode(){

if ( is_user_logged_in() ) {

	global $wpdb;
	$dirs2 = '/usr/www/users/helixi/plugin-test/wp-content/plugins/upload-sort/move-files/folders/';
	$scanned_dirs2 = array_diff(scandir($dirs2), array('..', '.'));
	$user = wp_get_current_user();

	if ( current_user_can('administrator') ) { // Ako je korisnik administrator - Prikaz svih tablica

		foreach ($scanned_dirs2 as $dir) {
			echo '<table class="us-table">';
			echo '<tr class="table-name"><th>'.$dir.'</th></tr>';
			echo '<tr class="table-header"><th>Broj</th><th>Datum</th><th>Naziv datoteke</th><th>Klikni za preuzimanje</th><th>Preuzeto</th></tr>';
			if (file_exists($dirs2 . $dir)) {
			    $scanned_files = array_diff(scandir($dirs2 . $dir . '/'), array('..', '.'));
			    $count = 1;
			    foreach ($scanned_files as $file) {
			    	$date_sorted = $wpdb->get_results( "SELECT sorted FROM {$wpdb->prefix}us_sort WHERE filename='{$file}'" ); //$date_sorted[0]->sorted
			    	$dl_count = $wpdb->get_results( "SELECT dl_count FROM {$wpdb->prefix}us_sort WHERE filename='{$file}'" ); //$dl_count[0]->dl_count

			        echo '<tr class="table-row">
			        		<td>'.$count.'.</td>
			        		<td>'.$date_sorted[0]->sorted.'</td>
			        		<td>'.$file.'</td>

			        		<td>
			        			<a href="'.plugins_url().'/upload-sort/download.php?href='.$file.'" target="_blank">Download</a>
			        		</td>

			        		<td>'.$dl_count[0]->dl_count.'</td>
			        	  </tr>';
			        $count++;

			    }
			}
			echo "</table>";
		}		
	
	} else { // Ako korisnik nije administrator - Prikaz onih tablica koje mu pripadaju

		$user_cat = $wpdb->get_results( "SELECT category_id FROM {$wpdb->prefix}us_users WHERE user_id={$user->ID}" ); //$user_cat[0]->category_id
		$db_fname = $wpdb->get_results( "SELECT folder_name FROM {$wpdb->prefix}us_folders WHERE category_id={$user_cat[0]->category_id}" ); //$db_fname[0]->folder_name

		foreach ($scanned_dirs2 as $dir) {
			if ( $db_fname[0]->folder_name === $dir ) {
				echo '<table class="us-table">';
			    echo '<tr class="table-name"><th>'.$dir.'</th></tr>';
			    echo '<tr class="table-header"><th>Broj</th><th>Datum</th><th>Naziv datoteke</th><th>Klikni za preuzimanje</th><th>Preuzeto</th></tr>';
			    if (file_exists($dirs2 . $dir)) {
			        $scanned_files = array_diff(scandir($dirs2 . $dir . '/'), array('..', '.'));
			        $count = 1;
			        foreach ($scanned_files as $file) {
			        	$date_sorted = $wpdb->get_results( "SELECT sorted FROM {$wpdb->prefix}us_sort WHERE filename='{$file}'" ); //$date_sorted[0]->sorted
			        	$dl_count = $wpdb->get_results( "SELECT dl_count FROM {$wpdb->prefix}us_sort WHERE filename='{$file}'" ); //$dl_count[0]->dl_count

			            echo '<tr class="table-row">
			            		<td>'.$count.'.</td>
			            		<td>'.$date_sorted[0]->sorted.'</td>
			            		<td>'.$file.'</td>

			            		<td>
			            			<a href="'.plugins_url().'/upload-sort/download.php?href='.$file.'" target="_blank">Download</a>
			            		</td>

			            		<td>'.$dl_count[0]->dl_count.'</td>
			            	  </tr>';
			            $count++;

			        }
			    }
			    echo "</table>";
			}
		}
	}


} else {
	echo "You need to login";
}

}
add_shortcode('display-files', 'wp_upload_sort_shortcode');

?>