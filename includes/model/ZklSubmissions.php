<?php
/**
 * Created by PhpStorm.
 * User: black
 * Date: 14-12-2020
 * Time: 23:48
 */

class ZklSubmissions
{
    /**
     * getPostValues :
     * Filter input and retrieve POST input params
     * @return array containing known POST input fields
     */
    public function getPostValues(){

        // Define the check for params
        $post_check_array = array (
            // submit action
            'add' => array('filter' => FILTER_SANITIZE_STRING ),
            'update'   => array('filter' =>FILTER_SANITIZE_STRING ),
            // List all update form fields !!!
            // file name
            'functie'   => array('filter' => FILTER_SANITIZE_STRING ),
            // file description
            'overview_description'   => array('filter' => FILTER_SANITIZE_STRING ),
            // file link
            'optional'   => array('filter' => FILTER_SANITIZE_STRING ),
            // Id of current row
            'id'    => array( 'filter'    => FILTER_VALIDATE_INT ),
        );
        // Get filtered input:
        $inputs = filter_input_array( INPUT_POST, $post_check_array );
        // RTS
        return $inputs;
    }

    /**
     * @global Submissions $wpdb The Wordpress database class
     * @param Submissions $input_array containing insert data
     * @return boolean TRUE on succes OR FALSE
     */
    public function save($input_array){
        try {
            if (!isset($input_array['functie']) OR
                !isset($input_array['overview_description'])OR
                !isset($input_array['optional'])){
                // Mandatory fields are missing
                throw new Exception(__("Missing mandatory fields"));
            }
            if ( (strlen($input_array['functie']) < 1) OR
                (strlen($input_array['overview_description']) < 1) OR
                (strlen($input_array['optional']) < 1)){
                // Mandatory fields are empty
                throw new Exception( __("Empty mandatory fields") );
            }

            global $wpdb;

            // Insert query
            $wpdb->query($wpdb->prepare("INSERT INTO `". $wpdb->prefix
                ."zkl_submissions` ( `functie`, `description`, `optional`)".
                " VALUES ( '%s', '%s','%s');",$input_array['functie'],
                $input_array['overview_description'],$input_array['optional']) );
            // Error ? It's in there:
            if ( !empty($wpdb->last_error) ){
                $this->last_error = $wpdb->last_error;
                return FALSE;
            }

        } catch (Exception $exc) {
            echo '<div class="alert text-center alert-danger">
            <strong>Error!</strong> U heeft één of meerdere velden niet correct ingevuld.
            </div>';
        }
        echo '<div class="alert alert-success text-center">
            <strong>Success!</strong> Succesvol aangemaakt.</div>';
        return TRUE;
    }

    /**
     *
     * @return int number of Submissions stored in db
     */
    public function getNrOfSubmissions(){
        global $wpdb;

        $query = "SELECT COUNT(*) AS nr FROM `". $wpdb->prefix ."zkl_submissions`";
        $result = $wpdb->get_results( $query, ARRAY_A );

        return $result[0]['nr'];
    }

    /**
     *
     * @return Submissions
     */
    public function getSubmissionsList(){

        global $wpdb;
        $return_array = array();

        $result_array = $wpdb->get_results( "SELECT * FROM `". $this->getTableName() .
            "` ORDER BY `zkl_submission_files_id`", ARRAY_A);


        // For all database results:
        foreach ( $result_array as $idx => $array){
            // New object
            $submissions = new ZklSubmissions();
            // Set all info
            $submissions->setId($array['zkl_submission_files_id']);
            $submissions->setFunctie($array['functie']);
            $submissions->setDescription($array['description']);
            $submissions->setOptional($array['optional']);

            // Add new object toe return array.
            $return_array[] = $submissions;
        }
        return $return_array;
    }

    /**
     *
     * @param Submissions $id Id of the event type
     */
    public function setId( $id ){
        if ( is_int(intval($id) ) ){
            $this->id = $id;
        }
    }

    /**
     *
     * @param Submissions $name name of the event type
     */
    public function setFunctie($functie ){
        if ( is_string( $functie )){
            $this->functie = trim($functie);
        }
    }

    /**
     *
     * @param Submissions $desc The help text of the event type
     */
    public function setDescription ($description){
        if ( is_string($description)){
            $this->description = trim($description);
        }
    }

    /**
     *
     * @param Submissions $desc The help text of the event type
     */
    public function setOptional($optional){
        if ( is_string($optional)){
            $this->optional = trim($optional);
        }
    }

    /**
     *
     * @return int The db id of this event
     */
    public function getId(){
        return $this->id;
    }

    /**
     *
     * @return string The name of the download file
     */
    public function getFunctie(){
        return $this->functie;
    }

    /**
     *
     * @return string The description of the download file
     */
    public function getDescription(){
        return $this->description;
    }

    /**
     *
     * @return string the link of the download files
     */
    public function getOptional(){
        return $this->optional;
    }

    /**
     * getGetValues :
     *  Filter input and retrieve GET input params
     *
     * @return array containing known GET input fields
     */
    public function getGetValues(){
        //Define the check for params
        $get_check_array = array (
            //Action
            'action' => array('filter' => FILTER_SANITIZE_STRING ),

            //Id of current row
            'id' => array('filter' => FILTER_VALIDATE_INT ));

        //Get filtered input:
        $inputs = filter_input_array( INPUT_GET, $get_check_array );

        // RTS
        return $inputs;

    }

