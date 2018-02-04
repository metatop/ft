<?php

/**
 * Description of listado_marcas
 *
 * @author Tiz
 */

require_model('tablatipos.php');

class listado_tipos extends fs_controller
{
    public $lista_tipos;
    public $tablatipos;
    public $mostrar;
    public $offset;
    public $resultados;

    public function __construct() {
        parent::__construct(__CLASS__, 'Listado Tipos de aparato', 'Aparatos', FALSE, FALSE);
    }
    
    protected function private_core() {
        $this->tablatipos = new tablatipos();
        
        $this->lista_tipos = $this->tablatipos->all();
      
    }
}
