<?php
// Include model:
include ZKL_FE_MODEL_DIR . "/ZklDownloadFiles.php";

// Declare class variable:
$download_files = new ZklDownloadFiles();

// Set base url to current file and add page specific vars
$base_url = get_admin_url() . 'admin.php';
$params = array('page' => basename(__FILE__, ".php"));

// Add params to base url
$base_url = add_query_arg($params, $base_url);

// Get the GET data in filtered array
$get_array = $download_files->getGetValues();

// Keep track of current action.
$action = FALSE;
if (!empty($get_array)) {

    // Check actions
    if (isset($get_array['action'])) {
        $action = $download_files->handleGetAction($get_array);
    }
}

/* Na checken     */
// Get the POST data in filtered array
$post_array = $download_files->getPostValues();

// Collect Errors
$error = FALSE;
// Check the POST data
if (!empty($post_array['add'])) {

    // Check the add form:
    $add = FALSE;
    // Save event types
    $result = $download_files->save($post_array);
    if ($result) {
        // Save was succesfull
        $add = TRUE;
    } else {
        // Indicate error
        $error = TRUE;
    }
}

// Check the update form:
if (isset($post_array['update'])) {
    // Save event types
    $download_files->update($post_array);
}

// Add bootstrap.
include_once ZKL_FE_BOOTSTRAP_DIR . '/bootstrap.php';
// include stylesheet
wp_enqueue_style('style', '/wp-content/plugins/zeeuwsekringloop-file-extension/includes/bootstrap/style.css');

?>


<div class="wrap">

    <?php
    if (isset($add)) {
        echo($add ? "<p>Added a new type</p>" : "");
    }
    // Check if action == update : then start update form
    echo(($action == 'update') ? '<form action="' . $base_url . '" method="post">' : '');
    ?>
    <h2>Download Files Overview</h2>
    <table class="table table-backend">
        <thead class="table-dark">
        <tr>
            <th width="10">#</th>
            <th width="500">Download file name</th>
            <th width="2000">Download file description</th>
            <th width="2000">Download Link</th>
            <th colspan="2" width="200">Actions</th>
        </tr>
        </thead>
        <!-- <tr><td colspan="3">Event types rij 1</td></tr> -->
        <?php
        //*
        if ($download_files->getNrOfDownloadFiles() < 1) {
            ?>
            <tr>
                <td colspan="6">Start adding Download files
            </tr>
        <?php } else {
            $type_list = $download_files->getDownloadFileList();

            //** Show all event types in the tabel
            foreach ($type_list as $download_files_obj) {

                // Create update link
                $params = array('action' => 'update', 'id' => $download_files_obj->getId());

                // Add params to base url update link
                $upd_link = add_query_arg($params, $base_url);

                // Create delete link
                $params = array('action' => 'delete', 'id' => $download_files_obj->getId());

                // Add params to base url delete link
                $del_link = add_query_arg($params, $base_url);
                ?>

                <tr>
                    <td width="10"><?php echo $download_files_obj->getId();
                        ?></td>
                    <?php
                    // If update and id match show update form
                    // Add hidden field id for id transfer
                    if (($action == 'update') && ($download_files_obj->getId() == $get_array['id'])) {
                        ?>
                        <td width="500"><input type="hidden" name="id" value="<?php echo $download_files_obj->getId(); ?>">
                            <input type="text" name="file_name" value="<?php echo $download_files_obj->getName(); ?>"></td>
                        <td width="2000"><input style="width:500px;" type="text" name="file_description" value="<?php echo $download_files_obj->getDescription(); ?>"></td>
                        <td width="2000"><input style="width:500px;" type="text" name="file_link" value="<?php echo $download_files_obj->getLink(); ?>"></td>
                        <td colspan="2"><input type="submit" name="update" value="Updaten"/></td>
                    <?php } else { ?>
                        <td width="180"><?php echo $download_files_obj->getName(); ?></td>
                        <td width="200"><?php echo $download_files_obj->getDescription(); ?></td>
                        <td width="200"><?php echo $download_files_obj->getLink(); ?></td>
                        <?php if ($action !== 'update') {
                            // If action is update don’t show the action button
                            ?>
                            <td><a href="<?php echo $upd_link; ?>">Update</a></td>
                            <td><a href="<?php echo $del_link; ?>">Delete</a></td>
                            <?php
                        } // if action !== update
                        ?>
                    <?php } // if acton !== update ?>
                </tr>
                <?php
            }
            ?>


        <?php }
        ?>
    </table>
    <?php
    // Check if action = update : then end update form
    echo(($action == 'update') ? '</form>' : '');
    /** Finally add the new entry line only if no update action **/
    if ($action !== 'update') {
        ?>

        <?php
    } // if action !== update
    ?>
</div>