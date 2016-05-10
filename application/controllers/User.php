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
     * Função de busca de Dados cadastrais do CPF
     *
     * @access public
     * @param integer $_POST['cpf']
     * @param string $_POST['access_token']
     * @return void
     */
    public function cpf_post() {
        $documento = $this->post('cpf');
        $access_token = $this->post('access_token');

        // Caso o parâmetro não seja enviado, retorna erro com código 400
        if (!$documento || !$access_token || empty($documento) || empty($access_token)) {
            $this->response(array('error' => $this->lang->line('error_empty_parameter')), REST_Controller::HTTP_BAD_REQUEST);
        } else {
            // Função de validação do CPF
            $validaCpf = $this->validacao->cpf($documento);
            // Prossegue somente se o CPF for válido
            if ($validaCpf) {
                // Carga da biblioteca responsável pela funções de Token
                $this->load->library('oauth');

                // Funçao de validaçao do token
                $valida = $this->oauth->validate($access_token);

                // Se a resposta da validação for vazia, o token não é válido
                if ($valida) {
                    // Inicializa variável dos parâmetros para busca de dados
                    $parametros = array();
                    $parametros["cpf"] = $documento;

                    // Carga do modelo de acesso aos dados no Oracle
                    $this->load->model('oracle');
                    // Procedure para busca de dados
                    $dados = $this->oracle->execute('PROC_PF_DADOSCADASTRAIS', $parametros);

                    // Chama pela função que retorna os dados
                    $this->return_data($dados);
                } else {
                    $this->response(array('error' => $this->lang->line('error_invalid_token')), REST_Controller::HTTP_UNAUTHORIZED);
                }
            } else {
                // Se o CPF for inválido retorna erro 400
                $this->response(array('error' => $this->lang->line('error_invalid_cpf')), REST_Controller::HTTP_BAD_REQUEST);
            }
        }
    }

}
