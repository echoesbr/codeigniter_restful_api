<?php

/**
 * Oracle model
 * A Oracle model class for dealing with procudures calls
 *
 * @package         Codeigniter RESTful API
 * @category        Models
 * @author          Bruno Moraes
 * @version         1.0
 */

class Oracle {

    /**
     * Constructor for the Oracle Model
     *
     * @access public
     * @return void
     */
    
    public function __construct() {

        $this->parameter = array();

        $this->array_fields = NULL;
        $this->has_cursor = NULL;
        $this->data = NULL;
    }

    /*
     * Model function for connecting to Oracle databases
     * 
     * @access private
     * @param  string $user
     * @param  string $password
     * @param  string $tnsname
     * @return bool
     */

    private function connect($user, $password, $tnsname) {

        if ($conn = oci_connect($user, $password, $tnsname)) {
            return $conn;
        }
        return false;
    }

    /*
     * Model function for setting the request parameters
     * 
     * @access public
     * @param  array $array
     * @param  bool $cursor
     * @param  bool $clob
     * @return bool
     */

    public function setParameterIn($array, $cursor = false, $clob = false) {

        foreach ($array as $key => $value) {
            $this->parameter[]['name'] = "P_" . strtoupper($key);

            if ($this->has_cursor && $cursor) {
                return false;
            }

            $this->parameter[count($this->parameter) * 1 - 1]['value'] = $value;
            $this->parameter[count($this->parameter) * 1 - 1]['cursor'] = (!$this->has_cursor ? ($cursor) : false);
            $this->parameter[count($this->parameter) * 1 - 1]['clob'] = $clob;
        }

        // Sets the V_CURSOR as output parameter
        $this->parameter[]['name'] = "V_CURSOR";
        $this->parameter[count($this->parameter) * 1 - 1]['value'] = NULL;
        $this->parameter[count($this->parameter) * 1 - 1]['cursor'] = TRUE;

        $this->has_cursor = true;

        return true;
    }

    /*
     * Model function for calling Procedures
     * 
     * @access public
     * @param  string $procedure
     * @param  array $parameter
     * @return array $this->data otherwise bool FALSE
     */

    public function execute($procedure, $parameter) {

        // Makes the connection with the database
        $conn = $this->connect(ORACLE_USERNAME, ORACLE_PASSWORD, ORACLE_HOSTNAME);
        // If connection is successful
        if ($conn) {
            // Call the function to define the input parameters
            $this->setParameterIn($parameter);
            
            // Workaround to set the parameters name with ":"
            foreach ($this->parameter as $param) {
                $key[] = ":" . $param['name'];
            }
            $param_string = implode(",", $key);
            // Procedure string to be called
            $procedure = "BEGIN " . ORACLE_SCHEMA . ".{$procedure}({$param_string}); END;";

            if (!$_stmt = oci_parse($conn, $procedure)) {
                return false;
            }
            if (!$_cursor = oci_new_cursor($conn)) {
                return false;
            }
            for ($i = 0; $i < count($this->parameter); $i++) {
                if (!$this->parameter[$i]['cursor']) {
                    if ($this->parameter[$i]['clob']) {
                        ${$this->parameter[$i]['name']} = $this->parameter[$i]['value'];
                        $clob = ocinewdescriptor($conn, OCI_D_LOB);
                        if (!oci_bind_by_name($_stmt, ":" . $this->parameter[$i]['name'], $clob, -1, OCI_B_CLOB)) {
                            return false;
                        }
                        $clob->WriteTemporary($this->parameter[$i]['value']);
                    } else {
                        ${$this->parameter[$i]['name']} = $this->parameter[$i]['value'];
                        if (!oci_bind_by_name($_stmt, ":" . $this->parameter[$i]['name'], ${$this->parameter[$i]['name']}, -1)) {
                            return false;
                        }
                    }
                } else {
                    if (!oci_bind_by_name($_stmt, ":" . (string) $this->parameter[$i]['name'], $_cursor, -1, OCI_B_CURSOR)) {
                        return false;
                    }
                }
            }
            if (!oci_execute($_stmt)) {
                return false;
            }
            oci_execute($_cursor);

            $this->data = NULL;

            if (!$this->has_cursor) {
                for ($i = 0; $i < count($this->parameter); $i++) {
                    $this->array_fields[0][$this->parameter[$i]['name']] = ${$this->parameter[$i]['name']};
                }
            } else {
                for ($i = 0; $i < count($this->parameter); $i++) {
                    //$this->array_fields[0][$this->parameter[$i]['name']] = ${$this->parameter[$i]['name']};
                }

                while ($result = oci_fetch_assoc($_cursor)) {
                    while (list($name, $value) = each($result)) {
                        $this->array_fields[][$name] = $value;
                    }
                }
            }

            while (list($name, $value) = each($this->array_fields)) {
                $this->data[strtolower($name)] = $value;
            }
            oci_free_statement($_stmt);
            oci_free_statement($_cursor);

            return $this->data;
        } else {
            return false;
        }
    }

}