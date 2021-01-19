<?php

//	Add	the	main view shortcode
add_shortcode('submissions','load_main_view');

function load_main_view( $atts, $content = NULL){
    //Include the main view
        include ZKL_FE_INCLUDES_VIEWS_DIR.
            '/submissions.php';
}

