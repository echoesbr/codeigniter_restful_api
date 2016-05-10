<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Biblioteca Requests
 *  Esta classe tem por finalidade oferecer funções para realizar
 *  requisições HTTP, implementando a biblioteca Requests (third_party)
 *
 * @package         Requests
 * @subpackage      Libraries
 * @category        Libraries
 * @author          Bruno Moraes
 * @version         1.0
 */
class Request {

    function __construct() {
        require_once(__DIR__ . '/../third_party/Requests/library/Requests.php'); // Requests library
        // Carga da classe principal da biblioteca
        Requests::register_autoloader();
    }
    
    /**
     * Envio de uma requisição GET
     * 
     * @param string $url
     * @param array $headers
     * @param array $options
     * @return Requests_Response
     */
    public function get($url, $headers = array(), $options = array()) {
        $request = Requests::get($url, $headers, $options);
        
        return $request;
    }

    /**
     * Envio de uma requisição POST
     * 
     * @param string $url
     * @param array $headers
     * @param array $data
     * @param array $options
     * @return Requests_Response
     */
    public function post($url, $headers = array(), $data = array(), $options = array()) {
        $request = Requests::post($url, $headers, $data, $options);
        
        return $request;
    }

    /**
     * Envio de uma requisição PUT
     * 
     * @param string $url
     * @param array $headers
     * @param array $data
     * @param array $options
     * @return Requests_Response
     */
    public function put($url, $headers = array(), $data = array(), $options = array()) {
        $request = Requests::put($url, $headers, $data, $options);
        
        return $request;
    }
    
    /**
     * Envio de uma requisição DELETE
     * 
     * @param string $url
     * @param array $headers
     * @param array $options
     * @return Requests_Response
     */
    public function delete($url, $headers = array(), $options = array()) {
        $request = Requests::delete($url, $headers, $options);
        
        return $request;
    }
}
