<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Validation class
 *
 * @package         Codeigniter RESTful API
 * @subpackage      Libraries
 * @category        Libraries
 * 
 */

class Validacao {

    /**
     * CPF (Brazil) validation function
     *
     * @access public
     * @param string|NULL $cpf
     * @return bool
     */
    public function cpf($cpf) {
        // Check if it was informed
        if (empty($cpf)) {
            return false;
        }

        // Eliminate the mask
        $cpf = preg_replace('[^0-9]', '', $cpf);
        $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);

        // Check if the lenght equal to 11 
        if (strlen($cpf) != 11) {
            return false;
        }
        // Check the cases above. They are all invalid
        else if ($cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' ||
                 $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' ||
                 $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' ||
                 $cpf == '99999999999') {
            return false;
            // Calculates the verification 2 digit number
        } else {

            for ($t = 9; $t < 11; $t++) {
                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf{$c} * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf{$c} != $d) {
                    return false;
                }
            }
            return true;
        }
    }

}
