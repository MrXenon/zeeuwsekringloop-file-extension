<?php

/**
 * This Admin controller file provide functionality for the Admin section of the 
 * My event organiser.
 * 
 * @author Jorinde Dekker
 * @version 0.1
 * 
 * Version history
 * 0.1 Jorinde Dekker Initial version
 */

 class ZeeuwseKringLoopFileExtension_AdminController {

    /**
     * This function will prepare all Admin functionality for the plugin
     */
    static function prepare() {
        
        // Check that we are in the admin area
        if ( is_admin() ) :

            // Add the sidebar Menu structure
            add_action( 'admin_menu', array('ZeeuwseKringLoopFileExtension_AdminController', 'addMenus' ) );

        endif;
    }

    /**
     * Add the Menu structure to the Admin sidebar
     */
    static function addMenus() {

        add_menu_page(
            //string $page_title The text to be displayed in the title tags
            // of the page when the menu is selected
            __( 'ZKL file extension', 'zeeuwsekringloop-file-extenstion'),
            // string $menu_title The text to be used for the menu
            __( 'ZKL file extension', 'zeeuwsekringloop-file-extenstion' ),
            // string $capability The capability required for this menu to be
            //displayed to the user.
            '',
            //string $menu_slug THe slug name to refer to this menu by (should
            //be unique for this menu)
            'zeeuwsekringloop-file-extenstion-admin',
            
            // callback $function The function to be called to output the content for this page
            array('ZeeuwseKringLoopFileExtension_AdminController', 'adminMenuPage'),

            'dashicons-download'
            
            // int $position The position in the menu order this one should appear
        );

        add_submenu_page (
        // string $parent_slug The slug name for the parent menu
        // (or the file name of a standard Wordpress admin page)
            'zeeuwsekringloop-file-extenstion-admin',

            // string $page_title The text to be displayed in the title tags of
            // the page when the menu is selected
            __( 'dashboard', 'zeeuwsekringloop-file-extenstion' ),

            // string $menu_title The text to be used for the menu
            __( 'Dashboard', 'zeeuwsekringloop-file-extenstion'),

            // string $capability The capability required for this menu to be
            // displayed to the user
            'manage_options',

            // string $menu_slug The slug name to refer to this menu by (should be
            // unique for this menu)
            'zkl_fe_dashboard',

            // callback $function The function to be called to output the content for this page
            array('ZeeuwseKringLoopFileExtension_AdminController', 'adminSubMenuDashboard')
        );

    //Opdracht 3        
        add_submenu_page (
            'zeeuwsekringloop-file-extenstion-admin',

            __( 'overview', 'zeeuwsekringloop-file-extenstion' ),

            __( 'Overview', 'zeeuwsekringloop-file-extenstion'),

            'manage_options',

            'zkl_fe_overview',

            array('ZeeuwseKringLoopFileExtension_AdminController', 'adminSubMenuOverview')

        );



        add_submenu_page (
            // string $parent_slug The slug name for the parent menu
            // (or the file name of a standard Wordpress admin page)
            'zeeuwsekringloop-file-extenstion-admin',

            // string $page_title The text to be displayed in the title tags of
            // the page when the menu is selected
            __( 'download_files', 'zeeuwsekringloop-file-extenstion' ),

            // string $menu_title The text to be used for the menu
            __( 'Download files', 'zeeuwsekringloop-file-extenstion'),

            // string $capability The capability required for this menu to be
            // displayed to the user
            'manage_options',

            // string $menu_slug The slug name to refer to this menu by (should be 
            // unique for this menu)
            'zkl_fe_download_files',

            // callback $function The function to be called to output the content for this page
            array('ZeeuwseKringLoopFileExtension_AdminController', 'adminSubMenuDownloadFiles')
        );

        add_submenu_page (
        // string $parent_slug The slug name for the parent menu
        // (or the file name of a standard Wordpress admin page)
            'zeeuwsekringloop-file-extenstion-admin',

            // string $page_title The text to be displayed in the title tags of
            // the page when the menu is selected
            __( 'download_files_overview', 'zeeuwsekringloop-file-extenstion' ),

            // string $menu_title The text to be used for the menu
            __( 'Download files overview', 'zeeuwsekringloop-file-extenstion'),

            // string $capability The capability required for this menu to be
            // displayed to the user
            'manage_options',

            // string $menu_slug The slug name to refer to this menu by (should be
            // unique for this menu)
            'zkl_fe_download_files_overview',

            // callback $function The function to be called to output the content for this page
            array('ZeeuwseKringLoopFileExtension_AdminController', 'adminSubMenuDownloadFilesOverview')
        );

    }

        /**
        * The main menu page
         */
            static function adminMenuPage() {

                //Include the view for this menu page.
                include ZKL_FE_ADMIN_VIEWS_DIR . '/admin_main.php';
            }

     /**
      * the submenu page for the event categories
      */
     static function adminSubMenuDashboard()
     {
         //include the view for this submenu page.
         include ZKL_FE_ADMIN_VIEWS_DIR . '/zkl_fe_dashboard.php';
     }

            //The Submenu page for the event types Opdr3
            static function adminSubMenuOverview (){
            include ZKL_FE_ADMIN_VIEWS_DIR . '/zkl_fe_overview.php';
            }

        /**
        * the submenu page for the event categories
        */
            static function adminSubMenuDownloadFiles()
        {
            //include the view for this submenu page.
            include ZKL_FE_ADMIN_VIEWS_DIR . '/zkl_fe_download_files.php';
        }
     /**
      * the submenu page for the event categories
      */
     static function adminSubMenuDownloadFilesOverview()
     {
         //include the view for this submenu page.
         include ZKL_FE_ADMIN_VIEWS_DIR . '/zkl_fe_download_files_overview.php';
     }


    }
?>