    /**
     *  Check the action and perform action on :
     *  -delete
     *
     * @param type $get_array all get vars en values
     * @return string the action provided by the $_GET array.
     */
    public function handleGetAction( $get_array ){
        $action = '';

        switch($get_array['action']){
            case 'update':
                // Indicate current action is update if id provided
                if ( !is_null($get_array['id']) ){
                    $action = $get_array['action'];
                }
                break;

            case 'delete':
                // Delete current id if provided
                if ( !is_null($get_array['id']) ){
                    $this->delete($get_array);
                }
                $action = 'delete';
                break;

            default:
                // Oops
                break;
        }
        return $action;
    }

    /**
     *
     * @global type $wpdb
     * @return type string table name with wordpress (and app prefix)
     */
    private function getTableName(){
        global $wpdb;
        return $table = $wpdb->prefix . "zkl_submissions";
    }

    /**
     *
     * @global type $wpdb WordPress database
     * @param type $input_array post_array
     * @return boolean TRUE on Succes else FALSE
     * @throws Exception
     */
    public function update($input_array){
        try {
            $array_fields = array('id', 'functie', 'overview_description', 'optional');
            $table_fields = array( 'zkl_submission_files_id', 'functie' , 'description', 'optional');
            $data_array = array();

            // Check fields
            foreach( $array_fields as $field){

                // Check fields
                if (!isset($input_array[$field])){
                    throw new Exception(__("$field is mandatory for update."));
                }

                // Add data_array (without hash idx)
                // (input_array is POST data -> Could have more fields)
                $data_array[] = $input_array[$field];
            }
            global $wpdb;
            // Update query
            //*
            $wpdb->query($wpdb->prepare("UPDATE ".$this->getTableName()."
            SET `functie` = '%s', `description` = '%s', `optional` = '%s' ".
                "WHERE `".$this->getTableName()."`.`zkl_submission_files_id` =%d;",$input_array['functie'],
                $input_array['overview_description'], $input_array['optional'], $input_array['id']) );

        } catch (Exception $exc) {
            echo '<div class="alert alert-danger text-center">
            <strong>Error!</strong> Er ging iets mis.</div>';
            return FALSE;
        }
        echo '<div class="alert alert-success text-center">
            <strong>Success!</strong> Succesvol bijgewerkt.</div>';
        return TRUE;
    }

    /**
     * The function takes the input data array and changes the
     * indexes to the column names
     * In case of update or insert action
     *
     * @param type $input_data_array  data array(id, name, descpription)
     * @param type $action            update | insert
     * @return type array with collumn index and values OR FALSE
     */
    private function getTableDataArray($input_data_array, $action=''){
        // Get the Table Column Names.
        $keys = $this->getTableColumnNames($this->getTableName());

        // Get data array with table collumns
        // NULL if collumns and data does not match in count
        //
        // Note: The order of the fields shall be the same for both!
        $table_data = array_combine($keys, $input_data_array);

        switch ( $action ){
            case 'update':  // Intended fall-through

            case 'insert':
                // Remove the index -> is primary key and can
                // therefore not be changed!

                if (!empty($table_data)){
                    unset($table_data['zkl_submission_files_id']);
                }
                break;
            // Remove
        }

        return $table_data;
    }

    /**
     * Get the column names of the specified table
     * @global type $wpdb
     * @param type $table
     * @return type
     */
    private function getTableColumnNames($table){
        global $wpdb;
        try {
            $result_array = $wpdb->get_results("SELECT `COLUMN_NAME`"."FROM INFORMATION_SCHEMA.COLUMNS".
                " WHERE `TABLE_SCHEMA`='".DB_NAME."' AND TABLE_NAME = '".$this->getTableName() ."'", ARRAY_A);
            $keys = array();
            foreach ( $result_array as $idx => $row ){
                $keys[$idx] = $row['COLUMN_NAME'];
            }
            return $keys;
        } catch (Exception $exc) {
            // @todo: Fix error handlin
            echo $exc->getTraceAsString();
            $this->last_error = $exc->getMessage();
            return FALSE;
        }
    }

    /**
     * @global type $wpdb The WordPress database class
     * @param type $input_array containing delete id
     * @return boolean TRUE on succes OR FALSE
     */
    public function delete($input_array){
        try {
            // Check input id
            if (!isset($input_array['id']) ) throw new Exception(__("Missing mandatory fields") );
            global $wpdb;

            // Delete row by provided id (WordPress style)
            $wpdb->delete( $this->getTableName(),
                array( 'zkl_submission_files_id' => $input_array['id'] ),
                array( '%d' ) );

            // Where format
            //*/
            // Error ? It's in there:
            if ( !empty($wpdb->last_error) ){
                throw new Exception( $wpdb->last_error);
            }
        } catch (Exception $exc) {
            echo '<div class="alert alert-danger text-center">
            <strong>Error!</strong> Er ging iets mis.</div>';
        }
        echo '<div class="alert alert-success text-center">
            <strong>Success!</strong> Succesvol verwijderd.</div>';
        return TRUE;
    }

}