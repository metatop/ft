<?php

/**
 * Description of listado_marcas
 *
 * @author Tiz
 */

require_model('tablamarcas.php');

class prueba1 extends fs_controller
{
    public $lista_marcas;
    public $tablamarcas;


    public function __construct() {
        parent::__construct(__CLASS__, 'Prueba1', 'Marcas');
    }
    
    protected function private_core() {
        $this->tablamarcas = new tablamarcas();
        
        //$this->lista_marcas = $this->db->select("SELECT * FROM marcas ORDER BY marca ASC;");
        $this->lista_marcas = $this->tablamarcas->all();
    }
}
