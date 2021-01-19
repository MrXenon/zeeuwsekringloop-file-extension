<?php
// Include the Event class from the model.
require_once ZKL_FE_MODEL_DIR . '/ZklSubmissions.php';

$submissions = new ZklSubmissions();

// Set base url to current file and add page specific vars
$base_url = get_site_url() . '/lesmateriaal/';
//$params = array('page' => basename(__FILE__, ".php"));

// Add params to base url
//$base_url = add_query_arg($params, $base_url);

// Keep track of current action.
$action = FALSE;
if (!empty($get_array)) {

    // Check actions
    if (isset($get_array['action'])) {
        $action = $submissions->handleGetAction($get_array);
    }
}
/* Na checken */
// Get the POST data in filtered array
$post_array = $submissions->getPostValues();

// Collect Errors
$error = FALSE;
// Check the POST data
if (!empty($post_array['add'])) {

    // Check the add form:
    $add = FALSE;
    // Save event types
    $result = $submissions->save($post_array);
    if ($result) {
        // Save was succesfull
        $add = TRUE;
    } else {
        // Indicate error
        $error = TRUE;
    }
}

//Get the list with the event categories
$submission_list = $submissions->getSubmissionsList();

//Set timezone default:
date_default_timezone_set('Europe/Amsterdam');
?>
<style>
    textarea{
        resize:none;
    }
    input[type=submit]{
        background-color: #1B7723;
    }
</style>

<h2>Lesmateriaal Aanvragen</h2>

<form action="<?= $base_url; ?>" method="post">

    <table class="col-md-12">
        <tr>
            <td style="width: 155px;"><span>Functie:</span></td>
            <td><input class="col-md-12 form-control" type="text" name="functie"></td>
        </tr>
        <tr>
            <td style="width: 155px;"><span>Description:</span></td>
            <td><textarea style="height:200px;" class="col-md-12 form-control" name="overview_description"></textarea></td>
        </tr>
        <tr>
            <td style="width: 155px;"><span>Optional:</span></td>
            <td><textarea style="height:200px;" class="col-md-12 form-control" name="optional"></textarea></td>
        </tr>
        <tr>
            <td></td>
            <td><input class="spacing col-md-4 btn btn-dark col-12" type="submit" name="add" value="Toevoegen"/>
            </td>
        </tr>
    </table>
</form>