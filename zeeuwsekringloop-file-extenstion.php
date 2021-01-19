<?php

defined( 'ABSPATH' ) OR exit;

/**
 * Plugin Name: ZeeuweseKringLoop file extension
 * Plugin URI: https://github.com/MrXenon
 * Description: This add-on will show a download link when the user submits the requested information. Alternatively the add-on will allow the admin to add download files by link in the back-end.
 * Author: Kevin Schuit
 * Author URI: https://kevinschuit.com
 * Version: 1.0.1
 * Text Domain: zeeuwsekringloop-file-extenstion
 * Domain Path: languages
 * 
 * This is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even teh implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
 * GNU General Public License for more details.
 * 
 * You should have received a cpoy of the GNU General Publilc License 
 * along with your plugin. If not, see <http://www.gnu.org/licenses/>.
 */

 //Define the plugin name:
 //Activeren en deactiveren
 define ( 'ZKL_FE', __FILE__ );

 //Inculde the general defenition file:
 require_once plugin_dir_path ( __FILE__ ) . 'includes/defs.php';

/* Register the hooks */
    register_activation_hook( __FILE__, array( 'ZeeuwseKringLoopFileExtension', 'on_activation' ) );
    register_deactivation_hook( __FILE__, array( 'ZeeuwseKringLoopFileExtension', 'on_deactivation' ) );
 
 class ZeeuwseKringLoopFileExtension
 {
     public function __construct()
     {

         //Fire a hook before the class is setup.
         do_action('zkl_fe_pre_init');

         //Load the plugin
         add_action('init', array($this, 'init'), 1);
     }

     public static function on_activation()
     {
         if ( ! current_user_can( 'activate_plugins' ) )
             return;
         $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
         check_admin_referer( "activate-plugin_{$plugin}" );

         // Loop through the database tables, if table does not exist, create the table.
         ZeeuwseKringLoopFileExtension::ZKLcreateDb();
     }
     public static function on_deactivation()
     {
         if ( ! current_user_can( 'activate_plugins' ) )
             return;
         $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
         check_admin_referer( "deactivate-plugin_{$plugin}" );


         # Uncomment the following line to see the function in action
         # exit( var_dump( $_GET ) );
     }

     /**
      * Loads the plugin into Wordpress
      *
      * @since 1.0.0
      */
     public function init()
     {

         // Run hook once Plugin has been initialized
         do_action('zkl_fe_init');

         // Load admin only components.
         if (is_admin()) {

             //Load all admin specific includes
             $this->requireAdmin();

             //Setup admin page
             $this->createAdmin();
         } else {

         }

         // Load the view shortcodes
         $this->loadViews();
     }

     /**
      * Loads all admin related files into scope
      *
      * @since 1.0.0
      */
     public function requireAdmin()
     {

         //Admin controller file
         require_once ZKL_FE_ADMIN_DIR . '/ZeeuwseKringLoopFileExtension_AdminController.php';
     }

     /**
      * Admin controller functionality
      */
     public function createAdmin()
     {
         ZeeuwseKringLoopFileExtension_AdminController::prepare();
     }

     /**
      * Load the view shortcodes:
      */
     public function loadViews()
     {
         include ZKL_FE_INCLUDES_VIEWS_DIR . '/view_shortcodes.php';
     }

     public static function ZKLcreateDb()
     {

         require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

         //Calling $wpdb;
         global $wpdb;

         $charset_collate = $wpdb->get_charset_collate();

         //Name of the table that will be added to the db
         $download = $wpdb->prefix . "zkl_download_files";
         $submissions =    $wpdb->prefix . "zkl_submissions";

        /*Create Database*/
         //Create the download table
         $sql = "CREATE TABLE IF NOT EXISTS $download (
            zkl_download_files_id INT NOT NULL AUTO_INCREMENT,
            zkl_name VARCHAR(150) NOT NULL,
            zkl_description VARCHAR(150) NOT NULL,
            zkl_link VARCHAR(1024) NOT NULL,
            PRIMARY KEY  (zkl_download_files_id))
            ENGINE = InnoDB $charset_collate";
         dbDelta($sql);

         //Create the submissions table
         $sql = "CREATE TABLE IF NOT EXISTS $submissions (
            zkl_submission_files_id INT NOT NULL AUTO_INCREMENT,
            functie VARCHAR(150) NOT NULL,
            description VARCHAR(150) NOT NULL,
            optional VARCHAR(1024) NOT NULL,
            PRIMARY KEY  (zkl_submission_files_id))
            ENGINE = InnoDB $charset_collate";
         dbDelta($sql);

     }

 }

 // Instantiate the class
 $zklfe = new ZeeuwseKringLoopFileExtension();
 ?>