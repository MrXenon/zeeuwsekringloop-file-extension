<?php

/**
 * Defenitions needed in the plugin
 * 
 * @author
 * @version 0.1
 * 
 * Version history
 * 0.1      Initial version
 */
// The version has to be equal to the version shown in wordpress inserted in the zeeuwsekringloop-file-extenstion.php file.
define ( 'ZKL_FE_VERSION', '1.0.1' );

// Minimum required Wordpress version for this plugin
define ( 'ZKL_FE_REQUIRED_WP_VERSION', '4.0' );

define ( 'ZKL_FE_BASENAME', plugin_basename( ZKL_FE ) );

define ( 'ZKL_FE_NAME', trim( dirname ( ZKL_FE_BASENAME ), '/' ) );

// Folder Structure
define ( 'ZKL_FE_DIR', untrailingslashit( dirname ( ZKL_FE ) ) );

define ( 'ZKL_FE_INCLUDES_DIR', ZKL_FE_DIR . '/includes' );

define ( 'ZKL_FE_INCLUDES_VIEWS_DIR', ZKL_FE_INCLUDES_DIR	. '/views'	);

define('ZKL_FE_BOOTSTRAP_DIR', ZKL_FE_INCLUDES_DIR . '/bootstrap');

define ( 'ZKL_FE_MODEL_DIR', ZKL_FE_INCLUDES_DIR . '/model' );

define ( 'ZKL_FE_ADMIN_DIR', ZKL_FE_DIR . '/admin' );

define ( 'ZKL_FE_ADMIN_VIEWS_DIR', ZKL_FE_ADMIN_DIR . '/views' );

?>