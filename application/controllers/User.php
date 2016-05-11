<?php

/*
 * Controller da classe de DadosCadastrais
 *
 * @package         Credify REST API
 * @category        Controllers
 * @author          Bruno Moraes
 * @version         1.0
 */

defined('BASEPATH') OR exit('No direct script access allowed');

// Controler das funções da API
require_once APPPATH . '/libraries/REST_Controller.php';

class User extends REST_Controller {

    /**
     * Construtor para a classe DadosCadastrais
     *
     * @access public
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Retrieve user data
     *
     * @access public
     * @param integer $_GET['id']
     * @param string $_GET['access_token']
     * @return void
     */
    public function user_get() {
        $id = $this->get('id');
        $access_token = $this->post('access_token');

        // In case of empty parameters, error 400 is returned
        if (!$id || !$access_token || empty($id) || empty($access_token)) {
            $this->response(array('error' => $this->lang->line('error_empty_parameter')), REST_Controller::HTTP_BAD_REQUEST);
        } else {
            // Load of the OAuth library responsible to validate the security token
            $this->load->library('oauth');

            // Validation token
            $check = $this->oauth->validate($access_token);

            // If the response is empty, the token in NOT validated
            if ($check) {
                $parameters = array();
                $parameters["cpf"] = $id;

                // Load of the Oracle model
                $this->load->model('oracle');
                // Query to retrieve the data
                $data = $this->oracle->execute('PROCEDURE_GET_USER', $parameters);

                // Calls the function responsible to return the requested data
                $this->return_data($data);
            } else {
                $this->response(array('error' => $this->lang->line('error_invalid_token')), REST_Controller::HTTP_UNAUTHORIZED);
            }
        }
    }

    /**
     * Insert user function
     *
     * @access public
     * @param integer $_POST['username']
     * @param integer $_POST['password']
     * @param string $_POST['access_token']
     * @return void
     */
    public function user_post() {
        $username = $this->post('username');
        $password = $this->post('password');
        $access_token = $this->post('access_token');

        // In case of empty parameters, error 400 is returned
        if (!$username || !$access_token || $password || empty($documento) || empty($access_token) || empty($password)) {
            $this->response(array('error' => $this->lang->line('error_empty_parameter')), REST_Controller::HTTP_BAD_REQUEST);
        } else {
            // Load of the OAuth library responsible to validate the security token
            $this->load->library('oauth');

            // Validation token
            $check = $this->oauth->validate($access_token);

            // If the response is empty, the token in NOT validated
            if ($check) {
                $parameters = array();
                $parameters["username"] = $username;
                $parameters["password"] = $password;

                // Load of the Oracle model
                $this->load->model('oracle');
                // Query to retrieve insert data
                $dados = $this->oracle->execute('PROCEDURE_INSERT_USER', $parameters);

                // Calls the function responsible to return the requested data
                $this->return_data($dados);
            } else {
                $this->response(array('error' => $this->lang->line('error_invalid_token')), REST_Controller::HTTP_UNAUTHORIZED);
            }
        }
    }

    /**
     * Update user function
     *
     * @access public
     * @param integer $_POST['id']
     * @param integer $_POST['password']
     * @param string $_POST['access_token']
     * @return void
     */
    public function user_put() {
        
    }
    
    /**
     * Delete user function
     *
     * @access public
     * @param integer $_POST['id']
     * @param string $_POST['access_token']
     * @return void
     */
    public function user_delete() {
        
    }

}
