<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class designed to validate token provided from a OAuth Server 
 *
 * @package         Codeigniter RESTful API
 * @subpackage      Libraries
 * @category        Libraries
 * @author          Bruno Moraes
 * @version         1.0
 */

class Oauth {

    private $request;
    
    function __construct() {
        // Load of the library responsible to perform HTTP requests
        require_once('Request.php'); // Requests library
        // Request class instance (application/libraries)
        $this->request = new Request();
    }

    /**
     * Function to validate the token
     *
     * @access public
     * @param string $access_token Access token
     * @return bool TRUE if valid, FALSE otherwise
     */
    public function validate($access_token) {
        if(empty($access_token) || !$access_token) {
            return false;
        }
        
        $data = array();
        // data must be inside an array
        $data['access_token'] = $access_token;
        
        // do the POST, informing an URL, Header and Payload
        $result = $this->request->post(TOKEN_VALIDATION_HOSTNAME, null, $data);
        $result = json_decode($result->body);

        if ($result->success)
            return true;
        
        return false;
    }

}
